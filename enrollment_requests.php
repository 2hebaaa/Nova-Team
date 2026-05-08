<?php
/**
 * Enrollment Request Management API
 * Handles student enrollment requests and doctor approvals
 */

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/helpers.php';

header('Content-Type: application/json');

session_start();

// Ensure user is authenticated
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$response = [];

// ============================================
// STUDENT: REQUEST ENROLLMENT
// ============================================
if ($action === 'request_enrollment' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!checkRole(ROLE_STUDENT)) {
        http_response_code(403);
        $response = ['success' => false, 'message' => MSG_UNAUTHORIZED];
    } else {
        $student_id = getCurrentUserID();
        $course_id = intval($_POST['course_id'] ?? 0);
        
        if ($course_id <= 0) {
            $response = ['success' => false, 'message' => 'Invalid course'];
        } elseif (isEnrolled($student_id, $course_id)) {
            $response = ['success' => false, 'message' => 'Already enrolled in this course'];
        } else {
            $db = getDB();
            $pending = ENROLLMENT_PENDING;
            
            $stmt = $db->prepare("
                INSERT INTO enrollment_requests (student_id, course_id, status)
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE status = VALUES(status)
            ");
            
            $stmt->bind_param("iis", $student_id, $course_id, $pending);
            
            if ($stmt->execute()) {
                logAction('enrollment_request', 'enrollment', $course_id, 
                         'Student requested enrollment', LOG_SUCCESS);
                $response = ['success' => true, 'message' => 'تم إرسال طلب الالتحاق بنجاح / Request sent successfully'];
            } else {
                $response = ['success' => false, 'message' => 'Error sending request'];
            }
        }
    }
}

// ============================================
// DOCTOR: GET PENDING REQUESTS
// ============================================
else if ($action === 'get_pending_requests' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!checkRole(ROLE_DOCTOR)) {
        http_response_code(403);
        $response = ['success' => false, 'message' => MSG_UNAUTHORIZED];
    } else {
        $doctor_id = getCurrentUserID();
        $course_id = intval($_GET['course_id'] ?? 0);
        
        $db = getDB();
        
        if ($course_id > 0) {
            // Get requests for specific course
            $stmt = $db->prepare("
                SELECT er.*, u.name as student_name, u.email as student_email
                FROM enrollment_requests er
                JOIN users u ON er.student_id = u.id
                JOIN courses c ON er.course_id = c.id
                WHERE c.doctor_id = ? AND er.course_id = ? AND er.status = ?
                ORDER BY er.requested_at DESC
            ");
            
            $status = ENROLLMENT_PENDING;
            $stmt->bind_param("iis", $doctor_id, $course_id, $status);
        } else {
            // Get all pending requests for doctor's courses
            $stmt = $db->prepare("
                SELECT er.*, u.name as student_name, u.email as student_email,
                       c.course_name
                FROM enrollment_requests er
                JOIN users u ON er.student_id = u.id
                JOIN courses c ON er.course_id = c.id
                WHERE c.doctor_id = ? AND er.status = ?
                ORDER BY er.requested_at DESC
            ");
            
            $status = ENROLLMENT_PENDING;
            $stmt->bind_param("is", $doctor_id, $status);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $requests = $result->fetch_all(MYSQLI_ASSOC);
        
        $response = [
            'success' => true,
            'data' => $requests,
            'count' => count($requests)
        ];
    }
}

// ============================================
// DOCTOR: APPROVE ENROLLMENT REQUEST
// ============================================
else if ($action === 'approve_request' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!checkRole(ROLE_DOCTOR)) {
        http_response_code(403);
        $response = ['success' => false, 'message' => MSG_UNAUTHORIZED];
    } else {
        $doctor_id = getCurrentUserID();
        $request_id = intval($_POST['request_id'] ?? 0);
        
        $db = getDB();
        
        // Verify request belongs to this doctor's course
        $verify_stmt = $db->prepare("
            SELECT er.student_id, er.course_id
            FROM enrollment_requests er
            JOIN courses c ON er.course_id = c.id
            WHERE er.id = ? AND c.doctor_id = ?
        ");
        
        $verify_stmt->bind_param("ii", $request_id, $doctor_id);
        $verify_stmt->execute();
        $result = $verify_stmt->get_result();
        
        if ($result->num_rows === 0) {
            $response = ['success' => false, 'message' => 'Request not found'];
        } else {
            $row = $result->fetch_assoc();
            $student_id = $row['student_id'];
            $course_id = $row['course_id'];
            
            // Begin transaction
            $db->begin_transaction();
            
            try {
                // Create enrollment
                $group_id = 'A'; // Default group
                $enroll_stmt = $db->prepare("
                    INSERT INTO enrollments (student_id, course_id, group_id)
                    VALUES (?, ?, ?)
                ");
                $enroll_stmt->bind_param("iis", $student_id, $course_id, $group_id);
                $enroll_stmt->execute();
                
                // Update request status
                $approved = ENROLLMENT_APPROVED;
                $now = date('Y-m-d H:i:s');
                $update_stmt = $db->prepare("
                    UPDATE enrollment_requests
                    SET status = ?, responded_at = ?
                    WHERE id = ?
                ");
                $update_stmt->bind_param("ssi", $approved, $now, $request_id);
                $update_stmt->execute();
                
                $db->commit();
                
                logAction('enrollment_approved', 'enrollment', $course_id, 
                         'Student enrollment approved', LOG_SUCCESS);
                $response = ['success' => true, 'message' => 'تم قبول الطالب / Student approved'];
                
            } catch (Exception $e) {
                $db->rollback();
                $response = ['success' => false, 'message' => 'Error processing request'];
            }
        }
    }
}

// ============================================
// DOCTOR: REJECT ENROLLMENT REQUEST
// ============================================
else if ($action === 'reject_request' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!checkRole(ROLE_DOCTOR)) {
        http_response_code(403);
        $response = ['success' => false, 'message' => MSG_UNAUTHORIZED];
    } else {
        $doctor_id = getCurrentUserID();
        $request_id = intval($_POST['request_id'] ?? 0);
        $reason = sanitize($_POST['reason'] ?? '');
        
        $db = getDB();
        
        // Verify request belongs to this doctor's course
        $verify_stmt = $db->prepare("
            SELECT er.course_id
            FROM enrollment_requests er
            JOIN courses c ON er.course_id = c.id
            WHERE er.id = ? AND c.doctor_id = ?
        ");
        
        $verify_stmt->bind_param("ii", $request_id, $doctor_id);
        $verify_stmt->execute();
        $result = $verify_stmt->get_result();
        
        if ($result->num_rows === 0) {
            $response = ['success' => false, 'message' => 'Request not found'];
        } else {
            $rejected = ENROLLMENT_REJECTED;
            $now = date('Y-m-d H:i:s');
            
            $update_stmt = $db->prepare("
                UPDATE enrollment_requests
                SET status = ?, responded_at = ?, rejected_reason = ?
                WHERE id = ?
            ");
            $update_stmt->bind_param("sssi", $rejected, $now, $reason, $request_id);
            
            if ($update_stmt->execute()) {
                logAction('enrollment_rejected', 'enrollment', $request_id, 
                         'Student enrollment rejected: ' . $reason, LOG_INFO);
                $response = ['success' => true, 'message' => 'تم رفض الطلب / Request rejected'];
            } else {
                $response = ['success' => false, 'message' => 'Error processing request'];
            }
        }
    }
}

// ============================================
// STUDENT: GET ENROLLMENT REQUEST STATUS
// ============================================
else if ($action === 'get_request_status' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!checkRole(ROLE_STUDENT)) {
        http_response_code(403);
        $response = ['success' => false, 'message' => MSG_UNAUTHORIZED];
    } else {
        $student_id = getCurrentUserID();
        $course_id = intval($_GET['course_id'] ?? 0);
        
        $db = getDB();
        
        $stmt = $db->prepare("
            SELECT id, status, requested_at, responded_at, rejected_reason
            FROM enrollment_requests
            WHERE student_id = ? AND course_id = ?
        ");
        $stmt->bind_param("ii", $student_id, $course_id);
        $stmt->execute();
        
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $request = $result->fetch_assoc();
            $response = ['success' => true, 'data' => $request];
        } else {
            $response = ['success' => true, 'data' => null];
        }
    }
}

// ============================================
// STUDENT: WITHDRAW FROM COURSE
// ============================================
else if ($action === 'withdraw_course' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!checkRole(ROLE_STUDENT)) {
        http_response_code(403);
        $response = ['success' => false, 'message' => MSG_UNAUTHORIZED];
    } else {
        $student_id = getCurrentUserID();
        $course_id = intval($_POST['course_id'] ?? 0);
        
        $db = getDB();
        
        // Delete enrollment
        $stmt = $db->prepare("
            DELETE FROM enrollments
            WHERE student_id = ? AND course_id = ?
        ");
        $stmt->bind_param("ii", $student_id, $course_id);
        
        if ($stmt->execute()) {
            logAction('enrollment_withdrawn', 'enrollment', $course_id, 
                     'Student withdrew from course', LOG_SUCCESS);
            $response = ['success' => true, 'message' => 'تم الانسحاب من المقرر / Withdrawn successfully'];
        } else {
            $response = ['success' => false, 'message' => 'Error withdrawing'];
        }
    }
}

// ============================================
// ADMIN: GET ALL ENROLLMENT REQUESTS
// ============================================
else if ($action === 'admin_get_requests' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!checkRole(ROLE_ADMIN)) {
        http_response_code(403);
        $response = ['success' => false, 'message' => MSG_UNAUTHORIZED];
    } else {
        $db = getDB();
        $status = $_GET['status'] ?? 'pending';
        
        $stmt = $db->prepare("
            SELECT er.*, 
                   u1.name as student_name, u1.email as student_email,
                   u2.name as doctor_name,
                   c.course_name
            FROM enrollment_requests er
            JOIN users u1 ON er.student_id = u1.id
            JOIN courses c ON er.course_id = c.id
            LEFT JOIN users u2 ON c.doctor_id = u2.id
            WHERE er.status = ?
            ORDER BY er.requested_at DESC
        ");
        $stmt->bind_param("s", $status);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $requests = $result->fetch_all(MYSQLI_ASSOC);
        
        $response = [
            'success' => true,
            'data' => $requests,
            'count' => count($requests)
        ];
    }
}

else {
    http_response_code(400);
    $response = ['success' => false, 'message' => 'Invalid action'];
}

echo json_encode($response);
?>
