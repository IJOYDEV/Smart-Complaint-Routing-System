<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

require_once "../config/database.php";

$totalQuery      = "SELECT COUNT(*) as total    FROM complaints";
$pendingQuery    = "SELECT COUNT(*) as pending  FROM complaints WHERE status='Pending'";
$resolvedQuery   = "SELECT COUNT(*) as resolved FROM complaints WHERE status='Resolved'";

$totalComplaints    = $conn->query($totalQuery)   ->fetch_assoc()['total'];
$pendingComplaints  = $conn->query($pendingQuery) ->fetch_assoc()['pending'];
$resolvedComplaints = $conn->query($resolvedQuery)->fetch_assoc()['resolved'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
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
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION["fullname"]); ?></h1>
    </div>

    <div class="cards">

        <div class="card">
            <h3>Total Complaints</h3>
            <p><?php echo $totalComplaints; ?></p>
        </div>

        <div class="card">
            <h3>Pending Review</h3>
            <p><?php echo $pendingComplaints; ?></p>
        </div>

        <div class="card">
            <h3>Resolved Cases</h3>
            <p><?php echo $resolvedComplaints; ?></p>
        </div>

    </div>

</div>

</body>
</html>