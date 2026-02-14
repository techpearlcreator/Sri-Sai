<?php
/**
 * PSR-4 style autoloader for the Sri Sai Mission application.
 * Maps namespace App\ to the app/ directory.
 */

spl_autoload_register(function (string $class): void {
    // Only handle our App namespace
    $prefix = 'App\\';
    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }

    // Strip the namespace prefix
    $relativeClass = substr($class, strlen($prefix));

    // Build file path: App\Core\Router → app/Core/Router.php
    $file = APP_PATH . '/' . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});
