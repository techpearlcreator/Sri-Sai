<?php

namespace App\Models;

use App\Core\Model;

class Media extends Model
{
    protected static string $table = 'media';
    protected static array $fillable = [
        'uploaded_by', 'file_name', 'file_path', 'thumbnail_path',
        'file_type', 'file_size', 'width', 'height', 'alt_text', 'used_in',
    ];
}
