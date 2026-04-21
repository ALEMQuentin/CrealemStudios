<?php

declare(strict_types=1);

$basePath = dirname(__DIR__);
$config = require $basePath . '/config/config.php';

$pdo = new PDO('sqlite:' . $config['db']['database']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$migrationFiles = glob($basePath . '/database/migrations/*.sql');
sort($migrationFiles);

foreach ($migrationFiles as $file) {
    echo "Migration : " . basename($file) . "\n";
    $sql = file_get_contents($file);
    $pdo->exec($sql);
}

echo "\nMigrations terminées.\n";
