<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "citizen") {
    header("Location: ../login.php");
    exit();
}
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
        <li><a href="#">Dashboard</a></li>
        <li><a href="submit-complaint.php">Submit Complaint</a></li>
        <li><a href="my-complaints.php">My Complaints</a></li>
        <li><a href="../logout.php" class="logout">Logout</a></li>
    </ul>
</div>

<div class="main-content">
    <div class="top-bar">
        <h1>Welcome, <?php echo $_SESSION["fullname"]; ?></h1>
    </div>

    <div class="cards">
        <div class="card">
            <h3>Total Complaints</h3>
            <p>0</p>
        </div>

        <div class="card">
            <h3>Pending</h3>
            <p>0</p>
        </div>

        <div class="card">
            <h3>Resolved</h3>
            <p>0</p>
        </div>
    </div>
</div>

</body>
</html>