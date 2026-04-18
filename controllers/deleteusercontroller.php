<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

require_once "../config/database.php";
require_once "../models/User.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../pages/users.php");
    exit();
}

$user_id = $_POST["user_id"] ?? null;

if (!$user_id) {
    header("Location: ../pages/users.php?error=failed");
    exit();
}

$user = new User($conn);

if ($user->delete($user_id)) {
    header("Location: ../pages/users.php?success=1");
} else {
    header("Location: ../pages/users.php?error=failed");
}

$conn->close();
exit();
?>