<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

require_once "../config/database.php";
require_once "../models/Complaint.php";

$id        = $_GET["id"] ?? null;
$complaint = new Complaint($conn);
$data      = $complaint->getById($id);
$replies   = $complaint->getReplies($id);

if (!$data) {
    header("Location: manage-complaints.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Complaint</title>
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
        <h1>Complaint #<?php echo str_pad($data['id'], 4, '0', STR_PAD_LEFT); ?></h1>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert success">Reply sent successfully.</div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <div class="alert error">
            <?php echo $_GET['error'] === 'empty' ? 'Reply cannot be empty.' : 'Failed to send reply.'; ?>
        </div>
    <?php endif; ?>

    <!-- complaint details -->
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
            <span>👤 <?php echo htmlspecialchars($data['fullname']); ?></span>
            <span>🏢 <?php echo htmlspecialchars($data['department_name'] ?? 'N/A'); ?></span>
            <span>📅 <?php echo date('M d, Y', strtotime($data['created_at'])); ?></span>
        </div>
        <p class="complaint-body"><?php echo htmlspecialchars($data['description']); ?></p>
    </div>

    <!-- replies thread -->
    <div class="replies-section">
        <h3>Replies</h3>
        <?php if ($replies->num_rows === 0): ?>
            <p class="no-replies">No replies yet. Be the first to respond.</p>
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

    <!-- reply form -->
    <div class="reply-form-card">
        <h3>Send Reply</h3>
        <form action="../controllers/replycontroller.php" method="POST">
            <input type="hidden" name="complaint_id" value="<?php echo $data['id']; ?>">
            <textarea name="response" rows="4" placeholder="Type your response here..." required></textarea>
            <button type="submit" class="update-btn">Send Reply</button>
        </form>
    </div>

    <a href="manage-complaints.php" class="back-btn">← Back to Complaints</a>

</div>
</body>
</html>