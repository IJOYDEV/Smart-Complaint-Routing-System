<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "citizen") {
    header("Location: ../login.php");
    exit();
}

require_once "../config/database.php";
require_once "../models/Complaint.php";

$id        = $_GET["id"] ?? null;
$complaint = new Complaint($conn);
$data      = $complaint->getById($id);
$replies   = $complaint->getReplies($id);

if (!$data || $data['user_id'] !== $_SESSION["user_id"]) {
    header("Location: citizen-dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Complaint</title>
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
        <h1>Complaint #<?php echo str_pad($data['id'], 4, '0', STR_PAD_LEFT); ?></h1>
    </div>

    <div class="complaint-card">
        <div class="complaint-card-header">
            <h2><?php echo htmlspecialchars($data['title']); ?></h2>
            <?php
            $s   = $data['status'];
            $cls = $s === 'Pending' ? 'pending' : ($s === 'In Progress' ? 'progress' : 'resolved');
            ?>
            <span class="badge <?php echo $cls; ?>"><?php echo $s; ?></span>
        </div>
        <div class="complaint-meta">
            <span>🏢 <?php echo htmlspecialchars($data['department_name'] ?? 'N/A'); ?></span>
            <span>📅 <?php echo date('M d, Y', strtotime($data['created_at'])); ?></span>
        </div>
        <p class="complaint-body"><?php echo htmlspecialchars($data['description']); ?></p>
    </div>

    <div class="replies-section">
        <h3>Admin Replies</h3>

        <?php if ($replies->num_rows === 0): ?>
            <p class="no-replies">No replies yet. Please check back later.</p>
        <?php else: ?>
            <?php while($reply = $replies->fetch_assoc()): ?>
            <div class="reply-card">
                <div class="reply-header">
                    <span class="reply-author">👨‍💼 <?php echo htmlspecialchars($reply['fullname']); ?></span>
                    <span class="reply-date"><?php echo date('M d, Y h:i A', strtotime($reply['responded_at'])); ?></span>
                </div>
                <p class="reply-body"><?php echo htmlspecialchars($reply['response']); ?></p>
            </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>

    <a href="citizen-dashboard.php" class="back-btn">← Back to Dashboard</a>

</div>

</body>
</html>