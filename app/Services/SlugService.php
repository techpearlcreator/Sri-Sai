<?php

namespace App\Services;

use App\Core\Database;

/**
 * SlugService — Generates unique, SEO-friendly URL slugs.
 *
 * Usage:
 *   $slug = SlugService::generate('Traditions of Hare Krishna', 'blogs');
 *   // Result: "traditions-of-hare-krishna"
 *   // If duplicate: "traditions-of-hare-krishna-1"
 */
class SlugService
{
    /**
     * Generate a unique slug for a given table.
     *
     * @param string   $title     The text to slugify
     * @param string   $table     Database table to check uniqueness against
     * @param int|null $exceptId  Exclude this ID when checking (for updates)
     */
    public static function generate(string $title, string $table, ?int $exceptId = null): string
    {
        $slug = self::slugify($title);

        if ($slug === '') {
            $slug = 'untitled';
        }

        // Check uniqueness
        $originalSlug = $slug;
        $counter = 0;
        $db = Database::getInstance();

        while (true) {
            $sql = "SELECT COUNT(*) as cnt FROM `{$table}` WHERE `slug` = ?";
            $bindings = [$slug];

            if ($exceptId !== null) {
                $sql .= " AND `id` != ?";
                $bindings[] = $exceptId;
            }

            $result = $db->fetch($sql, $bindings);

            if ((int) $result->cnt === 0) {
                break;
            }

            $counter++;
            $slug = $originalSlug . '-' . $counter;
        }

        return $slug;
    }

    /**
     * Convert a string to a URL-safe slug.
     */
    public static function slugify(string $text): string
    {
        // Convert to lowercase
        $text = mb_strtolower($text, 'UTF-8');

        // Replace non-alphanumeric characters with hyphens
        $text = preg_replace('/[^a-z0-9\-]/', '-', $text);

        // Replace multiple hyphens with single
        $text = preg_replace('/-+/', '-', $text);

        // Trim hyphens from edges
        return trim($text, '-');
    }
}
