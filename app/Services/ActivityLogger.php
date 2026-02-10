<?php

namespace App\Services;

use App\Core\Database;

/**
 * ActivityLogger — Logs admin actions into the activity_log table.
 *
 * Usage:
 *   ActivityLogger::log(1, 'created', 'blog', 5, 'Created blog: My New Post');
 *   ActivityLogger::log(null, 'login', null, null, 'Admin login from 192.168.1.1');
 */
class ActivityLogger
{
    public static function log(
        ?int $userId,
        string $action,
        ?string $entityType = null,
        ?int $entityId = null,
        ?string $description = null
    ): void {
        $db = Database::getInstance();
        $db->query(
            "INSERT INTO `activity_log` (`user_id`, `action`, `entity_type`, `entity_id`, `description`, `ip_address`, `user_agent`)
             VALUES (?, ?, ?, ?, ?, ?, ?)",
            [
                $userId,
                $action,
                $entityType,
                $entityId,
                $description,
                $_SERVER['REMOTE_ADDR'] ?? null,
                substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255),
            ]
        );
    }
}
