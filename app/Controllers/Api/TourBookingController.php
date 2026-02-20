<?php

namespace App\Controllers\Api;

use App\Core\Controller;
use App\Helpers\Response;
use App\Models\TourBooking;
use App\Models\Tour;
use App\Services\ActivityLogger;

class TourBookingController extends Controller
{
    public function index(): void
    {
        $page = max(1, (int) $this->getQuery('page', 1));
        $perPage = min(50, max(1, (int) $this->getQuery('per_page', 15)));

        $query = TourBooking::query()
            ->select('tour_bookings.*, tours.title as tour_title, public_users.name as user_name, public_users.phone as user_phone')
            ->join('tours', 'tour_bookings.tour_id = tours.id')
            ->join('public_users', 'tour_bookings.user_id = public_users.id');

        $status = $this->getQuery('payment_status');
        if ($status && in_array($status, ['pending', 'paid', 'failed', 'refunded'])) {
            $query->andWhere('tour_bookings.payment_status', $status);
        }

        $tourId = $this->getQuery('tour_id');
        if ($tourId) {
            $query->andWhere('tour_bookings.tour_id', '=', (int) $tourId);
        }

        $query->orderByRaw('tour_bookings.created_at DESC');
        $result = $query->paginate($page, $perPage);

        Response::paginated($result['data'], $result['total'], $result['page'], $result['per_page']);
    }

    public function show(string $id): void
    {
        $booking = TourBooking::findOrFail((int) $id);
        $this->json($booking);
    }

    public function update(string $id): void
    {
        $user = $this->getAuthUser();
        $booking = TourBooking::findOrFail((int) $id);
        $data = $this->getJsonBody();

        if (isset($data['status']) && $data['status'] === 'cancelled' && $booking->status !== 'cancelled') {
            TourBooking::update((int) $id, ['status' => 'cancelled']);

            // Return seats if payment was completed
            if ($booking->payment_status === 'paid') {
                Tour::decrement((int) $booking->tour_id, 'booked_seats', (int) $booking->seats);
            }

            ActivityLogger::log((int) $user->id, 'update', 'tour_booking', (int) $id, "Cancelled tour booking");
        }

        $this->json(['message' => 'Tour booking updated successfully']);
    }
}
