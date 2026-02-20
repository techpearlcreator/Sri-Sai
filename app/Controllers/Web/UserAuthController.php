<?php

namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\PublicUser;
use App\Models\TourBooking;

class UserAuthController extends Controller
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * GET /login
     */
    public function loginForm(): void
    {
        if (!empty($_SESSION['public_user'])) {
            header('Location: /srisai/public/profile');
            exit;
        }

        $this->render('auth.login', [
            'pageTitle' => 'Login — Sri Sai Mission',
            'pageClass' => 'auth',
        ]);
    }

    /**
     * POST /login
     */
    public function login(): void
    {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents('php://input'), true);
        $credential = trim($data['credential'] ?? '');
        $password = $data['password'] ?? '';

        if ($credential === '' || $password === '') {
            http_response_code(422);
            echo json_encode(['error' => 'Email/phone and password are required']);
            exit;
        }

        $user = PublicUser::findByCredential($credential);

        if (!$user || !password_verify($password, $user->password_hash)) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid credentials']);
            exit;
        }

        if (!$user->is_active) {
            http_response_code(403);
            echo json_encode(['error' => 'Your account has been deactivated']);
            exit;
        }

        $_SESSION['public_user'] = [
            'id'    => (int) $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
        ];

        echo json_encode(['success' => true, 'redirect' => '/srisai/public/profile']);
        exit;
    }

    /**
     * GET /register
     */
    public function registerForm(): void
    {
        if (!empty($_SESSION['public_user'])) {
            header('Location: /srisai/public/profile');
            exit;
        }

        $this->render('auth.register', [
            'pageTitle' => 'Register — Sri Sai Mission',
            'pageClass' => 'auth',
        ]);
    }

    /**
     * POST /register
     */
    public function register(): void
    {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents('php://input'), true);
        $name     = trim($data['name'] ?? '');
        $email    = trim($data['email'] ?? '');
        $phone    = trim($data['phone'] ?? '');
        $password = $data['password'] ?? '';
        $confirm  = $data['confirm_password'] ?? '';

        // Validate
        $errors = [];
        if ($name === '') $errors[] = 'Name is required';
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required';
        if ($phone === '' || !preg_match('/^[6-9]\d{9}$/', $phone)) $errors[] = 'Valid 10-digit phone number is required';
        if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters';
        if ($password !== $confirm) $errors[] = 'Passwords do not match';

        if (!empty($errors)) {
            http_response_code(422);
            echo json_encode(['error' => implode(', ', $errors)]);
            exit;
        }

        // Check unique email/phone
        if (PublicUser::findByEmail($email)) {
            http_response_code(422);
            echo json_encode(['error' => 'Email already registered']);
            exit;
        }
        if (PublicUser::findByPhone($phone)) {
            http_response_code(422);
            echo json_encode(['error' => 'Phone number already registered']);
            exit;
        }

        $user = PublicUser::create([
            'name'          => $name,
            'email'         => $email,
            'phone'         => $phone,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
        ]);

        $_SESSION['public_user'] = [
            'id'    => (int) $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
        ];

        echo json_encode(['success' => true, 'redirect' => '/srisai/public/profile']);
        exit;
    }

    /**
     * GET /logout
     */
    public function logout(): void
    {
        unset($_SESSION['public_user']);
        header('Location: /srisai/public/login');
        exit;
    }

    /**
     * GET /profile
     */
    public function profile(): void
    {
        $userId = $_SESSION['public_user']['id'];
        $user = PublicUser::find($userId);
        $bookings = TourBooking::byUser($userId);

        $this->render('auth.profile', [
            'pageTitle' => 'My Profile — Sri Sai Mission',
            'pageClass' => 'auth',
            'user'      => $user,
            'bookings'  => $bookings,
        ]);
    }

    /**
     * POST /profile/update
     */
    public function updateProfile(): void
    {
        header('Content-Type: application/json');

        $userId = $_SESSION['public_user']['id'];
        $data = json_decode(file_get_contents('php://input'), true);

        $name = trim($data['name'] ?? '');
        if ($name === '') {
            http_response_code(422);
            echo json_encode(['error' => 'Name is required']);
            exit;
        }

        $updateData = ['name' => $name];

        // If changing password
        if (!empty($data['new_password'])) {
            $user = PublicUser::find($userId);
            if (!password_verify($data['current_password'] ?? '', $user->password_hash)) {
                http_response_code(422);
                echo json_encode(['error' => 'Current password is incorrect']);
                exit;
            }
            if (strlen($data['new_password']) < 6) {
                http_response_code(422);
                echo json_encode(['error' => 'New password must be at least 6 characters']);
                exit;
            }
            $updateData['password_hash'] = password_hash($data['new_password'], PASSWORD_DEFAULT);
        }

        PublicUser::update($userId, $updateData);

        $_SESSION['public_user']['name'] = $name;

        echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
        exit;
    }
}
