<?php 
include 'layout.php';
include '../config/db.php';

/* حماية */
if(!isset($_SESSION['user_id'])){
    header("Location: ../auth/login.php");
    exit();
}

$student_id = $_SESSION['user_id'];
$message = "";

/* رفع المشروع */
if(isset($_POST['upload'])){

    $course_id = (int) $_POST['course_id'];
    $title     = trim($_POST['title']);

    if(empty($course_id) || empty($title) || empty($_FILES['file']['name'])){
        $message = "<div class='alert alert-danger'>All fields are required</div>";
    } else {

        $fileName = time() . "_" . basename($_FILES['file']['name']);
        $tmp      = $_FILES['file']['tmp_name'];

        $folder = "../uploads/projects/";

        /* إنشاء الفولدر لو مش موجود */
        if(!is_dir($folder)){
            mkdir($folder,0777,true);
        }

        if(move_uploaded_file($tmp, $folder.$fileName)){

            $conn->query("
                INSERT INTO projects (student_id,course_id,title,file)
                VALUES ('$student_id','$course_id','$title','$fileName')
            ");

            $message = "<div class='alert alert-success'>Project uploaded successfully</div>";

        } else {
            $message = "<div class='alert alert-danger'>Upload failed</div>";
        }
    }
}

/* الكورسات الخاصة بالطالب */
$courses = $conn->query("
SELECT courses.*
FROM courses
JOIN enrollments ON courses.id = enrollments.course_id
WHERE enrollments.student_id = $student_id
");

/* المشاريع */
$projects = $conn->query("
SELECT projects.*, courses.course_name
FROM projects
JOIN courses ON courses.id = projects.course_id
WHERE projects.student_id = $student_id
ORDER BY projects.id DESC
");
?>

<div class="page-header mb-4">
    <h2>My Projects</h2>
    <p class="text-muted">Upload and manage your projects</p>
</div>

<?= $message ?>

<!-- 🔥 Upload Card -->
<div class="card shadow-sm p-4 mb-4">

    <h5 class="mb-3">Upload New Project</h5>

    <form method="POST" enctype="multipart/form-data">

        <select name="course_id" class="form-control mb-3" required>
            <option value="">Select Course</option>
            <?php while($c = $courses->fetch_assoc()): ?>
                <option value="<?= $c['id'] ?>">
                    <?= htmlspecialchars($c['course_name']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <input type="text" name="title" class="form-control mb-3" placeholder="Project Title" required>

        <input type="file" name="file" class="form-control mb-3" required>

        <button name="upload" class="btn btn-dark w-100">
            Upload Project
        </button>

    </form>

</div>

<!-- 🔥 Projects Grid -->
<div class="row">

<?php if($projects && $projects->num_rows > 0): ?>

<?php while($p = $projects->fetch_assoc()): ?>

<div class="col-md-4 mb-4">

    <div class="card h-100 shadow-sm">

        <div class="card-body d-flex flex-column">

            <h5 class="card-title">
                <?= htmlspecialchars($p['title']) ?>
            </h5>

            <p class="text-muted small mb-1">
                <?= htmlspecialchars($p['course_name']) ?>
            </p>

            <p class="text-muted small">
                Uploaded: <?= $p['created_at'] ?>
            </p>

            <div class="mt-auto">

                <a href="../uploads/projects/<?= $p['file'] ?>" 
                   class="btn btn-secondary w-100" download>
                    Download
                </a>

            </div>

        </div>

    </div>

</div>

<?php endwhile; ?>

<?php else: ?>

<div class="col-12 text-center">
    <div class="card p-4">
        <p>No projects uploaded yet</p>
    </div>
</div>

<?php endif; ?>

</div>

</div>