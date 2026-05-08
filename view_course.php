<?php 
include 'layout.php'; 
require_once("../config/db.php");

$student_id = (int) $_SESSION['user_id'] ?? 0;
$course_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if (!$student_id || !$course_id) {
    die("<div class='alert alert-danger m-4'><i class='fas fa-exclamation-circle'></i> Invalid request</div>");
}

// Verify student is enrolled
$enrolled = $conn->query("SELECT id FROM enrollments WHERE student_id = $student_id AND course_id = $course_id");
if (!$enrolled || $enrolled->num_rows == 0) {
    die("<div class='alert alert-danger m-4'><i class='fas fa-exclamation-circle'></i> Access denied</div>");
}

// Get course info
$course = $conn->query("SELECT * FROM courses WHERE id = $course_id");
$course = $course ? $course->fetch_assoc() : null;

if (!$course) {
    die("<div class='alert alert-danger m-4'><i class='fas fa-exclamation-circle'></i> Course not found</div>");
}

// Get materials
$materials = $conn->query("SELECT * FROM materials WHERE course_id = $course_id ORDER BY created_at DESC");
?>

<div class="page-header">
    <h2><i class="fas fa-book-open"></i> <?= htmlspecialchars($course['course_name'] ?? 'N/A') ?></h2>
    <p>Course Materials and Resources</p>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-file-pdf"></i> Course Materials</h5>
    </div>
    <div class="card-body">
        <?php if ($materials && $materials->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Date Uploaded</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($material = $materials->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <i class="fas fa-file-alt"></i>
                                    <?= htmlspecialchars($material['title'] ?? 'N/A') ?>
                                </td>
                                <td><?= date('M d, Y', strtotime($material['created_at'] ?? 'now')) ?></td>
                                <td>
                                    <a href="../uploads/materials/<?= urlencode($material['file'] ?? '') ?>" 
                                       class="btn btn-sm btn-dark" download>
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>No course materials available yet</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="mt-4">
    <a href="courses.php" class="btn btn-outline-dark">
        <i class="fas fa-arrow-left"></i> Back to Courses
    </a>
</div>

</div>
</body>
</html>