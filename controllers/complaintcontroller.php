<?php
session_start();
require_once "../config/database.php";
require_once "../models/NLPClassifier.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

if (isset($_POST['submit_complaint'])) {

    $user_id     = $_SESSION["user_id"];
    $title       = trim($_POST['title']);
    $description = trim($_POST['description']);

    // ✅ Combine for NLP
    $complaint_text = $title . " " . $description;

    // ✅ Run NLP classifier
    $classifier = new NLPClassifier();
    $result     = $classifier->classifyComplaint($complaint_text);

    $category      = $result[0]; // e.g. "Roads"
    $department_id = $result[1]; // ✅ must return an ID number e.g. 3

    // ✅ Validate
    if (empty($title) || empty($description)) {
        header("Location: ../submitcomplaint.php?error=missing");
        exit();
    }

 
    $stmt = $conn->prepare(
        "INSERT INTO complaints (user_id, department_id, title, description, status)
         VALUES (?, ?, ?, ?, 'Pending')"
    );

    $stmt->bind_param("iiss", $user_id, $department_id, $title, $description);

    if ($stmt->execute()) {
        header("Location: ../pages/citizen-dashboard.php?success=1");
    } else {
        header("Location: ../submitcomplaint.php?error=failed");
    }

    $stmt->close();
    $conn->close();
    exit();
}
?>