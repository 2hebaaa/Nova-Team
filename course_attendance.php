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
$course = $conn->query("SELECT course_name FROM courses WHERE id = $course_id");
$course = $course ? $course->fetch_assoc() : null;

if (!$course) {
    die("<div class='alert alert-danger m-4'><i class='fas fa-exclamation-circle'></i> Course not found</div>");
}

// Get attendance records
$result = $conn->query("
    SELECT status, date 
    FROM attendance
    WHERE student_id = $student_id 
    AND course_id = $course_id
    ORDER BY date DESC
");

// Calculate attendance statistics
$present_count = 0;
$absent_count = 0;
$total_count = 0;
$attendance_records = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $total_count++;
        
        // Handle different status formats
        $status = strtolower(trim($row['status'] ?? ''));
        
        // Check if present (handles 'present', 'p', '1', 'yes', etc.)
        $is_present = in_array($status, ['present', 'p', '1', 'yes', 'true', 'attended']);
        
        if ($is_present) {
            $present_count++;
            $badge_class = 'badge-success';
            $display_status = 'Present';
        } else {
            $absent_count++;
            $badge_class = 'badge-danger';
            $display_status = 'Absent';
        }
        
        $attendance_records[] = [
            'date' => $row['date'] ?? 'N/A',
            'status' => $display_status,
            'badge_class' => $badge_class,
            'raw_status' => $status
        ];
    }
}

// Calculate percentage
$percentage = $total_count > 0 ? round(($present_count / $total_count) * 100) : 0;
$status_color = $percentage >= 75 ? 'success' : ($percentage >= 50 ? 'warning' : 'danger');
?>

<div class="page-header">
    <h2><i class="fas fa-calendar-check"></i> Attendance - <?= htmlspecialchars($course['course_name'] ?? 'N/A') ?></h2>
    <p>Detailed attendance records</p>
</div>

<?php if ($total_count > 0): ?>
    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stat-card border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="mb-1">Attendance Rate</p>
                            <h3 class="mb-0"><?= $percentage ?>%</h3>
                        </div>
                        <div class="stat-icon bg-<?= $status_color ?> bg-opacity-10">
                            <i class="fas fa-chart-pie text-<?= $status_color ?>"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card stat-card border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="mb-1">Present Days</p>
                            <h3 class="mb-0"><?= $present_count ?></h3>
                        </div>
                        <div class="stat-icon bg-success bg-opacity-10">
                            <i class="fas fa-check text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card stat-card border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="mb-1">Absent Days</p>
                            <h3 class="mb-0"><?= $absent_count ?></h3>
                        </div>
                        <div class="stat-icon bg-danger bg-opacity-10">
                            <i class="fas fa-times text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card stat-card border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="mb-1">Total Days</p>
                            <h3 class="mb-0"><?= $total_count ?></h3>
                        </div>
                        <div class="stat-icon bg-info bg-opacity-10">
                            <i class="fas fa-calendar text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <strong>Overall Attendance</strong>
                <span class="badge badge-<?= $status_color ?>"><?= $percentage ?>%</span>
            </div>
            <div class="progress" style="height: 10px;">
                <div class="progress-bar bg-<?= $status_color ?>" style="width: <?= $percentage ?>%"></div>
            </div>
        </div>
    </div>

    <!-- Attendance Records Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Attendance History</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($attendance_records as $index => $record): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= date('M d, Y', strtotime($record['date'])) ?></td>
                                <td>
                                    <span class="badge <?= $record['badge_class'] ?>">
                                        <?= $record['status'] ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php else: ?>
    <div class="empty-state">
        <i class="fas fa-inbox"></i>
        <p>No attendance records for this course</p>
    </div>
<?php endif; ?>

<div class="mt-4">
    <a href="attendance.php" class="btn btn-outline-dark">
        <i class="fas fa-arrow-left"></i> Back to Attendance
    </a>
</div>

</div>
</body>
</html>