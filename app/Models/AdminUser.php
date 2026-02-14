<?php

namespace App\Models;

use App\Core\Model;

class AdminUser extends Model
{
    protected static string $table = 'admin_users';
    protected static array $fillable = [
        'role_id', 'name', 'email', 'password_hash', 'phone',
        'avatar', 'is_active', 'last_login_at', 'last_login_ip',
    ];

    /**
     * Find admin user by email.
     */
    public static function findByEmail(string $email): ?object
    {
        return static::findBy('email', $email);
    }

    /**
     * Get user with role data.
     */
    public static function findWithRole(int $id): ?object
    {
        return static::db()->fetch(
            "SELECT u.*, r.name as role_name, r.slug as role_slug, r.permissions as role_permissions
             FROM `admin_users` u
             LEFT JOIN `roles` r ON u.role_id = r.id
             WHERE u.id = ?",
            [$id]
        );
    }
}
