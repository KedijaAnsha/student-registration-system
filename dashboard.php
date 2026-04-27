<?php
// 1. START SESSION (MUST BE FIRST)
session_start();
require_once 'db.php';

// 2. SESSION PROTECTION
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

// 3. GET SESSION DATA
$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - OBU Registration System</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <nav>
        <a href="index.php" class="logo">OBU</a>
        <ul class="nav-links">
            <li><a href="dashboard.php" class="active">Dashboard</a></li>

            <?php if ($role === 'registrar'): ?>
                <li><a href="view_students.php">Manage Students</a></li>

            <?php elseif ($role === 'admin'): ?>
                <li><a href="manage_users.php">Manage Users</a></li>
                <li><a href="view_students.php">System Reports</a></li>
            <?php endif; ?>

            <li><a href="logout.php" class="btn btn-danger">Logout</a></li>
        </ul>
    </nav>

    <div class="dashboard-container">

        <!-- HEADER -->
        <div class="dashboard-header">
            <h2>Welcome,
                <?php echo htmlspecialchars(ucfirst($username)); ?>!
            </h2>
            <span style="background: var(--primary); color: white; padding: 0.3rem 0.8rem; border-radius: 9999px;">
                Role:
                <?php echo ucfirst($role); ?>
            </span>
        </div>

        <!-- ================= STUDENT ================= -->
        <?php if ($role === 'student'): ?>

            <h3>Your Registration Details</h3>

            <?php
            $stmt = $conn->prepare("SELECT * FROM students WHERE user_id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0):
                $student = $result->fetch_assoc();

                $status_class = "status-pending";
                if ($student['status'] === 'approved')
                    $status_class = "status-approved";
                if ($student['status'] === 'rejected')
                    $status_class = "status-rejected";
                ?>

                <div class="card">
                    <img src="uploads/<?php echo htmlspecialchars($student['profile_image']); ?>" width="150">

                    <p><strong>ID:</strong>
                        <?php echo htmlspecialchars($student['student_id']); ?>
                    </p>
                    <p><strong>Name:</strong>
                        <?php echo htmlspecialchars($student['first_name'] . ' ' . $student['middle_name'] . ' ' . $student['last_name']); ?>
                    </p>
                    <p><strong>Age:</strong>
                        <?php echo $student['age']; ?>
                    </p>
                    <p><strong>Email:</strong>
                        <?php echo htmlspecialchars($student['email']); ?>
                    </p>
                    <p><strong>Department:</strong>
                        <?php echo htmlspecialchars($student['department']); ?>
                    </p>

                    <p>
                        <strong>Status:</strong>
                        <span class="<?php echo $status_class; ?>">
                            <?php echo ucfirst($student['status']); ?>
                        </span>
                    </p>
                </div>

            <?php else: ?>
                <p>No student record found.</p>
            <?php endif; ?>


            <!-- ================= REGISTRAR ================= -->
        <?php elseif ($role === 'registrar'): ?>

            <h3>Registrar Dashboard</h3>
            <p>You can manage student registrations.</p>

            <a href="view_students.php" class="btn">
                View Student Applications →
            </a>


            <!-- ================= ADMIN ================= -->
        <?php elseif ($role === 'admin'): ?>

            <h3>Admin Dashboard</h3>
            <p>Manage system users and view reports.</p>

            <div style="margin-top: 1rem;">
                <a href="manage_users.php" class="btn">Manage Users</a>
                <a href="view_students.php" class="btn">View Students</a>
            </div>

            <?php
            // SAFE COUNTS
            $users_count = 0;
            $students_count = 0;

            $res1 = $conn->query("SELECT COUNT(*) as c FROM users");
            if ($res1)
                $users_count = $res1->fetch_assoc()['c'];

            $res2 = $conn->query("SELECT COUNT(*) as c FROM students");
            if ($res2)
                $students_count = $res2->fetch_assoc()['c'];
            ?>

            <div style="margin-top: 2rem;">
                <h4>System Stats</h4>

                <p>Total Users:
                    <?php echo $users_count; ?>
                </p>
                <p>Total Students:
                    <?php echo $students_count; ?>
                </p>
            </div>


            <!-- ================= FALLBACK ================= -->
        <?php else: ?>

            <p style="color:red;">Error: Invalid role.</p>

        <?php endif; ?>

    </div>

    <footer>
        <p>&copy;
            <?php echo date("Y"); ?> OBU GIS Department
        </p>
    </footer>

</body>

</html>