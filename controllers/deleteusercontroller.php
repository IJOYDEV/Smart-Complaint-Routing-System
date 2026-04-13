<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

require_once "../config/database.php";
require_once "../models/User.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id = $_POST["user_id"];
    $user    = new User($conn);

    if ($user->delete($user_id)) {
        header("Location: ../pages/users.php?success=1");
    } else {
        header("Location: ../pages/users.php?error=failed");
    }
    exit();
}
?>