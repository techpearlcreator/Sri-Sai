<?php

namespace App\Middleware;

use App\Helpers\Response;
use App\Models\AdminUser;
use App\Services\JwtService;

/**
 * AuthMiddleware — Validates JWT bearer token on protected API routes.
 *
 * Sets the global $authUser with user + role data if valid.
 */
class AuthMiddleware
{
    public function handle(): void
    {
        $token = $this->extractBearerToken();

        if (!$token) {
            Response::error(401, 'UNAUTHORIZED', 'Authentication token is required');
        }

        $payload = JwtService::decode($token);

        if (!$payload || !isset($payload->sub)) {
            Response::error(401, 'INVALID_TOKEN', 'Token is invalid or has expired');
        }

        // Look up the user with role data
        $user = AdminUser::findWithRole((int) $payload->sub);

        if (!$user) {
            Response::error(401, 'USER_NOT_FOUND', 'Authenticated user no longer exists');
        }

        if (!$user->is_active) {
            Response::error(403, 'ACCOUNT_DISABLED', 'Your account has been deactivated');
        }

        // Store authenticated user globally for controllers
        global $authUser;
        $authUser = $user;
    }

    /**
     * Extract Bearer token from Authorization header.
     */
    private function extractBearerToken(): ?string
    {
        $header = $_SERVER['HTTP_AUTHORIZATION']
            ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION']
            ?? '';

        if (preg_match('/^Bearer\s+(.+)$/i', $header, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
