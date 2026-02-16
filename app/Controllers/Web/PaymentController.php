<?php

namespace App\Controllers\Web;

use App\Core\Controller;
use App\Core\Database;
use App\Helpers\EnvLoader;

class PaymentController extends Controller
{
    /**
     * POST /donations/create-order
     * Creates a Razorpay order and returns order_id + key for frontend checkout.
     */
    public function createOrder(): void
    {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents('php://input'), true);
        $amount = (int) ($data['amount'] ?? 0);

        if ($amount < 10) {
            http_response_code(422);
            echo json_encode(['error' => 'Minimum donation is Rs.10']);
            exit;
        }

        $keyId = EnvLoader::get('RAZORPAY_KEY_ID');
        $keySecret = EnvLoader::get('RAZORPAY_KEY_SECRET');
        $currency = EnvLoader::get('RAZORPAY_CURRENCY', 'INR');

        // Create order via Razorpay Orders API
        $orderData = [
            'amount'   => $amount * 100, // Razorpay expects paise
            'currency' => $currency,
            'receipt'  => 'donation_' . time(),
            'notes'    => [
                'donor_name'  => $data['first_name'] ?? '',
                'donor_email' => $data['email'] ?? '',
                'donor_phone' => $data['phone'] ?? '',
                'comment'     => $data['comment'] ?? '',
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
            echo json_encode(['error' => 'Failed to create payment order', 'details' => $response]);
            exit;
        }

        $order = json_decode($response, true);

        echo json_encode([
            'order_id' => $order['id'],
            'amount'   => $amount,
            'currency' => $currency,
            'key'      => $keyId,
            'name'     => 'Sri Sai Mission',
            'description' => 'Donation to Sri Sai Mission Trust',
        ]);
        exit;
    }

    /**
     * POST /donations/verify
     * Verifies Razorpay payment signature and saves donation record.
     */
    public function verify(): void
    {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents('php://input'), true);

        $orderId   = $data['razorpay_order_id'] ?? '';
        $paymentId = $data['razorpay_payment_id'] ?? '';
        $signature = $data['razorpay_signature'] ?? '';

        $keySecret = EnvLoader::get('RAZORPAY_KEY_SECRET');

        // Verify signature
        $expectedSignature = hash_hmac('sha256', $orderId . '|' . $paymentId, $keySecret);

        if ($expectedSignature !== $signature) {
            http_response_code(400);
            echo json_encode(['error' => 'Payment verification failed']);
            exit;
        }

        // Save donation to database
        $db = Database::getInstance();
        $db->query(
            "INSERT INTO donations (donor_name, donor_email, donor_phone, amount, payment_method, transaction_id, status, notes, purpose)
             VALUES (?, ?, ?, ?, 'online', ?, 'completed', ?, 'Donation')",
            [
                trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? '')),
                $data['email'] ?? '',
                $data['phone'] ?? '',
                $data['amount'] ?? 0,
                $paymentId,
                $data['comment'] ?? '',
            ]
        );

        echo json_encode([
            'success' => true,
            'message' => 'Thank you for your generous donation!',
            'payment_id' => $paymentId,
        ]);
        exit;
    }
}
