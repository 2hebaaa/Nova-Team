<?php
include 'layout.php';
include '../config/db.php';

$id = $_SESSION['user_id'];

$result = $conn->query("
SELECT submissions.*, assignments.title
FROM submissions
JOIN assignments ON assignments.id = submissions.assignment_id
WHERE student_id = $id
");
?>

<h2>My Submissions</h2>

<?php while($row = $result->fetch_assoc()) { ?>

<div class="card p-3 mb-2">

<h5><?= $row['title'] ?></h5>

<a href="../uploads/assignments/<?= $row['file'] ?>" class="btn btn-dark" download>
Download My File
</a>

<?php if($row['grade'] !== null){ ?>
    <p>Grade: <?= $row['grade'] ?></p>
<?php } ?>

</div>

<?php } ?>