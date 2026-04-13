<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

require_once "../config/database.php";
require_once "../models/Complaint.php";

$complaint  = new Complaint($conn);
$complaints = $complaint->getAll();

$all        = $conn->query("SELECT COUNT(*) as c FROM complaints")->fetch_assoc()['c'];
$pending    = $conn->query("SELECT COUNT(*) as c FROM complaints WHERE status='Pending'")->fetch_assoc()['c'];
$inprogress = $conn->query("SELECT COUNT(*) as c FROM complaints WHERE status='In Progress'")->fetch_assoc()['c'];
$resolved   = $conn->query("SELECT COUNT(*) as c FROM complaints WHERE status='Resolved'")->fetch_assoc()['c'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Complaints</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body class="dashboard admin">

<div class="sidebar">
    <h2>Admin Panel</h2>
    <ul>
        <li><a href="admin-dashboard.php">Dashboard</a></li>
        <li><a href="manage-complaints.php">Manage Complaints</a></li>
        <li><a href="#">Users</a></li>
        <li><a href="../logout.php" class="logout">Logout</a></li>
    </ul>
</div>

<div class="main-content">

    <div class="top-bar">
        <h1>Manage Complaints</h1>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert success">Status updated successfully.</div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <div class="alert error">Failed to update. Try again.</div>
    <?php endif; ?>

    <div class="stats-row">
        <div class="stat-card all">
            <h4>Total</h4>
            <p><?php echo $all; ?></p>
        </div>
        <div class="stat-card pending">
            <h4>Pending</h4>
            <p><?php echo $pending; ?></p>
        </div>
        <div class="stat-card progress">
            <h4>In Progress</h4>
            <p><?php echo $inprogress; ?></p>
        </div>
        <div class="stat-card resolved">
            <h4>Resolved</h4>
            <p><?php echo $resolved; ?></p>
        </div>
    </div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Citizen</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Department</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                    <th>View</th>  <!-- ✅ CORRECT PLACE -->
                </tr>
            </thead>
            <tbody>
                <?php while($row = $complaints->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
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
                        <form class="update-form" action="../controllers/updatestatuscontroller.php" method="POST">
                            <input type="hidden" name="complaint_id" value="<?php echo $row['id']; ?>">
                            <select name="status">
                                <option value="Pending"     <?php if($row['status']==='Pending')     echo 'selected'; ?>>Pending</option>
                                <option value="In Progress" <?php if($row['status']==='In Progress') echo 'selected'; ?>>In Progress</option>
                                <option value="Resolved"    <?php if($row['status']==='Resolved')    echo 'selected'; ?>>Resolved</option>
                            </select>
                            <button type="submit" class="update-btn">Update</button>
                        </form>
                    </td>

                    <td> 
                        <a href="view-complaint.php?id=<?php echo $row['id']; ?>" class="view-btn">View</a>
                    </td>

                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>