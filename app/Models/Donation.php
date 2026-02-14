<?php

namespace App\Models;

use App\Core\Model;

class Donation extends Model
{
    protected static string $table = 'donations';
    protected static array $fillable = [
        'donor_name', 'donor_email', 'donor_phone', 'donor_address',
        'donor_pan', 'amount', 'currency', 'purpose', 'payment_method',
        'transaction_id', 'receipt_number', 'status', 'notes', 'donated_at',
    ];

    /**
     * Get monthly donation summary.
     */
    public static function monthlySummary(int $year, int $month): object
    {
        return static::db()->fetch(
            "SELECT COUNT(*) as count, COALESCE(SUM(amount), 0) as total
             FROM `donations`
             WHERE `status` = 'completed'
               AND YEAR(donated_at) = ? AND MONTH(donated_at) = ?",
            [$year, $month]
        );
    }
}
