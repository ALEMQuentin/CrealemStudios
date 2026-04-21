<?php

namespace App\Core;

class ThemeResolver
{
    public static function getActiveThemePath(array $settings): string
    {
        $theme = trim((string)($settings['theme'] ?? ''));

        if ($theme === '') {
            $theme = 'default';
        }

        return dirname(__DIR__, 2) . '/themes/' . $theme;
    }

    public static function resolveTemplate(string $themePath, array $context): string
    {
        $type = $context['type'] ?? 'page';
        $slug = $context['slug'] ?? null;
        $isFrontPage = $context['is_front_page'] ?? false;
        $isArchive = $context['is_archive'] ?? false;
        $is404 = $context['is_404'] ?? false;

        $candidates = [];

        if ($is404) {
            $candidates[] = 'templates/404.php';
        } elseif ($isArchive && $type === 'post') {
            $candidates[] = 'templates/archive-post.php';
        } elseif ($isFrontPage) {
            $candidates[] = 'templates/front-page.php';
        } elseif ($type === 'post') {
            if ($slug) {
                $candidates[] = 'templates/single-post-' . $slug . '.php';
            }
            $candidates[] = 'templates/single-post.php';
        } elseif ($type === 'page') {
            if ($slug) {
                $candidates[] = 'templates/page-' . $slug . '.php';
            }
            $candidates[] = 'templates/page.php';
        }

        $candidates[] = 'templates/page.php';
        $candidates[] = 'templates/404.php';

        foreach ($candidates as $relativePath) {
            $fullPath = $themePath . '/' . $relativePath;

            if (file_exists($fullPath)) {
                return $fullPath;
            }
        }

        throw new \RuntimeException('Aucun template trouvé.');
    }
}
