# Nova System - Learning Management System

**جامعة بني سويف التكنولوجية - نظام إدارة التعلم**

A modern, feature-rich Learning Management System (LMS) designed for Beni-Suef Technological University.

---

## 🎯 Project Overview

Nova System is a comprehensive educational platform that simplifies teaching and learning for three main users:

### 👨‍🎓 **Students**
- Browse and enroll in courses
- Access lectures and course materials
- Submit assignments
- Track attendance
- View grades
- Download educational resources
- Complete personal profile management

### 👨‍🏫 **Doctors (Instructors)**
- Create and manage courses
- Upload lectures and materials
- Create assignments and quizzes
- Manage enrollment requests
- Track student attendance
- Grade assignments
- View student submissions
- Manage course sections

### 🏛️ **Admin (College)**
- User management (add/edit/delete students and instructors)
- Course management
- System configuration
- Activity logs and reports
- Global settings

---

## ✨ Key Features

### 🌐 **Multilingual Support**
- Full Arabic (عربي) and English (English) support
- Automatic language switching
- RTL/LTR layout support
- Localized date and number formats

### 🎨 **Dark/Light Mode**
- System preference detection
- User preference persistence
- Smooth theme transitions
- All components styled for both themes

### 🔐 **Security & Authentication**
- Institutional email validation (name.id@btu.edu.eg)
- Role-based access control (RBAC)
- Bcrypt password hashing
- Session management
- Activity logging

### 📋 **Enrollment System**
- Students request course enrollment
- Doctors approve/reject requests
- Prevents unauthorized access to courses
- Enrollment history tracking

### 📱 **Responsive Design**
- Mobile-first approach
- Works on all devices
- Professional UI/UX
- Bootstrap 5 framework

### 🔔 **Smart Notifications**
- Success/error messages
- Real-time feedback
- Loading overlays
- Activity logging

---

## 🛠️ Tech Stack

- **Backend**: PHP 8.2+
- **Database**: MySQL/MariaDB
- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Framework**: Bootstrap 5
- **Icons**: Font Awesome 6
- **Internationalization**: Custom i18n system
- **Theme**: CSS custom properties

---

## 📦 Installation & Setup

### Prerequisites
- PHP 8.0+ installed
- MySQL 5.7+ or MariaDB 10.4+
- Apache web server (with mod_rewrite)
- Composer (optional)

### Step 1: Database Setup

1. Open phpMyAdmin and create a new database named `lms`
2. Go to `lms_project` folder
3. Import both SQL files:
   - First: `lms.sql` (main database schema)
   - Then: `db_upgrade.sql` (enhancements)

```bash
mysql -u root -p lms < lms.sql
mysql -u root -p lms < db_upgrade.sql
```

### Step 2: Configuration

1. Update `/config/db.php` if your database credentials differ:
```php
$conn = new mysqli("localhost", "root", "", "lms");
```

### Step 3: File Permissions

Ensure upload directories are writable:
```bash
chmod 755 /uploads
chmod 755 /uploads/profiles
chmod 755 /uploads/materials
chmod 755 /uploads/assignments
chmod 755 /uploads/projects
```

### Step 4: Access the System

1. **Landing Page**: `http://localhost/lms_project/landing.php` or `/`
2. **Login**: `http://localhost/lms_project/auth/login.php`

---

## 👤 Default Users

### Admin
- **Email**: `admin@btu.edu.eg`
- **Password**: Configured during installation

### Sample Doctor
- **Email**: `osama@btu.edu.eg`
- **Password**: Check database

### Sample Student
- **Email**: `Ibrahim.20241@btu.edu.eg`
- **Password**: Check database

---

## 📂 Project Structure

```
lms_project/
├── api/                    # API endpoints
│   └── enrollment_requests.php
├── assets/                 # Static assets
│   ├── css/
│   │   ├── darkmode.css   # Theme styles
│   │   └── style.css
│   ├── js/
│   │   ├── i18n.js        # Internationalization
│   │   ├── theme-switcher.js
│   │   └── utils.js       # Utilities
│   └── images/
├── auth/                   # Authentication
│   ├── login.php
│   ├── logout.php
│   ├── check_access.php   # Authorization
│   └── check_role.php
├── admin/                  # Admin panel
│   └── dashboard.php
├── doctor/                 # Instructor dashboard
│   ├── dashboard.php
│   ├── courses.php
│   ├── add_course.php
│   └── ...
├── student/                # Student dashboard
│   ├── dashboard.php
│   ├── courses.php
│   ├── assignments.php
│   └── ...
├── config/                 # Configuration
│   ├── db.php
│   ├── constants.php      # Application constants
│   └── helpers.php        # Helper functions
├── uploads/               # User uploads
│   ├── profiles/
│   ├── materials/
│   ├── assignments/
│   └── projects/
├── landing.php            # Landing page
├── index.php              # Home page
├── lms.sql                # Main database schema
├── db_upgrade.sql         # Database upgrades
└── README.md
```

---

## 🚀 Quick Start

### For Students
1. Navigate to `http://localhost/lms_project/landing.php`
2. Click "تسجيل الدخول / Login"
3. Use your institutional email (e.g., name.202481@btu.edu.eg)
4. After login, access your dashboard at `/student/dashboard.php`

### For Instructors
1. Login with doctor credentials
2. Access `/doctor/dashboard.php`
3. Create courses and manage students

### For Administrators
1. Login with admin credentials
2. Access `/admin/dashboard.php`
3. Manage all system users and settings

---

## 🔑 Key Features Breakdown

### 📚 Enrollment System
Students request enrollment → Doctor approves/rejects → Student access granted

### 📊 Grading System
- Assign grades to assignments
- Track student performance
- Provide feedback
- Calculate course grades

### 📝 Materials Management
- Upload lectures
- Manage course sections
- Share educational resources
- Version control

### ✅ Attendance Tracking
- Mark attendance
- Calculate attendance percentage
- Generate reports
- Track patterns

### 💬 Assignment Management
- Create assignments
- Set deadlines
- Receive submissions
- Grade submissions

---

## 🌍 Internationalization (i18n)

The system uses a custom i18n system:

```javascript
// Change language
setLanguage('ar');  // Arabic
setLanguage('en');  // English

// Get translation
const text = t('nav.login');  // Returns translated text

// Add translations in assets/js/i18n.js
```

### Supported Languages
- **Arabic (ar)** - Default
- **English (en)**

---

## 🎨 Dark Mode

The system automatically detects system preference and allows manual toggle:

```javascript
// Toggle dark mode
window.themeSwitcher.toggle();

// Check current theme
const theme = window.themeSwitcher.getCurrentTheme(); // 'dark' or 'light'
```

---

## 🔐 Security Features

1. **Email Validation**: Only institutional emails allowed
2. **Password Hashing**: Bcrypt with automatic migration
3. **Role-Based Access**: Protect pages by role
4. **Activity Logging**: Track all important actions
5. **Session Management**: Secure session handling
6. **CSRF Protection**: Available via Bootstrap CSRF
7. **SQL Injection Prevention**: Prepared statements

---

## 📋 API Documentation

### Enrollment Requests API
**Endpoint**: `/api/enrollment_requests.php`

#### Request Course Enrollment
```
POST /api/enrollment_requests.php
action: request_enrollment
course_id: [integer]
```

#### Get Pending Requests (Doctor)
```
GET /api/enrollment_requests.php?action=get_pending_requests&course_id=[optional]
```

#### Approve Request (Doctor)
```
POST /api/enrollment_requests.php
action: approve_request
request_id: [integer]
```

#### Reject Request (Doctor)
```
POST /api/enrollment_requests.php
action: reject_request
request_id: [integer]
reason: [string]
```

---

## 📱 Database Schema

### Key Tables
- `users` - All users (students, doctors, admin)
- `courses` - Course information
- `enrollments` - Student course enrollments
- `enrollment_requests` - Course enrollment requests
- `assignments` - Assignment and quiz data
- `submissions` - Student submissions
- `attendance` - Attendance records
- `materials` - Course materials (lectures, sections)
- `grades` - Student grades
- `system_logs` - Activity logs

---

## 🐛 Troubleshooting

### Login Issues
- Ensure database is running
- Check email format (must be institutional)
- Verify user exists in database
- Check password is correct

### Database Connection Error
- Verify MySQL server is running
- Check credentials in `/config/db.php`
- Ensure database and tables are created

### Page Shows Blank
- Check PHP error logs
- Verify all required files are present
- Check file permissions

### Dark Mode Not Working
- Clear browser cache
- Check browser localStorage support
- Verify theme-switcher.js is loaded

---

## 📞 Support & Contact

**Email**: lms@btu.edu.eg  
**Address**: Beni-Suef Technological University  
**Phone**: +20 (2) XXXX-XXXX

---

## 📜 License & Rights

© 2026 Beni-Suef Technological University  
All Rights Reserved

---

## 👥 Development Team

Nova System was developed as a comprehensive solution for managing education at BTU.

---

## 🗺️ Roadmap

- [ ] Video streaming support
- [ ] Advanced analytics and reports
- [ ] Discussion forums
- [ ] Student groups/teams
- [ ] Assignment rubrics
- [ ] Mobile app
- [ ] Email notifications
- [ ] SMS notifications
- [ ] Calendar integration
- [ ] Plagiarism detection

---

**Last Updated**: May 8, 2026

---

**Getting Help**: 
1. Check this README
2. Review inline code comments
3. Check browser console for errors
4. Contact support team
