<?php

namespace App\Models;

class Stock {
    public int $id;
    public int $product_id;
    public string $variation;
    public int $quantity;

    public function __construct(array $data = []) {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) $this->$key = $value;
        }
    }
}
