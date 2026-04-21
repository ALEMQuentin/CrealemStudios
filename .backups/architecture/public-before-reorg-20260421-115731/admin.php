<?php

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../app/bootstrap.php';

use App\Core\Database;
use App\Controllers\Admin\Kernel;

$config = appConfig();
$pdo = Database::getInstance($config['db']);

$module = isset($_GET['module']) ? trim((string) $_GET['module']) : 'dashboard';
$action = isset($_GET['action']) ? trim((string) $_GET['action']) : 'index';

if ($module === '') {
    $module = 'dashboard';
}

if ($action === '') {
    $action = 'index';
}

$kernel = new Kernel($pdo, $config, $module, $action);
$kernel->handle();
