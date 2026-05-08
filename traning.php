<?php
include 'layout.php';
include '../config/db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: ../auth/login.php");
    exit();
}

$student_id = $_SESSION['user_id'];
$message = "";

/* إضافة تدريب */
if(isset($_POST['add'])){

    $name  = $_POST['training_name'];
    $place = $_POST['place'];
    $period = $_POST['period'];
    $weeks = (int) $_POST['weeks'];

    if(empty($name) || empty($place) || empty($weeks)){
        $message = "<div class='alert alert-danger'>All fields required</div>";
    } else {

        $conn->query("
        INSERT INTO training (student_id,training_name,place,period,total_weeks)
        VALUES ('$student_id','$name','$place','$period','$weeks')
        ");

        $message = "<div class='alert alert-success'>Training added</div>";
    }
}

/* جلب التدريبات */
$trainings = $conn->query("
SELECT * FROM training 
WHERE student_id=$student_id
ORDER BY id DESC
");

/* حساب مجموع الأسابيع */
$total = 0;
while($row = $trainings->fetch_assoc()){
    $total += $row['total_weeks'];
}

/* إعادة تنفيذ الكويري */
$trainings = $conn->query("
SELECT * FROM training 
WHERE student_id=$student_id
ORDER BY id DESC
");
?>

<h2>Training</h2>

<?= $message ?>

<!-- 🔥 Summary -->
<div class="card p-3 mb-3">
<h5>Total Weeks: <?= $total ?></h5>

<?php if($total >= 12): ?>
<p style="color:green;">Completed Required Training ✔</p>
<?php else: ?>
<p style="color:red;">Remaining: <?= 12 - $total ?> weeks</p>
<?php endif; ?>
</div>

<!-- 🔥 Add Training -->
<div class="card p-4 mb-4">

<h5>Add Training</h5>

<form method="POST">

<input name="training_name" class="form-control mb-2" placeholder="Training Name">

<input name="place" class="form-control mb-2" placeholder="Company / Place">

<input name="period" class="form-control mb-2" placeholder="Period (ex: Jan - Feb)">

<input name="weeks" type="number" class="form-control mb-2" placeholder="Total Weeks">

<button name="add" class="btn btn-dark w-100">Add</button>

</form>

</div>

<!-- 🔥 Display -->
<div class="row">

<?php while($t=$trainings->fetch_assoc()){ ?>

<div class="col-md-4">
<div class="card p-3 mb-3">

<h5><?= $t['training_name'] ?></h5>
<p><?= $t['place'] ?></p>
<p><?= $t['period'] ?></p>
<p><?= $t['total_weeks'] ?> weeks</p>

</div>
</div>

<?php } ?>

</div>

</div>