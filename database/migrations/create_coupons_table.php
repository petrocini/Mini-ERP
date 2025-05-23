<?php

$db->exec("
    CREATE TABLE IF NOT EXISTS coupons (
        id INT AUTO_INCREMENT PRIMARY KEY,
        code VARCHAR(50) NOT NULL UNIQUE,
        discount_percent DECIMAL(5,2),
        min_value DECIMAL(10,2),
        valid_until DATE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
");