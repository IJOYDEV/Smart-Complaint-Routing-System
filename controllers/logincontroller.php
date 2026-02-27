<?php
session_start();
require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $role = "citizen";

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND role = ?");
    $stmt->bind_param("ss", $email, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $row = $result->fetch_assoc();

        if (password_verify($password, $row["password"])) {

            $_SESSION["user_id"] = $row["id"];
            $_SESSION["fullname"] = $row["fullname"];
            $_SESSION["role"] = $row["role"];

        
            if ($row["role"] === "admin") {
                header("Location: ../pages/admin-dashboard.php");
            } else {
                header("Location: ../pages/citizen-dashboard.php");
            }
            exit();

        } else {
            echo "Invalid password";
        }

    } else {
        echo "User not found";
    }

    $stmt->close();
}
?>