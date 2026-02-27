<?php

class User {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register($fullname, $email, $password, $role) {

        $stmt = $this->conn->prepare(
            "INSERT INTO users (fullname, email, password, role) 
             VALUES (?, ?, ?, ?)"
        );

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param("ssss", $fullname, $email, $password, $role);

        return $stmt->execute();
    }
}