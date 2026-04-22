<?php

class Csrf
{
    public static function generateToken(): string
    {
        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf_token'];
    }

    public static function input(): string
    {
        $token = self::generateToken();
        return '<input type="hidden" name="_csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }

    public static function validate(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        if (
            empty($_POST['_csrf_token']) ||
            empty($_SESSION['_csrf_token']) ||
            !hash_equals($_SESSION['_csrf_token'], $_POST['_csrf_token'])
        ) {
            http_response_code(403);
            exit('CSRF validation failed');
        }
    }
}
