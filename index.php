<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OBU Student Registration System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <a href="index.php" class="logo">
            <svg xmlns="http://www.w3.org/-2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
            OBU Registration
        </a>
        <ul class="nav-links">
            <li><a href="index.php" class="active">Home</a></li>
            <li><a href="#">About us</a></li>
            <li><a href="#">Contact us</a></li>
            <?php if(isset($_SESSION['user_id'])): ?>
                <li><a href="dashboard.php" class="btn">Dashboard</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php" class="btn">Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <div class="hero">
        <h1>Welcome to Oda Bultum University</h1>
        <p>Geographic Information Science Department Web-Based Student Registration System. Manage your academic journey seamlessly and securely.</p>
        
        <?php if(!isset($_SESSION['user_id'])): ?>
            <div>
                <a href="register.php" class="btn btn-success" style="font-size: 1.1rem; padding: 0.75rem 1.5rem; margin-right: 1rem;">Apply Now</a>
                <a href="login.php" class="btn btn-secondary" style="font-size: 1.1rem; padding: 0.75rem 1.5rem;">Student Login</a>
            </div>
        <?php endif; ?>
    </div>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Oda Bultum University - Geographic Information Science Department.</p>
    </footer>
</body>
</html>
