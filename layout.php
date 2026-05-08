<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Verify user role
if ($_SESSION['role'] !== 'student') {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'] ?? null;
$user_name = $_SESSION['name'] ?? 'User';
$user_role = $_SESSION['role'] ?? 'student';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="BTU LMS - Student Learning Management System">
    <title>Student Panel - LMS</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        html, body {
            height: 100%;
            background: #f0f2f5;
        }

        body {
            display: flex;
            overflow-x: hidden;
        }

        /* ============ SIDEBAR ============ */
        .sidebar {
            width: 260px;
            height: 100vh;
            background: linear-gradient(135deg, #614b6f 0%, #4a3a53 100%);
            color: white;
            padding: 30px 20px;
            position: fixed;
            overflow-y: auto;
            box-shadow: 2px 0 15px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
        }

        .sidebar-brand h5 {
            margin: 0;
            font-weight: 700;
            font-size: 1.2rem;
        }

        .sidebar-brand i {
            font-size: 1.5rem;
        }

        .sidebar-menu {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .sidebar-menu li {
            margin: 8px 0;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255, 255, 255, 0.85);
            padding: 12px 15px;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .sidebar-menu a:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding-left: 20px;
        }

        .sidebar-menu a i {
            width: 18px;
            text-align: center;
        }

        .sidebar-divider {
            border-color: rgba(255, 255, 255, 0.2);
            margin: 20px 0;
        }

        .sidebar-logout {
            margin-top: auto;
            padding-top: 20px;
            border-top: 2px solid rgba(255, 255, 255, 0.2);
        }

        /* ============ CONTENT AREA ============ */
        .content {
            margin-left: 260px;
            flex: 1;
            padding: 30px;
            min-height: 100vh;
        }

        /* ============ PAGE HEADER ============ */
        .page-header {
            margin-bottom: 30px;
        }

        .page-header h2 {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 8px;
            font-size: 1.8rem;
        }

        .page-header p {
            color: #666;
            font-size: 0.95rem;
            margin: 0;
        }

        /* ============ CARDS ============ */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
            transform: translateY(-4px);
        }

        .card-body {
            padding: 20px;
        }

        .card-header {
            background: #f8f9fa !important;
            border-bottom: 1px solid #e9ecef;
        }

        /* ============ STAT CARDS ============ */
        .stat-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .stat-card .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .stat-card h3 {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            margin: 0;
        }

        .stat-card p {
            color: #666;
            font-size: 0.9rem;
            margin: 0;
        }

        /* ============ TABLES ============ */
        .table {
            font-size: 0.95rem;
            margin-bottom: 0;
        }

        .table thead th {
            background: #f8f9fa;
            font-weight: 600;
            border-top: none;
            color: #333;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px;
        }

        .table tbody td {
            padding: 12px;
            vertical-align: middle;
        }

        .table tbody tr {
            transition: 0.2s;
            border-bottom: 1px solid #f0f0f0;
        }

        .table tbody tr:hover {
            background: #f8f9fa;
        }

        /* ============ BUTTONS ============ */
        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            border: none;
        }

        .btn-dark {
            background: #614b6f;
            color: white;
        }

        .btn-dark:hover {
            background: #4a3a53;
            color: white;
        }

        .btn-outline-dark {
            color: #614b6f;
            border: 2px solid #614b6f;
        }

        .btn-outline-dark:hover {
            background: #614b6f;
            color: white;
            border-color: #614b6f;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 0.85rem;
        }

        /* ============ BADGES ============ */
        .badge {
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.8rem;
        }

        .badge-success {
            background: #10b981;
            color: white;
        }

        .badge-danger {
            background: #ef4444;
            color: white;
        }

        .badge-warning {
            background: #f59e0b;
            color: white;
        }

        .badge-info {
            background: #3b82f6;
            color: white;
        }

        /* ============ ALERTS ============ */
        .alert {
            border-radius: 10px;
            border: none;
            padding: 14px 16px;
        }

        .alert-danger {
            background: #fee;
            color: #c33;
        }

        .alert-success {
            background: #efe;
            color: #3c3;
        }

        .alert-warning {
            background: #ffe;
            color: #cc3;
        }

        /* ============ FORMS ============ */
        .form-control, .form-select {
            border-radius: 8px;
            border: 1.5px solid #ddd;
            padding: 10px 15px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #614b6f;
            box-shadow: 0 0 0 3px rgba(97, 75, 111, 0.1);
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        /* ============ EMPTY STATE ============ */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .empty-state i {
            font-size: 3rem;
            color: #ddd;
            margin-bottom: 20px;
        }

        .empty-state p {
            font-size: 1rem;
            margin-bottom: 10px;
        }

        .empty-state small {
            color: #999;
        }

        /* ============ PROGRESS BAR ============ */
        .progress {
            height: 8px;
            border-radius: 10px;
            background: #e9ecef;
        }

        .progress-bar {
            border-radius: 10px;
        }

        /* ============ RESPONSIVE ============ */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                margin-bottom: 20px;
            }

            .content {
                margin-left: 0;
                padding: 20px;
            }

            .page-header h2 {
                font-size: 1.5rem;
            }

            .col-md-4,
            .col-md-6 {
                margin-bottom: 15px;
            }
        }

        /* ============ SCROLLBAR ============ */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="sidebar-brand">
        <i class="fas fa-graduation-cap"></i>
        <h5>BTU LMS</h5>
    </div>

    <ul class="sidebar-menu">
        <li><a href="dashboard.php"><i class="fas fa-home"></i> Home</a></li>
        <li><a href="courses.php"><i class="fas fa-book"></i> Courses</a></li>
        <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
        <li><a href="attendance.php"><i class="fas fa-calendar-check"></i> Attendance</a></li>
        <hr class="sidebar-divider">
        <li style="color: #a09393; text-align: center;"><b>ACTIVITIES</b></a></li>
        <li><a href="traning.php"><i class="fa-solid fa-chart-line"></i> Training</a></li>
        <li><a href="assignments.php"><i class="fas fa-tasks"></i> Assignments</a></li>
        <li><a href="timetable.php"><i class="fas fa-clock"></i> Timetable</a></li>
        <li><a href="results.php"><i class="fas fa-chart-bar"></i> Results</a></li>
        <li><a href="projects.php"><i class="fas fa fa-diagram-project"></i> Projects</a></li>
        
        <hr class="sidebar-divider">
        <li style="color: #a09393; text-align: center;"><b>SERVICES</b></a></li>
        <a href="../auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        
    </ul>



</div>

<!-- CONTENT AREA -->
<div class="content">