<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Config\Database;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$db = Database::connect();

$files = glob(__DIR__ . '/migrations/*.php');

foreach ($files as $file) {
    require $file;
    echo basename($file) . " executado.\n";
}
