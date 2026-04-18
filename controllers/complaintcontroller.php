<?php
session_start();
require_once "../config/database.php";
require_once "../models/NLPClassifier.php";

// ✅ Must be logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

// ✅ Must be a POST request
if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST["submit_complaint"])) {
    header("Location: ../submitcomplaint.php");
    exit();
}

$user_id     = $_SESSION["user_id"];
$title       = trim($_POST['title']       ?? "");
$description = trim($_POST['description'] ?? "");

// ✅ Validate fields first
if (empty($title) || empty($description)) {
    header("Location: ../submitcomplaint.php?error=missing");
    exit();
}

// ✅ Combine title + description for better NLP accuracy
$complaint_text = $title . " " . $description;

// ✅ Run NLP classifier
$classifier = new NLPClassifier();
[$category, $department_id] = $classifier->classifyComplaint($complaint_text);

// ✅ Insert complaint into database
$stmt = $conn->prepare(
    "INSERT INTO complaints (user_id, department_id, title, description, status)
     VALUES (?, ?, ?, ?, 'Pending')"
);

if (!$stmt) {
    header("Location: ../submitcomplaint.php?error=failed");
    exit();
}

$stmt->bind_param("iiss", $user_id, $department_id, $title, $description);

if ($stmt->execute()) {
    $complaint_id = $conn->insert_id; // get the new complaint ID

    // ✅ Log the NLP classification result
    $log = $conn->prepare(
        "INSERT INTO nlp_logs (complaint_id, classified_as, department_id)
         VALUES (?, ?, ?)"
    );

    if ($log) {
        $log->bind_param("isi", $complaint_id, $category, $department_id);
        $log->execute();
        $log->close();
    }

    $stmt->close();
    $conn->close();
    header("Location: ../pages/citizen-dashboard.php?success=1");
    exit();

} else {
    $stmt->close();
    $conn->close();
    header("Location: ../submitcomplaint.php?error=failed");
    exit();
}
?>