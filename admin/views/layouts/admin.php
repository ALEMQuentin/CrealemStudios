<?php
$currentModule = $module ?? 'dashboard';
$currentAction = $action ?? 'index';
$currentSearch = $_GET['q'] ?? '';

$adminDisplayName = 'Administrateur';
if (!empty($_SESSION['user_name'])) {
    $adminDisplayName = (string) $_SESSION['user_name'];
} elseif (!empty($_SESSION['user']['name'])) {
    $adminDisplayName = (string) $_SESSION['user']['name'];
} elseif (!empty($_SESSION['admin_name'])) {
    $adminDisplayName = (string) $_SESSION['admin_name'];
}

$accordionSiteOpen = in_array($currentModule, ['pages', 'blog', 'media', 'menus'], true);
$accordionCatalogOpen = in_array($currentModule, ['products'], true);
$accordionFormsOpen = in_array($currentModule, ['forms'], true);
$accordionGalleryOpen = in_array($currentModule, ['gallery'], true);
$accordionRelationsOpen = in_array($currentModule, ['clients', 'testimonials', 'booking', 'subscriptions'], true);
$accordionSystemOpen = in_array($currentModule, ['users', 'settings'], true);
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
                <a class="cs-admin-nav-link <?= $currentModule === 'dashboard' ? 'is-active' : '' ?>" href="/admin.php?module=dashboard">
                    <i class="icon ion-speedometer"></i>
                    <span>Dashboard</span>
                </a>

                <button class="cs-admin-accordion-trigger <?= $accordionSiteOpen ? 'is-open' : '' ?>" type="button" data-accordion-target="accordionSite">
                    <span class="cs-admin-accordion-left">
                        <i class="icon ion-folder"></i>
                        <span>Site</span>
                    </span>
                    <i class="icon ion-chevron-down"></i>
                </button>

                <div id="accordionSite" class="cs-admin-accordion-panel <?= $accordionSiteOpen ? 'is-open' : '' ?>">
                    <a class="cs-admin-subnav-link <?= ($currentModule === 'pages' && $currentAction === 'index') ? 'is-active' : '' ?>" href="/admin.php?module=pages">
                        Toutes les pages
                    </a>
                    <a class="cs-admin-subnav-link <?= ($currentModule === 'pages' && $currentAction === 'create') ? 'is-active' : '' ?>" href="/admin.php?module=pages&action=create">
                        Ajouter une page
                    </a>
                    <a class="cs-admin-subnav-link <?= ($currentModule === 'blog' && $currentAction === 'index') ? 'is-active' : '' ?>" href="/admin.php?module=blog">
                        Tous les articles
                    </a>
                    <a class="cs-admin-subnav-link <?= ($currentModule === 'blog' && $currentAction === 'create') ? 'is-active' : '' ?>" href="/admin.php?module=blog&action=create">
                        Ajouter un article
                    </a>
                    <a class="cs-admin-subnav-link <?= ($currentModule === 'media') ? 'is-active' : '' ?>" href="/admin.php?module=media">
                        Médias
                    </a>
                    <a class="cs-admin-subnav-link <?= ($currentModule === 'menus') ? 'is-active' : '' ?>" href="/admin.php?module=menus">
                        Menus
                    </a>
                </div>

                <?php if (($settings['module_products'] ?? '0') === '1'): ?>
                    <button class="cs-admin-accordion-trigger <?= $accordionCatalogOpen ? 'is-open' : '' ?>" type="button" data-accordion-target="accordionCatalog">
                        <span class="cs-admin-accordion-left">
                            <i class="icon ion-pricetags"></i>
                            <span>Catalogue</span>
                        </span>
                        <i class="icon ion-chevron-down"></i>
                    </button>

                    <div id="accordionCatalog" class="cs-admin-accordion-panel <?= $accordionCatalogOpen ? 'is-open' : '' ?>">
                    <a class="cs-admin-subnav-link <?= ($currentModule === 'products' && $currentAction === 'index') ? 'is-active' : '' ?>" href="/admin.php?module=products">
                        Tous les produits
                    </a>
                    <a class="cs-admin-subnav-link <?= ($currentModule === 'products' && $currentAction === 'create') ? 'is-active' : '' ?>" href="/admin.php?module=products&action=create">
                        Ajouter un produit
                    </a>
                    <a class="cs-admin-subnav-link <?= ($currentModule === 'products' && in_array($currentAction, ['categories','create_category','edit_category'], true)) ? 'is-active' : '' ?>" href="/admin.php?module=products&action=categories">
                        Catégories
                    </a>
                    <a class="cs-admin-subnav-link <?= ($currentModule === 'products' && in_array($currentAction, ['attributes','create_attribute','edit_attribute','attribute_terms','create_attribute_term','edit_attribute_term'], true)) ? 'is-active' : '' ?>" href="/admin.php?module=products&action=attributes">
                        Attributs
                    </a>
                    <a class="cs-admin-subnav-link <?= ($currentModule === 'products' && $currentAction === 'settings') ? 'is-active' : '' ?>" href="/admin.php?module=products&action=settings">
                        Paramètres
                    </a>
                </div>
                <?php endif; ?>

                <?php if (($settings['module_forms'] ?? '0') === '1'): ?>
                    <button class="cs-admin-accordion-trigger <?= $accordionFormsOpen ? 'is-open' : '' ?>" type="button" data-accordion-target="accordionForms">
                        <span class="cs-admin-accordion-left">
                            <i class="icon ion-clipboard"></i>
                            <span>Formulaires</span>
                        </span>
                        <i class="icon ion-chevron-down"></i>
                    </button>

                    <div id="accordionForms" class="cs-admin-accordion-panel <?= $accordionFormsOpen ? 'is-open' : '' ?>">
                        <a class="cs-admin-subnav-link <?= ($currentModule === 'forms' && $currentAction === 'index') ? 'is-active' : '' ?>" href="/admin.php?module=forms">
                            Tous les formulaires
                        </a>
                        <a class="cs-admin-subnav-link <?= ($currentModule === 'forms' && $currentAction === 'create') ? 'is-active' : '' ?>" href="/admin.php?module=forms&action=create">
                            Ajouter un formulaire
                        </a>
                    </div>
                <?php endif; ?>

                <?php if (($settings['module_gallery'] ?? '0') === '1'): ?>
                    <button class="cs-admin-accordion-trigger <?= $accordionGalleryOpen ? 'is-open' : '' ?>" type="button" data-accordion-target="accordionGallery">
                        <span class="cs-admin-accordion-left">
                            <i class="icon ion-image"></i>
                            <span>Galerie</span>
                        </span>
                        <i class="icon ion-chevron-down"></i>
                    </button>

                    <div id="accordionGallery" class="cs-admin-accordion-panel <?= $accordionGalleryOpen ? 'is-open' : '' ?>">
                        <a class="cs-admin-subnav-link <?= ($currentModule === 'gallery' && $currentAction === 'index') ? 'is-active' : '' ?>" href="/admin.php?module=gallery">
                            Tous les éléments
                        </a>
                        <a class="cs-admin-subnav-link <?= ($currentModule === 'gallery' && $currentAction === 'create') ? 'is-active' : '' ?>" href="/admin.php?module=gallery&action=create">
                            Ajouter un élément
                        </a>
                    </div>
                <?php endif; ?>

                <?php if (
                    ($settings['module_clients'] ?? '0') === '1' ||
                    ($settings['module_testimonials'] ?? '0') === '1' ||
                    ($settings['module_booking'] ?? '0') === '1' ||
                    ($settings['module_subscriptions'] ?? '0') === '1'
                ): ?>
                    <button class="cs-admin-accordion-trigger <?= $accordionRelationsOpen ? 'is-open' : '' ?>" type="button" data-accordion-target="accordionRelations">
                        <span class="cs-admin-accordion-left">
                            <i class="icon ion-person-stalker"></i>
                            <span>Relation client</span>
                        </span>
                        <i class="icon ion-chevron-down"></i>
                    </button>

                    <div id="accordionRelations" class="cs-admin-accordion-panel <?= $accordionRelationsOpen ? 'is-open' : '' ?>">
                        <?php if (($settings['module_clients'] ?? '0') === '1'): ?>
                            <a class="cs-admin-subnav-link <?= ($currentModule === 'clients' && $currentAction === 'index') ? 'is-active' : '' ?>" href="/admin.php?module=clients">
                                Tous les clients
                            </a>
                            <a class="cs-admin-subnav-link <?= ($currentModule === 'clients' && $currentAction === 'create') ? 'is-active' : '' ?>" href="/admin.php?module=clients&action=create">
                                Ajouter un client
                            </a>
                        <?php endif; ?>

                        <?php if (($settings['module_testimonials'] ?? '0') === '1'): ?>
                            <a class="cs-admin-subnav-link <?= ($currentModule === 'testimonials' && $currentAction === 'index') ? 'is-active' : '' ?>" href="/admin.php?module=testimonials">
                                Tous les avis
                            </a>
                            <a class="cs-admin-subnav-link <?= ($currentModule === 'testimonials' && $currentAction === 'create') ? 'is-active' : '' ?>" href="/admin.php?module=testimonials&action=create">
                                Ajouter un avis
                            </a>
                        <?php endif; ?>

                        <?php if (($settings['module_booking'] ?? '0') === '1'): ?>
                            <a class="cs-admin-subnav-link <?= ($currentModule === 'booking' && $currentAction === 'index') ? 'is-active' : '' ?>" href="/admin.php?module=booking">
                                Toutes les réservations
                            </a>
                            <a class="cs-admin-subnav-link <?= ($currentModule === 'booking' && $currentAction === 'create') ? 'is-active' : '' ?>" href="/admin.php?module=booking&action=create">
                                Ajouter une réservation
                            </a>
                        <?php endif; ?>

                        <?php if (($settings['module_subscriptions'] ?? '0') === '1'): ?>
                            <a class="cs-admin-subnav-link <?= ($currentModule === 'subscriptions' && $currentAction === 'index') ? 'is-active' : '' ?>" href="/admin.php?module=subscriptions">
                                Tous les abonnements
                            </a>
                            <a class="cs-admin-subnav-link <?= ($currentModule === 'subscriptions' && $currentAction === 'create') ? 'is-active' : '' ?>" href="/admin.php?module=subscriptions&action=create">
                                Ajouter un abonnement
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <button class="cs-admin-accordion-trigger <?= $accordionSystemOpen ? 'is-open' : '' ?>" type="button" data-accordion-target="accordionSystem">
                    <span class="cs-admin-accordion-left">
                        <i class="icon ion-gear-b"></i>
                        <span>Système</span>
                    </span>
                    <i class="icon ion-chevron-down"></i>
                </button>

                <div id="accordionSystem" class="cs-admin-accordion-panel <?= $accordionSystemOpen ? 'is-open' : '' ?>">
                    <a class="cs-admin-subnav-link <?= ($currentModule === 'users' && $currentAction === 'index') ? 'is-active' : '' ?>" href="/admin.php?module=users">
                        Utilisateurs
                    </a>
                    <a class="cs-admin-subnav-link <?= ($currentModule === 'users' && $currentAction === 'create') ? 'is-active' : '' ?>" href="/admin.php?module=users&action=create">
                        Ajouter un utilisateur
                    </a>
                    <a class="cs-admin-subnav-link <?= ($currentModule === 'settings') ? 'is-active' : '' ?>" href="/admin.php?module=settings">
                        Paramètres
                    </a>
                </div>
            </nav>
        </aside>

        <div class="cs-admin-main">
            <header class="cs-admin-topbar">
                <div class="cs-admin-topbar-left">
                    <button class="cs-admin-menu-toggle" type="button" id="csAdminMenuToggle" aria-label="Ouvrir le menu">
                        <i class="icon ion-navicon-round"></i>
                    </button>

                    <div class="cs-admin-topbar-title">
                        <h1><?= e($pageTitle ?? 'Administration') ?></h1>
                        <p>Gestion du site et des modules</p>
                    </div>
                </div>

                <div class="cs-admin-topbar-center">
                    <form method="get" action="/admin.php" class="cs-admin-search-form">
                        <input type="hidden" name="module" value="<?= e($currentModule) ?>">
                        <?php if ($currentAction !== 'index'): ?>
                            <input type="hidden" name="action" value="<?= e($currentAction) ?>">
                        <?php endif; ?>
                        <i class="icon ion-search cs-admin-search-icon"></i>
                        <input type="text" name="q" class="cs-admin-search-input" value="<?= e((string)$currentSearch) ?>" placeholder="Rechercher...">
                    </form>
                </div>

                <div class="cs-admin-topbar-right">
                    <div class="cs-admin-userbox">
                        <div class="cs-admin-userbox-avatar">
                            <i class="icon ion-person"></i>
                        </div>
                        <div class="cs-admin-userbox-text">
                            <strong><?= e($adminDisplayName) ?></strong>
                            <span>Compte connecté</span>
                        </div>
                    </div>

                    <a class="cs-admin-topbar-link cs-admin-topbar-link-ghost" href="/?slug=home" target="_blank" rel="noopener noreferrer">
                        <i class="icon ion-link"></i>
                        <span>Voir le site</span>
                    </a>

                    <a class="cs-admin-topbar-link cs-admin-topbar-link-danger" href="/logout.php">
                        <i class="icon ion-log-out"></i>
                        <span>Déconnexion</span>
                    </a>
                </div>
            </header>

            <main class="cs-admin-content">
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

        if (toggle && sidebar) {
            toggle.addEventListener('click', function () {
                sidebar.classList.toggle('is-open');
            });
        }

        document.querySelectorAll('[data-accordion-target]').forEach(function (button) {
            button.addEventListener('click', function () {
                var targetId = button.getAttribute('data-accordion-target');
                var panel = document.getElementById(targetId);
                if (!panel) return;

                var open = panel.classList.contains('is-open');
                panel.classList.toggle('is-open', !open);
                button.classList.toggle('is-open', !open);
            });
        });
    })();
    </script>
    <script src="/assets/js/admin-media-modal.js"></script>
    <script src="/assets/js/admin-wysiwyg.js"></script>

<div id="cs-media-modal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,.55); z-index:9999; align-items:center; justify-content:center; padding:24px;">
    <div style="width:min(1100px, 96vw); max-height:90vh; overflow:hidden; background:#fff; border-radius:14px; box-shadow:0 20px 60px rgba(0,0,0,.18); display:flex; flex-direction:column;">
        <div style="display:flex; align-items:center; justify-content:space-between; padding:16px 18px; border-bottom:1px solid #e5e7eb;">
            <strong id="cs-media-modal-title">Bibliothèque média</strong>
            <button type="button" id="cs-media-modal-close" class="btn btn-outline-secondary btn-sm">Fermer</button>
        </div>
        <div style="padding:16px 18px 0 18px;">
            <input type="text" id="cs-media-search-input" class="form-control" placeholder="Rechercher un média par nom ou ID">
        </div>
        <div style="padding:16px 18px; overflow:auto;">
            <div id="cs-media-modal-grid" style="display:grid; grid-template-columns:repeat(auto-fill,minmax(180px,1fr)); gap:14px;"></div>
        </div>
    </div>
</div>

</body>
</html>
