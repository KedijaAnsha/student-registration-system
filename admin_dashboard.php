<?php
session_start();
require_once 'db.php';

// Strict admin role check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch system stats
// 1. Total users
$res_users = $conn->query("SELECT COUNT(*) as total FROM users");
$total_users = $res_users->fetch_assoc()['total'];

// 2. Total students
$res_students = $conn->query("SELECT COUNT(*) as total FROM students");
$total_students = $res_students->fetch_assoc()['total'];

// 3. Status counts
$res_pending = $conn->query("SELECT COUNT(*) as total FROM students WHERE status = 'pending'");
$pending_count = $res_pending->fetch_assoc()['total'];

$res_approved = $conn->query("SELECT COUNT(*) as total FROM students WHERE status = 'approved'");
$approved_count = $res_approved->fetch_assoc()['total'];

$res_rejected = $conn->query("SELECT COUNT(*) as total FROM students WHERE status = 'rejected'");
$rejected_count = $res_rejected->fetch_assoc()['total'];

// 4. Recent student applications
$recent_students = $conn->query("SELECT student_id, first_name, last_name, department, status, created_at FROM students ORDER BY created_at DESC LIMIT 5");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - OBU Registration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <a href="index.php" class="logo">OBU Admin</a>
        <ul class="nav-links">
            <li><a href="admin_dashboard.php" class="active">Dashboard</a></li>
            <li><a href="view_students.php">Students</a></li>
            <li><a href="manage_users.php">Manage Users</a></li>
            <li><a href="logout.php" class="btn btn-danger">Logout</a></li>
        </ul>
    </nav>

    <div class="dashboard-container">
        <h2>Admin Dashboard</h2>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>

        <div style="display: flex; gap: 1rem; margin-top: 2rem; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 200px; background: #fff; padding: 1.5rem; border-radius: 8px; border: 1px solid var(--border); text-align: center;">
                <h3>Total Users</h3>
                <p style="font-size: 2rem; font-weight: bold; color: var(--primary);"><?php echo $total_users; ?></p>
            </div>
            <div style="flex: 1; min-width: 200px; background: #fff; padding: 1.5rem; border-radius: 8px; border: 1px solid var(--border); text-align: center;">
                <h3>Total Students</h3>
                <p style="font-size: 2rem; font-weight: bold; color: var(--primary);"><?php echo $total_students; ?></p>
            </div>
            <div style="flex: 1; min-width: 200px; background: #fff; padding: 1.5rem; border-radius: 8px; border: 1px solid var(--border); text-align: center;">
                <h3>Pending</h3>
                <p style="font-size: 2rem; font-weight: bold; color: orange;"><?php echo $pending_count; ?></p>
            </div>
            <div style="flex: 1; min-width: 200px; background: #fff; padding: 1.5rem; border-radius: 8px; border: 1px solid var(--border); text-align: center;">
                <h3>Approved</h3>
                <p style="font-size: 2rem; font-weight: bold; color: var(--success);"><?php echo $approved_count; ?></p>
            </div>
            <div style="flex: 1; min-width: 200px; background: #fff; padding: 1.5rem; border-radius: 8px; border: 1px solid var(--border); text-align: center;">
                <h3>Rejected</h3>
                <p style="font-size: 2rem; font-weight: bold; color: var(--danger);"><?php echo $rejected_count; ?></p>
            </div>
        </div>

        <div style="margin-top: 3rem;">
            <h3>Recent Student Applications</h3>
            <table>
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Date Applied</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($recent_students->num_rows > 0): ?>
                        <?php while ($row = $recent_students->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['student_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['department']); ?></td>
                                <td>
                                    <?php
                                        $status_color = ($row['status'] == 'approved') ? 'var(--success)' : (($row['status'] == 'rejected') ? 'var(--danger)' : 'orange');
                                    ?>
                                    <span style="color: <?php echo $status_color; ?>; font-weight: 600;">
                                        <?php echo ucfirst($row['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" style="text-align: center;">No recent applications found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> OBU GIS Department. All rights reserved.</p>
    </footer>
</body>
</html>