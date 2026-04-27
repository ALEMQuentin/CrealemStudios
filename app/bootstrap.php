<?php
declare(strict_types=1);

spl_autoload_register(static function (string $class): void {
    $prefixes = [
        'App\\Core\\' => dirname(__DIR__) . '/includes/core/',
        'App\\Controllers\\Admin\\' => dirname(__DIR__) . '/admin/controllers/',
    ];

    foreach ($prefixes as $prefix => $baseDir) {
        if (str_starts_with($class, $prefix)) {
            $relative = substr($class, strlen($prefix));
            $file = $baseDir . str_replace('\\', '/', $relative) . '.php';

            if (is_file($file)) {
                require_once $file;
            }

            return;
        }
    }
});

function appConfig(): array
{
    $configFile = dirname(__DIR__) . '/config/config.php';

    if (!is_file($configFile)) {
        throw new RuntimeException('Fichier config/config.php introuvable.');
    }

    return require $configFile;
}

function e(mixed $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
