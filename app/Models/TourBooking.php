<?php

namespace App\Models;

use App\Core\Model;

class TourBooking extends Model
{
    protected static string $table = 'tour_bookings';
    protected static array $fillable = [
        'tour_id', 'user_id', 'seats', 'total_amount',
        'razorpay_order_id', 'razorpay_payment_id', 'razorpay_signature',
        'payment_status', 'status',
    ];

    public static function byUser(int $userId): array
    {
        return static::db()->fetchAll(
            "SELECT tb.*, t.title as tour_title, t.destination, t.start_date, t.end_date
             FROM `tour_bookings` tb
             LEFT JOIN `tours` t ON tb.tour_id = t.id
             WHERE tb.user_id = ?
             ORDER BY tb.created_at DESC",
            [$userId]
        );
    }
}
