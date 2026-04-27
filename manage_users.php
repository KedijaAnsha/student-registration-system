<?php
session_start();
require_once 'db.php';

// Only Admin can access this
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();

}

// Handle Add Registrar
if (isset($_POST['add_registrar'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $_SESSION['error_msg'] = "Username already exists.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $role = 'registrar';
            $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $hashed, $role);
            $stmt->execute();
            $_SESSION['success_msg'] = "Registrar account created.";
        }
    } else {
        $_SESSION['error_msg'] = "All fields are required.";
    }
    header("Location: manage_users.php");
    exit();
}

// Handle User Deletion
if (isset($_GET['delete'])) {
    $del_id = $_GET['delete'];
    if ($del_id == $_SESSION['user_id']) {
        $_SESSION['error_msg'] = "You cannot delete your own admin account.";
    } else {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $del_id);
        if($stmt->execute()){
            $_SESSION['success_msg'] = "User account completely removed.";
        } else {
            $_SESSION['error_msg'] = "Failed to remove user.";
        }
    }
    header("Location: manage_users.php");
    exit();
}

$users = $conn->query("SELECT id, username, role FROM users ORDER BY role ASC, id ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - OBU Admin</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function confirmDelete(id, role) {
            if(confirm('Are you sure you want to delete this ' + role + ' account? This action is irreversible.')) {
                window.location.href = 'manage_users.php?delete=' + id;
            }
        }
    </script>
</head>
<body>
    <nav>
        <a href="index.php" class="logo">OBU Admin</a>
        <ul class="nav-links">
            <li><a href="admin_dashboard.php">Dashboard</a></li>
            <li><a href="view_students.php">Students</a></li>
            <li><a href="manage_users.php" class="active">Manage Users</a></li>
            <li><a href="logout.php" class="btn btn-danger">Logout</a></li>
        </ul>
    </nav>

    <div class="dashboard-container">
        <h2>Manage System Users</h2>
        
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

        <div style="display: flex; gap: 2rem; margin-top: 1.5rem; flex-wrap: wrap;">
            
            <div style="flex: 1; min-width: 300px; background: #f8fafc; padding: 1.5rem; border-radius: 8px; border: 1px solid var(--border);">
                <h3>Create Registrar Account</h3>
                <form action="manage_users.php" method="POST" style="margin-top: 1rem;">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" required>
                    </div>
                    <button type="submit" name="add_registrar" class="btn btn-primary" style="width: 100%;">Create Registrar</button>
                </form>
            </div>

            <div style="flex: 2; min-width: 400px;">
                <h3>Active Users List</h3>
                <table>
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Username</th>
                            <th>System Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $users->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                <td>
                                    <?php 
                                        $clr = ($row['role'] == 'admin') ? 'var(--primary)' : (($row['role'] == 'registrar') ? 'var(--success)' : 'var(--text-light)');
                                    ?>
                                    <span style="color: <?php echo $clr; ?>; font-weight: 600;">
                                        <?php echo ucfirst($row['role']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if($row['id'] != $_SESSION['user_id']): ?>
                                        <button onclick="confirmDelete(<?php echo $row['id']; ?>, '<?php echo $row['role']; ?>')" class="btn btn-danger" style="padding: 0.3rem 0.6rem; font-size: 0.85rem;">Remove</button>
                                    <?php else: ?>
                                        <span style="color: var(--text-light); font-size: 0.85rem;">(You)</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> OBU GIS Department</p>
    </footer>
</body>
</html>
