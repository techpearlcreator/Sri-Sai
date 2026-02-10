<?php
/**
 * API Routes — /api/v1/*
 * All routes here serve JSON and are consumed by the React admin panel.
 */

// --- Test Route (remove in production) ---
$router->get('/api/v1/test',        App\Controllers\Api\TestController::class, 'index');
$router->get('/api/v1/test/models', App\Controllers\Api\TestController::class, 'models');

// --- Authentication ---
$router->post('/api/v1/auth/login',    App\Controllers\Api\AuthController::class, 'login');
$router->post('/api/v1/auth/logout',   App\Controllers\Api\AuthController::class, 'logout',   ['AuthMiddleware']);
$router->get('/api/v1/auth/me',        App\Controllers\Api\AuthController::class, 'me',       ['AuthMiddleware']);
$router->put('/api/v1/auth/password',  App\Controllers\Api\AuthController::class, 'changePassword', ['AuthMiddleware']);

// --- Routes will be uncommented as each API module is built ---
