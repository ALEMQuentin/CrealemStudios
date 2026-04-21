<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../app/bootstrap.php';

use App\Core\Database;
use App\Core\Router;
use App\Core\ThemeRenderer;
use App\Core\ThemeResolver;
use App\Models\Content;

$config = appConfig();
$pdo = Database::getInstance($config['db']);
$settings = getSettings($pdo);

if (Router::isAdminRequest()) {
    require __DIR__ . '/admin.php';
    exit;
}

$themePath = ThemeResolver::getActiveThemePath($settings);
$menuItems = ThemeRenderer::getMenuItems($pdo, 'main');

if (isset($_GET['post'])) {
    $slug = trim($_GET['post']);
    $content = Content::findByTypeAndSlug($pdo, 'post', $slug);

    if (!$content) {
        http_response_code(404);
        $pageTitle = 'Article introuvable';
        $templatePath = ThemeResolver::resolveTemplate($themePath, ['is_404' => true]);
        require $templatePath;
        exit;
    }

    $meta = Content::meta($pdo, (int)$content['id']);
    $blocks = ThemeRenderer::getBlocks($pdo, (int)$content['id']);
    $pageTitle = $meta['meta_title'] ?? $content['title'] ?? 'Article';

    $templatePath = ThemeResolver::resolveTemplate($themePath, [
        'type' => 'post',
        'slug' => $content['slug'] ?? null,
    ]);

    require $templatePath;
    exit;
}

if (isset($_GET['blog'])) {
    $posts = Content::allByType($pdo, 'post');
    $pageTitle = 'Blog';

    $templatePath = ThemeResolver::resolveTemplate($themePath, [
        'type' => 'post',
        'is_archive' => true,
    ]);

    require $templatePath;
    exit;
}

$slug = Router::currentSlug() ?? 'home';
$content = Content::findByTypeAndSlug($pdo, 'page', $slug);

if (!$content) {
    http_response_code(404);
    $pageTitle = 'Page introuvable';
    $templatePath = ThemeResolver::resolveTemplate($themePath, ['is_404' => true]);
    require $templatePath;
    exit;
}

$meta = Content::meta($pdo, (int)$content['id']);
$blocks = ThemeRenderer::getBlocks($pdo, (int)$content['id']);
$pageTitle = $meta['meta_title'] ?? $content['title'] ?? 'Page';

$templatePath = ThemeResolver::resolveTemplate($themePath, [
    'type' => 'page',
    'slug' => $content['slug'] ?? null,
    'is_front_page' => ($slug === 'home'),
]);

require $templatePath;
