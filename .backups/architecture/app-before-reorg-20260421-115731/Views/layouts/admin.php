<?php
$currentModule = $module ?? 'dashboard';

$navItems = [
    ['key' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'ion-speedometer'],
    ['key' => 'pages', 'label' => 'Pages', 'icon' => 'ion-document-text'],
    ['key' => 'blog', 'label' => 'Articles', 'icon' => 'ion-compose'],
    ['key' => 'media', 'label' => 'Médias', 'icon' => 'ion-images'],
    ['key' => 'menus', 'label' => 'Menus', 'icon' => 'ion-navicon-round'],
    ['key' => 'users', 'label' => 'Utilisateurs', 'icon' => 'ion-person-stalker'],
    ['key' => 'settings', 'label' => 'Paramètres', 'icon' => 'ion-gear-b'],
];

$optionalItems = [
    ['key' => 'products', 'label' => 'Produits', 'icon' => 'ion-pricetags'],
    ['key' => 'forms', 'label' => 'Formulaires', 'icon' => 'ion-clipboard'],
    ['key' => 'gallery', 'label' => 'Galerie', 'icon' => 'ion-image'],
    ['key' => 'testimonials', 'label' => 'Avis', 'icon' => 'ion-chatbubbles'],
    ['key' => 'clients', 'label' => 'Clients', 'icon' => 'ion-person-stalker'],
    ['key' => 'booking', 'label' => 'Réservations', 'icon' => 'ion-calendar'],
    ['key' => 'subscriptions', 'label' => 'Abonnements', 'icon' => 'ion-card'],
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Administration') ?></title>
    <link rel="stylesheet" href="/vendor/ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body class="cs-admin-body">
    <div class="cs-admin-shell">
        <aside class="cs-admin-sidebar" id="csAdminSidebar">
            <div class="cs-admin-brand">
                <div class="cs-admin-brand-logo">CS</div>
                <div class="cs-admin-brand-text">
                    <strong>CrealemStudios</strong>
                    <span>Administration</span>
                </div>
            </div>

            <nav class="cs-admin-nav">
                <div class="cs-admin-nav-group-title">Contenu</div>

                <?php foreach ($navItems as $item): ?>
                    <a class="cs-admin-nav-link <?= $currentModule === $item['key'] ? 'is-active' : '' ?>" href="/admin.php?module=<?= e($item['key']) ?>">
                        <i class="icon <?= e($item['icon']) ?>"></i>
                        <span><?= e($item['label']) ?></span>
                    </a>
                <?php endforeach; ?>

                <div class="cs-admin-nav-group-title">Modules</div>

                <?php foreach ($optionalItems as $item): ?>
                    <?php $enabled = ($settings['module_' . $item['key']] ?? '0') === '1'; ?>
                    <?php if ($enabled): ?>
                        <a class="cs-admin-nav-link <?= $currentModule === $item['key'] ? 'is-active' : '' ?>" href="/admin.php?module=<?= e($item['key']) ?>">
                            <i class="icon <?= e($item['icon']) ?>"></i>
                            <span><?= e($item['label']) ?></span>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </nav>
        </aside>

        <div class="cs-admin-main">
            <header class="cs-admin-topbar">
                <button class="cs-admin-menu-toggle" type="button" id="csAdminMenuToggle" aria-label="Ouvrir le menu">
                    <i class="icon ion-navicon-round"></i>
                </button>

                <div class="cs-admin-topbar-title">
                    <h1><?= e($pageTitle ?? 'Administration') ?></h1>
                    <p>Gestion du site et des modules</p>
                </div>

                <div class="cs-admin-topbar-actions">
                    <a class="cs-admin-topbar-link" href="/?slug=home" target="_blank" rel="noopener noreferrer">
                        <i class="icon ion-link"></i>
                        <span>Voir le site</span>
                    </a>
                </div>
            </header>

            <main class="cs-admin-content">
                <div class="cs-admin-alert cs-admin-alert-error" style="margin-bottom:12px;">
                    DEBUG → module: <?= e($module ?? '') ?> | action: <?= e($action ?? '') ?> | vue: <?= e($viewPath ?? '') ?>
                </div>
                <?php if (!empty($_GET['success'])): ?>
                    <div class="cs-admin-alert cs-admin-alert-success">
                        <?= e($_GET['success']) ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($_GET['error'])): ?>
                    <div class="cs-admin-alert cs-admin-alert-error">
                        <?= e($_GET['error']) ?>
                    </div>
                <?php endif; ?>

                <?php require $viewPath; ?>
            </main>
        </div>
    </div>

    <script>
    (function () {
        var toggle = document.getElementById('csAdminMenuToggle');
        var sidebar = document.getElementById('csAdminSidebar');

        if (!toggle || !sidebar) return;

        toggle.addEventListener('click', function () {
            sidebar.classList.toggle('is-open');
        });
    })();
    </script>
</body>
</html>
