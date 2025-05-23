<?php

namespace App\Repositories;

use App\Config\Database;
use App\Models\Stock;

class StockRepository {
    private \PDO $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function findByProduct(int $productId): array {
        $stmt = $this->db->prepare("SELECT * FROM stock WHERE product_id = ?");
        $stmt->execute([$productId]);
        return $stmt->fetchAll(\PDO::FETCH_CLASS, Stock::class);
    }

    public function create(Stock $stock): int {
        $stmt = $this->db->prepare("INSERT INTO stock (product_id, variation, quantity) VALUES (?, ?, ?)");
        $stmt->execute([$stock->product_id, $stock->variation, $stock->quantity]);
        return (int) $this->db->lastInsertId();
    }

    public function update(Stock $stock): bool {
        $stmt = $this->db->prepare("UPDATE stock SET variation = ?, quantity = ? WHERE id = ?");
        return $stmt->execute([$stock->variation, $stock->quantity, $stock->id]);
    }

    public function deleteByProduct(int $productId): bool {
        $stmt = $this->db->prepare("DELETE FROM stock WHERE product_id = ?");
        return $stmt->execute([$productId]);
    }
}
