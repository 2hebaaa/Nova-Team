<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-i18n="home.title">Nova System - Learning Management System</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    
    <!-- Custom Theme CSS -->
    <link rel="stylesheet" href="/assets/css/darkmode.css">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Hero Section */
        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #353e67 0%, #6a5384 100%);
            color: #ffffff;
            padding: 100px 20px;
            text-align: center;
        }

        .hero-section h1 {
            font-family: 'Poppins', sans-serif;
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            animation: slideIn 0.8s ease;
        }

        .hero-section p {
            font-size: 1.3rem;
            margin-bottom: 30px;
            opacity: 0.95;
            animation: slideIn 0.8s ease 0.2s backwards;
        }

        .btn-cta {
            padding: 15px 40px;
            font-size: 1.1rem;
            animation: slideIn 0.8s ease 0.4s backwards;
        }

        /* Features Section */
        .features-section {
            padding: 80px 20px;
            background-color: var(--bg-primary);
        }

        .features-section h2 {
            text-align: center;
            font-family: 'Poppins', sans-serif;
            font-size: 2.5rem;
            margin-bottom: 50px;
            color: var(--text-primary);
        }

        .feature-card {
            background-color: var(--bg-secondary);
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            margin-bottom: 30px;
            border: 1px solid var(--border-color);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-lg);
        }

        .feature-icon {
            font-size: 3rem;
            color: #6a5384;
            margin-bottom: 20px;
        }

        .feature-card h3 {
            font-family: 'Poppins', sans-serif;
            color: var(--text-primary);
            margin-bottom: 15px;
        }

        .feature-card p {
            color: var(--text-secondary);
            line-height: 1.6;
        }

        /* Services Section */
        .services-section {
            padding: 80px 20px;
            background-color: var(--bg-secondary);
        }

        .service-item {
            background-color: var(--bg-primary);
            padding: 40px;
            border-radius: 10px;
            margin-bottom: 30px;
            border-left: 5px solid #6a5384;
            border: 1px solid var(--border-color);
        }

        .service-item h3 {
            font-family: 'Poppins', sans-serif;
            color: #353e67;
            margin-bottom: 15px;
        }

        .service-item p {
            color: var(--text-secondary);
            line-height: 1.8;
        }

        /* Contact Section */
        .contact-section {
            padding: 80px 20px;
            background: linear-gradient(135deg, #353e67 0%, #6a5384 100%);
            color: #ffffff;
        }

        .contact-section h2 {
            text-align: center;
            font-family: 'Poppins', sans-serif;
            font-size: 2.5rem;
            margin-bottom: 50px;
        }

        .contact-card {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 30px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .contact-card i {
            font-size: 2rem;
            margin-bottom: 15px;
        }

        .contact-card h4 {
            font-family: 'Poppins', sans-serif;
            margin-bottom: 10px;
        }

        .contact-form {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 10px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .contact-form .form-control {
            background-color: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #ffffff;
        }

        .contact-form .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        /* Navbar */
        .navbar-custom {
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            background-color: var(--bg-primary) !important;
            border-bottom: 1px solid var(--border-color);
        }

        body {
            padding-top: 70px;
        }

        /* Footer */
        .footer {
            background-color: var(--bg-secondary);
            padding: 30px 20px;
            text-align: center;
            color: var(--text-secondary);
            border-top: 1px solid var(--border-color);
        }

        /* Call to Action Buttons */
        .btn-primary {
            background-color: #6a5384;
            border-color: #6a5384;
        }

        .btn-primary:hover {
            background-color: #5a4874;
            border-color: #5a4874;
        }

        /* Language & Theme Toggle */
        .navbar-custom .nav-item {
            margin: 0 10px;
        }

        @media (max-width: 768px) {
            .hero-section h1 {
                font-size: 2.5rem;
            }

            .hero-section p {
                font-size: 1rem;
            }

            .features-section h2 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container-fluid px-5">
            <a class="navbar-brand" href="/">
                <i class="fas fa-book-reader"></i> NOVA System
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#features" data-i18n="nav.features">المميزات</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#services" data-i18n="nav.services">الخدمات</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact" data-i18n="nav.contact">اتصل بنا</a>
                    </li>
                    
                    <!-- Language Switcher -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="languageDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-language"></i> <span data-i18n="common.language">اللغة</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
                            <li><a class="dropdown-item" href="#" onclick="setLanguage('ar'); return false;">العربية</a></li>
                            <li><a class="dropdown-item" href="#" onclick="setLanguage('en'); return false;">English</a></li>
                        </ul>
                    </li>
                    
                    <!-- Theme Toggler -->
                    <li class="nav-item">
                        <button class="btn btn-outline-dark btn-sm" data-theme-toggle>
                            <i class="fas fa-moon"></i> <span data-i18n="common.darkMode">الوضع الليلي</span>
                        </button>
                    </li>
                    
                    <!-- Login Button -->
                    <li class="nav-item ms-2">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a class="btn btn-primary btn-sm" href="/<?php echo $_SESSION['role']; ?>/dashboard.php" data-i18n="nav.dashboard">لوحة التحكم</a>
                            <a class="btn btn-outline-danger btn-sm ms-2" href="/auth/logout.php" data-i18n="nav.logout">تسجيل الخروج</a>
                        <?php else: ?>
                            <a class="btn btn-primary btn-sm" href="/auth/login.php" data-i18n="nav.login">تسجيل الدخول</a>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto">
                    <h1 data-i18n="home.title">Nova System - Learning Management System</h1>
                    <p data-i18n="home.subtitle">منصة تعليمية حديثة لتسهيل التعليم والتعلم</p>
                    
                    <div class="mt-5">
                        <?php if (!isset($_SESSION['user_id'])): ?>
                            <a href="/auth/login.php" class="btn btn-light btn-lg btn-cta me-3">
                                <i class="fas fa-sign-in-alt"></i> <span data-i18n="nav.login">تسجيل الدخول</span>
                            </a>
                        <?php endif; ?>
                        <a href="#features" class="btn btn-outline-light btn-lg btn-cta">
                            <i class="fas fa-arrow-down"></i> <span data-i18n="home.getStarted">اعرف المزيد</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section" id="features">
        <div class="container">
            <h2 data-i18n="home.features">المميزات الرئيسية</h2>
            
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <h3 data-i18n="home.feature1Title">إدارة سهلة للمقررات</h3>
                        <p data-i18n="home.feature1Desc">إدارة المقررات والمحاضرات والواجبات بسهولة</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <h3 data-i18n="home.feature2Title">تتبع الحضور</h3>
                        <p data-i18n="home.feature2Desc">نظام متكامل لتسجيل ومتابعة حضور الطلاب</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <h3 data-i18n="home.feature3Title">نظام التقييم</h3>
                        <p data-i18n="home.feature3Desc">نظام متقدم لإعطاء الدرجات والتغذية الراجعة</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-upload"></i>
                        </div>
                        <h3 data-i18n="home.feature4Title">تسليم الواجبات</h3>
                        <p data-i18n="home.feature4Desc">منصة سهلة لتسليم الواجبات والمشاريع</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-language"></i>
                        </div>
                        <h3 data-i18n="home.feature5Title">دعم متعدد اللغات</h3>
                        <p data-i18n="home.feature5Desc">دعم العربية والإنجليزية</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-paint-brush"></i>
                        </div>
                        <h3 data-i18n="home.feature6Title">واجهة حديثة</h3>
                        <p data-i18n="home.feature6Desc">واجهة مستخدم سهلة وحديثة وسريعة</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services-section" id="services">
        <div class="container">
            <h2 class="text-center" style="font-family: 'Poppins'; font-size: 2.5rem; margin-bottom: 50px;" data-i18n="home.services">الخدمات</h2>
            
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="service-item">
                        <h3><i class="fas fa-user-tie me-2"></i> <span data-i18n="admin.dashboard">لوحة إدارة للكليات</span></h3>
                        <p>إدارة شاملة للنظام والمستخدمين والمقررات</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="service-item">
                        <h3><i class="fas fa-chalkboard-user me-2"></i> <span data-i18n="doctor.myCourses">منصة أساتذة</span></h3>
                        <p>تسهيل إدارة المقررات والطلاب والدرجات</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="service-item">
                        <h3><i class="fas fa-graduation-cap me-2"></i> <span data-i18n="student.courses">منصة طلاب</span></h3>
                        <p>كل احتياجات الطالب في مكان واحد</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section" id="contact">
        <div class="container">
            <h2 data-i18n="home.contact">اتصل بنا</h2>
            
            <div class="row mt-5">
                <div class="col-lg-6 mb-4">
                    <div class="contact-card">
                        <i class="fas fa-phone"></i>
                        <h4 data-i18n="home.contactPhone">الهاتف</h4>
                        <p>+20 100 XXX XXXX</p>
                    </div>
                    
                    <div class="contact-card">
                        <i class="fas fa-envelope"></i>
                        <h4>البريد الإلكتروني</h4>
                        <p><a href="mailto:lms@btu.edu.eg" style="color: #ffffff;">lms@btu.edu.eg</a></p>
                    </div>
                    
                    <div class="contact-card">
                        <i class="fas fa-map-marker-alt"></i>
                        <h4 data-i18n="home.contactAddress">العنوان</h4>
                        <p>جامعة بني سويف التكنولوجية</p>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <form class="contact-form">
                        <div class="mb-3">
                            <input type="text" class="form-control" placeholder="اسمك" required>
                        </div>
                        <div class="mb-3">
                            <input type="email" class="form-control" placeholder="بريدك الإلكتروني" required>
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control" rows="4" placeholder="رسالتك..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-light w-100">
                            <i class="fas fa-paper-plane"></i> <span data-i18n="home.sendMessage">إرسال الرسالة</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2026 Nova System - Learning Management System. جميع الحقوق محفوظة.</p>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/i18n.js"></script>
    <script src="/assets/js/theme-switcher.js"></script>
</body>
</html>
