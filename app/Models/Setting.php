<?php

namespace App\Models;

use App\Core\Model;

class Setting extends Model
{
    protected static string $table = 'settings';
    protected static array $fillable = [
        'group_name', 'key_name', 'value', 'type', 'label',
    ];

    /**
     * Get a single setting value by key.
     */
    public static function getValue(string $key, mixed $default = null): mixed
    {
        $result = static::findBy('key_name', $key);
        return $result ? $result->value : $default;
    }

    /**
     * Set a single setting value by key.
     */
    public static function setValue(string $key, mixed $value): void
    {
        static::db()->query(
            "UPDATE `settings` SET `value` = ? WHERE `key_name` = ?",
            [$value, $key]
        );
    }

    /**
     * Get all settings as key => value array.
     */
    public static function allAsArray(): array
    {
        $rows = static::all('group_name');
        $result = [];
        foreach ($rows as $row) {
            $result[$row->key_name] = $row->value;
        }
        return $result;
    }

    /**
     * Get settings by group.
     */
    public static function byGroup(string $group): array
    {
        return static::where('group_name', $group)->orderBy('id')->get();
    }

    /**
     * Bulk update settings from key-value pairs.
     */
    public static function bulkUpdate(array $data): void
    {
        foreach ($data as $key => $value) {
            static::setValue($key, $value);
        }
    }
}
