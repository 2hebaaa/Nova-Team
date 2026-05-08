<?php 
include 'layout.php'; 
require_once("../config/db.php");

$student_id = (int) $_SESSION['user_id'] ?? 0;

if (!$student_id) {
    header("Location: ../auth/login.php");
    exit();
}

// Query with correct columns: mid, final, total
$courses = $conn->query("
    SELECT courses.id, courses.course_name, results.mid, results.final, results.total
    FROM courses
    JOIN results ON courses.id = results.course_id
    WHERE results.student_id = $student_id
    ORDER BY courses.course_name ASC
");
?>

<div class="page-header">
    <h2><i class="fas fa-chart-bar"></i> My Results</h2>
    <p>View your course grades and performance</p>
</div>

<?php if ($courses && $courses->num_rows > 0): ?>
    <div class="row">
        <?php while ($course = $courses->fetch_assoc()): 
            $total = (float) ($course['total'] ?? 0);
            
            // Calculate grade based on total
            if ($total >= 90) {
                $grade = 'A';
                $grade_color = 'success';
            } elseif ($total >= 80) {
                $grade = 'B';
                $grade_color = 'info';
            } elseif ($total >= 70) {
                $grade = 'C';
                $grade_color = 'warning';
            } else {
                $grade = 'F';
                $grade_color = 'danger';
            }
        ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3"><?= htmlspecialchars($course['course_name'] ?? 'N/A') ?></h5>
                    
                    <div class="text-center mb-4">
                        <div class="display-5 text-<?= $grade_color ?> mb-2">
                            <strong><?= $grade ?></strong>
                        </div>
                        <p class="text-muted mb-0">
                            Total Score: <strong><?= number_format($total, 1) ?></strong>
                        </p>
                    </div>

                    <div class="progress mb-3">
                        <div class="progress-bar bg-<?= $grade_color ?>" style="width: <?= $total ?>%"></div>
                    </div>

                    <a href="course_result.php?course_id=<?= (int) $course['id'] ?>" 
                       class="btn btn-outline-dark w-100">
                       <i class="fas fa-eye"></i> View Details
                    </a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
<?php else: ?>
    <div class="empty-state">
        <i class="fas fa-inbox"></i>
        <p>No results available yet</p>
        <small>Results will appear here once they are published</small>
    </div>
<?php endif; ?>

</div>
</body>
</html>