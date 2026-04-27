<?php
require_once 'db.php';

$password = 'admin123';
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Check if admin exists
$stmt = $conn->prepare("SELECT id FROM users WHERE username = 'admin'");
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Update existing admin
    $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = 'admin'");
    $update_stmt->bind_param("s", $hashed_password);
    if ($update_stmt->execute()) {
        echo "<h2 style='color: green;'>Success: Admin password successfully updated to 'admin123'!</h2>";
        echo "<p>You can now <a href='login.php'>click here to login</a>.</p>";
    } else {
        echo "<h2 style='color: red;'>Error updating password.</h2>";
    }
} else {
    // Insert new admin
    $insert_stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES ('admin', 'admin@obu.edu', ?, 'admin')");
    $insert_stmt->bind_param("s", $hashed_password);
    if ($insert_stmt->execute()) {
        echo "<h2 style='color: green;'>Success: Admin account created with password 'admin123'!</h2>";
        echo "<p>You can now <a href='login.php'>click here to login</a>.</p>";
    } else {
        echo "<h2 style='color: red;'>Error creating admin account.</h2>";
    }
}
?>
