<?php

namespace App\Services;

/**
 * JwtService — HMAC-SHA256 JWT token generation and validation.
 *
 * Usage:
 *   $token = JwtService::encode(['user_id' => 1, 'role' => 'super_admin']);
 *   $payload = JwtService::decode($token);  // returns object or null
 */
class JwtService
{
    /**
     * Create a JWT token.
     */
    public static function encode(array $payload): string
    {
        $config = require CONFIG_PATH . '/auth.php';
        $secret = $config['jwt_secret'];
        $expiry = $config['jwt_expiry'];

        $header = self::base64UrlEncode(json_encode([
            'alg' => 'HS256',
            'typ' => 'JWT',
        ]));

        $payload['iat'] = time();
        $payload['exp'] = time() + $expiry;

        $payloadEncoded = self::base64UrlEncode(json_encode($payload));

        $signature = self::base64UrlEncode(
            hash_hmac('sha256', "{$header}.{$payloadEncoded}", $secret, true)
        );

        return "{$header}.{$payloadEncoded}.{$signature}";
    }

    /**
     * Decode and validate a JWT token.
     * Returns the payload as an object, or null if invalid/expired.
     */
    public static function decode(string $token): ?object
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return null;
        }

        [$headerB64, $payloadB64, $signatureB64] = $parts;

        $config = require CONFIG_PATH . '/auth.php';
        $secret = $config['jwt_secret'];

        // Verify signature
        $expectedSignature = self::base64UrlEncode(
            hash_hmac('sha256', "{$headerB64}.{$payloadB64}", $secret, true)
        );

        if (!hash_equals($expectedSignature, $signatureB64)) {
            return null;
        }

        // Decode payload
        $payload = json_decode(self::base64UrlDecode($payloadB64));
        if (!$payload) {
            return null;
        }

        // Check expiration
        if (isset($payload->exp) && $payload->exp < time()) {
            return null;
        }

        return $payload;
    }

    private static function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private static function base64UrlDecode(string $data): string
    {
        return base64_decode(strtr($data, '-_', '+/'));
    }
}
