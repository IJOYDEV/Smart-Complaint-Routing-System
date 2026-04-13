<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

require_once "config/database.php";

$departments = $conn->query("SELECT id, department_name FROM departments");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Submit Complaint</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="dashboard citizen">

<div class="sidebar">
    <h2>Citizen Panel</h2>
    <ul>
        <li><a href="pages/citizen-dashboard.php">Dashboard</a></li>
        <li><a href="submitcomplaint.php">Submit Complaint</a></li>
        <li><a href="logout.php" class="logout">Logout</a></li>
    </ul>
</div>

<div class="main-content">

    <div class="top-bar">
        <h1>Submit a Complaint</h1>
    </div>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert error">
            <?php
            if ($_GET['error'] === 'failed')  echo "Failed to submit. Try again.";
            if ($_GET['error'] === 'missing') echo "Please fill in all fields.";
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert success">Complaint submitted successfully!</div>
    <?php endif; ?>

    <div class="form-card">
        <form action="controllers/complaintcontroller.php" method="POST">

            <div class="form-group">
                <label>Complaint Title</label>
                <input type="text" name="title" placeholder="Enter complaint title" required>
            </div>

            <div class="form-group">
                <label>Department</label>
                <select name="department_id" required>
                    <option value="">-- Select Department --</option>
                    <?php while($dept = $departments->fetch_assoc()): ?>
                        <option value="<?php echo $dept['id']; ?>">
                            <?php echo htmlspecialchars($dept['department_name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Complaint Description</label>
                <textarea name="description" rows="5" placeholder="Describe your complaint in detail..." required></textarea>
            </div>

            <button type="submit" name="submit_complaint" class="submit-btn">Submit Complaint</button>

        </form>
    </div>

</div>

</body>
</html>