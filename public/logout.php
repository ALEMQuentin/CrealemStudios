<?php

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_SESSION = [];

if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'] ?? '/',
        $params['domain'] ?? '',
        (bool)($params['secure'] ?? false),
        (bool)($params['httponly'] ?? true)
    );
}

session_destroy();

header('Location: /admin.php?module=dashboard&success=Déconnecté');
exit;
