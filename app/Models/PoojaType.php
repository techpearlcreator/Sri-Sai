<?php

namespace App\Models;

use App\Core\Model;

class PoojaType extends Model
{
    protected static string $table = 'pooja_types';
    protected static array $fillable = [
        'temple', 'name', 'name_ta', 'description', 'description_ta',
        'duration', 'price', 'is_active', 'sort_order',
    ];

    public static function activeForTemple(string $temple): array
    {
        return static::db()->fetchAll(
            "SELECT * FROM `pooja_types`
             WHERE `is_active` = 1
               AND (`temple` = ? OR `temple` = 'both')
             ORDER BY `sort_order` ASC",
            [$temple]
        );
    }

    public static function allActive(): array
    {
        return static::where('is_active', 1)
            ->orderBy('sort_order')
            ->get();
    }
}
