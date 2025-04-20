<?php
require_once 'config/db.php';

class User
{
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->conn;
    }

    public function getAllUsers(array $filters = []): array
    {
        $query = "SELECT * FROM users";
        $where = [];
        $params = [];

        // Apply filters if provided
        if (!empty($filters)) {
            if (isset($filters['status'])) {
                $where[] = "status = ?";
                $params[] = $filters['status'];
            }
            // Add more filters as needed
        }

        if (!empty($where)) {
            $query .= " WHERE " . implode(" AND ", $where);
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addUser($name, $email, $phone)
    {
        $stmt = $this->db->prepare("INSERT INTO users (name, email, phone) VALUES (?, ?, ?)");
        return $stmt->execute([$name, $email, $phone]);
    }

    public function updateUser($id, $name, $email, $phone)
    {
        $stmt = $this->db->prepare("UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?");
        return $stmt->execute([$name, $email, $phone, $id]);
    }

    // Check if user has associated transactions
    public function hasUserTransactions($id)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM transactions WHERE id_user = ?");
        $stmt->execute([$id]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function deleteUser($id)
    {
        // First check if there are associated transactions
        if ($this->hasUserTransactions($id)) {
            return false; // Cannot delete due to existing transactions
        }

        // If no associated transactions, proceed with deletion
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function searchUsers($keyword)
    {
        $sql = "SELECT * FROM users 
                WHERE name LIKE :keyword 
                OR email LIKE :keyword 
                OR phone LIKE :keyword";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':keyword' => '%' . $keyword . '%'
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $status)
    {
        $stmt = $this->db->prepare("UPDATE users SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }
}
