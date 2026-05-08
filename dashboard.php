<?php
session_start();

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../auth/check_access.php';

// Ensure only students can access
studentOnly();

$student_id = getCurrentUserID();
$student = getCurrentUser();

// Get database connection
$db = getDB();

// Get enrolled courses
$courses_stmt = $db->prepare("
    SELECT c.id, c.course_name, c.description, c.credits, d.full_name as doctor_name, c.created_at
    FROM enrollments e
    JOIN courses c ON e.course_id = c.id
    JOIN users d ON c.doctor_id = d.id
    WHERE e.student_id = ? AND e.status = ?
    ORDER BY c.course_name
");
$enrolled_status = ENROLLMENT_APPROVED;
$courses_stmt->bind_param("is", $student_id, $enrolled_status);
$courses_stmt->execute();
$enrolled_courses = $courses_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get pending enrollment requests
$requests_stmt = $db->prepare("
    SELECT c.course_name, er.status, er.created_at
    FROM enrollment_requests er
    JOIN courses c ON er.course_id = c.id
    WHERE er.student_id = ?
    ORDER BY er.created_at DESC
    LIMIT 5
");
$requests_stmt->bind_param("i", $student_id);
$requests_stmt->execute();
$pending_requests = $requests_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Count statistics
$total_courses = count($enrolled_courses);

// Get pending assignments
$assignments_stmt = $db->prepare("
    SELECT COUNT(*) as count FROM assignments a
    JOIN enrollments e ON a.course_id = e.course_id
    WHERE e.student_id = ? AND a.due_date > NOW()
");
$assignments_stmt->bind_param("i", $student_id);
$assignments_stmt->execute();
$pending_assignments = $assignments_stmt->get_result()->fetch_assoc()['count'] ?? 0;

// Get attendance percentage
$attendance_stmt = $db->prepare("
    SELECT COUNT(*) as total, 
           SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as present
    FROM attendance
    WHERE student_id = ?
");
$present_status = 'Present';
$attendance_stmt->bind_param("si", $present_status, $student_id);
$attendance_stmt->execute();
$attendance_result = $attendance_stmt->get_result()->fetch_assoc();
$attendance_percentage = ($attendance_result['total'] > 0) 
    ? round(($attendance_result['present'] / $attendance_result['total']) * 100) 
    : 0;

// Get average grade
$grades_stmt = $db->prepare("
    SELECT AVG(grade) as avg_grade FROM grades g
    JOIN enrollments e ON g.enrollment_id = e.id
    WHERE e.student_id = ?
");
$grades_stmt->bind_param("i", $student_id);
$grades_stmt->execute();
$avg_grade = $grades_stmt->get_result()->fetch_assoc()['avg_grade'] ?? 0;

// Get latest materials
$materials_stmt = $db->prepare("
    SELECT m.id, m.title, m.file, m.created_at, c.course_name
    FROM materials m
    JOIN courses c ON m.course_id = c.id
    JOIN enrollments e ON e.course_id = c.id
    WHERE e.student_id = ?
    ORDER BY m.created_at DESC 
    LIMIT 5
");
$materials_stmt->bind_param("i", $student_id);
$materials_stmt->execute();
$latest_materials = $materials_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Log dashboard access
logAction('view_dashboard', 'dashboard', null, 'Student viewed dashboard', LOG_INFO);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-i18n="student.dashboard">لوحة تحكم الطالب</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Custom Theme CSS -->
    <link rel="stylesheet" href="/assets/css/darkmode.css">

    <style>
        body {
            background-color: var(--bg-primary);
        }

        .page-container {
            display: flex;
            min-height: 100vh;
        }

        .main-content {
            flex: 1;
            padding: 30px;
            margin-top: 80px;
        }

        .page-header {
            margin-bottom: 30px;
        }

        .page-header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 10px;
        }

        .page-header p {
            color: var(--text-secondary);
            font-size: 1rem;
        }

        .stat-card {
            background-color: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }

        .stat-card-icon {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .stat-card-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .stat-card-label {
            color: var(--text-secondary);
            font-size: 0.95rem;
        }

        .stat-card.primary .stat-card-icon {
            color: var(--primary-color);
        }

        .stat-card.success .stat-card-icon {
            color: var(--success-color);
        }

        .stat-card.info .stat-card-icon {
            color: var(--info-color);
        }

        .stat-card.warning .stat-card-icon {
            color: var(--warning-color);
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-top: 30px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--border-color);
        }

        .btn-primary {
            background: linear-gradient(135deg, #353e67 0%, #6a5384 100%);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #2a3050 0%, #5a4874 100%);
        }

        .btn-primary-outline {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
        }

        .btn-primary-outline:hover {
            background-color: var(--primary-color);
            color: white;
        }

        .quick-action-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            border-radius: 10px;
            background-color: var(--bg-secondary);
            border: 2px solid var(--border-color);
            color: var(--text-primary);
            text-decoration: none;
            transition: all 0.3s ease;
            min-height: 150px;
        }

        .quick-action-btn:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-5px);
            text-decoration: none;
        }

        .quick-action-btn i {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .quick-action-btn span {
            font-weight: 600;
            text-align: center;
        }

        .course-card {
            background-color: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .course-card:hover {
            border-color: var(--primary-color);
            box-shadow: var(--shadow-md);
        }

        .course-card-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 10px;
        }

        .course-card-desc {
            color: var(--text-secondary);
            font-size: 0.95rem;
            margin-bottom: 15px;
        }

        .course-card-meta {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }

        .course-card-meta-item {
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        .course-card-meta-item i {
            margin-right: 8px;
            color: var(--primary-color);
        }

        .request-item {
            padding: 15px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .request-item:last-child {
            border-bottom: none;
        }

        .request-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .request-badge.pending {
            background-color: rgba(255, 193, 7, 0.2);
            color: var(--warning-color);
        }

        .request-badge.approved {
            background-color: rgba(76, 175, 80, 0.2);
            color: var(--success-color);
        }

        .request-badge.rejected {
            background-color: rgba(244, 67, 54, 0.2);
            color: var(--danger-color);
        }

        .material-item {
            padding: 15px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .material-item:last-child {
            border-bottom: none;
        }

        .material-info {
            flex: 1;
        }

        .material-info strong {
            color: var(--text-primary);
        }

        .material-info small {
            color: var(--text-secondary);
            display: block;
        }

        .table-container {
            background-color: var(--bg-secondary);
            border-radius: 10px;
            padding: 20px;
            border: 1px solid var(--border-color);
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: var(--text-secondary);
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 15px;
            }

            .page-header h1 {
                font-size: 1.5rem;
            }

            .stat-card-value {
                font-size: 1.5rem;
            }

            .quick-action-btn {
                min-height: 120px;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <?php include __DIR__ . '/../components/navbar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1>
                <i class="fas fa-graduation-cap"></i>
                <span data-i18n="student.dashboard">لوحة تحكم الطالب</span>
            </h1>
            <p data-i18n="dashboard.welcome">أهلاً وسهلاً</p>
        </div>

        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="stat-card primary">
                    <div class="stat-card-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="stat-card-value"><?php echo $total_courses; ?></div>
                    <div class="stat-card-label" data-i18n="student.enrolledCourses">مقررات مسجلة</div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="stat-card success">
                    <div class="stat-card-icon">
                        <i class="fas fa-percent"></i>
                    </div>
                    <div class="stat-card-value"><?php echo round($avg_grade, 1); ?></div>
                    <div class="stat-card-label" data-i18n="student.averageGrade">متوسط الدرجات</div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="stat-card info">
                    <div class="stat-card-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-card-value"><?php echo $attendance_percentage; ?>%</div>
                    <div class="stat-card-label" data-i18n="student.attendance">الحضور</div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="stat-card warning">
                    <div class="stat-card-icon">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div class="stat-card-value"><?php echo $pending_assignments; ?></div>
                    <div class="stat-card-label" data-i18n="student.pendingAssignments">واجبات معلقة</div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <h2 class="section-title" data-i18n="common.quickActions">الإجراءات السريعة</h2>
        <div class="row mb-4">
            <div class="col-md-4 col-sm-6 mb-3">
                <a href="/student/courses.php" class="quick-action-btn">
                    <i class="fas fa-search"></i>
                    <span data-i18n="student.browseCourses">تصفح المقررات</span>
                </a>
            </div>

            <div class="col-md-4 col-sm-6 mb-3">
                <a href="/student/assignments.php" class="quick-action-btn">
                    <i class="fas fa-file-upload"></i>
                    <span data-i18n="student.myAssignments">واجباتي</span>
                </a>
            </div>

            <div class="col-md-4 col-sm-6 mb-3">
                <a href="/student/results.php" class="quick-action-btn">
                    <i class="fas fa-chart-line"></i>
                    <span data-i18n="student.grades">درجاتي</span>
                </a>
            </div>
        </div>

        <!-- Enrolled Courses -->
        <h2 class="section-title" data-i18n="student.enrolledCourses">مقررات مسجلة</h2>
        <div class="row">
            <?php if (empty($enrolled_courses)): ?>
                <div class="col-12">
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p data-i18n="student.noCoursesEnrolled">لم تسجل في أي مقرر</p>
                        <a href="/student/courses.php" class="btn btn-primary mt-3">
                            <i class="fas fa-plus"></i>
                            <span data-i18n="student.browseCourses">تصفح المقررات</span>
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($enrolled_courses as $course): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="course-card">
                            <div class="course-card-title">
                                <?php echo htmlspecialchars($course['course_name']); ?>
                            </div>
                            <div class="course-card-desc">
                                <?php echo htmlspecialchars(substr($course['description'] ?? 'No description', 0, 100)) . '...'; ?>
                            </div>
                            <div class="course-card-meta">
                                <div class="course-card-meta-item">
                                    <i class="fas fa-user"></i>
                                    <?php echo htmlspecialchars($course['doctor_name']); ?>
                                </div>
                                <div class="course-card-meta-item">
                                    <i class="fas fa-star"></i>
                                    <?php echo $course['credits']; ?> وحدات
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="/student/view_course.php?id=<?php echo $course['id']; ?>" class="btn btn-sm btn-primary flex-grow-1">
                                    <i class="fas fa-eye"></i> عرض
                                </a>
                                <a href="/student/course_result.php?id=<?php echo $course['id']; ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-chart-bar"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Enrollment Requests -->
        <?php if (!empty($pending_requests)): ?>
        <h2 class="section-title" data-i18n="student.enrollmentStatus">حالة طلبات الالتحاق</h2>
        <div class="table-container">
            <?php foreach ($pending_requests as $request): ?>
                <div class="request-item">
                    <div>
                        <strong><?php echo htmlspecialchars($request['course_name']); ?></strong>
                        <small class="text-muted">
                            <?php echo date('d M Y', strtotime($request['created_at'])); ?>
                        </small>
                    </div>
                    <span class="request-badge <?php echo strtolower($request['status']); ?>">
                        <?php echo ucfirst($request['status']); ?>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Latest Course Materials -->
        <h2 class="section-title" data-i18n="student.latestMaterials">آخر المواد الدراسية</h2>
        <div class="table-container">
            <?php if (empty($latest_materials)): ?>
                <div class="empty-state">
                    <i class="fas fa-file-alt" style="font-size: 2rem;"></i>
                    <p data-i18n="common.noData">لا توجد بيانات</p>
                </div>
            <?php else: ?>
                <?php foreach ($latest_materials as $material): ?>
                    <div class="material-item">
                        <div class="material-info">
                            <strong>
                                <i class="fas fa-file-pdf"></i>
                                <?php echo htmlspecialchars($material['title']); ?>
                            </strong>
                            <small><?php echo htmlspecialchars($material['course_name']); ?></small>
                            <small class="d-block mt-1">
                                <?php echo date('d M Y H:i', strtotime($material['created_at'])); ?>
                            </small>
                        </div>
                        <a href="/uploads/materials/<?php echo urlencode($material['file']); ?>" 
                           class="btn btn-sm btn-primary" download>
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/i18n.js"></script>
    <script src="/assets/js/theme-switcher.js"></script>
    <script src="/assets/js/utils.js"></script>

    <script>
        // Initialize theme switcher
        ThemeSwitcher.init();
        
        // Apply translations
        document.querySelectorAll('[data-i18n]').forEach(el => {
            const key = el.getAttribute('data-i18n');
            el.textContent = t(key);
        });
    </script>
</body>
</html>