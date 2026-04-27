<?php session_start();
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
    <title>Student Registration - OBU</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
</head>

<body>
    <nav>
        <a href="index.php" class="logo">
            <svg xmlns="http://www.w3.org/-2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
                <path d="M6 12v5c3 3 9 3 12 0v-5" />
            </svg>
            OBU
        </a>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php" class="btn active">Register</a></li>
        </ul>
    </nav>

    <div class="form-container" style="max-width: 550px;">
        <h2>Student Registration</h2>
        <p style="text-align:center; color: var(--text-light); margin-bottom: 2rem;">Apply for GIS Department Admissions
        </p>

        <?php
        if (isset($_SESSION['error_msg'])) {
            echo '<div class="error-msg">' . $_SESSION['error_msg'] . '</div>';
            unset($_SESSION['error_msg']);
        }
        ?>

        <form action="process_register.php" method="POST" enctype="multipart/form-data" class="validate-form">
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name" required placeholder="First name">
                </div>
                <div class="form-group">
                    <label for="middle_name">Middle Name</label>
                    <input type="text" id="middle_name" name="middle_name" required placeholder="Middle name">
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" required placeholder="Last name">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label for="age">Age</label>
                    <input type="number" id="age" name="age" required placeholder="e.g. 20">
                </div>
                <div class="form-group">
                    <label for="year_of_joining">Year of Joining</label>
                    <input type="number" id="year_of_joining" name="year_of_joining" required placeholder="e.g. 2026">
                </div>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required placeholder="you@example.com">
            </div>
            <div class="form-group">
                <label for="department">Department</label>
                <input type="text" id="department" name="department" value="Geographic Information Science" readonly
                    style="background:#f1f5f9;">
            </div>
            <div class="form-group">
                <label for="profile_image">Profile Picture</label>
                <input type="file" id="profile_image" name="profile_image" required accept="image/*">
                <small style="color: var(--text-light);">Accepted: JPG, PNG, GIF</small>
            </div>

            <hr style="margin: 2rem 0; border: 0; border-top: 1px solid var(--border);">
            <h3>Create Login Credentials</h3>

            <div class="form-group">
                <label for="username">Choose System Username</label>
                <input type="text" id="username" name="username" required placeholder="Choose a unique username">
            </div>
            <div class="form-group">
                <label for="password">Create Password</label>
                <input type="password" id="password" name="password" required placeholder="Create strong password">
            </div>
            <label class="show-password">
                <input type="checkbox"
                    onclick="document.getElementById('password').type = this.checked ? 'text' : 'password'"> Show
                Password
            </label>

            <button type="submit" name="register" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Submit
                Registration Application</button>
            <p style="text-align:center; margin-top: 1rem; font-size: 0.9rem;">Already applied? <a href="login.php"
                    style="color:var(--primary);">Login here &rarr;</a></p>
        </form>
    </div>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> OBU GIS Department</p>
    </footer>
</body>

</html>