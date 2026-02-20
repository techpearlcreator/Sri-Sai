<?php

namespace App\Models;

use App\Core\Model;

class PublicUser extends Model
{
    protected static string $table = 'public_users';
    protected static array $fillable = [
        'name', 'email', 'phone', 'password_hash', 'is_active',
    ];

    public static function findByEmail(string $email): ?object
    {
        return static::findBy('email', $email);
    }

    public static function findByPhone(string $phone): ?object
    {
        return static::findBy('phone', $phone);
    }

    /**
     * Find user by email OR phone (for login with single credential field).
     */
    public static function findByCredential(string $credential): ?object
    {
        return static::db()->fetch(
            "SELECT * FROM `public_users` WHERE `email` = ? OR `phone` = ? LIMIT 1",
            [$credential, $credential]
        );
    }
}
