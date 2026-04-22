<?php
declare(strict_types=1);

/* CREALEM_SECURITY_BOOTSTRAP */
if (session_status() !== PHP_SESSION_ACTIVE) {
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (isset($_SERVER['SERVER_PORT']) && (string)$_SERVER['SERVER_PORT'] === '443');

    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    session_start();
}

if (!headers_sent()) {
    header('X-Frame-Options: SAMEORIGIN');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header("Permissions-Policy: camera=(), microphone=(), geolocation=()");
    header('Cross-Origin-Opener-Policy: same-origin');
}


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
