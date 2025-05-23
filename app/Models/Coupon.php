<?php

namespace App\Models;

class Coupon {
    public int $id;
    public string $code;
    public float $discount_percent;
    public float $min_value;
    public string $valid_until;
    public string $created_at;

    public function __construct(array $data = []) {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) $this->$key = $value;
        }
    }
}
