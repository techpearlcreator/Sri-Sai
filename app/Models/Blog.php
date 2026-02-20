<?php

namespace App\Models;

use App\Core\Model;

class Blog extends Model
{
    protected static string $table = 'blogs';
    protected static array $fillable = [
        'category_id', 'created_by', 'title', 'title_ta', 'slug', 'excerpt', 'excerpt_ta',
        'content', 'content_ta', 'featured_image', 'status', 'is_featured', 'published_at',
    ];

    /**
     * Get published blogs for public website.
     */
    public static function published(): static
    {
        return static::where('status', 'published')
            ->orderBy('published_at', 'DESC');
    }

    /**
     * Get blog with author and category data.
     */
    public static function findFull(int $id): ?object
    {
        return static::db()->fetch(
            "SELECT b.*, c.name as category_name, c.slug as category_slug,
                    u.name as author_name
             FROM `blogs` b
             LEFT JOIN `categories` c ON b.category_id = c.id
             LEFT JOIN `admin_users` u ON b.created_by = u.id
             WHERE b.id = ?",
            [$id]
        );
    }

    /**
     * Get blog by slug (public).
     */
    public static function findBySlug(string $slug): ?object
    {
        return static::db()->fetch(
            "SELECT b.*, c.name as category_name, c.slug as category_slug,
                    u.name as author_name
             FROM `blogs` b
             LEFT JOIN `categories` c ON b.category_id = c.id
             LEFT JOIN `admin_users` u ON b.created_by = u.id
             WHERE b.slug = ? AND b.status = 'published'",
            [$slug]
        );
    }
}
