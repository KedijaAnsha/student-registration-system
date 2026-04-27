<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - OBU Registration System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <a href="index.php" class="logo">OBU</a>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="login.php" class="active">Login</a></li>
            <li><a href="register.php">Register</a></li>
        </ul>
    </nav>

    <div class="form-container" style="max-width: 400px;">
        <h2>System Login</h2>
        <p style="text-align:center; color: var(--text-light); margin-bottom: 2rem;">Enter your credentials to access the portal</p>

        <?php
        if (isset($_SESSION['error_msg'])) {
            echo '<div class="error-msg">' . htmlspecialchars($_SESSION['error_msg']) . '</div>';
            unset($_SESSION['error_msg']);
        }
        if (isset($_SESSION['success_msg'])) {
            echo '<div class="success-msg">' . htmlspecialchars($_SESSION['success_msg']) . '</div>';
            unset($_SESSION['success_msg']);
        }
        ?>

        <form action="process_login.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required placeholder="Enter your username">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password">
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Login to Portal</button>
            <p style="text-align:center; margin-top: 1rem; font-size: 0.9rem;">New student? <a href="register.php" style="color:var(--primary);">Apply for admission &rarr;</a></p>
        </form>
    </div>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Oda Bultum University — Geographic Information Science Department. All rights reserved.</p>
    </footer>
</body>
</html>