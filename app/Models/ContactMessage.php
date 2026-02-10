<?php

namespace App\Models;

use App\Core\Model;

class ContactMessage extends Model
{
    protected static string $table = 'contact_messages';
    protected static array $fillable = [
        'name', 'last_name', 'email', 'phone', 'subject',
        'message', 'source_page', 'ip_address', 'status', 'admin_notes', 'replied_at',
    ];

    /**
     * Count unread messages.
     */
    public static function unreadCount(): int
    {
        $result = static::db()->fetch(
            "SELECT COUNT(*) as cnt FROM `contact_messages` WHERE `status` = 'unread'"
        );
        return (int) $result->cnt;
    }
}
