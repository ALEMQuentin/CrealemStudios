<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/app/bootstrap.php';

use App\Core\Database;
use App\Controllers\Admin\Kernel;

$config = appConfig();
$pdo = Database::getInstance($config['db']);

$module = trim((string)($_GET['module'] ?? 'dashboard'));
$action = trim((string)($_GET['action'] ?? 'index'));

if ($module === '') {
    $module = 'dashboard';
}

if ($action === '') {
    $action = 'index';
}

$kernel = new Kernel($pdo, $config, $module, $action);
$kernel->handle();
