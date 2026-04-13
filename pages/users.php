<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

require_once "../config/database.php";
require_once "../models/User.php";

$user          = new User($conn);
$users         = $user->getAll();
$totalUsers    = $conn->query("SELECT COUNT(*) as c FROM users")->fetch_assoc()['c'];
$totalCitizens = $conn->query("SELECT COUNT(*) as c FROM users WHERE role='citizen'")->fetch_assoc()['c'];
$totalAdmins   = $conn->query("SELECT COUNT(*) as c FROM users WHERE role='admin'")->fetch_assoc()['c'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body class="dashboard admin">

<div class="sidebar">
    <h2>Admin Panel</h2>
    <ul>
        <li><a href="admin-dashboard.php">Dashboard</a></li>
        <li><a href="manage-complaints.php">Manage Complaints</a></li>
        <li><a href="users.php">Users</a></li>
        <li><a href="../logout.php" class="logout">Logout</a></li>
    </ul>
</div>

<div class="main-content">

    <div class="top-bar">
        <h1>Manage Users</h1>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert success">User deleted successfully.</div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <div class="alert error">Failed to delete user.</div>
    <?php endif; ?>

    <div class="stats-row">
        <div class="stat-card all">
            <h4>Total Users</h4>
            <p><?php echo $totalUsers; ?></p>
        </div>
        <div class="stat-card pending">
            <h4>Citizens</h4>
            <p><?php echo $totalCitizens; ?></p>
        </div>
        <div class="stat-card resolved">
            <h4>Admins</h4>
            <p><?php echo $totalAdmins; ?></p>
        </div>
    </div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Registered</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($users->num_rows === 0): ?>
                <tr>
                    <td colspan="6" style="text-align:center; color:#999; padding:30px;">
                        No users found.
                    </td>
                </tr>
                <?php else: ?>
                <?php while($row = $users->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td>
                        <span class="badge <?php echo $row['role'] === 'admin' ? 'resolved' : 'progress'; ?>">
                            <?php echo ucfirst($row['role']); ?>
                        </span>
                    </td>
                    <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                    <td>
                        <?php if ($row['role'] !== 'admin'): ?>
                        <form action="../controllers/deleteusercontroller.php" method="POST"
                              onsubmit="return confirm('Delete this user?')">
                            <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="delete-btn">Delete</button>
                        </form>
                        <?php else: ?>
                            <span style="color:#999; font-size:12px;">Protected</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>