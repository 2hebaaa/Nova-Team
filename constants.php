<?php
// Application Constants & Configuration

// ============================================
// USER ROLES
// ============================================
define('ROLE_STUDENT', 'student');
define('ROLE_DOCTOR', 'doctor');
define('ROLE_ADMIN', 'admin');

// ============================================
// ENROLLMENT REQUEST STATUSES
// ============================================
define('ENROLLMENT_PENDING', 'pending');
define('ENROLLMENT_APPROVED', 'approved');
define('ENROLLMENT_REJECTED', 'rejected');
define('ENROLLMENT_WITHDRAWN', 'withdrawn');

// ============================================
// ATTENDANCE STATUS
// ============================================
define('ATTENDANCE_PRESENT', 'present');
define('ATTENDANCE_ABSENT', 'absent');

// ============================================
// ASSIGNMENT TYPES
// ============================================
define('TYPE_ASSIGNMENT', 'assignment');
define('TYPE_QUIZ', 'quiz');

// ============================================
// MATERIAL TYPES
// ============================================
define('MATERIAL_LECTURE', 'lecture');
define('MATERIAL_SECTION', 'section');
define('MATERIAL_RESOURCE', 'resource');

// ============================================
// LOG STATUS
// ============================================
define('LOG_SUCCESS', 'success');
define('LOG_ERROR', 'error');
define('LOG_WARNING', 'warning');
define('LOG_INFO', 'info');

// ============================================
// UPLOAD PATHS
// ============================================
define('UPLOAD_PROFILES', '/uploads/profiles/');
define('UPLOAD_MATERIALS', '/uploads/materials/');
define('UPLOAD_ASSIGNMENTS', '/uploads/assignments/');
define('UPLOAD_PROJECTS', '/uploads/projects/');

// ============================================
// FILE SIZE LIMITS (in bytes)
// ============================================
define('MAX_PROFILE_SIZE', 5242880); // 5MB
define('MAX_MATERIAL_SIZE', 52428800); // 50MB
define('MAX_ASSIGNMENT_SIZE', 52428800); // 50MB

// ============================================
// PAGINATION
// ============================================
define('ITEMS_PER_PAGE', 15);

// ============================================
// MESSAGES
// ============================================
define('MSG_SUCCESS', 'تم بنجاح / Success');
define('MSG_ERROR', 'حدث خطأ / Error');
define('MSG_INVALID_ROLE', 'دور غير صحيح / Invalid Role');
define('MSG_UNAUTHORIZED', 'غير مصرح / Unauthorized');
?>
