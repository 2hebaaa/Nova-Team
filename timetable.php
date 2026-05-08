<?php
include 'layout.php'; 
// ربط قاعدة البيانات
require_once("../config/db.php");

// التأكد من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// لو مفيش group_id نحاول نجيبه من الداتا بيز بدل ما نعمل error
if (!isset($_SESSION['group_id'])) {

    $stmtUser = $conn->prepare("SELECT group_id FROM users WHERE id = ?");
    $stmtUser->bind_param("i", $_SESSION['user_id']);
    $stmtUser->execute();
    $resUser = $stmtUser->get_result();

    if ($user = $resUser->fetch_assoc()) {
        $_SESSION['group_id'] = $user['group_id'];
    } else {
        echo "Error: user not found.";
        exit();
    }
}

$group = $_SESSION['group_id'];

// جلب الجدول حسب الجروب + ALL
$stmt = $conn->prepare("
    SELECT day, time_slot, course_name, room, instructor, group_id
    FROM timetable
    WHERE group_id = ? OR group_id = 'ALL'
    ORDER BY 
        FIELD(day,'Saturday','Sunday','Monday','Tuesday','Wednesday','Thursday'),
        time_slot
");

$stmt->bind_param("s", $group);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>My Timetable</title>

    <style>
        body {
            font-family: Arial;
            background: #f4f6f9;
            margin: 0;
        }

        h2 {
            text-align: center;
            padding: 20px;
        }

        table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background: #2c3e50;
            color: white;
        }

        tr:hover {
            background: #f1f1f1;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 6px;
            color: white;
            font-size: 12px;
        }

        .A { background: #3498db; }
        .B { background: #e67e22; }
        .C { background: #9b59b6; }
        .ALL { background: #2ecc71; }

        .empty {
            text-align: center;
            padding: 20px;
            color: gray;
        }
    </style>
</head>

<body>

<h2>My Schedule (Group <?= htmlspecialchars($group) ?>)</h2>

<table>
    <tr>
        <th>Day</th>
        <th>Time</th>
        <th>Course</th>
        <th>Room</th>
        <th>Instructor</th>
        <th>Group</th>
    </tr>

    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['day']) ?></td>
            <td><?= htmlspecialchars($row['time_slot']) ?></td>
            <td><?= htmlspecialchars($row['course_name']) ?></td>
            <td><?= htmlspecialchars($row['room']) ?></td>
            <td><?= htmlspecialchars($row['instructor']) ?></td>
            <td>
                <span class="badge <?= $row['group_id'] ?>">
                    <?= htmlspecialchars($row['group_id']) ?>
                </span>
            </td>
        </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="6" class="empty">No schedule found</td>
        </tr>
    <?php endif; ?>

</table>

</body>
</html>