<?php
session_start();
require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../login.php");
    exit();
}

$email    = trim($_POST["email"]   ?? '');
$password = trim($_POST["password"] ?? '');


if (empty($email) || empty($password)) {
    $_SESSION['login_error'] = 'missing_fields';
    header("Location: ../login.php");
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['login_error']     = 'invalid_email';
    $_SESSION['login_old_email'] = $email;
    header("Location: ../login.php");
    exit();
}


$stmt = $conn->prepare("SELECT id, fullname, role, password FROM users WHERE email = ?");

if (!$stmt) {
    $_SESSION['login_error'] = 'login_failed';
    header("Location: ../login.php");
    exit();
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['login_error']     = 'user_not_found';
    $_SESSION['login_old_email'] = $email;
    $stmt->close();
    $conn->close();
    header("Location: ../login.php");
    exit();
}

$user = $result->fetch_assoc();
$stmt->close();
$conn->close();


if (!password_verify($password, $user["password"])) {
    $_SESSION['login_error']     = 'invalid_password';
    $_SESSION['login_old_email'] = $email;
    header("Location: ../login.php");
    exit();
}


if (password_needs_rehash($user["password"], PASSWORD_DEFAULT)) {
    $newHash = password_hash($password, PASSWORD_DEFAULT);
    $rehash  = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $rehash->bind_param("si", $newHash, $user["id"]);
    $rehash->execute();
    $rehash->close();
}


session_regenerate_id(true);


$_SESSION["user_id"]  = $user["id"];
$_SESSION["fullname"] = $user["fullname"];
$_SESSION["role"]     = $user["role"];

unset($_SESSION['login_error'], $_SESSION['login_old_email']);

$redirects = [
    'admin'   => '../pages/admin-dashboard.php',
    'citizen' => '../pages/citizen-dashboard.php',
];

$destination = $redirects[$user["role"]] ?? '../login.php';
header("Location: " . $destination);
exit();
?>