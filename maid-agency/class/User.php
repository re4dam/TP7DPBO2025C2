<?php
require_once 'config/db.php';

class User
{
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->conn;
    }

    // Database methods
    public function getAllUsers(): array
    {
        $stmt = $this->db->prepare("SELECT * FROM users");
        $stmt->execute();
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

    public function deleteUser($id)
    {
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
}
