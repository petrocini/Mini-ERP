<?php

$db->exec("
    CREATE TABLE IF NOT EXISTS orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        subtotal DECIMAL(10,2),
        shipping DECIMAL(10,2),
        total DECIMAL(10,2),
        status VARCHAR(50) DEFAULT 'pendente',
        customer_name VARCHAR(255),
        customer_email VARCHAR(255),
        customer_address TEXT,
        cep VARCHAR(10),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
");
