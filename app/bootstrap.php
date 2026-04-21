<?php

require_once __DIR__ . '/../includes/core/paths.php';
require_once __DIR__ . '/../includes/core/theme.php';

if (!defined('CREALEM_BOOTSTRAPPED')) {
    define('CREALEM_BOOTSTRAPPED', true);

    spl_autoload_register(function ($class) {
        $prefix = 'App\\';
        $baseDir = __DIR__ . '/';

        if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
            return;
        }

        $relativeClass = substr($class, strlen($prefix));
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

        if (file_exists($file)) {
            require $file;
        }
    });

    if (!function_exists('e')) {
        function e(?string $value): string
        {
            return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
        }
    }

    if (!function_exists('redirectTo')) {
        function redirectTo(string $url): void
        {
            header('Location: ' . $url);
            exit;
        }
    }

    if (!function_exists('appConfig')) {
        function appConfig(): array
        {
            static $config = null;

            if ($config === null) {
                $config = require __DIR__ . '/../config/config.php';
            }

            return $config;
        }
    }

    if (!function_exists('getSettings')) {
        function getSettings(\PDO $pdo): array
        {
            $settings = [];

            try {
                $stmt = $pdo->query("SELECT setting_key, setting_value FROM settings");
                $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                foreach ($rows as $row) {
                    $settings[$row['setting_key']] = $row['setting_value'];
                }
            } catch (\Throwable $e) {
                return [];
            }

            return $settings;
        }
    }

    if (!function_exists('saveSetting')) {
        function saveSetting(\PDO $pdo, string $key, string $value): void
        {
            $now = date('Y-m-d H:i:s');

            $check = $pdo->prepare("SELECT id FROM settings WHERE setting_key = :setting_key LIMIT 1");
            $check->execute(['setting_key' => $key]);
            $existing = $check->fetch(\PDO::FETCH_ASSOC);

            if ($existing) {
                $stmt = $pdo->prepare("UPDATE settings SET setting_value = :setting_value, updated_at = :updated_at WHERE setting_key = :setting_key");
                $stmt->execute([
                    'setting_value' => $value,
                    'updated_at' => $now,
                    'setting_key' => $key,
                ]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value, created_at, updated_at) VALUES (:setting_key, :setting_value, :created_at, :updated_at)");
                $stmt->execute([
                    'setting_key' => $key,
                    'setting_value' => $value,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    if (!function_exists('isModuleEnabled')) {
        function isModuleEnabled(array $settings, string $module): bool
        {
            $fixedModules = ['dashboard', 'pages', 'media', 'menus', 'users', 'settings'];

            if (in_array($module, $fixedModules, true)) {
                return true;
            }

            return ($settings['module_' . $module] ?? '0') === '1';
        }
    }

    if (!function_exists('renderShortcodes')) {
        function renderShortcodes(?string $content): string
        {
            return nl2br(e($content ?? ''));
        }
    }
}
