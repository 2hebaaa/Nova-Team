<?php 
include 'layout.php'; 
require_once("../config/db.php");

$student_id = (int) $_SESSION['user_id'] ?? 0;

if (!$student_id) {
    header("Location: ../auth/login.php");
    exit();
}

$message = '';
$message_type = '';

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    if (empty($name)) {
        $message = "Name cannot be empty";
        $message_type = "danger";
    } else {
        if (!empty($password)) {
            if (strlen($password) < 6) {
                $message = "Password must be at least 6 characters";
                $message_type = "danger";
            } else {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE users SET name = ?, password = ? WHERE id = ?");
                
                if ($stmt) {
                    $stmt->bind_param("ssi", $name, $hashed, $student_id);
                    if ($stmt->execute()) {
                        $_SESSION['name'] = $name;
                        $message = "Profile updated successfully!";
                        $message_type = "success";
                    } else {
                        $message = "Error updating profile. Please try again.";
                        $message_type = "danger";
                    }
                    $stmt->close();
                }
            }
        } else {
            $stmt = $conn->prepare("UPDATE users SET name = ? WHERE id = ?");
            
            if ($stmt) {
                $stmt->bind_param("si", $name, $student_id);
                if ($stmt->execute()) {
                    $_SESSION['name'] = $name;
                    $message = "Profile updated successfully!";
                    $message_type = "success";
                } else {
                    $message = "Error updating profile. Please try again.";
                    $message_type = "danger";
                }
                $stmt->close();
            }
        }
    }
}

// Get user data
$stmt = $conn->prepare("SELECT id, name, email, role FROM users WHERE id = ?");
if ($stmt) {
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();
} else {
    $user = null;
}

if (!$user) {
    die("<div class='alert alert-danger m-4'><i class='fas fa-exclamation-circle'></i> User not found</div>");
}
?>

<div class="page-header">
    <h2><i class="fas fa-user-circle"></i> My Profile</h2>
    <p>Update your profile information</p>
</div>

<?php if (!empty($message)): ?>
    <div class="alert alert-<?= htmlspecialchars($message_type) ?> alert-dismissible fade show" role="alert">
        <i class="fas fa-<?= $message_type === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
        <?= htmlspecialchars($message) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Update Profile</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-user"></i> Full Name</label>
                        <input 
                            type="text" 
                            name="name" 
                            value="<?= htmlspecialchars($user['name'] ?? '') ?>" 
                            class="form-control" 
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-envelope"></i> Email</label>
                        <input 
                            type="email" 
                            value="<?= htmlspecialchars($user['email'] ?? '') ?>" 
                            class="form-control" 
                            disabled
                        >
                        <small class="text-muted">Email cannot be changed</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-key"></i> New Password</label>
                        <input 
                            type="password" 
                            name="password" 
                            placeholder="Leave empty to keep current password" 
                            class="form-control"
                        >
                        <small class="text-muted">Minimum 6 characters</small>
                    </div>

                    <button type="submit" name="update" class="btn btn-dark w-100">
                        <i class="fas fa-save"></i> Update Profile
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Account Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted d-block">Student ID</small>
                    <strong><?= (int) ($user['id'] ?? 0) ?></strong>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block">Role</small>
                    <strong><?= ucfirst(htmlspecialchars($user['role'] ?? 'student')) ?></strong>
                </div>

                <div class="mb-0">
                    <small class="text-muted d-block">Account Status</small>
                    <span class="badge badge-success">Active</span>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
</body>
</html>