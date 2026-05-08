<?php
include 'layout.php';
require_once("../config/db.php");

$student_id = (int) $_SESSION['user_id'] ?? 0;
$course_id = isset($_GET['course_id']) ? (int) $_GET['course_id'] : 0;

if (!$student_id || !$course_id) {
    die("<div class='alert alert-danger m-4'><i class='fas fa-exclamation-circle'></i> Invalid request</div>");
}

// Verify student is enrolled
$enrolled = $conn->query("SELECT id FROM enrollments WHERE student_id = $student_id AND course_id = $course_id");
if (!$enrolled || $enrolled->num_rows == 0) {
    die("<div class='alert alert-danger m-4'><i class='fas fa-exclamation-circle'></i> Access denied</div>");
}

// Get course
$course = $conn->query("SELECT * FROM courses WHERE id = $course_id");
$course = $course ? $course->fetch_assoc() : null;

if (!$course) {
    die("<div class='alert alert-danger m-4'><i class='fas fa-exclamation-circle'></i> Course not found</div>");
}

// Get assignments
$assignments = $conn->query("
    SELECT * FROM assignments 
    WHERE course_id = $course_id
    ORDER BY deadline ASC
");
?>

<div class="page-header">
    <h2><i class="fas fa-tasks"></i> <?= htmlspecialchars($course['course_name'] ?? 'N/A') ?></h2>
    <p>View and submit assignments</p>
</div>

<?php if ($assignments && $assignments->num_rows > 0): ?>
    <div class="row">
        <?php while ($assignment = $assignments->fetch_assoc()): 
            $deadline = strtotime($assignment['deadline'] ?? 'now');
            $now = time();
            $is_overdue = $deadline < $now;
        ?>
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title flex-grow-1 mb-0"><?= htmlspecialchars($assignment['title'] ?? 'N/A') ?></h5>
                            <?php if ($is_overdue): ?>
                                <span class="badge badge-danger">Overdue</span>
                            <?php else: ?>
                                <span class="badge badge-success">Active</span>
                            <?php endif; ?>
                        </div>

                        <p class="card-text text-muted small">
                            <?= htmlspecialchars(substr($assignment['description'] ?? 'No description', 0, 100)) ?>...
                        </p>

                        <div class="mb-3 p-3 bg-light rounded">
                            <small class="text-muted d-block mb-1"><i class="fas fa-calendar"></i> Deadline</small>
                            <strong><?= date('M d, Y H:i', $deadline) ?></strong>
                        </div>

                        <a href="submit_assignment.php?id=<?= (int) $assignment['id'] ?>" 
                           class="btn btn-dark w-100">
                            <i class="fas fa-arrow-right"></i> Open Assignment
                        </a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
<?php else: ?>
    <div class="empty-state">
        <i class="fas fa-inbox"></i>
        <p>No assignments available for this course</p>
    </div>
<?php endif; ?>

<div class="mt-4">
    <a href="assignments.php" class="btn btn-outline-dark">
        <i class="fas fa-arrow-left"></i> Back to Assignments
    </a>
</div>

</div>
</body>
</html>