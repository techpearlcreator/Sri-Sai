<?php

namespace App\Models;

use App\Core\Model;

class PoojaBooking extends Model
{
    protected static string $table = 'pooja_bookings';
    protected static array $fillable = [
        'pooja_type_id', 'temple', 'name', 'email', 'phone',
        'preferred_date', 'notes', 'num_persons', 'status',
        'razorpay_order_id', 'razorpay_payment_id', 'razorpay_signature', 'payment_status',
    ];

    public static function withPoojaType(int $id): ?object
    {
        return static::db()->fetch(
            "SELECT pb.*, pt.name as pooja_name, pt.price as pooja_price
             FROM `pooja_bookings` pb
             LEFT JOIN `pooja_types` pt ON pb.pooja_type_id = pt.id
             WHERE pb.id = ?",
            [$id]
        );
    }
}
