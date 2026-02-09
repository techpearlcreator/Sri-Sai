<?php

namespace App\Controllers\Api;

use App\Core\Controller;
use App\Core\Database;

class TestController extends Controller
{
    public function index(): void
    {
        $db = Database::getInstance();

        // Test database connection
        $result = $db->fetch("SELECT VERSION() as version, NOW() as server_time");

        $this->json([
            'message'      => 'Sri Sai Mission API is running!',
            'environment'  => $_ENV['APP_ENV'] ?? 'unknown',
            'php_version'  => PHP_VERSION,
            'mysql_version' => $result->version ?? 'unknown',
            'server_time'  => $result->server_time ?? 'unknown',
            'status'       => 'healthy',
        ]);
    }
}
