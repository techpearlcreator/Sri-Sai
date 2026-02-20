<?php

namespace App\Controllers\Web;

use App\Core\Controller;
use App\Core\Database;
use App\Helpers\EnvLoader;
use App\Models\Tour;
use App\Models\TourBooking;

class TourController extends Controller
{
    /**
     * GET /tours
     */
    public function index(): void
    {
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 9;

        $result = Tour::where('is_active', 1)
            ->orderBy('start_date')
            ->paginate($page, $perPage);

        $totalPages = ceil($result['total'] / $result['per_page']);

        $this->render('tours.index', [
            'pageTitle'   => 'Tours & Trips — Sri Sai Mission',
            'pageClass'   => 'tours',
            'tours'       => $result['data'],
            'pagination'  => [
                'page'        => $result['page'],
                'total_pages' => $totalPages,
            ],
        ]);
    }

    /**
     * GET /tours/{slug}
     */
    public function show(string $slug): void
    {
        $tour = Tour::findBySlug($slug);
        if (!$tour) {
            http_response_code(404);
            $this->render('errors.404', ['pageTitle' => 'Not Found', 'pageClass' => 'error']);
            return;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $loggedIn = !empty($_SESSION['public_user']);
        $user = $loggedIn ? $_SESSION['public_user'] : null;

        $seatsLeft = (int) $tour->max_seats - (int) $tour->booked_seats;

        $this->render('tours.show', [
            'pageTitle'  => htmlspecialchars($tour->title) . ' — Tours',
            'pageClass'  => 'tours',
            'tour'       => $tour,
            'seatsLeft'  => $seatsLeft,
            'loggedIn'   => $loggedIn,
            'currentUser' => $user,
        ]);
    }

    /**
     * POST /tours/create-order (Razorpay)
     */
    public function createOrder(): void
    {
        header('Content-Type: application/json');

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $userId = $_SESSION['public_user']['id'] ?? null;
        if (!$userId) {
            http_response_code(401);
            echo json_encode(['error' => 'Please login to book a tour']);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $tourId = (int) ($data['tour_id'] ?? 0);
        $seats  = max(1, (int) ($data['seats'] ?? 1));

        $tour = Tour::find($tourId);
        if (!$tour || !$tour->is_active || $tour->status !== 'upcoming') {
            http_response_code(400);
            echo json_encode(['error' => 'Tour is not available for booking']);
            exit;
        }

        $seatsLeft = (int) $tour->max_seats - (int) $tour->booked_seats;
        if ($seats > $seatsLeft) {
            http_response_code(400);
            echo json_encode(['error' => "Only {$seatsLeft} seats available"]);
            exit;
        }

        $totalAmount = $seats * (float) $tour->price_per_person;

        $keyId = EnvLoader::get('RAZORPAY_KEY_ID');
        $keySecret = EnvLoader::get('RAZORPAY_KEY_SECRET');
        $currency = EnvLoader::get('RAZORPAY_CURRENCY', 'INR');

        $orderData = [
            'amount'   => (int) ($totalAmount * 100),
            'currency' => $currency,
            'receipt'  => 'tour_' . $tourId . '_' . time(),
            'notes'    => [
                'tour_id'   => $tourId,
                'user_id'   => $userId,
                'seats'     => $seats,
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
            echo json_encode(['error' => 'Failed to create payment order']);
            exit;
        }

        $order = json_decode($response, true);

        // Create pending booking record
        $booking = TourBooking::create([
            'tour_id'           => $tourId,
            'user_id'           => $userId,
            'seats'             => $seats,
            'total_amount'      => $totalAmount,
            'razorpay_order_id' => $order['id'],
            'payment_status'    => 'pending',
        ]);

        echo json_encode([
            'order_id'    => $order['id'],
            'booking_id'  => (int) $booking->id,
            'amount'      => $totalAmount,
            'currency'    => $currency,
            'key'         => $keyId,
            'name'        => 'Sri Sai Mission',
            'description' => "Tour Booking: {$tour->title} ({$seats} seat" . ($seats > 1 ? 's' : '') . ")",
        ]);
        exit;
    }

    /**
     * POST /tours/verify (Razorpay)
     */
    public function verify(): void
    {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents('php://input'), true);

        $orderId   = $data['razorpay_order_id'] ?? '';
        $paymentId = $data['razorpay_payment_id'] ?? '';
        $signature = $data['razorpay_signature'] ?? '';
        $bookingId = (int) ($data['booking_id'] ?? 0);

        $keySecret = EnvLoader::get('RAZORPAY_KEY_SECRET');
        $expectedSignature = hash_hmac('sha256', $orderId . '|' . $paymentId, $keySecret);

        if ($expectedSignature !== $signature) {
            // Mark booking as failed
            if ($bookingId) {
                TourBooking::update($bookingId, ['payment_status' => 'failed']);
            }
            http_response_code(400);
            echo json_encode(['error' => 'Payment verification failed']);
            exit;
        }

        // Update booking with payment details
        $booking = TourBooking::find($bookingId);
        if (!$booking) {
            http_response_code(400);
            echo json_encode(['error' => 'Booking not found']);
            exit;
        }

        TourBooking::update($bookingId, [
            'razorpay_payment_id' => $paymentId,
            'razorpay_signature'  => $signature,
            'payment_status'      => 'paid',
        ]);

        // Increment booked_seats on the tour
        Tour::increment((int) $booking->tour_id, 'booked_seats', (int) $booking->seats);

        echo json_encode([
            'success'    => true,
            'message'    => 'Tour booked successfully! Check your profile for booking details.',
            'payment_id' => $paymentId,
        ]);
        exit;
    }
}
