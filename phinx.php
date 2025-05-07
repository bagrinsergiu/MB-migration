<?php

return [
    'paths' => [
        'migrations' => 'db/migrations',
        'seeds' => 'db/seeds',
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'production',
        'production' => [
            'adapter' => 'mysql',
            'host' => 'mg_mysql',
            'name' => 'MG_prepare_mapping',
            'user' => 'user',
            'pass' => 'user',
            'port' => 3306,
            'charset' => 'utf8mb4',
        ],
    ],
];
