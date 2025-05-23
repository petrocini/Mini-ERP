<?php

namespace App\Models;

class Product {
    public int $id;
    public string $name;
    public float $price;
    public string $created_at;

    public function __construct(array $data = []) {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) $this->$key = $value;
        }
    }
}
