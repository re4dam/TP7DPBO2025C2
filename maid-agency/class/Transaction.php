<?php
require_once 'config/db.php';

class Transaction
{
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->conn;
    }

    public function getAllTransactions(array $filters = []): array
    {
        $query = "SELECT t.*, m.name AS maid_name, u.name AS user_name 
                 FROM transactions t
                 LEFT JOIN maids m ON t.id_maid = m.id
                 LEFT JOIN users u ON t.id_user = u.id";

        $where = [];
        $params = [];

        // Apply filters if provided
        if (!empty($filters)) {
            if (isset($filters['status'])) {
                $where[] = "t.status = ?";
                $params[] = $filters['status'];
            }
            if (isset($filters['payment_status'])) {
                $where[] = "t.payment_status = ?";
                $params[] = $filters['payment_status'];
            }
            if (isset($filters['date_from'])) {
                $where[] = "t.date >= ?";
                $params[] = $filters['date_from'];
            }
            if (isset($filters['date_to'])) {
                $where[] = "t.date <= ?";
                $params[] = $filters['date_to'];
            }
        }

        if (!empty($where)) {
            $query .= " WHERE " . implode(" AND ", $where);
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTransactionById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM transactions WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserTransactions(int $user_id): array
    {
        $stmt = $this->db->prepare("SELECT t.*, m.name AS maid_name 
                                  FROM transactions t
                                  LEFT JOIN maids m ON t.id_maid = m.id
                                  WHERE t.id_user = ? 
                                  ORDER BY t.date DESC");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMaidTransactions(int $maid_id): array
    {
        $stmt = $this->db->prepare("SELECT t.*, u.name AS user_name 
                                  FROM transactions t
                                  LEFT JOIN users u ON t.id_user = u.id
                                  WHERE t.id_maid = ? 
                                  ORDER BY t.date DESC");
        $stmt->execute([$maid_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus(int $id, string $status): bool
    {
        $stmt = $this->db->prepare("UPDATE transactions SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public function updatePaymentStatus(int $id, string $payment_status): bool
    {
        $stmt = $this->db->prepare("UPDATE transactions SET payment_status = ? WHERE id = ?");
        return $stmt->execute([$payment_status, $id]);
    }

    public function searchTransactions(string $searchTerm = ''): array
    {
        $query = "SELECT t.*, m.name AS maid_name, u.name AS user_name
                FROM transactions t
                LEFT JOIN maids m ON t.id_maid = m.id
                LEFT JOIN users u ON t.id_user = u.id";

        if (!empty($searchTerm)) {
            $query .= " WHERE m.name LIKE :search OR u.name LIKE :search OR t.job_type LIKE :search";
        }

        $query .= " ORDER BY t.date DESC";

        $stmt = $this->db->prepare($query);

        if (!empty($searchTerm)) {
            $stmt->bindValue(':search', '%' . $searchTerm . '%');
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
