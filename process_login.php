<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $_SESSION['error_msg'] = "Please fill all fields.";
        header("Location: login.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT id, username, password, role, email FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['email'] = $user['email'];

            // Redirect based on role
            if ($user['role'] === 'admin') {
                header("Location: admin_dashboard.php");
            } elseif ($user['role'] === 'registrar') {
                header("Location: dashboard.php");
            } else {
                header("Location: dashboard.php");
            }

            exit();

        } else {
            $_SESSION['error_msg'] = "Incorrect password.";
            header("Location: login.php");
            exit();
        }

    } else {
        $_SESSION['error_msg'] = "User not found.";
        header("Location: login.php");
        exit();
    }

} else {
    header("Location: login.php");
    exit();
}
?>