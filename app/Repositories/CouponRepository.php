<?php

namespace App\Repositories;

use App\Config\Database;
use App\Models\Coupon;

class CouponRepository
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function findByCode(string $code): ?Coupon
    {
        $stmt = $this->db->prepare("SELECT * FROM coupons WHERE code = ?");
        $stmt->execute([$code]);

        $data = $stmt->fetchObject(Coupon::class);
        return $data ?: null;
    }
}
