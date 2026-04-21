# Architecture du CMS

## Structure principale

- `public/`
  - point d’entrée HTTP
  - assets
  - uploads

- `app/`
  - logique PHP
  - vues
  - bootstrap
  - classes coeur

- `database/`
  - base SQLite
  - scripts SQL

- `documentation/`
  - documentation technique et fonctionnelle

## Point d’entrée

- `public/index.php`

## Couches principales

- Routage simple par `module` et `action`
- Vues PHP dans `app/Views/`
- Réglages globaux stockés dans `settings`
- Modules activables via Paramètres
