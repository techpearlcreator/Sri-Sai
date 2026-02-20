<?php

namespace App\Models;

use App\Core\Model;

class GalleryAlbum extends Model
{
    protected static string $table = 'gallery_albums';
    protected static array $fillable = [
        'category_id', 'created_by', 'title', 'title_ta', 'slug', 'description', 'description_ta',
        'cover_image', 'status', 'sort_order', 'image_count',
    ];

    public static function findFull(int $id): ?object
    {
        return static::db()->fetch(
            "SELECT a.*, c.name as category_name, u.name as author_name
             FROM `gallery_albums` a
             LEFT JOIN `categories` c ON a.category_id = c.id
             LEFT JOIN `admin_users` u ON a.created_by = u.id
             WHERE a.id = ?",
            [$id]
        );
    }

    /**
     * Recalculate image count for an album.
     */
    public static function refreshImageCount(int $albumId): void
    {
        $result = static::db()->fetch(
            "SELECT COUNT(*) as cnt FROM `gallery_images` WHERE `album_id` = ?",
            [$albumId]
        );
        static::update($albumId, ['image_count' => (int) $result->cnt]);
    }
}
