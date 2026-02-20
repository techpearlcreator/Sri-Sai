<?php

namespace App\Models;

use App\Core\Model;

class ShopEnquiry extends Model
{
    protected static string $table = 'shop_enquiries';
    protected static array $fillable = [
        'product_id', 'name', 'email', 'phone', 'quantity', 'message', 'status',
    ];

    public static function withProduct(int $id): ?object
    {
        return static::db()->fetch(
            "SELECT se.*, p.name as product_name, p.price as product_price
             FROM `shop_enquiries` se
             LEFT JOIN `products` p ON se.product_id = p.id
             WHERE se.id = ?",
            [$id]
        );
    }
}
