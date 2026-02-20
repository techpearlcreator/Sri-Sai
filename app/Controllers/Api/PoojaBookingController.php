<?php

namespace App\Controllers\Api;

use App\Core\Controller;
use App\Helpers\Response;
use App\Models\PoojaBooking;
use App\Services\ActivityLogger;

class PoojaBookingController extends Controller
{
    public function index(): void
    {
        $page = max(1, (int) $this->getQuery('page', 1));
        $perPage = min(50, max(1, (int) $this->getQuery('per_page', 15)));

        $query = PoojaBooking::query()
            ->select('pooja_bookings.*, pooja_types.name as pooja_name')
            ->join('pooja_types', 'pooja_bookings.pooja_type_id = pooja_types.id');

        $status = $this->getQuery('status');
        if ($status && in_array($status, ['pending', 'confirmed', 'completed', 'cancelled'])) {
            $query->andWhere('pooja_bookings.status', $status);
        }

        $temple = $this->getQuery('temple');
        if ($temple && in_array($temple, ['perungalathur', 'keerapakkam'])) {
            $query->andWhere('pooja_bookings.temple', $temple);
        }

        $query->orderByRaw('pooja_bookings.created_at DESC');
        $result = $query->paginate($page, $perPage);

        Response::paginated($result['data'], $result['total'], $result['page'], $result['per_page']);
    }

    public function show(string $id): void
    {
        $booking = PoojaBooking::withPoojaType((int) $id);
        if (!$booking) {
            Response::error(404, 'NOT_FOUND', 'Pooja booking not found');
        }
        $this->json($booking);
    }

    public function update(string $id): void
    {
        $user = $this->getAuthUser();
        PoojaBooking::findOrFail((int) $id);

        $data = $this->getJsonBody();
        $updateData = [];
        if (isset($data['status']) && in_array($data['status'], ['pending', 'confirmed', 'completed', 'cancelled'])) {
            $updateData['status'] = $data['status'];
        }

        if (!empty($updateData)) {
            PoojaBooking::update((int) $id, $updateData);
        }

        ActivityLogger::log((int) $user->id, 'update', 'pooja_booking', (int) $id, "Updated pooja booking status");
        $this->json(['message' => 'Pooja booking updated successfully']);
    }

    public function destroy(string $id): void
    {
        $user = $this->getAuthUser();
        PoojaBooking::findOrFail((int) $id);
        PoojaBooking::destroy((int) $id);
        ActivityLogger::log((int) $user->id, 'delete', 'pooja_booking', (int) $id, "Deleted pooja booking");
        $this->json(['message' => 'Pooja booking deleted successfully']);
    }
}
