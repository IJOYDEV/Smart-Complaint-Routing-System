<?php
session_start();
require_once "../config/database.php";
require_once "../models/User.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../register.php");
    exit();
}

$fullname         = trim($_POST["fullname"]         ?? '');
$email            = trim($_POST["email"]            ?? '');
$password         = trim($_POST["password"]         ?? '');
$confirm_password = trim($_POST["confirm_password"] ?? '');
$role             = "citizen";

$_SESSION['register_old'] = [
    'fullname' => $fullname,
    'email'    => $email,
    'role'     => $role,
];

if (empty($fullname) || empty($email) || empty($password) || empty($confirm_password)) {
    $_SESSION['register_error'] = 'missing_fields';
    header("Location: ../register.php");
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['register_error'] = 'invalid_email';
    header("Location: ../register.php");
    exit();
}

if (strlen($password) < 8) {
    $_SESSION['register_error'] = 'weak_password';
    header("Location: ../register.php");
    exit();
}

if ($password !== $confirm_password) {
    $_SESSION['register_error'] = 'password_mismatch';
    header("Location: ../register.php");
    exit();
}

$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");

if (!$stmt) {
    $_SESSION['register_error'] = 'register_failed';
    header("Location: ../register.php");
    exit();
}

$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $_SESSION['register_error'] = 'email_taken';
    $stmt->close();
    header("Location: ../register.php");
    exit();
}

$stmt->close();

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$user = new User($conn);

if ($user->register($fullname, $email, $hashed_password, $role)) {
    unset($_SESSION['register_old']);
    $_SESSION['register_success'] = 'registered';
    header("Location: ../login.php");
    exit();
} else {
    $_SESSION['register_error'] = 'register_failed';
    header("Location: ../register.php");
    exit();
}

$conn->close();
?>