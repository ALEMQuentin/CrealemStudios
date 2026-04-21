<?php

return [
    'app' => [
        'name' => 'CrealemStudios',
        'env' => 'local',
        'debug' => true,
        'url' => 'http://localhost:8000',
        'installed' => false,
    ],

    'db' => [
        'driver' => 'sqlite',
        'database' => __DIR__ . '/../database/crealemstudios.sqlite',
    ],

    'paths' => [
        'storage' => __DIR__ . '/../storage',
        'uploads' => __DIR__ . '/../public/uploads',
        'themes' => __DIR__ . '/../themes',
        'modules' => __DIR__ . '/../modules',
    ],
];
