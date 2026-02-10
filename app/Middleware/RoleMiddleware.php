<?php

namespace App\Middleware;

use App\Helpers\Response;

/**
 * RoleMiddleware — Checks role-based permissions for the authenticated user.
 *
 * Usage in routes:
 *   $router->get('/api/v1/blogs', BlogController::class, 'index', ['AuthMiddleware', 'RoleMiddleware:blogs,read']);
 *
 * The Router passes the parameters after the colon to handle().
 * First param = module name, second param = action (create/read/update/delete).
 */
class RoleMiddleware
{
    public function handle(string $module = '', string $action = ''): void
    {
        global $authUser;

        if (!$authUser) {
            Response::error(401, 'UNAUTHORIZED', 'Authentication required');
        }

        // Super admins bypass all permission checks
        if (($authUser->role_slug ?? '') === 'super_admin') {
            return;
        }

        if (!$module || !$action) {
            return; // No specific permission required
        }

        $permissions = json_decode($authUser->role_permissions ?? '{}', true);

        if (!is_array($permissions)) {
            Response::error(403, 'FORBIDDEN', 'You do not have permission to perform this action');
        }

        $modulePermissions = $permissions[$module] ?? [];

        if (!in_array($action, $modulePermissions, true)) {
            Response::error(403, 'FORBIDDEN', "You do not have '{$action}' permission for '{$module}'");
        }
    }
}
