<?php
declare(strict_types=1);

return [
    'app_name' => 'CrealemStudios',
    'environment' => 'local',
    'db' => [
        'driver' => 'sqlite',
        'path' => dirname(__DIR__) . '/database/crealemstudios.sqlite',
    ],
];
