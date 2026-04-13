<?php
session_start();
require_once "../config/database.php";
require_once "../models/User.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullname = trim($_POST["fullname"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $role = "citizen"; 

    if ($password !== $confirm_password) {
    header("Location: ../register.php?error=password_mismatch");
    exit();
}

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $user = new User($conn);

    if ($user->register($fullname, $email, $hashed_password,
     $role)) {
        header("Location: ../login.php");
        exit();
    } else {
        echo "Registration failed: " . $conn->error;
    }
}