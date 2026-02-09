<?php
/**
 * Web Routes — Public website pages (server-rendered PHP views).
 * These routes serve HTML to the browser.
 */

// --- Homepage ---
$router->get('/', App\Controllers\Web\HomeController::class, 'index');

// --- Admin SPA shell (serves React app) ---
// All /admin/* routes serve the same React index.html, React Router handles the rest
// $router->get('/admin',       App\Controllers\Web\AdminController::class, 'index');
// $router->get('/admin/{any}', App\Controllers\Web\AdminController::class, 'index');

// --- Public pages will be added as Phase 8 progresses ---
