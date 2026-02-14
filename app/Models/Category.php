<?php

namespace App\Models;

use App\Core\Model;

class Category extends Model
{
    protected static string $table = 'categories';
    protected static array $fillable = [
        'name', 'slug', 'type', 'description', 'parent_id', 'sort_order', 'is_active',
    ];

    /**
     * Get categories by type.
     */
    public static function byType(string $type): array
    {
        return static::where('type', $type)
            ->andWhere('is_active', 1)
            ->orderBy('sort_order')
            ->get();
    }
}
