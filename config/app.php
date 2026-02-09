<?php

use App\Helpers\EnvLoader;

return [
    'name'  => EnvLoader::get('APP_NAME', 'Sri Sai Mission'),
    'env'   => EnvLoader::get('APP_ENV', 'production'),
    'debug' => EnvLoader::get('APP_DEBUG', 'false') === 'true',
    'url'   => EnvLoader::get('APP_URL', 'http://localhost/srisai/public'),
];
