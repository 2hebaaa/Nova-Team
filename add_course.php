<?php
include '../config/db.php';
include 'layout.php';

if(isset($_POST['add'])){
    $name = $_POST['name'];
    $doctor_id = $_SESSION['user_id'];

    $conn->query("INSERT INTO courses (course_name, doctor_id)
                  VALUES ('$name','$doctor_id')");
}
?>

<h2>Add Course</h2>

<form method="POST">
<input type="text" name="name" class="form-control mb-3" placeholder="Course name">
<button name="add" class="btn btn-dark">Add</button>
</form>

</div></body></html>