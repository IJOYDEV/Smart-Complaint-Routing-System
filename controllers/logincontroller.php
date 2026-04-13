<?php
session_start();
require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email    = trim($_POST["email"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $row = $result->fetch_assoc();

        
        $passwordMatch = password_verify($password, $row["password"]) 
                         || $password === $row["password"];

        if ($passwordMatch) {

            $_SESSION["user_id"]  = $row["id"];
            $_SESSION["fullname"] = $row["fullname"];
            $_SESSION["role"]     = $row["role"];

            
            if ($row["role"] === "admin") {
                header("Location: ../pages/admin-dashboard.php");
            } else {
                header("Location: ../pages/citizen-dashboard.php");
            }
            exit();

        } else {
         
            header("Location: ../login.php?error=invalid_password");
            exit();
        }

    } else {
        header("Location: ../login.php?error=user_not_found");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>