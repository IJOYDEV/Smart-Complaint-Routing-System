<?php

$host     = "localhost";
$user     = "root";
$password = ""; // XAMPP default is empty password
$dbname   = "smart_complaint_db"; // your database name

$conn = mysqli_connect($host, $user, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>