<?php

namespace App\Repositories;

use App\Models\Product;
use Database;

class ProductRepository {
    private \PDO $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function all(): array {
        $stmt = $this->db->query("SELECT * FROM products");
        return $stmt->fetchAll(\PDO::FETCH_CLASS, Product::class);
    }

    public function find(int $id): ?Product {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetchObject(Product::class);
        return $product ?: null;
    }

    public function create(Product $product): int {
        $stmt = $this->db->prepare("INSERT INTO products (name, price) VALUES (?, ?)");
        $stmt->execute([$product->name, $product->price]);
        return (int) $this->db->lastInsertId();
    }

    public function update(Product $product): bool {
        $stmt = $this->db->prepare("UPDATE products SET name = ?, price = ? WHERE id = ?");
        return $stmt->execute([$product->name, $product->price, $product->id]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
