<?php

namespace App\Helpers;

/**
 * Simple translation helper.
 *
 * Usage:
 *   Lang::set('ta');          // switch language (stored in session)
 *   Lang::get('nav.home');    // returns translated string
 *   __('nav.home');           // shorthand global function
 *   t('nav.home');            // alias
 */
class Lang
{
    private static ?array $strings = null;
    private static string $locale  = 'en';

    /**
     * Boot the language system — reads locale from session, loads strings.
     * Call once per request (done in master layout).
     */
    public static function boot(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $locale = $_SESSION['lang'] ?? 'en';

        // Allow override via ?lang=ta (or en)
        if (isset($_GET['lang']) && in_array($_GET['lang'], ['en', 'ta'], true)) {
            $locale = $_GET['lang'];
            $_SESSION['lang'] = $locale;
        }

        self::$locale = $locale;
        self::load($locale);
    }

    /**
     * Switch language and redirect back.
     */
    public static function set(string $locale): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $allowed = ['en', 'ta'];
        if (!in_array($locale, $allowed, true)) {
            $locale = 'en';
        }

        $_SESSION['lang'] = $locale;
    }

    /**
     * Get translated string by key. Falls back to key itself.
     */
    public static function get(string $key, array $replace = []): string
    {
        if (self::$strings === null) {
            self::load(self::$locale);
        }

        $str = self::$strings[$key] ?? $key;

        // sprintf-style replacements: __('footer.copyright', [date('Y')])
        if (!empty($replace)) {
            $str = vsprintf($str, $replace);
        }

        return $str;
    }

    /**
     * Get current locale code ('en' or 'ta').
     */
    public static function current(): string
    {
        return self::$locale;
    }

    /**
     * Load translation file.
     */
    private static function load(string $locale): void
    {
        $file = dirname(__DIR__, 2) . "/lang/{$locale}.php";

        if (!file_exists($file)) {
            $file = dirname(__DIR__, 2) . '/lang/en.php';
        }

        self::$strings = require $file;
        self::$locale  = $locale;
    }
}
