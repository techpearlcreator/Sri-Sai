<?php

namespace App\Middleware;

class PublicAuthMiddleware
{
    public function handle(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['public_user'])) {
            // For AJAX requests, return JSON
            if (
                !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
            ) {
                http_response_code(401);
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Please login to continue']);
                exit;
            }

            // For regular requests, redirect to login
            header('Location: /srisai/public/login');
            exit;
        }
    }
}
