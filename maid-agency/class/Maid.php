<?php
require_once 'config/db.php';

class Maid
{
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->conn;
    }

    // Database methods
    public function getAllMaids()
    {
        $stmt = $this->db->prepare("SELECT * FROM maids");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMaidById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM maids WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addMaid($name, $specialization, $salary, $availability_status, $phone)
    {
        $stmt = $this->db->prepare("INSERT INTO maids (name, specialization, salary, availability_status, phone) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$name, $specialization, $salary, $availability_status, $phone]);
    }

    public function updateMaid($id, $name, $specialization, $salary, $availability_status, $phone)
    {
        $stmt = $this->db->prepare("UPDATE maids SET name = ?, specialization = ?, salary = ?, availability_status = ?, phone = ? WHERE id = ?");
        return $stmt->execute([$name, $specialization, $salary, $availability_status, $phone, $id]);
    }

    // Check if a maid has associated transactions
    public function hasMaidTransactions($id)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM transactions WHERE id_maid = ?");
        $stmt->execute([$id]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function deleteMaid($id)
    {
        // First check if there are associated transactions
        if ($this->hasMaidTransactions($id)) {
            return false; // Cannot delete due to existing transactions
        }

        // If no associated transactions, proceed with deletion
        $stmt = $this->db->prepare("DELETE FROM maids WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function searchMaids($keyword)
    {
        $sql = "SELECT * FROM maids 
                WHERE name LIKE :keyword 
                OR specialization LIKE :keyword 
                OR availability_status LIKE :keyword";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':keyword' => '%' . $keyword . '%'
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
