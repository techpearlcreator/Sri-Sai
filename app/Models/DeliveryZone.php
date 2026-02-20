<?php

namespace App\Models;

use App\Core\Model;

class DeliveryZone extends Model
{
    protected static string $table = 'delivery_zones';
    protected static array $fillable = [
        'name', 'min_km', 'max_km', 'charge', 'is_active',
    ];

    /**
     * Return all zones (active + inactive) ordered by min_km — for admin use.
     */
    public static function allForAdmin(): array
    {
        return static::db()->fetchAll(
            "SELECT * FROM `delivery_zones` ORDER BY `min_km` ASC"
        );
    }

    /**
     * Return all active zones ordered by min_km.
     */
    public static function allActive(): array
    {
        return static::where('is_active', 1)
            ->orderBy('min_km', 'ASC')
            ->get();
    }

    /**
     * Find the delivery zone that covers a given distance (km).
     *   Zone matches when:  min_km <= distance < max_km
     *   max_km = 0  means unlimited (catches everything above min_km).
     */
    public static function findByDistance(float $km): ?object
    {
        foreach (static::allActive() as $zone) {
            $min = (float) $zone->min_km;
            $max = (float) $zone->max_km;

            if ($km >= $min && ($max === 0.0 || $km < $max)) {
                return $zone;
            }
        }

        return null;
    }

    /**
     * Haversine formula — straight-line distance between two lat/lng points.
     * Returns distance in kilometres.
     */
    public static function haversineKm(
        float $lat1, float $lng1,
        float $lat2, float $lng2
    ): float {
        $R    = 6371.0;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a    = sin($dLat / 2) ** 2
              + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;

        return $R * 2.0 * atan2(sqrt($a), sqrt(1.0 - $a));
    }
}
