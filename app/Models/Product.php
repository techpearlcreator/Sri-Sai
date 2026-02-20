<?php

namespace App\Models;

use App\Core\Model;

class Product extends Model
{
    protected static string $table = 'products';
    protected static array $fillable = [
        'category', 'name', 'name_ta', 'slug', 'description', 'description_ta', 'price',
        'featured_image', 'stock_qty', 'is_active', 'sort_order',
    ];

    public static function findBySlug(string $slug): ?object
    {
        return static::db()->fetch(
            "SELECT * FROM `products` WHERE `slug` = ? AND `is_active` = 1",
            [$slug]
        );
    }

    public static function activeByCategory(?string $category = null): array
    {
        if ($category && $category !== 'all') {
            return static::where('is_active', 1)
                ->andWhere('category', $category)
                ->orderBy('sort_order')
                ->get();
        }
        return static::where('is_active', 1)
            ->orderBy('sort_order')
            ->get();
    }
}
