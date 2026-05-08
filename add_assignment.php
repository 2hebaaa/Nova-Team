<?php
include 'layout.php';
include '../config/db.php';

$course_id = $_GET['course_id'];

if(isset($_POST['upload'])){

    $title = $_POST['title'];

    
    $conn->query("INSERT INTO materials (course_id,title)
                  VALUES ('$course_id','$title')");

    $material_id = $conn->insert_id;

    $files = $_FILES['files'];

    for($i=0; $i<count($files['name']); $i++){

        $fileName = $files['name'][$i];
        $tmpName  = $files['tmp_name'][$i];

        $target = "../uploads/materials/".$fileName;

        move_uploaded_file($tmpName, $target);

        $conn->query("INSERT INTO material_files (material_id,file_name)
                      VALUES ('$material_id','$fileName')");
    }
}
?>


<div class="card p-4">

<h3>Upload Assignments</h3>

<form method="POST" enctype="multipart/form-data">

<input type="text" name="title" class="form-control mb-3" placeholder="Lecture title">

<input type="file" name="files[]" multiple class="form-control mb-3">

<button name="upload" class="btn btn-dark w-100">
Upload Files
</button>


</div>