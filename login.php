<?php
session_start();

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/helpers.php';

// Redirect to landing page if already logged in
if (isLoggedIn()) {
    $role = getCurrentUserRole();
    if ($role === ROLE_ADMIN) {
        redirect('/admin/dashboard.php');
    } elseif ($role === ROLE_DOCTOR) {
        redirect('/doctor/dashboard.php');
    } elseif ($role === ROLE_STUDENT) {
        redirect('/student/dashboard.php');
    }
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validate BTU institutional email
    if (!isValidBtuEmail($email)) {
        $error = 'يجب استخدام بريد جامعي صحيح (name.id@btu.edu.eg) / Invalid BTU Email Format';
        logAction('login_attempt', 'authentication', null, 'Invalid email format: ' . $email, LOG_WARNING);
    } elseif (empty($password)) {
        $error = 'كلمة المرور مطلوبة / Password is required';
    } else {
        // Get user by email
        $user = getUserByEmail($email);
        
        if ($user && verifyPassword($password, $user['password'])) {
            // Successful login
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['image'] = $user['image'];
            
            // Log successful login
            logAction('login', 'authentication', $user['id'], 'User logged in successfully', LOG_SUCCESS);
            
            // Redirect to appropriate dashboard
            $role = $user['role'];
            if ($role === ROLE_ADMIN) {
                redirect('/admin/dashboard.php');
            } elseif ($role === ROLE_DOCTOR) {
                redirect('/doctor/dashboard.php');
            } else {
                redirect('/student/dashboard.php');
            }
        } else {
            // Failed login
            $error = 'بيانات الدخول غير صحيحة / Invalid credentials';
            logAction('login_attempt', 'authentication', null, 'Failed login attempt for email: ' . $email, LOG_WARNING);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - BTU LMS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: white;
            overflow: hidden;
        }

        .login-container {
            display: flex;
            height: 100vh;
            align-items: stretch;
        }

        /* LEFT SIDE - PURPLE */
        .login-left {
            flex: 0 0 50%;
            background: linear-gradient(135deg, #614b6f 0%, #4a3a53 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 80px 60px;
            position: relative;
            overflow: hidden;
            color: white;
        }

        .login-left::before {
            content: "";
            position: absolute;
            top: -50%;
            right: -10%;
            width: 700px;
            height: 700px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .login-left::after {
            content: "";
            position: absolute;
            bottom: -40%;
            left: -5%;
            width: 600px;
            height: 600px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
        }

        .left-content {
            position: relative;
            z-index: 1;
            text-align: center;
            max-width: 420px;
        }

        .left-logo {
            margin-bottom: 40px;
        }

        .left-logo img {
            max-width: 100px;
            height: auto;
            opacity: 0.95;
            margin-bottom: 12px;
        }

        .left-logo-text {
            font-size: 0.75rem;
            opacity: 0.85;
            letter-spacing: 1.5px;
            font-weight: 500;
            margin-bottom: 4px;
            color:white;
        }
        .left-logo-text1 {
            font-size: 0.95rem;
            opacity: 0.85;
            letter-spacing: 1.5px;
            font-weight: 500;
            margin-bottom: 4px;
            color:white;
        }

        .left-title {
            font-family: 'Poppins', sans-serif;
            font-size: 2.5rem;
            font-weight: 700;
            margin: 30px 0 20px;
            line-height: 1.2;
        }

        .left-subtitle {
            font-size: 1rem;
            opacity: 0.95;
            margin-bottom: 50px;
            line-height: 1.6;
            font-weight: 400;
        }

        .left-features {
            text-align: left;
            width: 100%;
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 22px;
            font-size: 0.95rem;
            opacity: 0.95;
            font-weight: 400;
        }

        .feature-icon {
            width: 28px;
            height: 28px;
            background: rgba(255, 255, 255, 0.25);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 14px;
            flex-shrink: 0;
            border: 1.5px solid rgba(255, 255, 255, 0.4);
        }

        .feature-icon i {
            font-size: 0.8rem;
        }

        /* RIGHT SIDE - WHITE */
        .login-right {
            flex: 1;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 80px 60px;
            position: relative;
        }

        .form-wrapper {
            width: 65%;
            padding: 40px;
            border-radius: 15px;
            height: 500px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            background: white;
            animation: fadeIn 1s ease;
        }

        .form-header {
            margin-bottom: 40px;
        }

        .form-header h1 {
            font-family: 'Poppins', sans-serif;
            font-size: 2rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 12px;
            letter-spacing: -0.5px;
        }

        .form-header p {
            color: #888;
            font-size: 0.95rem;
            margin: 0;
        }

        .alert-error {
            background: #fee;
            color: #c33;
            padding: 14px 16px;
            border-radius: 8px;
            margin-bottom: 28px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.9rem;
            border-left: 4px solid #c33;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .alert-error i {
            font-size: 1.1rem;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 10px;
            font-size: 0.9rem;
        }

        .form-control {
            width: 100%;
            padding: 13px 16px;
            border: 1.5px solid #ddd;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: #f9f9f9;
            font-weight: 400;
        }

        .form-control:focus {
            outline: none;
            border-color: #614b6f;
            background: white;
            box-shadow: 0 0 0 3px rgba(97, 75, 111, 0.08);
        }

        .form-control::placeholder {
            color: #bbb;
        }

        .remember-section {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            gap: 10px;
        }

        .remember-toggle {
            width: 44px;
            height: 26px;
            background: #ddd;
            border-radius: 13px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            border: none;
            padding: 2px;
            flex-shrink: 0;
        }

        .remember-toggle::after {
            content: '';
            position: absolute;
            width: 22px;
            height: 22px;
            background: white;
            border-radius: 50%;
            top: 2px;
            left: 2px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        #remember:checked + .remember-toggle {
            background: #806393;
        }

        #remember:checked + .remember-toggle::after {
            left: 20px;
        }

        .remember-label {
            color: #666;
            font-size: 0.9rem;
            cursor: pointer;
            margin: 0;
            font-weight: 400;
        }

        #remember {
            display: none;
        }

        .btn-signin {
            width: 100%;
            padding: 13px 20px;
            background: linear-gradient(135deg, #745685 0%, #6f558f 100%);
            color: white;
            border: none;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 24px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 12px rgba(97, 75, 111, 0.3);
        }

        .btn-signin:hover {
            background: linear-gradient(135deg, #724e73 0%, #61536c 100%);
            box-shadow: 0 6px 20px rgba(97, 75, 111, 0.4);
            transform: translateY(-2px);
        }

        .btn-signin:active {
            transform: translateY(0);
        }

        .divider {
            text-align: center;
            margin: 24px 0;
            color: #999;
            font-size: 0.85rem;
            font-weight: 400;
        }

        .btn-google {
            width: 100%;
            padding: 12px 20px;
            background: white;
            color: #333;
            border: 1.5px solid #ddd;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-google:hover {
            background: #f9f9f9;
            border-color: #bbb;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .btn-google i {
            font-size: 1.1rem;
        }

        /* RESPONSIVE */
        @media (max-width: 1024px) {
            .login-left {
                padding: 60px 50px;
            }

            .login-right {
                padding: 60px 50px;
            }

            .left-title {
                font-size: 2.2rem;
            }

            .form-header h1 {
                font-size: 1.8rem;
            }
        }

        @media (max-width: 992px) {
            .login-container {
                flex-direction: column;
            }

            .login-left {
                flex: 0 0 45%;
                padding: 50px 40px;
            }

            .login-right {
                flex: 0 0 55%;
                padding: 50px 40px;
            }

            .left-title {
                font-size: 2rem;
            }

            .form-header h1 {
                font-size: 1.6rem;
            }

            .left-logo img {
                max-width: 90px;
            }
        }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                height: auto;
                min-height: 100vh;
            }

            .login-left {
                flex: 1;
                min-height: 350px;
                padding: 40px 30px;
            }

            .login-right {
                flex: 1;
                padding: 40px 30px;
            }

            .left-title {
                font-size: 1.8rem;
                margin: 20px 0 15px;
            }

            .form-header h1 {
                font-size: 1.5rem;
            }

            .left-subtitle {
                font-size: 0.95rem;
                margin-bottom: 35px;
            }

            .left-logo img {
                max-width: 80px;
            }

            .feature-item {
                margin-bottom: 18px;
                font-size: 0.9rem;
            }

            .left-features {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .login-left {
                padding: 30px 20px;
                min-height: 300px;
            }

            .login-right {
                padding: 30px 20px;
            }

            .form-wrapper {
                max-width: 100%;
            }

            .left-title {
                font-size: 1.6rem;
                margin: 15px 0 12px;
            }

            .left-subtitle {
                font-size: 0.9rem;
                margin-bottom: 25px;
            }

            .form-header h1 {
                font-size: 1.3rem;
            }

            .form-header p {
                font-size: 0.85rem;
            }

            .form-control {
                padding: 12px 14px;
                font-size: 16px;
            }

            .btn-signin,
            .btn-google {
                padding: 12px 16px;
                font-size: 0.9rem;
            }

            .left-logo img {
                max-width: 70px;
            }
        }
    </style>
</head>

<body>

<div class="login-container">
    <!-- LEFT SIDE - PURPLE -->
    <div class="login-left">
        <div class="left-content">
            <div class="left-logo">
                <img src="../assets/images/img3.png" alt="BTU Logo">
                <div class="left-logo-text1">جامعه بني سويف التكنولوجيه</div>
                <div class="left-logo-text">BENI-SUEF TECHNOLOGICAL UNIVERSITY</div>
            </div><br>

            <h1 class="left-title">BTU Portal</h1>
            <p class="left-subtitle">Access your academic records, results, and documents in one place.</p>

            <div class="left-features">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-check"></i>
                    </div>
                    <span>View your latest results</span>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-check"></i>
                    </div>
                    <span>Download documents & transcripts</span>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-check"></i>
                    </div>
                    <span>Track academic progress</span>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT SIDE - WHITE -->
    <div class="login-right">
        <div class="form-wrapper">
            <div class="form-header">
                <h1>Welcome back</h1>
                <p>Sign in to your account to continue</p>
            </div>

            <?php if (!empty($message)): ?>
                <div class="alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?= htmlspecialchars($message) ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-control" 
                        placeholder="you@example.com" 
                        required
                        value="<?= htmlspecialchars($remembered_email) ?>"
                        autocomplete="email"
                    >
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-control" 
                        placeholder="••••••••" 
                        required
                        autocomplete="current-password"
                    >
                </div>

                <div class="remember-section">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember" class="remember-toggle"></label>
                    <label for="remember" class="remember-label">Remember me</label>
                </div>

                <button type="submit" class="btn-signin">Sign In</button>

            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function handleGoogleLogin() {
        alert('Google login integration needed');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const alertError = document.querySelector('.alert-error');
        if (alertError) {
            setTimeout(() => {
                alertError.style.opacity = '0';
                alertError.style.transition = 'opacity 0.3s ease';
                setTimeout(() => alertError.remove(), 300);
            }, 5000);
        }
    });
</script>

</body>

</html>