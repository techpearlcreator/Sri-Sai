<?php

namespace App\Models;

use App\Core\Model;

class GalleryImage extends Model
{
    protected static string $table = 'gallery_images';
    protected static array $fillable = [
        'album_id', 'file_path', 'thumbnail', 'caption',
        'alt_text', 'sort_order', 'file_size', 'width', 'height',
    ];

    /**
     * Get all images for an album, ordered by sort_order.
     */
    public static function byAlbum(int $albumId): array
    {
        return static::where('album_id', $albumId)
            ->orderBy('sort_order')
            ->get();
    }
}
