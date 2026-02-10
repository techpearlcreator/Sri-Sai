<?php

namespace App\Middleware;

/**
 * CorsMiddleware — Sets CORS headers for API routes.
 *
 * In development, allows the React dev server (Vite on port 5173).
 * In production, restricts to the configured APP_URL.
 */
class CorsMiddleware
{
    public function handle(): void
    {
        $allowedOrigins = [
            'http://localhost:5173',   // Vite React dev server
            'http://localhost:3000',   // Fallback dev port
            'http://127.0.0.1:5173',
        ];

        // In production, add the APP_URL
        $appUrl = $_ENV['APP_URL'] ?? '';
        if ($appUrl) {
            $allowedOrigins[] = rtrim($appUrl, '/');
        }

        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';

        if (in_array($origin, $allowedOrigins, true)) {
            header("Access-Control-Allow-Origin: {$origin}");
            header('Access-Control-Allow-Credentials: true');
        } elseif (($_ENV['APP_ENV'] ?? '') === 'local') {
            // In local dev, allow any origin for easy testing
            header('Access-Control-Allow-Origin: *');
        }

        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        header('Access-Control-Max-Age: 86400');
    }
}
