<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use App\Controllers\ProductController;
use App\Helpers\Router;
use Whoops\Run;
use Whoops\Handler\PrettyPageHandler;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$whoops = new Run();
$whoops->pushHandler(new PrettyPageHandler());
$whoops->register();

require_once __DIR__ . '/../routes/web.php';
