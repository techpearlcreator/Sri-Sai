<?php

use App\Helpers\EnvLoader;

return [
    'jwt_secret'      => EnvLoader::get('JWT_SECRET', ''),
    'jwt_expiry'      => (int) EnvLoader::get('JWT_EXPIRY', 3600),
    'jwt_algorithm'   => 'HS256',
    'password_cost'   => 12,
    'max_login_attempts' => 5,
    'lockout_minutes' => 30,
];
