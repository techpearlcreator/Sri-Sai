<?php

namespace App\Models;

use App\Core\Model;

class TempleTiming extends Model
{
    protected static string $table = 'temple_timings';
    protected static array $fillable = [
        'title', 'day_type', 'start_time', 'end_time',
        'description', 'location', 'is_active', 'sort_order',
    ];

    /**
     * Get all active timings ordered.
     */
    public static function allActive(): array
    {
        return static::where('is_active', 1)
            ->orderBy('sort_order')
            ->get();
    }
}
