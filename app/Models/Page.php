<?php

namespace App\Models;

use App\Core\Model;

class Page extends Model
{
    protected static string $table = 'pages';
    protected static array $fillable = [
        'created_by', 'title', 'title_ta', 'slug', 'content', 'content_ta', 'featured_image',
        'template', 'status', 'sort_order', 'show_in_menu', 'menu_position', 'parent_id',
    ];

    /**
     * Find published page by slug (public).
     */
    public static function findBySlug(string $slug): ?object
    {
        return static::db()->fetch(
            "SELECT * FROM `pages` WHERE `slug` = ? AND `status` = 'published'",
            [$slug]
        );
    }

    /**
     * Get menu pages ordered by position.
     */
    public static function menuItems(): array
    {
        return static::where('show_in_menu', 1)
            ->andWhere('status', 'published')
            ->orderBy('menu_position')
            ->get();
    }
}
