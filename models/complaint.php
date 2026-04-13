<?php

class Complaint {

    private $conn;
    private $table = "complaints";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($user_id, $department_id, $title, $description) {
        $stmt = $this->conn->prepare(
            "INSERT INTO " . $this->table . " 
             (user_id, department_id, title, description, status) 
             VALUES (?, ?, ?, ?, 'Pending')"
        );
        $stmt->bind_param("iiss", $user_id, $department_id, $title, $description);
        return $stmt->execute();
    }

    public function getByUser($user_id) {
        $stmt = $this->conn->prepare(
            "SELECT c.id, c.title, c.description, c.status, c.created_at,
                    d.department_name
             FROM complaints c
             LEFT JOIN departments d ON c.department_id = d.id
             WHERE c.user_id = ?
             ORDER BY c.created_at DESC"
        );
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getAll() {
        return $this->conn->query(
            "SELECT c.id, c.title, c.description, c.status, c.created_at,
                    d.department_name, u.fullname
             FROM complaints c
             LEFT JOIN departments d ON c.department_id = d.id
             LEFT JOIN users u ON c.user_id = u.id
             ORDER BY c.created_at DESC"
        );
    }

    public function updateStatus($complaint_id, $status) {
        $stmt = $this->conn->prepare(
            "UPDATE complaints SET status = ? WHERE id = ?"
        );
        $stmt->bind_param("si", $status, $complaint_id);
        return $stmt->execute();
    }

    public function getById($complaint_id) {
        $stmt = $this->conn->prepare(
            "SELECT c.*, d.department_name, u.fullname
             FROM complaints c
             LEFT JOIN departments d ON c.department_id = d.id
             LEFT JOIN users u ON c.user_id = u.id
             WHERE c.id = ?"
        );
        $stmt->bind_param("i", $complaint_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getReplies($complaint_id) {
        $stmt = $this->conn->prepare(
            "SELECT r.*, u.fullname
             FROM responses r
             LEFT JOIN users u ON r.admin_id = u.id
             WHERE r.complaint_id = ?
             ORDER BY r.responded_at ASC"
        );
        $stmt->bind_param("i", $complaint_id);
        $stmt->execute();
        return $stmt->get_result();
    }
}
?>