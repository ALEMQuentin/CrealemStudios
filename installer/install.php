<?php

declare(strict_types=1);

$basePath = dirname(__DIR__);
$configPath = $basePath . '/config/config.php';
$config = require $configPath;

$databasePath = $config['db']['database'];
$storagePath = $config['paths']['storage'];
$uploadsPath = $config['paths']['uploads'];

$checks = [
    'PHP >= 8.1' => version_compare(PHP_VERSION, '8.1.0', '>='),
    'SQLite disponible' => extension_loaded('pdo_sqlite'),
    'Dossier storage présent' => is_dir($storagePath),
    'Dossier uploads présent' => is_dir($uploadsPath),
];

echo "=== Vérification environnement ===\n";
foreach ($checks as $label => $result) {
    echo ($result ? '[OK] ' : '[KO] ') . $label . "\n";
}

if (in_array(false, $checks, true)) {
    exit("\nInstallation interrompue.\n");
}

if (!file_exists($databasePath)) {
    touch($databasePath);
    echo "\nBase SQLite créée : $databasePath\n";
} else {
    echo "\nBase SQLite déjà présente.\n";
}

echo "\nÉtape suivante : lancer les migrations.\n";
