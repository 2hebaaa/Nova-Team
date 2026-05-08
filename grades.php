<?php
include 'layout.php';
include '../config/db.php';

$message = '';
$message_type = '';

// get students
$students = $conn->query("SELECT id, name FROM users WHERE role='student' ORDER BY name ASC");

// get courses
$courses = $conn->query("SELECT id, course_name FROM courses ORDER BY course_name ASC");

if(isset($_POST['save'])){
    $student_id = (int) $_POST['student'];
    $course_id = (int) $_POST['course'];
    $mid = (float) $_POST['mid'];
    $final = (float) $_POST['final'];

    if($student_id == 0 || $course_id == 0 || $mid < 0 || $final < 0){
        $message = "Please fill all fields with valid values";
        $message_type = "danger";
    } else {
        $total = $mid + $final;
        $percentage = ($total / 200) * 100;

        // Check if result already exists
        $check = $conn->prepare("SELECT id FROM results WHERE student_id=? AND course_id=?");
        $check->bind_param("ii", $student_id, $course_id);
        $check->execute();
        
        if($check->get_result()->num_rows > 0){
            // Update existing
            $stmt = $conn->prepare("UPDATE results SET mid=?, final=?, marks=?, percentage=? WHERE student_id=? AND course_id=?");
            $stmt->bind_param("ddddii", $mid, $final, $total, $percentage, $student_id, $course_id);
        } else {
            // Insert new
            $stmt = $conn->prepare("INSERT INTO results(student_id, course_id, mid, final, marks, percentage) VALUES(?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iidddd", $student_id, $course_id, $mid, $final, $total, $percentage);
        }

        if($stmt->execute()){
            $message = "Grade saved successfully!";
            $message_type = "success";
        } else {
            $message = "Error saving grade: " . $conn->error;
            $message_type = "danger";
        }
    }
}
?>

<div class="page-header">
    <h2><i class="fas fa-star"></i> Manage Grades</h2>
    <p>Add or update student grades</p>
</div>

<?php if(!empty($message)): ?>
<div class="alert alert-<?= $message_type ?> alert-dismissible fade show" role="alert">
    <?= $message ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Add Grade</h5>
                <form method="POST">

                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-user"></i> Student</label>
                        <select name="student" class="form-control" required>
                            <option value="">-- Select Student --</option>
                            <?php 
                            $students->data_seek(0);
                            while($s = $students->fetch_assoc()){ 
                            ?>
                            <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-book"></i> Course</label>
                        <select name="course" class="form-control" required>
                            <option value="">-- Select Course --</option>
                            <?php 
                            $courses->data_seek(0);
                            while($c = $courses->fetch_assoc()){ 
                            ?>
                            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['course_name']) ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label">Mid (Out of 100)</label>
                                <input type="number" name="mid" class="form-control" min="0" max="100" step="0.01" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label">Final (Out of 100)</label>
                                <input type="number" name="final" class="form-control" min="0" max="100" step="0.01" required>
                            </div>
                        </div>
                    </div>

                    <button type="submit" name="save" class="btn btn-dark w-100">
                        <i class="fas fa-save"></i> Save Grade
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Grading Scale</h5>
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <small class="text-muted d-block">90-100</small>
                        <h4 class="text-success">A</h4>
                    </div>
                    <div class="col-6 mb-3">
                        <small class="text-muted d-block">80-89</small>
                        <h4 class="text-success">B</h4>
                    </div>
                    <div class="col-6 mb-3">
                        <small class="text-muted d-block">70-79</small>
                        <h4 class="text-info">C</h4>
                    </div>
                    <div class="col-6 mb-3">
                        <small class="text-muted d-block">60-69</small>
                        <h4 class="text-warning">D</h4>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Below 60</small>
                        <h4 class="text-danger">F</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div></body></html>