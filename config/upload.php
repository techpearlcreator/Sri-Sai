<?php

use App\Helpers\EnvLoader;

return [
    'max_size'      => (int) EnvLoader::get('UPLOAD_MAX_SIZE', 5242880),
    'allowed_types' => explode(',', EnvLoader::get('UPLOAD_ALLOWED_TYPES', 'jpg,jpeg,png,gif,webp,pdf')),
    'thumb_width'   => 300,
    'thumb_height'  => 300,
    'storage_path'  => PUBLIC_PATH . '/storage/uploads',
];
