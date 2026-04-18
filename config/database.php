<?php
if ($_SERVER['HTTP_HOST'] === 'localhost') {
    $host     = "localhost";
    $user     = "root";
    $password = "";
    $dbname   = "smart_complaint_db";
} else {
    $host     = "sql212.infinityfree.com";
    $user     = "if0_41694283";
    $password = "Immaculate03";
    $dbname   = "if0_41694283_smart_complaint_db";
}

$conn = mysqli_connect($host, $user, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
