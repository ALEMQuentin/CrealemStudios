# Architecture cible inspirée de WordPress

## Racine
- public/              -> points d’entrée web + assets publics
- admin/               -> back-office (équivalent wp-admin)
- content/             -> thèmes, uploads, modules (équivalent wp-content)
- includes/            -> coeur applicatif (équivalent wp-includes)
- database/            -> base sqlite/mysql + migrations
- storage/             -> cache, logs, tmp

## admin/
- controllers/         -> contrôleurs admin
- views/layouts/       -> layout admin
- views/modules/       -> vues des modules admin
- views/partials/      -> morceaux réutilisables admin

## content/
- themes/default/      -> thème actif
- themes/default/templates/ -> templates front
- themes/default/partials/  -> partials front
- uploads/             -> médias uploadés
- modules/             -> logique métier spécifique par module
- languages/           -> traductions

## includes/
- core/                -> noyau (router, db, theme loader, config)
- controllers/         -> contrôleurs front
- models/              -> modèles
- functions/           -> helpers globaux
- bootstrap/           -> chargement de l’application
- rendering/           -> moteur de rendu / thèmes / builder

## Convention
- admin = interface de gestion
- content = personnalisable / métier / thème
- includes = coeur du CMS

## Mapping ancien -> nouveau
- app/Controllers/Admin/      -> admin/controllers/
- app/Views/layouts/          -> admin/views/layouts/
- app/Views/modules/          -> admin/views/modules/
- app/Core/                   -> includes/core/
- app/Models/                 -> includes/models/
- themes/default/             -> content/themes/default/
- public/uploads/             -> content/uploads/ (ou alias public si besoin)

## Objectif
Reprendre la logique structurelle WordPress :
- coeur séparé
- contenu séparé
- admin séparé
- thème séparé
- modules optionnels séparés
