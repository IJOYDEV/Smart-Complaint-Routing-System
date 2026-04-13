<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "citizen") {
    header("Location: ../login.php");
    exit();
}

require_once "../config/database.php";
require_once "../models/Complaint.php";

$user_id   = $_SESSION["user_id"];
$complaint = new Complaint($conn);
$complaints = $complaint->getByUser($user_id);

$total    = $conn->query("SELECT COUNT(*) as c FROM complaints WHERE user_id=$user_id")->fetch_assoc()['c'];
$pending  = $conn->query("SELECT COUNT(*) as c FROM complaints WHERE user_id=$user_id AND status='Pending'")->fetch_assoc()['c'];
$resolved = $conn->query("SELECT COUNT(*) as c FROM complaints WHERE user_id=$user_id AND status='Resolved'")->fetch_assoc()['c'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Citizen Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body class="dashboard citizen">

<div class="sidebar">
    <h2>Citizen Panel</h2>
    <ul>
        <li><a href="citizen-dashboard.php">Dashboard</a></li>
        <li><a href="../submitcomplaint.php">Submit Complaint</a></li>
        <li><a href="../logout.php" class="logout">Logout</a></li>
    </ul>
</div>

<div class="main-content">

    <div class="top-bar">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION["fullname"]); ?></h1>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert success">Complaint submitted successfully.</div>
    <?php endif; ?>

    <!-- stats -->
    <div class="stats-row">
        <div class="stat-card all">
            <h4>Total</h4>
            <p><?php echo $total; ?></p>
        </div>
        <div class="stat-card pending">
            <h4>Pending</h4>
            <p><?php echo $pending; ?></p>
        </div>
        <div class="stat-card resolved">
            <h4>Resolved</h4>
            <p><?php echo $resolved; ?></p>
        </div>
    </div>

    <!-- complaints table -->
    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Department</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>View</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($complaints->num_rows === 0): ?>
                <tr>
                    <td colspan="6" style="text-align:center; color:#999; padding:30px;">
                        No complaints submitted yet.
                        <a href="../submitcomplaint.php">Submit one now</a>
                    </td>
                </tr>
                <?php else: ?>
                <?php while($row = $complaints->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['department_name'] ?? 'N/A'); ?></td>
                    <td>
                        <?php
                        $s   = $row['status'];
                        $cls = $s === 'Pending' ? 'pending' : ($s === 'In Progress' ? 'progress' : 'resolved');
                        ?>
                        <span class="badge <?php echo $cls; ?>"><?php echo $s; ?></span>
                    </td>
                    <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                    <td>
                        <a href="citizen-view-complaint.php?id=<?php echo $row['id']; ?>" class="view-btn">View</a>
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