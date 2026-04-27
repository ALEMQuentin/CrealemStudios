<?php
declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

final class Database
{
    private static ?PDO $instance = null;

    public static function getInstance(array $config): PDO
    {
        if (self::$instance instanceof PDO) {
            return self::$instance;
        }

        if (($config['driver'] ?? '') !== 'sqlite') {
            throw new \RuntimeException('Seul SQLite est configuré pour le moment.');
        }

        $path = (string)($config['path'] ?? '');

        if ($path === '') {
            throw new \RuntimeException('Chemin SQLite manquant.');
        }

        $dir = dirname($path);

        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        try {
            self::$instance = new PDO('sqlite:' . $path);
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new \RuntimeException('Connexion base de données impossible : ' . $e->getMessage());
        }

        return self::$instance;
    }
}
