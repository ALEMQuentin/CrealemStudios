<?php

declare(strict_types=1);

function active_theme_path(array $settings = []): string
{
    $theme = trim((string)($settings['theme'] ?? 'default'));
    if ($theme === '') {
        $theme = 'default';
    }

    $path = THEMES_PATH . '/' . $theme;

    if (!is_dir($path)) {
        return DEFAULT_THEME_PATH;
    }

    return $path;
}
