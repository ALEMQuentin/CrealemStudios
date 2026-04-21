<?php

declare(strict_types=1);

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__, 2));
}

if (!defined('ADMIN_PATH')) {
    define('ADMIN_PATH', BASE_PATH . '/admin');
}

if (!defined('ADMIN_CONTROLLERS_PATH')) {
    define('ADMIN_CONTROLLERS_PATH', ADMIN_PATH . '/controllers');
}

if (!defined('ADMIN_VIEWS_PATH')) {
    define('ADMIN_VIEWS_PATH', ADMIN_PATH . '/views');
}

if (!defined('INCLUDES_PATH')) {
    define('INCLUDES_PATH', BASE_PATH . '/includes');
}

if (!defined('CONTENT_PATH')) {
    define('CONTENT_PATH', BASE_PATH . '/content');
}

if (!defined('THEMES_PATH')) {
    define('THEMES_PATH', CONTENT_PATH . '/themes');
}

if (!defined('DEFAULT_THEME_PATH')) {
    define('DEFAULT_THEME_PATH', THEMES_PATH . '/default');
}

if (!defined('UPLOADS_PATH')) {
    define('UPLOADS_PATH', CONTENT_PATH . '/uploads');
}

if (!defined('PUBLIC_PATH')) {
    define('PUBLIC_PATH', BASE_PATH . '/public');
}
