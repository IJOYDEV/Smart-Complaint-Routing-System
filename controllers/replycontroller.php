<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $complaint_id = $_POST["complaint_id"];
    $admin_id     = $_SESSION["user_id"];
    $response     = trim($_POST["response"]);

    if (empty($response)) {
        header("Location: ../pages/view-complaint.php?id=$complaint_id&error=empty");
        exit();
    }

    $stmt = $conn->prepare(
        "INSERT INTO responses (complaint_id, admin_id, response)
         VALUES (?, ?, ?)"
    );
    $stmt->bind_param("iis", $complaint_id, $admin_id, $response);

    if ($stmt->execute()) {
        header("Location: ../pages/view-complaint.php?id=$complaint_id&success=1");
    } else {
        header("Location: ../pages/view-complaint.php?id=$complaint_id&error=failed");
    }

    $stmt->close();
    $conn->close();
    exit();
}
?>