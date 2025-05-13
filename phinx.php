<?php
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

Dotenv::createMutable(__DIR__)->safeLoad();

$prodEnv = '.env.prod.local';
if (file_exists(__DIR__ . '/' . $prodEnv)) {
    Dotenv::createMutable(__DIR__, [$prodEnv])->safeLoad();
}

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
            'host' => $_ENV['MG_DB_HOST'],
            'name' => $_ENV['MG_DB_NAME'],
            'user' => $_ENV['MG_DB_USER'],
            'pass' => $_ENV['MG_DB_PASS'],
            'port' => $_ENV['MG_DB_PORT'],
            'charset' => 'utf8mb4',
        ],
    ],
];
