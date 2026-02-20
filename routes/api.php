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

// --- Media ---
$router->post('/api/v1/media/upload',  App\Controllers\Api\MediaController::class, 'upload',  ['AuthMiddleware', 'RoleMiddleware:media,create']);
$router->get('/api/v1/media',          App\Controllers\Api\MediaController::class, 'index',   ['AuthMiddleware', 'RoleMiddleware:media,read']);
$router->get('/api/v1/media/{id}',     App\Controllers\Api\MediaController::class, 'show',    ['AuthMiddleware', 'RoleMiddleware:media,read']);
$router->put('/api/v1/media/{id}',     App\Controllers\Api\MediaController::class, 'update',  ['AuthMiddleware', 'RoleMiddleware:media,create']);
$router->delete('/api/v1/media/{id}',  App\Controllers\Api\MediaController::class, 'destroy', ['AuthMiddleware', 'RoleMiddleware:media,delete']);

// --- Dashboard ---
$router->get('/api/v1/dashboard', App\Controllers\Api\DashboardController::class, 'index', ['AuthMiddleware']);

// --- Categories ---
$router->get('/api/v1/categories',          App\Controllers\Api\CategoryController::class, 'index',   ['AuthMiddleware']);
$router->get('/api/v1/categories/{id}',     App\Controllers\Api\CategoryController::class, 'show',    ['AuthMiddleware']);
$router->post('/api/v1/categories',         App\Controllers\Api\CategoryController::class, 'store',   ['AuthMiddleware', 'RoleMiddleware:blogs,create']);
$router->put('/api/v1/categories/{id}',     App\Controllers\Api\CategoryController::class, 'update',  ['AuthMiddleware', 'RoleMiddleware:blogs,update']);
$router->delete('/api/v1/categories/{id}',  App\Controllers\Api\CategoryController::class, 'destroy', ['AuthMiddleware', 'RoleMiddleware:blogs,delete']);

// --- Blogs ---
$router->get('/api/v1/blogs',          App\Controllers\Api\BlogController::class, 'index',   ['AuthMiddleware', 'RoleMiddleware:blogs,read']);
$router->get('/api/v1/blogs/{id}',     App\Controllers\Api\BlogController::class, 'show',    ['AuthMiddleware', 'RoleMiddleware:blogs,read']);
$router->post('/api/v1/blogs',         App\Controllers\Api\BlogController::class, 'store',   ['AuthMiddleware', 'RoleMiddleware:blogs,create']);
$router->put('/api/v1/blogs/{id}',     App\Controllers\Api\BlogController::class, 'update',  ['AuthMiddleware', 'RoleMiddleware:blogs,update']);
$router->delete('/api/v1/blogs/{id}',  App\Controllers\Api\BlogController::class, 'destroy', ['AuthMiddleware', 'RoleMiddleware:blogs,delete']);

// --- Magazines ---
$router->get('/api/v1/magazines',          App\Controllers\Api\MagazineController::class, 'index',   ['AuthMiddleware', 'RoleMiddleware:magazines,read']);
$router->get('/api/v1/magazines/{id}',     App\Controllers\Api\MagazineController::class, 'show',    ['AuthMiddleware', 'RoleMiddleware:magazines,read']);
$router->post('/api/v1/magazines',         App\Controllers\Api\MagazineController::class, 'store',   ['AuthMiddleware', 'RoleMiddleware:magazines,create']);
$router->put('/api/v1/magazines/{id}',     App\Controllers\Api\MagazineController::class, 'update',  ['AuthMiddleware', 'RoleMiddleware:magazines,update']);
$router->delete('/api/v1/magazines/{id}',  App\Controllers\Api\MagazineController::class, 'destroy', ['AuthMiddleware', 'RoleMiddleware:magazines,delete']);

// --- Gallery (Albums) ---
$router->get('/api/v1/gallery',          App\Controllers\Api\GalleryController::class, 'index',   ['AuthMiddleware', 'RoleMiddleware:gallery,read']);
$router->get('/api/v1/gallery/{id}',     App\Controllers\Api\GalleryController::class, 'show',    ['AuthMiddleware', 'RoleMiddleware:gallery,read']);
$router->post('/api/v1/gallery',         App\Controllers\Api\GalleryController::class, 'store',   ['AuthMiddleware', 'RoleMiddleware:gallery,create']);
$router->put('/api/v1/gallery/{id}',     App\Controllers\Api\GalleryController::class, 'update',  ['AuthMiddleware', 'RoleMiddleware:gallery,update']);
$router->delete('/api/v1/gallery/{id}',  App\Controllers\Api\GalleryController::class, 'destroy', ['AuthMiddleware', 'RoleMiddleware:gallery,delete']);

// --- Gallery (Images) ---
$router->post('/api/v1/gallery/{albumId}/images',          App\Controllers\Api\GalleryController::class, 'uploadImage',   ['AuthMiddleware', 'RoleMiddleware:gallery,create']);
$router->put('/api/v1/gallery/{albumId}/images/reorder',   App\Controllers\Api\GalleryController::class, 'reorderImages', ['AuthMiddleware', 'RoleMiddleware:gallery,update']);
$router->put('/api/v1/gallery/images/{id}',                App\Controllers\Api\GalleryController::class, 'updateImage',   ['AuthMiddleware', 'RoleMiddleware:gallery,update']);
$router->delete('/api/v1/gallery/images/{id}',             App\Controllers\Api\GalleryController::class, 'deleteImage',   ['AuthMiddleware', 'RoleMiddleware:gallery,delete']);

// --- Events ---
$router->get('/api/v1/events',          App\Controllers\Api\EventController::class, 'index',   ['AuthMiddleware', 'RoleMiddleware:events,read']);
$router->get('/api/v1/events/{id}',     App\Controllers\Api\EventController::class, 'show',    ['AuthMiddleware', 'RoleMiddleware:events,read']);
$router->post('/api/v1/events',         App\Controllers\Api\EventController::class, 'store',   ['AuthMiddleware', 'RoleMiddleware:events,create']);
$router->put('/api/v1/events/{id}',     App\Controllers\Api\EventController::class, 'update',  ['AuthMiddleware', 'RoleMiddleware:events,update']);
$router->delete('/api/v1/events/{id}',  App\Controllers\Api\EventController::class, 'destroy', ['AuthMiddleware', 'RoleMiddleware:events,delete']);

// --- Trustees ---
$router->get('/api/v1/trustees',            App\Controllers\Api\TrusteeController::class, 'index',   ['AuthMiddleware', 'RoleMiddleware:trustees,read']);
$router->get('/api/v1/trustees/{id}',       App\Controllers\Api\TrusteeController::class, 'show',    ['AuthMiddleware', 'RoleMiddleware:trustees,read']);
$router->post('/api/v1/trustees',           App\Controllers\Api\TrusteeController::class, 'store',   ['AuthMiddleware', 'RoleMiddleware:trustees,create']);
$router->put('/api/v1/trustees/reorder',    App\Controllers\Api\TrusteeController::class, 'reorder', ['AuthMiddleware', 'RoleMiddleware:trustees,update']);
$router->put('/api/v1/trustees/{id}',       App\Controllers\Api\TrusteeController::class, 'update',  ['AuthMiddleware', 'RoleMiddleware:trustees,update']);
$router->delete('/api/v1/trustees/{id}',    App\Controllers\Api\TrusteeController::class, 'destroy', ['AuthMiddleware', 'RoleMiddleware:trustees,delete']);

// --- Donations ---
$router->get('/api/v1/donations',           App\Controllers\Api\DonationController::class, 'index',   ['AuthMiddleware', 'RoleMiddleware:donations,read']);
$router->get('/api/v1/donations/summary',   App\Controllers\Api\DonationController::class, 'summary', ['AuthMiddleware', 'RoleMiddleware:donations,read']);
$router->get('/api/v1/donations/{id}',      App\Controllers\Api\DonationController::class, 'show',    ['AuthMiddleware', 'RoleMiddleware:donations,read']);
$router->post('/api/v1/donations',          App\Controllers\Api\DonationController::class, 'store',   ['AuthMiddleware', 'RoleMiddleware:donations,create']);
$router->put('/api/v1/donations/{id}',      App\Controllers\Api\DonationController::class, 'update',  ['AuthMiddleware', 'RoleMiddleware:donations,update']);
$router->delete('/api/v1/donations/{id}',   App\Controllers\Api\DonationController::class, 'destroy', ['AuthMiddleware', 'RoleMiddleware:donations,delete']);

// --- Contact Messages ---
$router->get('/api/v1/contacts',              App\Controllers\Api\ContactController::class, 'index',      ['AuthMiddleware', 'RoleMiddleware:contacts,read']);
$router->get('/api/v1/contacts/unread-count', App\Controllers\Api\ContactController::class, 'unreadCount',['AuthMiddleware']);
$router->get('/api/v1/contacts/{id}',         App\Controllers\Api\ContactController::class, 'show',       ['AuthMiddleware', 'RoleMiddleware:contacts,read']);
$router->put('/api/v1/contacts/bulk',         App\Controllers\Api\ContactController::class, 'bulk',       ['AuthMiddleware', 'RoleMiddleware:contacts,delete']);
$router->put('/api/v1/contacts/{id}',         App\Controllers\Api\ContactController::class, 'update',     ['AuthMiddleware', 'RoleMiddleware:contacts,update']);
$router->delete('/api/v1/contacts/{id}',      App\Controllers\Api\ContactController::class, 'destroy',    ['AuthMiddleware', 'RoleMiddleware:contacts,delete']);

// --- Contact Form (PUBLIC — no auth) ---
$router->post('/api/v1/contact', App\Controllers\Api\ContactController::class, 'submit');

// --- Settings ---
$router->get('/api/v1/settings', App\Controllers\Api\SettingController::class, 'index',  ['AuthMiddleware', 'RoleMiddleware:settings,read']);
$router->put('/api/v1/settings', App\Controllers\Api\SettingController::class, 'update', ['AuthMiddleware', 'RoleMiddleware:settings,update']);

// --- Temple Timings ---
$router->get('/api/v1/temple-timings',          App\Controllers\Api\TempleTimingController::class, 'index',   ['AuthMiddleware']);
$router->get('/api/v1/temple-timings/{id}',     App\Controllers\Api\TempleTimingController::class, 'show',    ['AuthMiddleware']);
$router->post('/api/v1/temple-timings',         App\Controllers\Api\TempleTimingController::class, 'store',   ['AuthMiddleware', 'RoleMiddleware:settings,update']);
$router->put('/api/v1/temple-timings/{id}',     App\Controllers\Api\TempleTimingController::class, 'update',  ['AuthMiddleware', 'RoleMiddleware:settings,update']);
$router->delete('/api/v1/temple-timings/{id}',  App\Controllers\Api\TempleTimingController::class, 'destroy', ['AuthMiddleware', 'RoleMiddleware:settings,update']);

// --- SEO Meta ---
$router->get('/api/v1/seo/{entityType}/{entityId}', App\Controllers\Api\SeoMetaController::class, 'show',   ['AuthMiddleware']);
$router->put('/api/v1/seo/{entityType}/{entityId}', App\Controllers\Api\SeoMetaController::class, 'upsert', ['AuthMiddleware']);

// --- Products (Shop) ---
$router->get('/api/v1/products',          App\Controllers\Api\ProductController::class, 'index',   ['AuthMiddleware', 'RoleMiddleware:shop,read']);
$router->get('/api/v1/products/{id}',     App\Controllers\Api\ProductController::class, 'show',    ['AuthMiddleware', 'RoleMiddleware:shop,read']);
$router->post('/api/v1/products',         App\Controllers\Api\ProductController::class, 'store',   ['AuthMiddleware', 'RoleMiddleware:shop,create']);
$router->put('/api/v1/products/{id}',     App\Controllers\Api\ProductController::class, 'update',  ['AuthMiddleware', 'RoleMiddleware:shop,update']);
$router->delete('/api/v1/products/{id}',  App\Controllers\Api\ProductController::class, 'destroy', ['AuthMiddleware', 'RoleMiddleware:shop,delete']);

// --- Pooja Types ---
$router->get('/api/v1/pooja-types',              App\Controllers\Api\PoojaTypeController::class, 'index',     ['AuthMiddleware', 'RoleMiddleware:shop,read']);
$router->get('/api/v1/pooja-types/{id}',         App\Controllers\Api\PoojaTypeController::class, 'show',      ['AuthMiddleware', 'RoleMiddleware:shop,read']);
$router->post('/api/v1/pooja-types',             App\Controllers\Api\PoojaTypeController::class, 'store',     ['AuthMiddleware', 'RoleMiddleware:shop,create']);
$router->put('/api/v1/pooja-types/{id}',         App\Controllers\Api\PoojaTypeController::class, 'update',    ['AuthMiddleware', 'RoleMiddleware:shop,update']);
$router->delete('/api/v1/pooja-types/{id}',      App\Controllers\Api\PoojaTypeController::class, 'destroy',   ['AuthMiddleware', 'RoleMiddleware:shop,delete']);

// --- Pooja Bookings (admin view) ---
$router->get('/api/v1/pooja-bookings',          App\Controllers\Api\PoojaBookingController::class, 'index',   ['AuthMiddleware', 'RoleMiddleware:shop,read']);
$router->get('/api/v1/pooja-bookings/{id}',     App\Controllers\Api\PoojaBookingController::class, 'show',    ['AuthMiddleware', 'RoleMiddleware:shop,read']);
$router->put('/api/v1/pooja-bookings/{id}',     App\Controllers\Api\PoojaBookingController::class, 'update',  ['AuthMiddleware', 'RoleMiddleware:shop,update']);
$router->delete('/api/v1/pooja-bookings/{id}',  App\Controllers\Api\PoojaBookingController::class, 'destroy', ['AuthMiddleware', 'RoleMiddleware:shop,delete']);

// --- Shop Enquiries (admin view) ---
$router->get('/api/v1/shop-enquiries',          App\Controllers\Api\ShopEnquiryController::class, 'index',   ['AuthMiddleware', 'RoleMiddleware:shop,read']);
$router->get('/api/v1/shop-enquiries/{id}',     App\Controllers\Api\ShopEnquiryController::class, 'show',    ['AuthMiddleware', 'RoleMiddleware:shop,read']);
$router->put('/api/v1/shop-enquiries/{id}',     App\Controllers\Api\ShopEnquiryController::class, 'update',  ['AuthMiddleware', 'RoleMiddleware:shop,update']);
$router->delete('/api/v1/shop-enquiries/{id}',  App\Controllers\Api\ShopEnquiryController::class, 'destroy', ['AuthMiddleware', 'RoleMiddleware:shop,delete']);

// --- Tours ---
$router->get('/api/v1/tours',          App\Controllers\Api\TourApiController::class, 'index',   ['AuthMiddleware', 'RoleMiddleware:tours,read']);
$router->get('/api/v1/tours/{id}',     App\Controllers\Api\TourApiController::class, 'show',    ['AuthMiddleware', 'RoleMiddleware:tours,read']);
$router->post('/api/v1/tours',         App\Controllers\Api\TourApiController::class, 'store',   ['AuthMiddleware', 'RoleMiddleware:tours,create']);
$router->put('/api/v1/tours/{id}',     App\Controllers\Api\TourApiController::class, 'update',  ['AuthMiddleware', 'RoleMiddleware:tours,update']);
$router->delete('/api/v1/tours/{id}',  App\Controllers\Api\TourApiController::class, 'destroy', ['AuthMiddleware', 'RoleMiddleware:tours,delete']);

// --- Tour Bookings (admin view) ---
$router->get('/api/v1/tour-bookings',          App\Controllers\Api\TourBookingController::class, 'index',   ['AuthMiddleware', 'RoleMiddleware:tours,read']);
$router->get('/api/v1/tour-bookings/{id}',     App\Controllers\Api\TourBookingController::class, 'show',    ['AuthMiddleware', 'RoleMiddleware:tours,read']);
$router->put('/api/v1/tour-bookings/{id}',     App\Controllers\Api\TourBookingController::class, 'update',  ['AuthMiddleware', 'RoleMiddleware:tours,update']);

// --- Delivery Zones (admin CRUD + public check) ---
$router->get('/api/v1/delivery-zones/check',    App\Controllers\Api\DeliveryZoneController::class, 'check');
$router->get('/api/v1/delivery-zones',          App\Controllers\Api\DeliveryZoneController::class, 'index',   ['AuthMiddleware', 'RoleMiddleware:shop,read']);
$router->get('/api/v1/delivery-zones/{id}',     App\Controllers\Api\DeliveryZoneController::class, 'show',    ['AuthMiddleware', 'RoleMiddleware:shop,read']);
$router->post('/api/v1/delivery-zones',         App\Controllers\Api\DeliveryZoneController::class, 'store',   ['AuthMiddleware', 'RoleMiddleware:shop,create']);
$router->put('/api/v1/delivery-zones/{id}',     App\Controllers\Api\DeliveryZoneController::class, 'update',  ['AuthMiddleware', 'RoleMiddleware:shop,update']);
$router->delete('/api/v1/delivery-zones/{id}',  App\Controllers\Api\DeliveryZoneController::class, 'destroy', ['AuthMiddleware', 'RoleMiddleware:shop,delete']);

// --- Shop Orders (admin view) ---
$router->get('/api/v1/shop-orders',          App\Controllers\Api\ShopOrderController::class, 'index',   ['AuthMiddleware', 'RoleMiddleware:shop,read']);
$router->get('/api/v1/shop-orders/{id}',     App\Controllers\Api\ShopOrderController::class, 'show',    ['AuthMiddleware', 'RoleMiddleware:shop,read']);
$router->put('/api/v1/shop-orders/{id}',     App\Controllers\Api\ShopOrderController::class, 'update',  ['AuthMiddleware', 'RoleMiddleware:shop,update']);

// --- Translation Proxy (LibreTranslate) ---
$router->post('/api/v1/translate', App\Controllers\Api\TranslateController::class, 'translate', ['AuthMiddleware']);
