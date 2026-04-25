<?php
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
    </div>

    <button class="cs-admin-accordion-trigger <?= $accordionSystemOpen ? 'is-open' : '' ?>" type="button" data-accordion-target="accordionSystem">
        <span class="cs-admin-accordion-left">
            <i class="icon ion-gear-b"></i>
            <span>Système</span>
        </span>
        <i class="icon ion-chevron-down"></i>
    </button>

    <div id="accordionSystem" class="cs-admin-accordion-panel <?= $accordionSystemOpen ? 'is-open' : '' ?>">

        <a class="cs-admin-subnav-link <?= ($currentModule === 'users' && $currentAction === 'index') ? 'is-active' : '' ?>"
           href="/admin.php?module=users">
            Utilisateurs
        </a>

        <a class="cs-admin-subnav-link <?= ($currentModule === 'users' && $currentAction === 'create') ? 'is-active' : '' ?>"
           href="/admin.php?module=users&action=create">
            Ajouter un utilisateur
        </a>

        <a class="cs-admin-subnav-link <?= ($currentModule === 'settings') ? 'is-active' : '' ?>"
           href="/admin.php?module=settings">
            Paramètres
        </a>

        <a class="cs-admin-subnav-link <?= ($currentModule === 'settings' && $currentAction === 'company') ? 'is-active' : '' ?>"
           href="/admin.php?module=settings&action=company">
            Paramètres entreprise
        </a>

    </div>
</aside>
