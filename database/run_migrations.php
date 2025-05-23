<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php';

$db = Database::connect();

$files = glob(__DIR__ . '/migrations/*.php');

foreach ($files as $file) {
    require $file;
    echo basename($file) . " executado.\n";
}
