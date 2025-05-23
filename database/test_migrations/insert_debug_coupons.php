<?php

$db->exec("
    INSERT INTO coupons (code, discount_percent, min_value, valid_until)
    VALUES 
        ('DEBUG10', 10.00, 100.00, '2099-12-31'),
        ('DEBUG50', 50.00, 1000.00, '2099-12-31')
");
