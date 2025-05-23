<?php

namespace App\Repositories;

use App\Config\Database;
use App\Models\Order;

class OrderRepository
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function create(Order $order, array $items): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO orders (subtotal, shipping, total, status, customer_name, customer_email, customer_address, cep)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $order->subtotal,
            $order->shipping,
            $order->total,
            $order->status,
            $order->customer_name,
            $order->customer_email,
            $order->customer_address,
            $order->cep
        ]);

        $orderId = $this->db->lastInsertId();

        foreach ($items as $item) {
            $insert = $this->db->prepare("
                INSERT INTO order_items (order_id, product_id, variation, quantity, price)
                VALUES (?, ?, ?, ?, ?)
            ");
            $insert->execute([
                $orderId,
                $item['product_id'],
                $item['variation'],
                $item['quantity'],
                $item['price']
            ]);
        }

        return (int) $orderId;
    }

    public function updateStatus(int $id, string $status): bool
    {
        $stmt = $this->db->prepare("UPDATE orders SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM orders WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
