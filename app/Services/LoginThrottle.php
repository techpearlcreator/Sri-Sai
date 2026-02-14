<?php

namespace App\Services;

use App\Core\Database;

/**
 * LoginThrottle — Rate limits login attempts per email and IP.
 *
 * Usage:
 *   if (LoginThrottle::tooManyAttempts($email)) { ... blocked ... }
 *   LoginThrottle::recordAttempt($email);
 *   LoginThrottle::clearAttempts($email);  // on successful login
 */
class LoginThrottle
{
    private static int $maxAttempts = 5;
    private static int $windowMinutes = 15;

    /**
     * Check if the email/IP has exceeded the attempt limit.
     */
    public static function tooManyAttempts(string $email): bool
    {
        $config = require CONFIG_PATH . '/auth.php';
        $max = $config['max_login_attempts'] ?? self::$maxAttempts;
        $window = $config['lockout_minutes'] ?? self::$windowMinutes;

        $db = Database::getInstance();
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

        $result = $db->fetch(
            "SELECT COUNT(*) as cnt FROM `login_attempts`
             WHERE (`email` = ? OR `ip_address` = ?)
             AND `attempted_at` > DATE_SUB(NOW(), INTERVAL ? MINUTE)",
            [$email, $ip, $window]
        );

        return ((int) ($result->cnt ?? 0)) >= $max;
    }

    /**
     * Record a failed login attempt.
     */
    public static function recordAttempt(string $email): void
    {
        $db = Database::getInstance();
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

        $db->query(
            "INSERT INTO `login_attempts` (`email`, `ip_address`) VALUES (?, ?)",
            [$email, $ip]
        );
    }

    /**
     * Clear attempts for an email after successful login.
     */
    public static function clearAttempts(string $email): void
    {
        $db = Database::getInstance();
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

        $db->query(
            "DELETE FROM `login_attempts` WHERE `email` = ? OR `ip_address` = ?",
            [$email, $ip]
        );
    }

    /**
     * Get minutes remaining until lockout expires.
     */
    public static function minutesUntilUnlock(string $email): int
    {
        $config = require CONFIG_PATH . '/auth.php';
        $window = $config['lockout_minutes'] ?? self::$windowMinutes;

        $db = Database::getInstance();
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

        $result = $db->fetch(
            "SELECT MAX(`attempted_at`) as last_attempt FROM `login_attempts`
             WHERE (`email` = ? OR `ip_address` = ?)
             AND `attempted_at` > DATE_SUB(NOW(), INTERVAL ? MINUTE)",
            [$email, $ip, $window]
        );

        if (!$result || !$result->last_attempt) {
            return 0;
        }

        $lastAttempt = strtotime($result->last_attempt);
        $unlockAt = $lastAttempt + ($window * 60);
        $remaining = (int) ceil(($unlockAt - time()) / 60);

        return max(0, $remaining);
    }
}
