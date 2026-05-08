<?php
include 'layout.php';
require_once("../config/db.php");

$student_id = (int) $_SESSION['user_id'] ?? 0;

if (!$student_id) {
    header("Location: ../auth/login.php");
    exit();
}

$query = "
    SELECT courses.id, courses.course_name, 
           COUNT(CASE WHEN attendance.status = 'Present' THEN 1 END) as present,
           COUNT(attendance.id) as total
    FROM courses
    LEFT JOIN enrollments ON courses.id = enrollments.course_id
    LEFT JOIN attendance ON courses.id = attendance.course_id 
                         AND attendance.student_id = $student_id
    WHERE enrollments.student_id = $student_id
    GROUP BY courses.id, courses.course_name
    ORDER BY courses.course_name ASC
";

$courses = $conn->query($query);
?>

<div class="page-header">
    <h2><i class="fas fa-calendar-check"></i> Attendance</h2>
    <p>View your attendance records across all courses</p>
</div>

<div class="row">
    <?php 
    if ($courses && $courses->num_rows > 0):
        while ($course = $courses->fetch_assoc()): 
            $total = $course['total'] ?? 0;
            $present = $course['present'] ?? 0;
            $percentage = $total > 0 ? round(($present / $total) * 100) : 0;
            
            $status_class = $percentage >= 75 ? 'success' : 
                           ($percentage >= 50 ? 'warning' : 'danger');
    ?>
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title mb-3"><?= htmlspecialchars($course['course_name'] ?? 'N/A') ?></h5>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <small class="text-muted">Attendance Rate</small>
                        <span class="badge badge-<?= $status_class ?>"><?= $percentage ?>%</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-<?= $status_class ?>" style="width: <?= $percentage ?>%"></div>
                    </div>
                </div>

                <div class="row text-center mb-3">
                    <div class="col-6 border-end">
                        <small class="text-muted d-block">Present</small>
                        <h6 class="mb-0"><?= $present ?></h6>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">Total</small>
                        <h6 class="mb-0"><?= $total ?></h6>
                    </div>
                </div>

                <a href="course_attendance.php?course_id=<?= (int) $course['id'] ?>" 
                   class="btn btn-dark btn-sm w-100">
                   <i class="fas fa-eye"></i> View Details
                </a>
            </div>
        </div>
    </div>
    <?php 
        endwhile;
    else:
    ?>
    <div class="col-12">
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <p>No attendance records available</p>
        </div>
    </div>
    <?php endif; ?>
</div>

</div>
</body>
</html>