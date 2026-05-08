/**
 * Multilingual Support System
 * Supports: Arabic (ar), English (en)
 */

// Language configuration
const LANGUAGES = {
    'ar': 'العربية',
    'en': 'English'
};

const DEFAULT_LANGUAGE = localStorage.getItem('app_language') || 'ar';
let CURRENT_LANGUAGE = DEFAULT_LANGUAGE;

// Arabic translations
const AR = {
    // Navigation
    nav: {
        home: 'الرئيسية',
        about: 'عن النظام',
        courses: 'المقررات',
        profile: 'الملف الشخصي',
        logout: 'تسجيل الخروج',
        dashboard: 'لوحة التحكم',
        login: 'تسجيل الدخول',
        admin: 'الإدارة',
        settings: 'الإعدادات'
    },
    
    // Common
    common: {
        success: 'تم بنجاح',
        error: 'حدث خطأ',
        loading: 'جاري التحميل...',
        save: 'حفظ',
        cancel: 'إلغاء',
        delete: 'حذف',
        edit: 'تعديل',
        view: 'عرض',
        add: 'إضافة',
        search: 'بحث',
        filter: 'تصفية',
        submit: 'إرسال',
        confirm: 'تأكيد',
        close: 'إغلاق',
        back: 'رجوع',
        next: 'التالي',
        previous: 'السابق',
        noData: 'لا توجد بيانات',
        language: 'اللغة',
        theme: 'المظهر',
        darkMode: 'الوضع الليلي',
        lightMode: 'الوضع الفاتح'
    },
    
    // Auth
    auth: {
        email: 'البريد الإلكتروني',
        password: 'كلمة المرور',
        confirmPassword: 'تأكيد كلمة المرور',
        forgotPassword: 'هل نسيت كلمة المرور؟',
        noAccount: 'ليس لديك حساب؟',
        haveAccount: 'هل لديك حساب بالفعل؟',
        invalidEmail: 'بريد إلكتروني غير صحيح',
        invalidCredentials: 'بيانات دخول غير صحيحة',
        loginRequired: 'يجب تسجيل الدخول أولاً',
        unauthorized: 'غير مصرح لك بالوصول لهذه الصفحة',
        institutionalEmail: 'يجب استخدام بريد جامعي (name.id@btu.edu.eg)'
    },
    
    // Dashboard
    dashboard: {
        welcome: 'أهلاً وسهلاً',
        overview: 'نظرة عامة',
        recentActivity: 'النشاط الأخير',
        stats: 'الإحصائيات',
        totalCourses: 'إجمالي المقررات',
        averageGrade: 'المعدل المتوسط',
        attendanceRate: 'معدل الحضور'
    },
    
    // Student
    student: {
        courses: 'مقرراتي',
        assignments: 'الواجبات',
        grades: 'درجاتي',
        attendance: 'الحضور',
        materials: 'المواد الدراسية',
        submissions: 'تسليماتي',
        enrollCourse: 'الالتحاق بمقرر',
        courseRequested: 'تم إرسال طلب الالتحاق',
        pendingApproval: 'في انتظار الموافقة',
        requestRejected: 'تم رفض الطلب',
        withdrawCourse: 'الانسحاب من المقرر',
        submitAssignment: 'تسليم واجب',
        downloadMaterial: 'تحميل المادة',
        viewGrade: 'عرض الدرجة',
        lecturer: 'المحاضر',
        section: 'القسم',
        resource: 'مورد تعليمي'
    },
    
    // Doctor
    doctor: {
        myCourses: 'مقرراتي',
        addCourse: 'إضافة مقرر',
        editCourse: 'تعديل المقرر',
        students: 'الطلاب',
        enrollmentRequests: 'طلبات الالتحاق',
        approve: 'الموافقة',
        reject: 'الرفض',
        addAssignment: 'إضافة واجب',
        addQuiz: 'إضافة اختبار',
        addLecture: 'رفع محاضرة',
        addSection: 'رفع قسم',
        manageAttendance: 'إدارة الحضور',
        gradeStudent: 'إعطاء درجة',
        viewSubmissions: 'عرض التسليمات',
        deadline: 'تاريخ الاستحقاق',
        courseName: 'اسم المقرر',
        courseCode: 'رمز المقرر',
        credits: 'الوحدات',
        semester: 'الفصل'
    },
    
    // Admin
    admin: {
        dashboard: 'لوحة التحكم - الإدارة',
        userManagement: 'إدارة المستخدمين',
        addUser: 'إضافة مستخدم',
        editUser: 'تعديل المستخدم',
        deleteUser: 'حذف المستخدم',
        role: 'الدور',
        student: 'طالب',
        doctor: 'محاضر',
        admin: 'مسؤول',
        courseManagement: 'إدارة المقررات',
        systemSettings: 'إعدادات النظام',
        reports: 'التقارير',
        logs: 'السجلات',
        settings: 'الإعدادات',
        siteName: 'اسم الموقع',
        siteDescription: 'وصف الموقع',
        contactEmail: 'البريد الإلكتروني للاتصال',
        maxFileSize: 'أقصى حجم ملف'
    },
    
    // Home / Landing
    home: {
        title: 'نظام إدارة التعلم - Nova System',
        subtitle: 'منصة تعليمية حديثة لتسهيل التعليم والتعلم',
        features: 'المميزات الرئيسية',
        services: 'الخدمات',
        contact: 'اتصل بنا',
        about: 'عن النظام',
        getStarted: 'ابدأ الآن',
        
        feature1Title: 'إدارة سهلة للمقررات',
        feature1Desc: 'إدارة المقررات والمحاضرات والواجبات بسهولة',
        
        feature2Title: 'تتبع الحضور',
        feature2Desc: 'نظام متكامل لتسجيل ومتابعة حضور الطلاب',
        
        feature3Title: 'نظام التقييم',
        feature3Desc: 'نظام متقدم لإعطاء الدرجات والتغذية الراجعة',
        
        feature4Title: 'تسليم الواجبات',
        feature4Desc: 'منصة سهلة لتسليم الواجبات والمشاريع',
        
        feature5Title: 'دعم متعدد اللغات',
        feature5Desc: 'دعم العربية والإنجليزية',
        
        feature6Title: 'واجهة حديثة',
        feature6Desc: 'واجهة مستخدم سهلة وحديثة وسريعة',
        
        contactPhone: 'الهاتف',
        contactAddress: 'العنوان',
        sendMessage: 'إرسال رسالة'
    }
};

// English translations
const EN = {
    // Navigation
    nav: {
        home: 'Home',
        about: 'About',
        courses: 'Courses',
        profile: 'Profile',
        logout: 'Logout',
        dashboard: 'Dashboard',
        login: 'Login',
        admin: 'Admin',
        settings: 'Settings'
    },
    
    // Common
    common: {
        success: 'Success',
        error: 'Error',
        loading: 'Loading...',
        save: 'Save',
        cancel: 'Cancel',
        delete: 'Delete',
        edit: 'Edit',
        view: 'View',
        add: 'Add',
        search: 'Search',
        filter: 'Filter',
        submit: 'Submit',
        confirm: 'Confirm',
        close: 'Close',
        back: 'Back',
        next: 'Next',
        previous: 'Previous',
        noData: 'No data available',
        language: 'Language',
        theme: 'Theme',
        darkMode: 'Dark Mode',
        lightMode: 'Light Mode'
    },
    
    // Auth
    auth: {
        email: 'Email',
        password: 'Password',
        confirmPassword: 'Confirm Password',
        forgotPassword: 'Forgot Password?',
        noAccount: "Don't have an account?",
        haveAccount: 'Already have an account?',
        invalidEmail: 'Invalid email',
        invalidCredentials: 'Invalid credentials',
        loginRequired: 'Please login first',
        unauthorized: 'You are not authorized to access this page',
        institutionalEmail: 'Please use institutional email (name.id@btu.edu.eg)'
    },
    
    // Dashboard
    dashboard: {
        welcome: 'Welcome',
        overview: 'Overview',
        recentActivity: 'Recent Activity',
        stats: 'Statistics',
        totalCourses: 'Total Courses',
        averageGrade: 'Average Grade',
        attendanceRate: 'Attendance Rate'
    },
    
    // Student
    student: {
        courses: 'My Courses',
        assignments: 'Assignments',
        grades: 'My Grades',
        attendance: 'Attendance',
        materials: 'Course Materials',
        submissions: 'My Submissions',
        enrollCourse: 'Enroll in Course',
        courseRequested: 'Enrollment request sent',
        pendingApproval: 'Pending approval',
        requestRejected: 'Request rejected',
        withdrawCourse: 'Withdraw from Course',
        submitAssignment: 'Submit Assignment',
        downloadMaterial: 'Download Material',
        viewGrade: 'View Grade',
        lecturer: 'Lecturer',
        section: 'Section',
        resource: 'Learning Resource'
    },
    
    // Doctor
    doctor: {
        myCourses: 'My Courses',
        addCourse: 'Add Course',
        editCourse: 'Edit Course',
        students: 'Students',
        enrollmentRequests: 'Enrollment Requests',
        approve: 'Approve',
        reject: 'Reject',
        addAssignment: 'Add Assignment',
        addQuiz: 'Add Quiz',
        addLecture: 'Upload Lecture',
        addSection: 'Upload Section',
        manageAttendance: 'Manage Attendance',
        gradeStudent: 'Grade Student',
        viewSubmissions: 'View Submissions',
        deadline: 'Deadline',
        courseName: 'Course Name',
        courseCode: 'Course Code',
        credits: 'Credits',
        semester: 'Semester'
    },
    
    // Admin
    admin: {
        dashboard: 'Admin Dashboard',
        userManagement: 'User Management',
        addUser: 'Add User',
        editUser: 'Edit User',
        deleteUser: 'Delete User',
        role: 'Role',
        student: 'Student',
        doctor: 'Doctor',
        admin: 'Admin',
        courseManagement: 'Course Management',
        systemSettings: 'System Settings',
        reports: 'Reports',
        logs: 'Logs',
        settings: 'Settings',
        siteName: 'Site Name',
        siteDescription: 'Site Description',
        contactEmail: 'Contact Email',
        maxFileSize: 'Max File Size'
    },
    
    // Home / Landing
    home: {
        title: 'Learning Management System - Nova System',
        subtitle: 'A Modern Educational Platform for Better Teaching and Learning',
        features: 'Key Features',
        services: 'Services',
        contact: 'Contact Us',
        about: 'About',
        getStarted: 'Get Started',
        
        feature1Title: 'Course Management',
        feature1Desc: 'Manage courses, lectures, and assignments easily',
        
        feature2Title: 'Attendance Tracking',
        feature2Desc: 'Integrated system for student attendance',
        
        feature3Title: 'Grading System',
        feature3Desc: 'Advanced grading and feedback system',
        
        feature4Title: 'Assignment Submission',
        feature4Desc: 'Easy platform for assignment submission',
        
        feature5Title: 'Multilingual Support',
        feature5Desc: 'Support for Arabic and English',
        
        feature6Title: 'Modern Interface',
        feature6Desc: 'Fast, modern, and user-friendly interface',
        
        contactPhone: 'Phone',
        contactAddress: 'Address',
        sendMessage: 'Send Message'
    }
};

/**
 * Get translation for key
 */
function t(key) {
    const lang = CURRENT_LANGUAGE === 'ar' ? AR : EN;
    const keys = key.split('.');
    let value = lang;
    
    for (const k of keys) {
        if (value && typeof value === 'object' && k in value) {
            value = value[k];
        } else {
            return key; // Return key if translation not found
        }
    }
    
    return value;
}

/**
 * Change language
 */
function setLanguage(lang) {
    CURRENT_LANGUAGE = lang;
    localStorage.setItem('app_language', lang);
    document.documentElement.lang = lang;
    document.documentElement.dir = lang === 'ar' ? 'rtl' : 'ltr';
    updatePageLanguage();
}

/**
 * Get plural form
 */
function plural(key, count) {
    // Simple pluralization - can be enhanced
    return t(key);
}

/**
 * Format date based on language
 */
function formatDate(date, lang = CURRENT_LANGUAGE) {
    const d = new Date(date);
    if (lang === 'ar') {
        return d.toLocaleDateString('ar-EG');
    } else {
        return d.toLocaleDateString('en-US');
    }
}

/**
 * Format currency based on language
 */
function formatCurrency(amount, lang = CURRENT_LANGUAGE) {
    if (lang === 'ar') {
        return new Intl.NumberFormat('ar-EG', {
            style: 'currency',
            currency: 'EGP'
        }).format(amount);
    } else {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'EGP'
        }).format(amount);
    }
}

/**
 * Update page language (called when language changes)
 */
function updatePageLanguage() {
    // Update all elements with data-i18n attribute
    document.querySelectorAll('[data-i18n]').forEach(el => {
        el.textContent = t(el.getAttribute('data-i18n'));
    });
    
    // Update all input placeholders
    document.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
        el.placeholder = t(el.getAttribute('data-i18n-placeholder'));
    });
    
    // Trigger custom event so other scripts can update
    window.dispatchEvent(new Event('languageChanged'));
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    setLanguage(CURRENT_LANGUAGE);
});
