<?php

namespace App\Models;

use App\Core\Model;

class ShopOrder extends Model
{
    protected static string $table = 'shop_orders';
    protected static array $fillable = [
        'product_id', 'customer_name', 'customer_phone', 'customer_email',
        'delivery_address', 'pincode', 'lat', 'lng', 'distance_km',
        'zone_id', 'quantity',
        'product_price', 'delivery_charge', 'total_amount',
        'payment_status', 'razorpay_order_id', 'razorpay_payment_id',
        'razorpay_signature', 'order_status', 'notes',
    ];

    /**
     * Get orders with product info joined.
     */
    public static function withProduct(int $id): ?object
    {
        return static::db()->fetch(
            "SELECT so.*, p.name AS product_name, p.slug AS product_slug
             FROM `shop_orders` so
             LEFT JOIN `products` p ON so.product_id = p.id
             WHERE so.id = ?",
            [$id]
        );
    }

    /**
     * List all orders with product name, paginated.
     */
    public static function listPaginated(int $page, int $perPage, array $filters = []): array
    {
        $where  = '1=1';
        $params = [];

        if (!empty($filters['payment_status'])) {
            $where  .= ' AND so.payment_status = ?';
            $params[] = $filters['payment_status'];
        }
        if (!empty($filters['order_status'])) {
            $where  .= ' AND so.order_status = ?';
            $params[] = $filters['order_status'];
        }

        $offset = ($page - 1) * $perPage;

        $total = static::db()->fetch(
            "SELECT COUNT(*) AS cnt FROM `shop_orders` so WHERE {$where}",
            $params
        )->cnt;

        $rows = static::db()->fetchAll(
            "SELECT so.*, p.name AS product_name
             FROM `shop_orders` so
             LEFT JOIN `products` p ON so.product_id = p.id
             WHERE {$where}
             ORDER BY so.created_at DESC
             LIMIT {$perPage} OFFSET {$offset}",
            $params
        );

        return ['data' => $rows, 'total' => (int) $total, 'page' => $page, 'per_page' => $perPage];
    }
}
