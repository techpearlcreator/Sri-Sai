<?php

namespace App\Controllers\Api;

use App\Core\Controller;
use App\Core\Database;
use App\Helpers\Response;
use App\Helpers\Validator;
use App\Models\AdminUser;
use App\Services\ActivityLogger;
use App\Services\JwtService;
use App\Services\LoginThrottle;

class AuthController extends Controller
{
    /**
     * POST /api/v1/auth/login
     * Body: { "email": "...", "password": "..." }
     */
    public function login(): void
    {
        $data = $this->getJsonBody();

        $v = new Validator($data, [
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($v->fails()) {
            Response::error(422, 'VALIDATION_ERROR', 'Invalid input', $v->errors());
        }

        $email = trim($data['email']);
        $password = $data['password'];

        // Check throttle
        if (LoginThrottle::tooManyAttempts($email)) {
            $minutes = LoginThrottle::minutesUntilUnlock($email);
            Response::error(429, 'TOO_MANY_ATTEMPTS', "Too many login attempts. Try again in {$minutes} minutes.");
        }

        // Find user
        $user = AdminUser::findByEmail($email);

        if (!$user || !password_verify($password, $user->password_hash)) {
            LoginThrottle::recordAttempt($email);
            ActivityLogger::log(null, 'login_failed', null, null, "Failed login for: {$email}");
            Response::error(401, 'INVALID_CREDENTIALS', 'Invalid email or password');
        }

        if (!$user->is_active) {
            Response::error(403, 'ACCOUNT_DISABLED', 'Your account has been deactivated. Contact the administrator.');
        }

        // Successful login — clear throttle, update last login
        LoginThrottle::clearAttempts($email);

        AdminUser::update($user->id, [
            'last_login_at' => date('Y-m-d H:i:s'),
            'last_login_ip' => $_SERVER['REMOTE_ADDR'] ?? null,
        ]);

        // Generate JWT
        $userWithRole = AdminUser::findWithRole($user->id);

        $token = JwtService::encode([
            'sub'  => (int) $user->id,
            'name' => $user->name,
            'role' => $userWithRole->role_slug ?? 'viewer',
        ]);

        ActivityLogger::log((int) $user->id, 'login', null, null, 'Admin login');

        $this->json([
            'token' => $token,
            'user'  => [
                'id'        => (int) $userWithRole->id,
                'name'      => $userWithRole->name,
                'email'     => $userWithRole->email,
                'phone'     => $userWithRole->phone,
                'avatar'    => $userWithRole->avatar,
                'role'      => $userWithRole->role_slug,
                'role_name' => $userWithRole->role_name,
            ],
        ]);
    }

    /**
     * POST /api/v1/auth/logout
     * Requires: AuthMiddleware
     */
    public function logout(): void
    {
        $user = $this->getAuthUser();
        ActivityLogger::log((int) $user->id, 'logout', null, null, 'Admin logout');

        $this->json(['message' => 'Logged out successfully']);
    }

    /**
     * GET /api/v1/auth/me
     * Requires: AuthMiddleware
     */
    public function me(): void
    {
        $user = $this->getAuthUser();

        $permissions = json_decode($user->role_permissions ?? '{}', true);

        $this->json([
            'id'          => (int) $user->id,
            'name'        => $user->name,
            'email'       => $user->email,
            'phone'       => $user->phone,
            'avatar'      => $user->avatar,
            'role'        => $user->role_slug,
            'role_name'   => $user->role_name,
            'permissions' => $permissions,
            'last_login'  => $user->last_login_at,
        ]);
    }

    /**
     * PUT /api/v1/auth/password
     * Requires: AuthMiddleware
     * Body: { "current_password": "...", "new_password": "...", "confirm_password": "..." }
     */
    public function changePassword(): void
    {
        $user = $this->getAuthUser();
        $data = $this->getJsonBody();

        $v = new Validator($data, [
            'current_password' => 'required|string',
            'new_password'     => 'required|string|min:8|max:100',
            'confirm_password' => 'required|string',
        ]);

        if ($v->fails()) {
            Response::error(422, 'VALIDATION_ERROR', 'Invalid input', $v->errors());
        }

        if ($data['new_password'] !== $data['confirm_password']) {
            Response::error(422, 'VALIDATION_ERROR', 'New password and confirmation do not match');
        }

        // Fetch fresh user to get password hash
        $freshUser = AdminUser::find($user->id);

        if (!password_verify($data['current_password'], $freshUser->password_hash)) {
            Response::error(401, 'INVALID_PASSWORD', 'Current password is incorrect');
        }

        $newHash = password_hash($data['new_password'], PASSWORD_BCRYPT, ['cost' => 12]);

        AdminUser::update($user->id, [
            'password_hash' => $newHash,
        ]);

        ActivityLogger::log((int) $user->id, 'password_changed', 'admin_user', (int) $user->id, 'Password changed');

        $this->json(['message' => 'Password changed successfully']);
    }
}
