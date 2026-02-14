<?php

namespace App\Models;

use App\Core\Model;

class SeoMeta extends Model
{
    protected static string $table = 'seo_meta';
    protected static array $fillable = [
        'entity_type', 'entity_id', 'meta_title', 'meta_description',
        'meta_keywords', 'og_title', 'og_description', 'og_image',
        'canonical_url', 'no_index', 'no_follow',
    ];

    /**
     * Get SEO meta for a specific entity.
     */
    public static function forEntity(string $type, int $id): ?object
    {
        return static::db()->fetch(
            "SELECT * FROM `seo_meta` WHERE `entity_type` = ? AND `entity_id` = ?",
            [$type, $id]
        );
    }

    /**
     * Create or update SEO meta for an entity.
     */
    public static function upsert(string $entityType, int $entityId, array $data): void
    {
        $existing = self::forEntity($entityType, $entityId);

        $data['entity_type'] = $entityType;
        $data['entity_id'] = $entityId;

        if ($existing) {
            static::update($existing->id, $data);
        } else {
            static::create($data);
        }
    }
}
