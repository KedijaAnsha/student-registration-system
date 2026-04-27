# Web-Based OBU Student Registration System
**Oda Bultum University - Geographic Information Science Department**

## 1. System Description
The Web-Based OBU Student Registration System is a modernized, unified application designed to simplify the enrollment tracking and approval process for the GIS Department. The system transitions the department away from manual paper-based methods towards an automated, secure digital framework. 

The software utilizes a robust Three-Tier architecture, combining a presentation layer built with vanilla HTML5, CSS3, and JavaScript, application logic governed by PHP, and data persistence securely handled by a MySQL database. 

It provides strict **Role-Based Access Control (RBAC)** serving three distinct types of users:
1. **Student:** Prospective applicants can securely invoke new accounts, submit their academic details via a validated electronic form, and monitor whether their registration has been approved or rejected by the department.
2. **Registrar:** University administrative staff possess permissions to view the centralized database of student applications, utilizing a custom Search feature to isolate specific students. They review, evaluate, and dynamically alter the status (Approve, Reject, Pending) of applications or choose to completely revoke fraudulent records.
3. **System Admin:** The highest level of clearance responsible for maintaining the system's operational integrity. The administrator manages all user provisions, including creating accounts for new Registrars, removing outdated staff members, and observing system-wide statistical reports.

## 2. Advanced Security & Data Integrity

### 2.1 Backend PHP Input Validation
While JavaScript catches front-end typos, our strict PHP backend validation acts as the definitive security layer. In `process_register.php`, the system unconditionally blocks empty submittals:
```php
if (empty($first_name) || empty($last_name) || empty($email) || empty($username) || empty($password) || empty($age) || empty($year_of_joining)) {
    $_SESSION['error_msg'] = "All fields are required.";
    exit();
}
```

### 2.2 Duplicate Record Protection Mechanism
The database protects against fraudulent double-registrations. Before an applicant is accepted, `SELECT` queries evaluate if the `$username` or `$email` is already structurally bound to another row in the database.

### 2.3 Strict Session Check & RBAC Flow
Security relies on PHP sessions initialized at the very top of files. 
- **Session Flow:** Before any secure page renders, `session_start()` executes. If `$_SESSION['user_id']` is inactive, the user is redirected back to `login.php`.
- **Role Enforcement:** In restricted pages like `view_students.php`, we explicitly evaluate `if($_SESSION['role'] !== 'registrar' && $_SESSION['role'] !== 'admin')`. 

### 2.4 Auto-Generated Student ID Logic
The system automatically generates unique Student IDs in the format `OBU-YEAR-XXXX`. Upon registration, the system counts existing students for the specified year and assigns the next sequential number (e.g., `OBU-2026-0001`).

### 2.5 Audit Trails & Tracking
Real-world data evolution is tracked natively by our MySQL servers using native Audit Timestamps:
- `created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP`
- `updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP`

## 3. Recommended System Workflow (Process Flow)
1. **Initiation:** The student navigates to `register.php`. They provide their Full Name (First, Middle, Last), Age, Year of Joining, Email, and upload a Profile Picture.
2. **Processing:** They click register. The PHP layer hashes the password, generates the Student ID, and saves the record with a `pending` status.
3. **Review:** A Registrar logs in and navigates to the Students list.
4. **Action:** The Registrar reviews the application and updates the status to `approved` or `rejected`.

## 4. Database Schema Details
The system stores its application state in a relational database named `obu_registration`.

**Table: `users`**
- `id` : INT (Primary Key, Auto Increment)
- `username` : VARCHAR(50) (Unique)
- `password` : VARCHAR(255) (Hashed)
- `role` : ENUM('student', 'registrar', 'admin')

**Table: `students`**
- `student_id` : VARCHAR(20) (Primary Key) - `OBU-YEAR-XXXX`
- `user_id` : INT (Foreign Key)
- `first_name`, `middle_name`, `last_name` : VARCHAR(50)
- `email` : VARCHAR(100) (Unique)
- `department` : VARCHAR(100)
- `age` : INT
- `year_of_joining` : INT
- `profile_image` : VARCHAR(255)
- `status` : ENUM('pending', 'approved', 'rejected')

---
**Prepared By:** Antigravity AI
**Course:** ITCs 2111 (Web Application Development)
