<?php
include 'layout.php';
include '../config/db.php';

$id = $_GET['id'];

if(isset($_POST['grade'])){
$grade = $_POST['grade'];
$sub_id = $_POST['sub_id'];

$conn->query("UPDATE submissions SET grade='$grade' WHERE id=$sub_id");
}

$subs = $conn->query("
SELECT submissions.*, users.name
FROM submissions
JOIN users ON users.id=submissions.student_id
WHERE assignment_id=$id
");
?>

<table class="table">

<tr><th>Student</th><th>File</th><th>Grade</th></tr>

<?php while($s=$subs->fetch_assoc()){ ?>

<tr>
<td><?=$s['name']?></td>

<td>
<a href="../uploads/assignments/<?=$s['file']?>" target="_blank">
View
</a>
</td>

<td>
<form method="POST">
<input type="hidden" name="sub_id" value="<?=$s['id']?>">
<input type="number" name="grade" class="form-control">
<button class="btn btn-success btn-sm">Save</button>
</form>
</td>

</tr>

<?php } ?>

</table>

</div>