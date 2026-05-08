<?php
/**
 * Role-based Access Control
 * Ensures proper authorization for all protected pages
 */

require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/helpers.php';

// Add this at the top of any protected page:
// require_once __DIR__ . '/../auth/check_role.php';
// checkRole(ROLE_STUDENT); // or [ROLE_STUDENT, ROLE_ADMIN] for multiple roles

/**
 * Check if user is authenticated
 * If not, redirect to login
 */
function requireAuthenticated() {
    if (!isLoggedIn()) {
        $_SESSION['return_url'] = $_SERVER['REQUEST_URI'];
        redirect('/auth/login.php');
    }
}

/**
 * Check if user has specific role(s)
 * If not, show 403 Forbidden
 */
function checkAccess($required_roles) {
    requireAuthenticated();
    
    if (!is_array($required_roles)) {
        $required_roles = [$required_roles];
    }
    
    $current_role = getCurrentUserRole();
    
    if (!in_array($current_role, $required_roles)) {
        logAction('unauthorized_access', 'access_control', getCurrentUserID(), 
                 'User attempted unauthorized access to ' . $_SERVER['REQUEST_URI'], LOG_WARNING);
        
        http_response_code(403);
        die('
            <div style="text-align: center; padding: 50px; font-family: Arial;">
                <h1>🚫 403 Forbidden</h1>
                <p>غير مصرح لك بالوصول لهذه الصفحة / You are not authorized to access this page</p>
                <a href="/">العودة للرئيسية / Go Home</a>
            </div>
        ');
    }
}

/**
 * Shorthand for student-only pages
 */
function studentOnly() {
    checkAccess(ROLE_STUDENT);
}

/**
 * Shorthand for doctor-only pages
 */
function doctorOnly() {
    checkAccess(ROLE_DOCTOR);
}

/**
 * Shorthand for admin-only pages
 */
function adminOnly() {
    checkAccess(ROLE_ADMIN);
}

/**
 * Shorthand for staff pages (doctor + admin)
 */
function staffOnly() {
    checkAccess([ROLE_DOCTOR, ROLE_ADMIN]);
}

/**
 * Shorthand for authenticated users (any role)
 */
function authRequired() {
    requireAuthenticated();
}

?>
