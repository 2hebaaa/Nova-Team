<?php
session_start();
require_once("../config/db.php");

// If confirmation is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm_logout'])) {
    // Destroy all session data
    $_SESSION = [];

    // Destroy the session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    // Destroy the session
    session_destroy();

    // Clear remember me cookies
    setcookie("user_email", "", time() - 3600, "/");

    // Redirect to login with success message
    header("Location: login.php?logout=success");
    exit();
}

// If not confirmed, show confirmation page
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout - BTU LMS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #614b6f 0%, #4a3a53 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .logout-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 50px;
            max-width: 500px;
            width: 100%;
            text-align: center;
        }

        .logout-icon {
            width: 80px;
            height: 80px;
            background: #614b6f;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            font-size: 2.5rem;
            color: white;
        }

        .logout-container h1 {
            font-family: 'Poppins', sans-serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 15px;
        }

        .logout-container p {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .button-group {
            display: flex;
            gap: 15px;
        }

        .btn-logout {
            flex: 1;
            padding: 12px 24px;
            background: linear-gradient(135deg, #614b6f 0%, #4a3a53 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-logout:hover {
            box-shadow: 0 6px 20px rgba(97, 75, 111, 0.4);
            transform: translateY(-2px);
        }

        .btn-cancel {
            flex: 1;
            padding: 12px 24px;
            background: white;
            color: #614b6f;
            border: 2px solid #614b6f;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-cancel:hover {
            background: #614b6f;
            color: white;
        }

        .info-box {
            background: #f0f2f5;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 30px;
            font-size: 0.85rem;
            color: #666;
        }

        .info-box i {
            margin-right: 8px;
            color: #614b6f;
        }

        @media (max-width: 480px) {
            .logout-container {
                padding: 30px 20px;
            }

            .logout-container h1 {
                font-size: 1.5rem;
            }

            .button-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

<div class="logout-container">
    <div class="logout-icon">
        <i class="fas fa-sign-out-alt"></i>
    </div>

    <h1>Sign Out?</h1>
    
    <p>Are you sure you want to log out? You will need to sign in again to access your account.</p>

    <div class="info-box">
        <i class="fas fa-info-circle"></i>
        Your session will be securely terminated and all data will be cleared.
    </div>

    <form method="POST" class="mb-0">
        <div class="button-group">
            <button type="submit" name="confirm_logout" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i> Yes, Log Out
            </button>
            <a href="../student/dashboard.php" class="btn-cancel">
                <i class="fas fa-arrow-left"></i> Cancel
            </a>
        </div>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>