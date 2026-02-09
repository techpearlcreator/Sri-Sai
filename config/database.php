<?php

use App\Helpers\EnvLoader;

return [
    'host'     => EnvLoader::get('DB_HOST', 'localhost'),
    'port'     => EnvLoader::get('DB_PORT', '3306'),
    'database' => EnvLoader::get('DB_DATABASE', 'srisai_db'),
    'username' => EnvLoader::get('DB_USERNAME', 'root'),
    'password' => EnvLoader::get('DB_PASSWORD', ''),
    'charset'  => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
];
