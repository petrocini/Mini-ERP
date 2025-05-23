<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Config\Database;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$db = Database::connect();

// Rodar migrations padrão
echo "Rodando migrations principais...\n";
$files = glob(__DIR__ . '/migrations/*.php');
foreach ($files as $file) {
    require $file;
    echo basename($file) . " executado.\n";
}

// Rodar test migrations se DEBUG=true
if ($_ENV['DEBUG'] === 'true') {
    echo "DEBUG ativado — rodando migrations de teste...\n";
    $testFiles = glob(__DIR__ . '/test_migrations/*.php');
    foreach ($testFiles as $file) {
        require $file;
        echo basename($file) . " executado.\n";
    }
}
