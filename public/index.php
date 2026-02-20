<?php
/**
 * Sri Sai Mission — Front Controller
 * All requests are routed through this file.
 */

// Define base paths
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('VIEWS_PATH', ROOT_PATH . '/views');
define('STORAGE_PATH', ROOT_PATH . '/storage');
define('PUBLIC_PATH', __DIR__);

// Load autoloader
require_once APP_PATH . '/autoload.php';

// Load environment variables
$envLoader = new App\Helpers\EnvLoader(ROOT_PATH . '/.env');
$envLoader->load();

// Load configuration
$config = require CONFIG_PATH . '/app.php';

// Set error reporting based on environment
if ($config['debug']) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

// Set default timezone
date_default_timezone_set('Asia/Kolkata');

// Initialize database connection
$db = App\Core\Database::getInstance();

// Boot language system and register global helpers
session_name('SRISAI_NEW_SESS'); 
App\Helpers\Lang::boot();
if (!function_exists('__')) {
    function __(string $key, array $replace = []): string {
        return App\Helpers\Lang::get($key, $replace);
    }
}
if (!function_exists('t')) {
    function t(string $key, array $replace = []): string {
        return App\Helpers\Lang::get($key, $replace);
    }
}
// langField($obj, 'field') — returns Tamil value if lang=ta and _ta field exists, else English
if (!function_exists('langField')) {
    function langField(object $obj, string $field): string {
        if (App\Helpers\Lang::current() === 'ta') {
            $taField = $field . '_ta';
            if (!empty($obj->$taField)) {
                return $obj->$taField;
            }
        }
        return $obj->$field ?? '';
    }
}

// Initialize router
$router = new App\Core\Router();

// Load routes
require ROOT_PATH . '/routes/api.php';
require ROOT_PATH . '/routes/web.php';

// Get the request URI
$requestUri = $_SERVER['REQUEST_URI'];
$basePath = '/sai_sudarshan/public';

// Strip base path to get clean URL
$url = parse_url($requestUri, PHP_URL_PATH);
if (strpos($url, $basePath) === 0) {
    $url = substr($url, strlen($basePath));
}
$url = $url ?: '/';

// Get request method (support PUT/DELETE via _method field)
$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'POST' && isset($_POST['_method'])) {
    $method = strtoupper($_POST['_method']);
}

// Dispatch the request
$router->dispatch($method, $url);
