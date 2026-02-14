<?php

namespace App\Models;

use App\Core\Model;

class Magazine extends Model
{
    protected static string $table = 'magazines';
    protected static array $fillable = [
        'category_id', 'created_by', 'title', 'slug', 'excerpt', 'content',
        'featured_image', 'issue_number', 'issue_date', 'pdf_file',
        'status', 'is_featured', 'published_at',
    ];

    public static function findFull(int $id): ?object
    {
        return static::db()->fetch(
            "SELECT m.*, c.name as category_name, u.name as author_name
             FROM `magazines` m
             LEFT JOIN `categories` c ON m.category_id = c.id
             LEFT JOIN `admin_users` u ON m.created_by = u.id
             WHERE m.id = ?",
            [$id]
        );
    }

    public static function findBySlug(string $slug): ?object
    {
        return static::db()->fetch(
            "SELECT m.*, c.name as category_name, u.name as author_name
             FROM `magazines` m
             LEFT JOIN `categories` c ON m.category_id = c.id
             LEFT JOIN `admin_users` u ON m.created_by = u.id
             WHERE m.slug = ? AND m.status = 'published'",
            [$slug]
        );
    }
}
