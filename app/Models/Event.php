<?php

namespace App\Models;

use App\Core\Model;

class Event extends Model
{
    protected static string $table = 'events';
    protected static array $fillable = [
        'created_by', 'title', 'slug', 'description', 'featured_image',
        'event_date', 'event_time', 'end_date', 'end_time', 'location',
        'is_recurring', 'recurrence_rule', 'status', 'is_featured',
    ];

    /**
     * Get upcoming events.
     */
    public static function upcoming(int $limit = 5): array
    {
        return static::where('status', 'upcoming')
            ->andWhere('event_date', '>=', date('Y-m-d'))
            ->orderBy('event_date')
            ->limit($limit)
            ->get();
    }

    public static function findBySlug(string $slug): ?object
    {
        return static::findBy('slug', $slug);
    }
}
