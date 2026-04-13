<?php

class User {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register($fullname, $email, $password, $role) {

        $check = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $check->close();
            return false;
        }
        $check->close();

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

    public function getAll() {
        return $this->conn->query(
            "SELECT id, fullname, email, role, created_at 
             FROM users 
             ORDER BY created_at DESC"
        );
    }

    public function delete($user_id) {
        $stmt = $this->conn->prepare(
            "DELETE FROM users WHERE id = ? AND role != 'admin'"
        );
        $stmt->bind_param("i", $user_id);
        return $stmt->execute();
    }
}
?>