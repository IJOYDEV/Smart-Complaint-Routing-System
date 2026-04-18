<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../pages/manage-complaints.php");
    exit();
}

$complaint_id = $_POST["complaint_id"] ?? null;
$status       = $_POST["status"]       ?? null;

$allowed = ["Pending", "In Progress", "Resolved"];

if (!$complaint_id || !in_array($status, $allowed)) {
    header("Location: ../pages/manage-complaints.php?error=invalid");
    exit();
}

$stmt = $conn->prepare("UPDATE complaints SET status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $complaint_id);

if ($stmt->execute()) {
    header("Location: ../pages/manage-complaints.php?success=1");
} else {
    header("Location: ../pages/manage-complaints.php?error=1");
}

$stmt->close();
$conn->close();
exit();
?>