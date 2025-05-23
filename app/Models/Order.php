<?php

namespace App\Models;

class Order {
    public int $id;
    public float $subtotal;
    public float $shipping;
    public float $total;
    public string $status;
    public string $customer_name;
    public string $customer_email;
    public string $customer_address;
    public string $cep;
    public string $created_at;

    public function __construct(array $data = []) {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) $this->$key = $value;
        }
    }
}
