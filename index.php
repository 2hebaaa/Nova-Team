<?php
session_start();

// Redirect to landing page
header('Location: /lms_project/landing.php');
exit;

/* 
   OLD HOME PAGE - DEPRECATED
   Users are now redirected to landing.php which provides:
   - Modern UI/UX
   - Multilingual support (Arabic/English)
   - Dark/Light mode toggle
   - Professional features overview
   - Contact information
   - Call-to-action buttons
*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beni-Suef Technological University - Learning Management System</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: #333;
            line-height: 1.6;
        }

        /* Navbar Styling */
        .navbar-custom {
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-family: 'Poppins', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #353e67 0%, #6a5384 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-link {
            font-weight: 500;
            color: #333 !important;
            margin: 0 0.5rem;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: #667eea !important;
        }

        .login-btn {
            background: linear-gradient(135deg, #353e67 0%, #5f4f70 100%);
            color: white !important;
            border-radius: 25px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            background: linear-gradient(135deg, #4a537b 0%, #554567 100%);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero::before {
            content: "";
            position: absolute;
            top: -50%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .hero::after {
            content: "";
            position: absolute;
            bottom: -30%;
            left: -5%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite reverse;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(30px); }
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            color: white;
            max-width: 700px;
            padding: 2rem;
        }

        .hero h1 {
            font-family: 'Poppins', sans-serif;
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            opacity: 0.95;
            margin-bottom: 2rem;
            font-weight: 300;
        }

        /* Role Selection Buttons */
        .role-buttons {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 2rem;
        }

        .btn-role {
            padding: 1rem 2.5rem;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 50px;
            border: 2px solid white;
            background: transparent;
            color: white;
            transition: all 0.3s ease;
            min-width: 180px;
            text-decoration: none;
            display: inline-block;
        }

        .btn-role:hover {
            background: white;
            color: #667eea;
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            text-decoration: none;
        }

        .btn-role i {
            margin-right: 0.5rem;
        }

        /* About Section */
        .about-section {
            padding: 5rem 0;
            background: #f8f9fa;
        }

        .section-title {
            font-family: 'Poppins', sans-serif;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #333;
        }

        .section-subtitle {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 3rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .feature-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin: 1.5rem 0;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-left: 4px solid #667eea;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        }

        .feature-icon {
            font-size: 2.5rem;
            color: #667eea;
            margin-bottom: 1rem;
        }

        .feature-card h4 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #333;
        }

        .feature-card p {
            color: #666;
            margin: 0;
            font-size: 0.95rem;
        }

        /* Features Grid */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .feature-box {
            background: white;
            padding: 2.5rem 2rem;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border-top: 3px solid #667eea;
        }

        .feature-box:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.15);
        }

        .feature-box-icon {
            font-size: 3rem;
            color: #667eea;
            margin-bottom: 1rem;
            display: inline-block;
            width: 70px;
            height: 70px;
            line-height: 70px;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            border-radius: 50%;
        }

        .feature-box h5 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #333;
        }

        .feature-box p {
            color: #666;
            margin: 0;
            font-size: 0.95rem;
        }

        /* Student vs Doctor Features */
        .role-comparison {
            margin-top: 4rem;
        }

        .comparison-title {
            text-align: center;
            margin-bottom: 2rem;
        }

        .role-features {
            background: white;
            border-radius: 15px;
            padding: 2.5rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
        }

        .role-features h3 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: #333;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .role-features h3 i {
            font-size: 2rem;
            color: #667eea;
        }

        .feature-list {
            list-style: none;
        }

        .feature-list li {
            padding: 0.75rem 0;
            color: #555;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
        }

        .feature-list li:last-child {
            border-bottom: none;
        }

        .feature-list li::before {
            content: "✓";
            font-weight: bold;
            color: #667eea;
            margin-right: 1rem;
            font-size: 1.2rem;
        }

        .feature-list li strong {
            color: #333;
        }

        /* Stats Section */
        .stats-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 4rem 0;
            margin: 3rem 0;
        }

        .stat-item {
            text-align: center;
            padding: 2rem;
        }

        .stat-number {
            font-family: 'Poppins', sans-serif;
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 1rem;
            opacity: 0.9;
        }

        /* Technology Stack */
        .tech-section {
            padding: 4rem 0;
        }

        .tech-badge {
            display: inline-block;
            background: #f0f0f0;
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            margin: 0.5rem;
            color: #667eea;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .tech-badge:hover {
            background: #667eea;
            color: white;
            transform: scale(1.05);
        }

        /* Footer */
        footer {
            background: #1a1a1a;
            color: white;
            padding: 3rem 0 1rem;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .footer-section h5 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section ul li {
            margin-bottom: 0.5rem;
        }

        .footer-section a {
            color: #aaa;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-section a:hover {
            color: #667eea;
        }

        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(102, 126, 234, 0.2);
            border-radius: 50%;
            color: #667eea;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .social-links a:hover {
            background: #667eea;
            color: white;
            transform: translateY(-3px);
        }

        .footer-bottom {
            border-top: 1px solid #333;
            padding-top: 1.5rem;
            text-align: center;
            color: #aaa;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2rem;
            }

            .hero-subtitle {
                font-size: 1rem;
            }

            .role-buttons {
                flex-direction: column;
            }

            .btn-role {
                width: 100%;
            }

            .section-title {
                font-size: 1.8rem;
            }

            .stat-number {
                font-size: 2rem;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class="fas fa-graduation-cap"></i> BTU LMS
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                <li class="nav-item">
                    <a href="auth/login.php" class="btn login-btn ms-3">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero" id="home">
    <div class="hero-content">
        <h1>Beni-Suef Technological University</h1>
        <p class="hero-subtitle">Professional Learning Management System for Modern Education</p>

        <div class="role-buttons">
            <a href="auth/login.php?role=student" class="btn-role">
                <i class="fas fa-user-graduate"></i> Student Login
            </a>
            <a href="auth/login.php?role=doctor" class="btn-role">
                <i class="fas fa-chalkboard-user"></i> Doctor Login
            </a>
        </div>
    </div>
</section>

<!-- Main Features Section -->
<section class="about-section" id="features">
    <div class="container">
        <h2 class="section-title text-center">Powerful Features</h2>
        <p class="section-subtitle text-center">
            Our comprehensive platform offers everything you need for effective online learning and teaching.
        </p>

        <!-- Core Features Grid -->
        <div class="features-grid">
            <div class="feature-box">
                <div class="feature-box-icon">
                    <i class="fas fa-video"></i>
                </div>
                <h5>Live Classes & Recordings</h5>
                <p>Conduct interactive live sessions and access recorded lectures anytime, anywhere for flexible learning.</p>
            </div>

            <div class="feature-box">
                <div class="feature-box-icon">
                    <i class="fas fa-tasks"></i>
                </div>
                <h5>Assignment Management</h5>
                <p>Create, distribute, and grade assignments with automated feedback and plagiarism detection.</p>
            </div>

            <div class="feature-box">
                <div class="feature-box-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h5>Performance Analytics</h5>
                <p>Detailed progress tracking, grade analysis, and personalized learning recommendations.</p>
            </div>

            <div class="feature-box">
                <div class="feature-box-icon">
                    <i class="fas fa-comments"></i>
                </div>
                <h5>Discussion Forums</h5>
                <p>Engage in meaningful discussions, ask questions, and collaborate with peers and instructors.</p>
            </div>

            <div class="feature-box">
                <div class="feature-box-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <h5>Course Materials</h5>
                <p>Access comprehensive study materials including slides, documents, PDFs, and multimedia content.</p>
            </div>

            <div class="feature-box">
                <div class="feature-box-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <h5>Smart Notifications</h5>
                <p>Real-time alerts for important updates, deadlines, grades, and course announcements.</p>
            </div>

            <div class="feature-box">
                <div class="feature-box-icon">
                    <i class="fas fa-lock"></i>
                </div>
                <h5>Secure & Reliable</h5>
                <p>Bank-level security, data encryption, and 99.9% uptime guarantee for peace of mind.</p>
            </div>

            <div class="feature-box">
                <div class="feature-box-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h5>Collaboration Tools</h5>
                <p>Group projects, peer reviews, and team-based learning with integrated communication.</p>
            </div>
        </div>

    </div>
</section>

<!-- About Section -->
<section class="about-section" id="about">
    <div class="container">
        <h2 class="section-title text-center">About Our Platform</h2>
        <p class="section-subtitle text-center">
            A comprehensive Learning Management System designed to enhance educational excellence and student engagement.
        </p>

        <div class="row">
            <div class="col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <h4>Comprehensive Courses</h4>
                    <p>Access a wide range of courses in technology, engineering, and applied sciences, designed by industry experts.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h4>Collaborative Learning</h4>
                    <p>Interact with instructors and peers through discussion forums, group projects, and real-time collaboration tools.</p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h4>Progress Tracking</h4>
                    <p>Monitor your academic progress with detailed analytics, performance reports, and personalized recommendations.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h4>Mobile Friendly</h4>
                    <p>Learn anytime, anywhere with our fully responsive platform optimized for all devices.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="about-section" id="contact">
    <div class="container text-center">
        <h2 class="section-title">Get in Touch</h2>
        <p class="section-subtitle">Have questions? We're here to help!</p>

        <div class="row mt-4">
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <h4>Phone</h4>
                    <p>0822066651</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h4>Email</h4>
                    <p>info@btu.edu.eg</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h4>Address</h4>
                    <p>Beni-Suef, Egypt</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer>
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h5><i class="fas fa-graduation-cap"></i> BTU LMS</h5>
                <p>Empowering students and educators through innovative digital learning solutions.</p>
                <div class="social-links">
                    <a href="https://www.facebook.com/profile.php?id=100063584836266" target="_blank" title="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" target="_blank" title="Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" target="_blank" title="LinkedIn">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="#" target="_blank" title="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>
            </div>

            <div class="footer-section">
                <h5>Quick Links</h5>
                <ul>
                    <li><a href="#home">Home</a></li>
                    <li><a href="#features">Features</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h5>For Students</h5>
                <ul>
                    <li><a href="auth/login.php?role=student">Student Login</a></li>
                    <li><a href="#">My Courses</a></li>
                    <li><a href="#">Grades & Results</a></li>
                    <li><a href="#">Assignments</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h5>For Instructors</h5>
                <ul>
                    <li><a href="auth/login.php?role=doctor">Instructor Login</a></li>
                    <li><a href="#">Create Course</a></li>
                    <li><a href="#">Grade Management</a></li>
                    <li><a href="#">Resources</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; 2026 Beni-Suef Technological University | All Rights Reserved | <a href="#" style="color: #667eea;">Privacy Policy</a> | <a href="#" style="color: #667eea;">Terms of Service</a></p>
        </div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>