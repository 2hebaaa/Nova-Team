<?php
include 'layout.php';
include '../config/db.php';

$student_id = $_SESSION['user_id'];

$query = "
    SELECT courses.id, courses.course_name, COUNT(assignments.id) as total
    FROM courses
    JOIN enrollments ON courses.id = enrollments.course_id
    LEFT JOIN assignments ON courses.id = assignments.course_id
    WHERE enrollments.student_id = $student_id
    GROUP BY courses.id, courses.course_name
    ORDER BY courses.course_name ASC
";

$courses = $conn->query($query);

if(!$courses){
    die("Error: " . $conn->error);
}
?>

<div class="page-header">
    <h2><i class="fas fa-tasks"></i> Assignments</h2>
    <p>View and submit assignments for your courses</p>
</div>

<?php if($courses && $courses->num_rows > 0): ?>
<div class="row">
    <?php while($c = $courses->fetch_assoc()): ?>
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body d-flex flex-column justify-content-between">
                <div>
                    <h5 class="card-title mb-3"><?= htmlspecialchars($c['course_name']) ?></h5>
                    <p class="text-muted mb-0">
                        <i class="fas fa-list"></i> 
                        <strong><?= $c['total'] ?></strong> Assignment<?= $c['total'] != 1 ? 's' : '' ?>
                    </p>
                </div>

                <a href="view_assignment.php?course_id=<?= $c['id'] ?>" 
                   class="btn btn-dark w-100 mt-3">
                   <i class="fas fa-arrow-right"></i> View Assignments
                </a>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
</div>
<?php else: ?>
<div class="empty-state">
    <i class="fas fa-inbox"></i>
    <p>No assignments available</p>
</div>
<?php endif; ?>

</div></body></html>
