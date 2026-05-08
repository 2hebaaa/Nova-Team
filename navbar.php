<!-- 
Shared Navbar Component
Include this in all pages with: <?php include __DIR__ . '/../components/navbar.php'; ?>

This navbar includes:
- Responsive menu
- Language switcher (AR/EN)
- Dark/Light mode toggle
- User profile menu
- Logout button
-->

<?php
if (!isset($_SESSION)) {
    session_start();
}

// Get current user info if logged in
$current_user = null;
$user_role = null;
if (isset($_SESSION['user_id'])) {
    require_once __DIR__ . '/../config/helpers.php';
    $current_user = getCurrentUser();
    $user_role = $_SESSION['role'] ?? null;
}
?>

<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container-fluid px-4">
        <!-- Brand -->
        <a class="navbar-brand" href="/">
            <i class="fas fa-book-reader"></i> 
            <span>NOVA System</span>
        </a>
        
        <!-- Toggler for mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Navbar Content -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <!-- Left menu items -->
            <ul class="navbar-nav me-auto">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- Menu for logged-in users -->
                    <?php if ($user_role === ROLE_STUDENT): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/student/dashboard.php" data-i18n="nav.dashboard">
                                <i class="fas fa-home"></i> لوحة التحكم
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/student/courses.php" data-i18n="student.courses">
                                <i class="fas fa-book"></i> المقررات
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/student/assignments.php" data-i18n="student.assignments">
                                <i class="fas fa-tasks"></i> الواجبات
                            </a>
                        </li>
                    <?php elseif ($user_role === ROLE_DOCTOR): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/doctor/dashboard.php" data-i18n="nav.dashboard">
                                <i class="fas fa-home"></i> لوحة التحكم
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/doctor/courses.php" data-i18n="doctor.myCourses">
                                <i class="fas fa-book"></i> مقرراتي
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/doctor/students.php" data-i18n="doctor.students">
                                <i class="fas fa-users"></i> الطلاب
                            </a>
                        </li>
                    <?php elseif ($user_role === ROLE_ADMIN): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/dashboard.php" data-i18n="admin.dashboard">
                                <i class="fas fa-home"></i> لوحة التحكم
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/users.php" data-i18n="admin.userManagement">
                                <i class="fas fa-users"></i> إدارة المستخدمين
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/courses.php" data-i18n="admin.courseManagement">
                                <i class="fas fa-book"></i> إدارة المقررات
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/settings.php" data-i18n="admin.settings">
                                <i class="fas fa-cog"></i> الإعدادات
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
            
            <!-- Right menu items -->
            <ul class="navbar-nav ms-auto align-items-center">
                <!-- Language Switcher -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="languageDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-language"></i>
                        <span data-i18n="common.language">اللغة</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
                        <li><a class="dropdown-item" href="#" onclick="setLanguage('ar'); return false;">
                            <i class="fas fa-check me-2"></i> العربية
                        </a></li>
                        <li><a class="dropdown-item" href="#" onclick="setLanguage('en'); return false;">
                            <i class="fas fa-check me-2"></i> English
                        </a></li>
                    </ul>
                </li>
                
                <!-- Theme Toggler -->
                <li class="nav-item">
                    <button class="btn btn-sm btn-outline-secondary me-2" data-theme-toggle style="border-radius: 20px;">
                        <i class="fas fa-moon"></i>
                    </button>
                </li>
                
                <!-- Notifications (if needed) -->
                <?php if (isset($_SESSION['user_id'])): ?>
                <li class="nav-item">
                    <a class="nav-link position-relative" href="#" title="Notifications">
                        <i class="fas fa-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                            0
                        </span>
                    </a>
                </li>
                <?php endif; ?>
                
                <!-- User Profile / Login -->
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <img src="/uploads/profiles/<?php echo htmlspecialchars($current_user['image'] ?? 'default.png'); ?>" 
                                 alt="Profile" 
                                 style="width: 30px; height: 30px; border-radius: 50%; margin-right: 8px; object-fit: cover;">
                            <span class="d-none d-lg-inline" style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                <?php echo htmlspecialchars($current_user['name'] ?? 'User'); ?>
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <h6 class="dropdown-header">
                                    <?php echo htmlspecialchars($current_user['email'] ?? ''); ?>
                                </h6>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="<?php 
                                    if ($user_role === ROLE_STUDENT) echo '/student/profile.php';
                                    elseif ($user_role === ROLE_DOCTOR) echo '/doctor/profile.php';
                                    else echo '/admin/profile.php';
                                ?>">
                                    <i class="fas fa-user-circle me-2"></i>
                                    <span data-i18n="nav.profile">الملف الشخصي</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?php 
                                    if ($user_role === ROLE_STUDENT) echo '/student/profile.php?tab=settings';
                                    elseif ($user_role === ROLE_DOCTOR) echo '/doctor/profile.php?tab=settings';
                                    else echo '/admin/profile.php?tab=settings';
                                ?>">
                                    <i class="fas fa-cog me-2"></i>
                                    <span data-i18n="nav.settings">الإعدادات</span>
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="/auth/logout.php">
                                    <i class="fas fa-sign-out-alt me-2"></i>
                                    <span data-i18n="nav.logout">تسجيل الخروج</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="btn btn-outline-primary btn-sm ms-2" href="/auth/login.php">
                            <i class="fas fa-sign-in-alt me-1"></i>
                            <span data-i18n="nav.login">تسجيل الدخول</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<style>
.navbar-custom {
    background-color: var(--bg-primary);
    border-bottom: 1px solid var(--border-color);
    box-shadow: var(--shadow-sm);
    padding: 0.5rem 0;
}

.navbar-custom .nav-link {
    color: var(--text-primary) !important;
    transition: color var(--transition-speed) ease;
    font-weight: 500;
    padding: 0.5rem 0.75rem !important;
}

.navbar-custom .nav-link:hover {
    color: var(--primary-color) !important;
}

.navbar-custom .navbar-brand {
    font-family: 'Poppins', sans-serif;
    font-size: 1.3rem;
    font-weight: 700;
    background: linear-gradient(135deg, #353e67 0%, #6a5384 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.dropdown-menu {
    background-color: var(--bg-secondary);
    border: 1px solid var(--border-color);
}

.dropdown-item {
    color: var(--text-primary);
}

.dropdown-item:hover,
.dropdown-item.active {
    background-color: var(--bg-tertiary);
    color: var(--primary-color);
}

.dropdown-divider {
    border-color: var(--border-color);
}

@media (max-width: 991.98px) {
    .navbar-custom .navbar-brand {
        font-size: 1.2rem;
    }
}
</style>
