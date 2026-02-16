<?php
/**
 * Web Routes — Public website pages (server-rendered PHP views).
 */

// --- Homepage ---
$router->get('/', App\Controllers\Web\HomeController::class, 'index');

// --- Blog ---
$router->get('/blog', App\Controllers\Web\BlogController::class, 'index');
$router->get('/blog/{slug}', App\Controllers\Web\BlogController::class, 'show');

// --- Magazine ---
$router->get('/magazine', App\Controllers\Web\MagazineController::class, 'index');
$router->get('/magazine/{slug}', App\Controllers\Web\MagazineController::class, 'show');

// --- Gallery ---
$router->get('/gallery', App\Controllers\Web\GalleryController::class, 'index');
$router->get('/gallery/{slug}', App\Controllers\Web\GalleryController::class, 'show');

// --- Events ---
$router->get('/events', App\Controllers\Web\EventController::class, 'index');
$router->get('/events/{slug}', App\Controllers\Web\EventController::class, 'show');

// --- Trustees ---
$router->get('/trustees', App\Controllers\Web\TrusteeController::class, 'index');

// --- Donations ---
$router->get('/donations', App\Controllers\Web\DonationController::class, 'index');
$router->post('/donations/create-order', App\Controllers\Web\PaymentController::class, 'createOrder');
$router->post('/donations/verify', App\Controllers\Web\PaymentController::class, 'verify');

// --- Contact ---
$router->get('/contact', App\Controllers\Web\ContactController::class, 'index');
$router->post('/contact/submit', App\Controllers\Web\ContactController::class, 'submit');

// --- Dynamic CMS Pages (must be LAST — catches /{slug}) ---
$router->get('/{slug}', App\Controllers\Web\PageController::class, 'show');
