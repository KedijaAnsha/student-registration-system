<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = strtoupper(trim($_POST['first_name'] ?? ''));
    $middle_name = strtoupper(trim($_POST['middle_name'] ?? ''));
    $last_name = strtoupper(trim($_POST['last_name'] ?? ''));
    $age = intval($_POST['age'] ?? 0);
    $year_of_joining = intval($_POST['year_of_joining'] ?? 0);
    $email = trim($_POST['email'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Prevent empty submittals 
    if (empty($first_name) || empty($last_name) || empty($email) || empty($username) || empty($password) || empty($age) || empty($year_of_joining)) {
        $_SESSION['error_msg'] = "All fields are required.";
        header("Location: register.php");
        exit();
    }

    // Check if username already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $_SESSION['error_msg'] = "Username already taken.";
        header("Location: register.php");
        exit();
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT student_id FROM students WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $_SESSION['error_msg'] = "Email already registered.";
        header("Location: register.php");
        exit();
    }

    // Auto-generate Student ID: OBU-YEAR-XXXX
    $year = $year_of_joining;
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM students WHERE year_of_joining = ?");
    $stmt->bind_param("i", $year);
    $stmt->execute();
    $count = $stmt->get_result()->fetch_assoc()['total'];
    $new_id_num = str_pad($count + 1, 4, '0', STR_PAD_LEFT);
    $student_id = "OBU-$year-$new_id_num";

    // Handle Profile Image Upload
    $profile_image = 'default_profile.png';
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['profile_image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            $new_filename = uniqid('stu_', true) . '.' . $ext;
            $upload_path = 'uploads/' . $new_filename;
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $upload_path)) {
                $profile_image = $new_filename;
            }
        } else {
            $_SESSION['error_msg'] = "Invalid image type. Only JPG, PNG, and GIF allowed.";
            header("Location: register.php");
            exit();
        }
    }

    $conn->begin_transaction();
    try {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $role = 'student';

        // Insert into users table
        $stmt1 = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt1->bind_param("sss", $username, $hashed_password, $role);
        $stmt1->execute();
        $user_id = $conn->insert_id;

        // Insert into students table
        $stmt2 = $conn->prepare("INSERT INTO students (student_id, user_id, first_name, middle_name, last_name, email, department, age, year_of_joining, profile_image, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
        $stmt2->bind_param("sisssssiis", $student_id, $user_id, $first_name, $middle_name, $last_name, $email, $department, $age, $year_of_joining, $profile_image);
        $stmt2->execute();

        $conn->commit();

        $_SESSION['success_msg'] = "Registration successful! Your Student ID is $student_id. Please login. Your application is pending approval.";
        header("Location: login.php");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error_msg'] = "System error during registration: " . $e->getMessage();
        header("Location: register.php");
        exit();
    }
} else {
    header("Location: register.php");
    exit();
}
?>