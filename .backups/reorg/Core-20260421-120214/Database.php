<?php

namespace App\Core;

use PDO;

class Database
{
    private static ?PDO $instance = null;

    public static function getInstance(array $config): PDO
    {
        if (self::$instance === null) {
            $databasePath = $config['database'] ?? '';

            self::$instance = new PDO('sqlite:' . $databasePath);
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }

        return self::$instance;
    }
}
