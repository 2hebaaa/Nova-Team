<?php
include 'layout.php';
include '../config/db.php';

$course_id = $_GET['id'];

$materials = $conn->query("SELECT * FROM materials WHERE course_id=$course_id");
$assignments = $conn->query("SELECT * FROM assignments WHERE course_id=$course_id");
?>

<h2>Course Management</h2>

<a href="upload_material.php?course_id=<?=$course_id?>" class="btn btn-dark mb-2">Upload Lecture</a>
<a href="add_assignment.php?course_id=<?=$course_id?>" class="btn btn-primary mb-3">Add Assignment</a>

<h4>Lectures</h4>

<?php while($m = $materials->fetch_assoc()){ ?>

<div class="card p-2 mb-2">
<?=$m['title']?> | <?=$m['file']?>
</div>

<?php } ?>

<h4>Assignments</h4>

<?php while($a = $assignments->fetch_assoc()){ ?>

<div class="card p-2 mb-2">
<?=$a['title']?>

<a href="submissions.php?id=<?=$a['id']?>" class="btn btn-sm btn-success">
View Submissions
</a>

</div>

<?php } ?>

</div>