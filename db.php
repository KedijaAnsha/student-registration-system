<?php
$host = 'localhost';
$user = 'root'; // Default XAMPP user
$password = ''; // Default XAMPP password
$dbname = 'obu_registration';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
