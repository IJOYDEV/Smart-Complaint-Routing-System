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

    // ✅ Used by logincontroller
    public function findByEmail($email) {
        $stmt = $this->conn->prepare(
            "SELECT * FROM users WHERE email = ? LIMIT 1"
        );
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // ✅ Used to get any user by ID
    public function findById($id) {
        $stmt = $this->conn->prepare(
            "SELECT id, fullname, email, role, created_at 
             FROM users WHERE id = ? LIMIT 1"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // ✅ Used by admin to count all users
    public function countAll() {
        $result = $this->conn->query(
            "SELECT COUNT(*) as total FROM users"
        );
        return $result->fetch_assoc()['total'];
    }

    // ✅ Used by admin to count only citizens
    public function countCitizens() {
        $result = $this->conn->query(
            "SELECT COUNT(*) as total FROM users WHERE role = 'citizen'"
        );
        return $result->fetch_assoc()['total'];
    }

    // ✅ Used by admin to update password
    public function updatePassword($user_id, $newPassword) {
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare(
            "UPDATE users SET password = ? WHERE id = ?"
        );
        $stmt->bind_param("si", $hash, $user_id);
        return $stmt->execute();
    }
}
?>