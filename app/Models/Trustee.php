<?php

namespace App\Models;

use App\Core\Model;

class Trustee extends Model
{
    protected static string $table = 'trustees';
    protected static array $fillable = [
        'name', 'name_ta', 'designation', 'designation_ta', 'trustee_type',
        'bio', 'bio_ta', 'photo', 'phone', 'email', 'qualification',
        'sort_order', 'is_active',
    ];

    /**
     * Get all active trustees grouped by type.
     */
    public static function allActive(): array
    {
        return static::where('is_active', 1)
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Get main trustees only.
     */
    public static function mainTrustees(): array
    {
        return static::where('trustee_type', 'main')
            ->andWhere('is_active', 1)
            ->orderBy('sort_order')
            ->get();
    }
}
