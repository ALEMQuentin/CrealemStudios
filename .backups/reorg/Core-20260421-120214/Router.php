<?php

namespace App\Core;

class Router
{
    public static function isAdminRequest(): bool
    {
        return isset($_GET['module']) || basename($_SERVER['PHP_SELF']) === 'admin.php';
    }

    public static function currentSlug(): ?string
    {
        return $_GET['slug'] ?? null;
    }
}
