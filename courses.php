<?php 
include 'layout.php'; 
include '../config/db.php';

$student_id = $_SESSION['user_id'];

$courses = $conn->query("
    SELECT courses.*
    FROM courses
    JOIN enrollments ON courses.id = enrollments.course_id
    WHERE enrollments.student_id = $student_id
    ORDER BY courses.course_name ASC
");
?>

<div class="page-header">
    <h2><i class="fas fa-book"></i> My Courses</h2>
    <p>View and manage your enrolled courses</p>
</div>

<?php if($courses && $courses->num_rows > 0): ?>
<div class="row">
    <?php while($c = $courses->fetch_assoc()): ?>
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body d-flex flex-column">
                <div class="mb-3">
                    <h5 class="card-title mb-2"><?= htmlspecialchars($c['course_name']) ?></h5>
                    <p class="text-muted small mb-0">
                        <i class="fas fa-info-circle"></i> Course ID: <?= htmlspecialchars($c['id']) ?>
                    </p>
                </div>

                <div class="mt-auto">
                    <a href="view_course.php?id=<?= $c['id'] ?>" class="btn btn-dark w-100">
                        <i class="fas fa-arrow-right"></i> Open Course
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
</div>
<?php else: ?>
<div class="empty-state">
    <i class="fas fa-inbox"></i>
    <p>You are not enrolled in any courses yet</p>
    <small class="text-muted">Contact your instructor to enroll in a course</small>
</div>
<?php endif; ?>

</div></body></html>