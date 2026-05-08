<?php
// Core Helper Functions

require_once __DIR__ . '/constants.php';

// ============================================
// DATABASE & SESSION HELPERS
// ============================================

/**
 * Get database connection
 */
function getDB() {
    static $conn = null;
    
    if ($conn === null) {
        $conn = new mysqli("localhost", "root", "", "lms");
        
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $conn->set_charset("utf8mb4");
    }
    
    return $conn;
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Get current user ID
 */
function getCurrentUserID() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Get current user role
 */
function getCurrentUserRole() {
    return $_SESSION['role'] ?? null;
}

/**
 * Get current user info
 */
function getCurrentUser() {
    if (!isLoggedIn()) return null;
    
    $db = getDB();
    $user_id = getCurrentUserID();
    
    $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    
    return $stmt->get_result()->fetch_assoc();
}

/**
 * Check if user has required role
 */
function checkRole($required_role) {
    if (!isLoggedIn()) {
        return false;
    }
    
    $current_role = getCurrentUserRole();
    
    if (is_array($required_role)) {
        return in_array($current_role, $required_role);
    }
    
    return $current_role === $required_role;
}

/**
 * Redirect if not authenticated
 */
function requireLogin() {
    if (!isLoggedIn()) {
        redirect('/auth/login.php');
    }
}

/**
 * Redirect if role doesn't match
 */
function requireRole($roles) {
    requireLogin();
    
    if (!checkRole($roles)) {
        logAction('unauthorized_access', 'authorization', null, MSG_UNAUTHORIZED, LOG_WARNING);
        die("🚫 غير مصرح / Unauthorized Access");
    }
}

// ============================================
// UTILITY FUNCTIONS
// ============================================

/**
 * Redirect to URL
 */
function redirect($url) {
    header("Location: " . $url);
    exit();
}

/**
 * Sanitize input
 */
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(stripslashes(trim($data)), ENT_QUOTES, 'UTF-8');
}

/**
 * Hash password
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
}

/**
 * Verify password
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Validate email format
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate BTU institutional email
 */
function isValidBtuEmail($email) {
    return preg_match('/^[a-zA-Z0-9_.]+@btu\.edu\.eg$/', $email);
}

/**
 * Get user by email
 */
function getUserByEmail($email) {
    $db = getDB();
    $email = sanitize($email);
    
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    
    return $stmt->get_result()->fetch_assoc();
}

/**
 * Get user by ID
 */
function getUserByID($id) {
    $db = getDB();
    
    $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    return $stmt->get_result()->fetch_assoc();
}

// ============================================
// SYSTEM LOGGING
// ============================================

/**
 * Log system action
 */
function logAction($action, $entity_type, $entity_id = null, $message = null, $status = LOG_INFO) {
    $db = getDB();
    $user_id = isLoggedIn() ? getCurrentUserID() : null;
    $ip_address = $_SERVER['REMOTE_ADDR'];
    
    $stmt = $db->prepare("
        INSERT INTO system_logs (user_id, action, entity_type, entity_id, message, status, ip_address)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->bind_param("ississs", $user_id, $action, $entity_type, $entity_id, $message, $status, $ip_address);
    return $stmt->execute();
}

/**
 * Show success message
 */
function showSuccess($message) {
    logAction('operation', 'message', null, $message, LOG_SUCCESS);
    return [
        'success' => true,
        'message' => $message
    ];
}

/**
 * Show error message
 */
function showError($message) {
    logAction('operation', 'message', null, $message, LOG_ERROR);
    return [
        'success' => false,
        'message' => $message
    ];
}

// ============================================
// ENROLLMENT & COURSE HELPERS
// ============================================

/**
 * Check if student is enrolled in course
 */
function isEnrolled($student_id, $course_id) {
    $db = getDB();
    
    $stmt = $db->prepare("
        SELECT id FROM enrollments 
        WHERE student_id = ? AND course_id = ?
    ");
    $stmt->bind_param("ii", $student_id, $course_id);
    $stmt->execute();
    
    return $stmt->get_result()->num_rows > 0;
}

/**
 * Check if enrollment request exists
 */
function hasEnrollmentRequest($student_id, $course_id) {
    $db = getDB();
    
    $stmt = $db->prepare("
        SELECT id, status FROM enrollment_requests 
        WHERE student_id = ? AND course_id = ?
    ");
    $stmt->bind_param("ii", $student_id, $course_id);
    $stmt->execute();
    
    return $stmt->get_result()->fetch_assoc();
}

/**
 * Get course name
 */
function getCourseName($course_id) {
    $db = getDB();
    
    $stmt = $db->prepare("SELECT course_name FROM courses WHERE id = ?");
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    
    $result = $stmt->get_result()->fetch_assoc();
    return $result['course_name'] ?? 'Unknown Course';
}

/**
 * Get doctor name
 */
function getDoctorName($doctor_id) {
    $db = getDB();
    
    $stmt = $db->prepare("SELECT name FROM users WHERE id = ? AND role = 'doctor'");
    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();
    
    $result = $stmt->get_result()->fetch_assoc();
    return $result['name'] ?? 'Unknown Doctor';
}

/**
 * Get student courses
 */
function getStudentCourses($student_id) {
    $db = getDB();
    
    $stmt = $db->prepare("
        SELECT c.*, u.name as doctor_name 
        FROM courses c
        INNER JOIN enrollments e ON c.id = e.course_id
        LEFT JOIN users u ON c.doctor_id = u.id
        WHERE e.student_id = ?
        ORDER BY c.course_name
    ");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

/**
 * Get doctor courses
 */
function getDoctorCourses($doctor_id) {
    $db = getDB();
    
    $stmt = $db->prepare("
        SELECT * FROM courses 
        WHERE doctor_id = ?
        ORDER BY course_name
    ");
    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();
    
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

/**
 * Calculate attendance percentage
 */
function getAttendancePercentage($student_id, $course_id) {
    $db = getDB();
    
    $stmt = $db->prepare("
        SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present
        FROM attendance 
        WHERE student_id = ? AND course_id = ?
    ");
    $stmt->bind_param("ii", $student_id, $course_id);
    $stmt->execute();
    
    $result = $stmt->get_result()->fetch_assoc();
    
    if ($result['total'] == 0) {
        return 0;
    }
    
    return round(($result['present'] / $result['total']) * 100, 2);
}

/**
 * Check institutional email format
 */
function validateStudentEmail($email) {
    // Format: FirstName.202481@btu.edu.eg (where 202481 is student ID)
    return preg_match('/^[a-zA-Z0-9_.]+\.\d{6}@btu\.edu\.eg$/', $email);
}

// ============================================
// FILE UPLOAD HELPERS
// ============================================

/**
 * Validate and upload file
 */
function uploadFile($file, $target_dir, $max_size = MAX_MATERIAL_SIZE) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }
    
    if ($file['size'] > $max_size) {
        return false;
    }
    
    $filename = uniqid() . '_' . basename($file['name']);
    $target_file = __DIR__ . '/..' . $target_dir . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        return $filename;
    }
    
    return false;
}

?>
