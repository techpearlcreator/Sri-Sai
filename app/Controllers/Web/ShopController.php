<?php

namespace App\Controllers\Web;

use App\Core\Controller;
use App\Helpers\EnvLoader;
use App\Models\Product;
use App\Models\PoojaType;
use App\Models\PoojaBooking;
use App\Models\ShopEnquiry;
use App\Models\ShopOrder;
use App\Models\DeliveryZone;
use App\Models\Setting;

class ShopController extends Controller
{
    /**
     * GET /shop
     */
    public function index(): void
    {
        $category = $_GET['category'] ?? 'all';
        $products = Product::activeByCategory($category);

        $this->render('shop.index', [
            'pageTitle'       => 'Shop — Sri Sai Mission',
            'pageClass'       => 'shop',
            'products'        => $products,
            'activeCategory'  => $category,
        ]);
    }

    /**
     * GET /shop/{slug}
     */
    public function show(string $slug): void
    {
        $product = Product::findBySlug($slug);
        if (!$product) {
            http_response_code(404);
            $this->render('errors.404', ['pageTitle' => 'Not Found', 'pageClass' => 'error']);
            return;
        }

        $this->render('shop.show', [
            'pageTitle' => htmlspecialchars($product->name) . ' — Shop',
            'pageClass' => 'shop',
            'product'   => $product,
        ]);
    }

    /**
     * GET /shop/delivery-charge?lat=12.97&lng=80.14
     * Public JSON — calculates distance from shop origin to customer location,
     * finds matching delivery zone, returns charge.
     */
    public function checkDelivery(): void
    {
        header('Content-Type: application/json');

        $lat = isset($_GET['lat']) ? (float) $_GET['lat'] : null;
        $lng = isset($_GET['lng']) ? (float) $_GET['lng'] : null;

        if ($lat === null || $lng === null || ($lat === 0.0 && $lng === 0.0)) {
            echo json_encode(['success' => false, 'error' => 'Please pick a delivery location on the map']);
            exit;
        }

        // Load shop dispatch coordinates from Settings
        $originLat = (float) (Setting::getValue('shop_dispatch_lat') ?? 12.97350);
        $originLng = (float) (Setting::getValue('shop_dispatch_lng') ?? 80.14840);

        $distanceKm = DeliveryZone::haversineKm($originLat, $originLng, $lat, $lng);

        $zone = DeliveryZone::findByDistance($distanceKm);
        if (!$zone) {
            echo json_encode([
                'success'     => false,
                'error'       => 'Sorry, we do not deliver to this location yet (distance: ' . round($distanceKm, 1) . ' km).',
                'distance_km' => round($distanceKm, 2),
            ]);
            exit;
        }

        echo json_encode([
            'success'     => true,
            'zone_id'     => (int) $zone->id,
            'zone_name'   => $zone->name,
            'charge'      => (float) $zone->charge,
            'distance_km' => round($distanceKm, 2),
        ]);
        exit;
    }

    /**
     * POST /shop/create-order
     * Creates a Razorpay order and a pending shop_order record.
     */
    public function createOrder(): void
    {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents('php://input'), true);

        $productId   = (int) ($data['product_id'] ?? 0);
        $quantity    = max(1, (int) ($data['quantity'] ?? 1));
        $name        = trim($data['name'] ?? '');
        $phone       = trim($data['phone'] ?? '');
        $email       = trim($data['email'] ?? '');
        $address     = trim($data['address'] ?? '');
        $zoneId      = (int) ($data['zone_id'] ?? 0);
        $delivery    = (float) ($data['delivery_charge'] ?? 0);
        $lat         = isset($data['lat']) ? (float) $data['lat'] : null;
        $lng         = isset($data['lng']) ? (float) $data['lng'] : null;
        $distanceKm  = isset($data['distance_km']) ? (float) $data['distance_km'] : null;

        if (!$productId || $name === '' || $phone === '' || $address === '') {
            http_response_code(422);
            echo json_encode(['error' => 'Name, phone and address are required']);
            exit;
        }

        if (!$lat || !$lng) {
            http_response_code(422);
            echo json_encode(['error' => 'Please pick a delivery location on the map']);
            exit;
        }

        $product = Product::find($productId);
        if (!$product || !$product->is_active) {
            http_response_code(400);
            echo json_encode(['error' => 'Product not available']);
            exit;
        }

        if ((int) $product->stock_qty < $quantity) {
            http_response_code(400);
            echo json_encode(['error' => 'Not enough stock available']);
            exit;
        }

        $productPrice = (float) $product->price;
        $total        = ($productPrice * $quantity) + $delivery;

        // Create Razorpay order
        $keyId     = EnvLoader::get('RAZORPAY_KEY_ID');
        $keySecret = EnvLoader::get('RAZORPAY_KEY_SECRET');
        $currency  = EnvLoader::get('RAZORPAY_CURRENCY', 'INR');

        $orderData = [
            'amount'   => (int) ($total * 100),
            'currency' => $currency,
            'receipt'  => 'shop_' . $productId . '_' . time(),
            'notes'    => [
                'product_id'  => $productId,
                'distance_km' => $distanceKm,
                'quantity'    => $quantity,
            ],
        ];

        $ch = curl_init('https://api.razorpay.com/v1/orders');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($orderData),
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_USERPWD        => "{$keyId}:{$keySecret}",
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to create payment order. Please try again.']);
            exit;
        }

        $rzpOrder = json_decode($response, true);

        // Save pending order record
        $order = ShopOrder::create([
            'product_id'       => $productId,
            'customer_name'    => $name,
            'customer_phone'   => $phone,
            'customer_email'   => $email,
            'delivery_address' => $address,
            'lat'              => $lat,
            'lng'              => $lng,
            'distance_km'      => $distanceKm,
            'zone_id'          => $zoneId ?: null,
            'quantity'         => $quantity,
            'product_price'    => $productPrice,
            'delivery_charge'  => $delivery,
            'total_amount'     => $total,
            'payment_status'   => 'pending',
            'razorpay_order_id' => $rzpOrder['id'],
            'order_status'     => 'pending',
        ]);

        echo json_encode([
            'order_id'    => $rzpOrder['id'],
            'shop_order_id' => (int) $order->id,
            'amount'      => $total,
            'currency'    => $currency,
            'key'         => $keyId,
            'name'        => 'Sri Sai Mission',
            'description' => htmlspecialchars($product->name) . ' × ' . $quantity,
            'prefill'     => ['name' => $name, 'contact' => $phone, 'email' => $email],
        ]);
        exit;
    }

    /**
     * POST /shop/verify-payment
     * Verifies Razorpay signature and confirms the order.
     */
    public function verifyPayment(): void
    {
        header('Content-Type: application/json');

        $data      = json_decode(file_get_contents('php://input'), true);
        $orderId   = $data['razorpay_order_id'] ?? '';
        $paymentId = $data['razorpay_payment_id'] ?? '';
        $signature = $data['razorpay_signature'] ?? '';
        $shopOrderId = (int) ($data['shop_order_id'] ?? 0);

        $keySecret = EnvLoader::get('RAZORPAY_KEY_SECRET');
        $expected  = hash_hmac('sha256', $orderId . '|' . $paymentId, $keySecret);

        if ($expected !== $signature) {
            if ($shopOrderId) {
                ShopOrder::update($shopOrderId, ['payment_status' => 'failed']);
            }
            http_response_code(400);
            echo json_encode(['error' => 'Payment verification failed. Please contact support.']);
            exit;
        }

        $order = ShopOrder::find($shopOrderId);
        if (!$order) {
            http_response_code(400);
            echo json_encode(['error' => 'Order not found']);
            exit;
        }

        ShopOrder::update($shopOrderId, [
            'razorpay_payment_id' => $paymentId,
            'razorpay_signature'  => $signature,
            'payment_status'      => 'paid',
            'order_status'        => 'confirmed',
        ]);

        // Deduct stock
        $product = Product::find((int) $order->product_id);
        if ($product) {
            $newStock = max(0, (int) $product->stock_qty - (int) $order->quantity);
            Product::update((int) $product->id, ['stock_qty' => $newStock]);
        }

        echo json_encode([
            'success'    => true,
            'message'    => 'Order placed successfully! We will contact you for delivery.',
            'payment_id' => $paymentId,
            'order_id'   => $shopOrderId,
        ]);
        exit;
    }

    /**
     * POST /shop/enquiry  (kept for backward compatibility)
     */
    public function submitEnquiry(): void
    {
        header('Content-Type: application/json');

        $data       = json_decode(file_get_contents('php://input'), true);
        $name       = trim($data['name'] ?? '');
        $email      = trim($data['email'] ?? '');
        $phone      = trim($data['phone'] ?? '');
        $productId  = (int) ($data['product_id'] ?? 0);
        $quantity   = max(1, (int) ($data['quantity'] ?? 1));
        $message    = trim($data['message'] ?? '');

        if ($name === '' || $phone === '' || $productId === 0) {
            http_response_code(422);
            echo json_encode(['error' => 'Name, phone, and product are required']);
            exit;
        }

        ShopEnquiry::create([
            'product_id' => $productId,
            'name'       => $name,
            'email'      => $email,
            'phone'      => $phone,
            'quantity'   => $quantity,
            'message'    => $message,
        ]);

        echo json_encode(['success' => true, 'message' => 'Enquiry submitted! We will contact you shortly.']);
        exit;
    }

    /**
     * GET /pooja-booking
     */
    public function poojaBookingForm(): void
    {
        $poojaTypes = PoojaType::allActive();

        $this->render('shop.pooja-booking', [
            'pageTitle'   => 'Book a Pooja — Sri Sai Mission',
            'pageClass'   => 'shop',
            'poojaTypes'  => $poojaTypes,
        ]);
    }

    /**
     * POST /pooja-booking/create-order
     * Creates a Razorpay order and a pending pooja booking record.
     */
    public function createPoojaOrder(): void
    {
        header('Content-Type: application/json');

        $data          = json_decode(file_get_contents('php://input'), true);
        $name          = trim($data['name'] ?? '');
        $email         = trim($data['email'] ?? '');
        $phone         = trim($data['phone'] ?? '');
        $temple        = $data['temple'] ?? '';
        $poojaTypeId   = (int) ($data['pooja_type_id'] ?? 0);
        $preferredDate = $data['preferred_date'] ?? '';
        $notes         = trim($data['notes'] ?? '');
        $numPersons    = max(1, (int) ($data['num_persons'] ?? 1));

        if ($name === '' || $phone === '' || $temple === '' || $poojaTypeId === 0 || $preferredDate === '') {
            http_response_code(422);
            echo json_encode(['error' => 'Please fill all required fields']);
            exit;
        }

        if (!in_array($temple, ['perungalathur', 'keerapakkam'])) {
            http_response_code(422);
            echo json_encode(['error' => 'Invalid temple selection']);
            exit;
        }

        $poojaType = PoojaType::find($poojaTypeId);
        if (!$poojaType || !$poojaType->is_active) {
            http_response_code(400);
            echo json_encode(['error' => 'Selected pooja is not available']);
            exit;
        }

        $amount    = (float) $poojaType->price;
        $keyId     = EnvLoader::get('RAZORPAY_KEY_ID');
        $keySecret = EnvLoader::get('RAZORPAY_KEY_SECRET');
        $currency  = EnvLoader::get('RAZORPAY_CURRENCY', 'INR');

        $orderData = [
            'amount'   => (int) ($amount * 100),
            'currency' => $currency,
            'receipt'  => 'pooja_' . $poojaTypeId . '_' . time(),
            'notes'    => ['pooja_type_id' => $poojaTypeId, 'num_persons' => $numPersons],
        ];

        $ch = curl_init('https://api.razorpay.com/v1/orders');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($orderData),
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_USERPWD        => "{$keyId}:{$keySecret}",
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to create payment order. Please try again.']);
            exit;
        }

        $rzpOrder = json_decode($response, true);

        $booking = PoojaBooking::create([
            'pooja_type_id'     => $poojaTypeId,
            'temple'            => $temple,
            'name'              => $name,
            'email'             => $email,
            'phone'             => $phone,
            'preferred_date'    => $preferredDate,
            'notes'             => $notes,
            'num_persons'       => $numPersons,
            'razorpay_order_id' => $rzpOrder['id'],
            'payment_status'    => 'pending',
            'status'            => 'pending',
        ]);

        echo json_encode([
            'order_id'    => $rzpOrder['id'],
            'booking_id'  => (int) $booking->id,
            'amount'      => $amount,
            'currency'    => $currency,
            'key'         => $keyId,
            'name'        => 'Sri Sai Mission',
            'description' => htmlspecialchars($poojaType->name),
            'prefill'     => ['name' => $name, 'contact' => $phone, 'email' => $email],
        ]);
        exit;
    }

    /**
     * POST /pooja-booking/verify
     * Verifies Razorpay signature and confirms the pooja booking.
     */
    public function verifyPoojaPayment(): void
    {
        header('Content-Type: application/json');

        $data      = json_decode(file_get_contents('php://input'), true);
        $orderId   = $data['razorpay_order_id'] ?? '';
        $paymentId = $data['razorpay_payment_id'] ?? '';
        $signature = $data['razorpay_signature'] ?? '';
        $bookingId = (int) ($data['booking_id'] ?? 0);

        $keySecret = EnvLoader::get('RAZORPAY_KEY_SECRET');
        $expected  = hash_hmac('sha256', $orderId . '|' . $paymentId, $keySecret);

        if ($expected !== $signature) {
            if ($bookingId) {
                PoojaBooking::update($bookingId, ['payment_status' => 'failed']);
            }
            http_response_code(400);
            echo json_encode(['error' => 'Payment verification failed. Please contact support.']);
            exit;
        }

        PoojaBooking::update($bookingId, [
            'razorpay_payment_id' => $paymentId,
            'razorpay_signature'  => $signature,
            'payment_status'      => 'paid',
            'status'              => 'confirmed',
        ]);

        echo json_encode([
            'success'    => true,
            'message'    => 'Pooja booked successfully! Your booking is confirmed. We will contact you shortly.',
            'booking_id' => $bookingId,
        ]);
        exit;
    }
}
