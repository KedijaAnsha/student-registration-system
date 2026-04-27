<?php
session_start();
require_once 'db.php';

// Only Registrar and Admin can access this
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'registrar' && $_SESSION['role'] !== 'admin')) {
    header("Location: dashboard.php");
    exit();
}

$role = $_SESSION['role'];

// Handle Status Updates (Registrar Only)
if (isset($_POST['update_status']) && $role === 'registrar') {
    $student_id = $_POST['student_id'];
    $status = $_POST['status'];
    
    $stmt = $conn->prepare("UPDATE students SET status = ? WHERE student_id = ?");
    $stmt->bind_param("ss", $status, $student_id);
    if($stmt->execute()){
        $_SESSION['success_msg'] = "Status updated successfully for $student_id.";
    } else {
        $_SESSION['error_msg'] = "Failed to update status.";
    }
    header("Location: view_students.php");
    exit();
}

// Handle Deletions (Registrar Only)
if (isset($_GET['delete']) && $role === 'registrar') {
    $student_id = $_GET['delete'];
    
    // We must delete the user which will cascade to the student table
    $stmt = $conn->prepare("DELETE users FROM users JOIN students ON users.id = students.user_id WHERE students.student_id = ?");
    $stmt->bind_param("s", $student_id);
    if($stmt->execute()){
        $_SESSION['success_msg'] = "Student record deleted.";
    } else {
        $_SESSION['error_msg'] = "Failed to delete student.";
    }
    header("Location: view_students.php");
    exit();
}

// Fetch Students
$students = $conn->query("SELECT * FROM students ORDER BY student_id ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Students - OBU Registration System</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function confirmDelete(id) {
            if(confirm('Are you sure you want to completely remove student ' + id + '?')) {
                window.location.href = 'view_students.php?delete=' + id;
            }
        }
    </script>
</head>
<body>
    <nav>
        <a href="index.php" class="logo">OBU</a>
        <ul class="nav-links">
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="view_students.php" class="active">Students</a></li>
            <?php if($role === 'admin'): ?>
                <li><a href="manage_users.php">Manage Users</a></li>
            <?php endif; ?>
            <li><a href="logout.php" class="btn btn-danger">Logout</a></li>
        </ul>
    </nav>

    <div class="dashboard-container">
        <h2>Registered Students Database</h2>
        
        <?php
            if (isset($_SESSION['error_msg'])) {
                echo '<div class="error-msg" style="margin-top:1rem;">' . $_SESSION['error_msg'] . '</div>';
                unset($_SESSION['error_msg']);
            }
            if (isset($_SESSION['success_msg'])) {
                echo '<div class="success-msg" style="margin-top:1rem;">' . $_SESSION['success_msg'] . '</div>';
                unset($_SESSION['success_msg']);
            }
        ?>

        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Profile</th>
                        <th>Full Name</th>
                        <th>Age</th>
                        <th>Email</th>
                        <th>Year</th>
                        <th>Status</th>
                        <?php if($role === 'registrar'): ?>
                            <th>Actions</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if($students->num_rows > 0): ?>
                        <?php while($row = $students->fetch_assoc()): ?>
                            <tr>
                                <td><strong style="color:var(--primary);"><?php echo htmlspecialchars($row['student_id']); ?></strong></td>
                                <td>
                                    <img src="uploads/<?php echo htmlspecialchars($row['profile_image']); ?>" alt="Profile" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 1px solid var(--border);">
                                </td>
                                <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['age']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['year_of_joining']); ?></td>
                                <td>
                                    <?php 
                                        $s = $row['status'];
                                        $s_class = ($s == 'approved') ? 'status-approved' : (($s == 'rejected') ? 'status-rejected' : 'status-pending');
                                    ?>
                                    <span class="status-badge <?php echo $s_class; ?>"><?php echo ucfirst($s); ?></span>
                                </td>
                                
                                <?php if($role === 'registrar'): ?>
                                <td>
                                    <form action="view_students.php" method="POST" style="display:inline-block; margin-right:5px;">
                                        <input type="hidden" name="student_id" value="<?php echo $row['student_id']; ?>">
                                        <select name="status" onchange="this.form.submit()" style="padding: 0.3rem; border-radius: 4px; border: 1px solid var(--border);">
                                            <option value="pending" <?php if($s == 'pending') echo 'selected'; ?>>Pending</option>
                                            <option value="approved" <?php if($s == 'approved') echo 'selected'; ?>>Approve</option>
                                            <option value="rejected" <?php if($s == 'rejected') echo 'selected'; ?>>Reject</option>
                                        </select>
                                        <input type="hidden" name="update_status" value="1">
                                    </form>
                                    <button type="button" onclick="confirmDelete('<?php echo $row['student_id']; ?>')" class="btn btn-danger" style="padding: 0.3rem 0.6rem; font-size: 0.85rem;">Delete</button>
                                </td>
                                <?php endif; ?>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="<?php echo ($role === 'registrar') ? 6 : 5; ?>" style="text-align:center;">No students found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> OBU GIS Department</p>
    </footer>
</body>
</html>
