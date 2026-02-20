<?php

namespace App\Models;

use App\Core\Model;

class Tour extends Model
{
    protected static string $table = 'tours';
    protected static array $fillable = [
        'title', 'title_ta', 'slug', 'description', 'description_ta', 'destination', 'destination_ta',
        'featured_image', 'start_date', 'end_date', 'price_per_person', 'max_seats',
        'booked_seats', 'status', 'is_active',
    ];

    public static function findBySlug(string $slug): ?object
    {
        return static::db()->fetch(
            "SELECT * FROM `tours` WHERE `slug` = ? AND `is_active` = 1",
            [$slug]
        );
    }

    public static function upcoming(int $limit = 0): array
    {
        $sql = "SELECT * FROM `tours`
                WHERE `is_active` = 1 AND `status` = 'upcoming' AND `start_date` >= CURDATE()
                ORDER BY `start_date` ASC";
        if ($limit > 0) {
            $sql .= " LIMIT {$limit}";
        }
        return static::db()->fetchAll($sql);
    }

    public static function hasAvailability(int $id): bool
    {
        $tour = static::find($id);
        if (!$tour) return false;
        return ($tour->max_seats - $tour->booked_seats) > 0;
    }
}
