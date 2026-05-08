<?php 
include 'layout.php'; 
require_once("../config/db.php");

$student_id = (int) $_SESSION['user_id'] ?? 0;
$course_id = isset($_GET['course_id']) ? (int) $_GET['course_id'] : 0;

if (!$student_id || !$course_id) {
    die("<div class='alert alert-danger m-4'><i class='fas fa-exclamation-circle'></i> Invalid request</div>");
}

// Get result with correct columns
$result = $conn->query("
    SELECT results.id, results.mid, results.final, results.total, courses.course_name 
    FROM results
    JOIN courses ON courses.id = results.course_id
    WHERE results.student_id = $student_id 
    AND results.course_id = $course_id
");

if (!$result || $result->num_rows == 0) {
    die("<div class='alert alert-danger m-4'><i class='fas fa-exclamation-circle'></i> No results found</div>");
}

$row = $result->fetch_assoc();
$mid = (float) ($row['mid'] ?? 0);
$final = (float) ($row['final'] ?? 0);
$total = (float) ($row['total'] ?? 0);

// Calculate grade based on total score
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

<div class="page-header">
    <h2><i class="fas fa-chart-bar"></i> <?= htmlspecialchars($row['course_name'] ?? 'N/A') ?></h2>
    <p>Detailed result information</p>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body text-center">
                <h6 class="text-muted mb-3">Final Grade</h6>
                <div class="display-2 text-<?= $grade_color ?> mb-3">
                    <strong><?= $grade ?></strong>
                </div>
                <h4><?= number_format($total, 1) ?> / 100</h4>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted mb-3">Breakdown</h6>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <small class="text-muted">Mid Term Exam</small>
                        <strong><?= number_format($mid, 1) ?> / 20</strong>
                    </div>
                    <div class="progress">
                        <div class="progress-bar" style="width: <?= ($mid / 20) * 100 ?>%"></div>
                    </div>
                </div>

                <div class="mb-0">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <small class="text-muted">Final Exam</small>
                        <strong><?= number_format($final, 1) ?> / 80</strong>
                    </div>
                    <div class="progress">
                        <div class="progress-bar" style="width: <?= ($final / 80) * 100 ?>%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-4">
    <a href="results.php" class="btn btn-outline-dark">
        <i class="fas fa-arrow-left"></i> Back to Results
    </a>
</div>

</div>
</body>
</html>