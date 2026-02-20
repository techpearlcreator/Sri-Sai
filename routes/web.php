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

// --- Public Auth (Login/Register) ---
$router->get('/login',           App\Controllers\Web\UserAuthController::class, 'loginForm');
$router->post('/login',          App\Controllers\Web\UserAuthController::class, 'login');
$router->get('/register',        App\Controllers\Web\UserAuthController::class, 'registerForm');
$router->post('/register',       App\Controllers\Web\UserAuthController::class, 'register');
$router->get('/logout',          App\Controllers\Web\UserAuthController::class, 'logout');
$router->get('/profile',         App\Controllers\Web\UserAuthController::class, 'profile',       ['PublicAuthMiddleware']);
$router->post('/profile/update', App\Controllers\Web\UserAuthController::class, 'updateProfile', ['PublicAuthMiddleware']);

// --- Shop ---
$router->get('/shop',                       App\Controllers\Web\ShopController::class, 'index');
$router->get('/shop/delivery-charge',       App\Controllers\Web\ShopController::class, 'checkDelivery');
$router->post('/shop/create-order',         App\Controllers\Web\ShopController::class, 'createOrder');
$router->post('/shop/verify-payment',       App\Controllers\Web\ShopController::class, 'verifyPayment');
$router->post('/shop/enquiry',              App\Controllers\Web\ShopController::class, 'submitEnquiry');
$router->get('/pooja-booking',                  App\Controllers\Web\ShopController::class, 'poojaBookingForm');
$router->post('/pooja-booking/create-order',    App\Controllers\Web\ShopController::class, 'createPoojaOrder');
$router->post('/pooja-booking/verify',          App\Controllers\Web\ShopController::class, 'verifyPoojaPayment');
$router->get('/shop/{slug}',                App\Controllers\Web\ShopController::class, 'show');

// --- Tours ---
$router->get('/tours',               App\Controllers\Web\TourController::class, 'index');
$router->post('/tours/create-order', App\Controllers\Web\TourController::class, 'createOrder', ['PublicAuthMiddleware']);
$router->post('/tours/verify',       App\Controllers\Web\TourController::class, 'verify',      ['PublicAuthMiddleware']);
$router->get('/tours/{slug}',        App\Controllers\Web\TourController::class, 'show');

// --- Static pages (hardcoded content — edit view files to update) ---
$router->get('/about-us', App\Controllers\Web\StaticPageController::class, 'about');
$router->get('/about',    App\Controllers\Web\StaticPageController::class, 'about');
$router->get('/mission',  App\Controllers\Web\StaticPageController::class, 'mission');
$router->get('/vision',   App\Controllers\Web\StaticPageController::class, 'vision');

// --- Dynamic CMS Pages (must be LAST — catches remaining /{slug}) ---
$router->get('/{slug}', App\Controllers\Web\PageController::class, 'show');
