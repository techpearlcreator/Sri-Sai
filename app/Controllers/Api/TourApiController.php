<?php

namespace App\Controllers\Api;

use App\Core\Controller;
use App\Helpers\Response;
use App\Helpers\Validator;
use App\Models\Tour;
use App\Services\ActivityLogger;
use App\Services\SlugService;

class TourApiController extends Controller
{
    public function index(): void
    {
        $page = max(1, (int) $this->getQuery('page', 1));
        $perPage = min(50, max(1, (int) $this->getQuery('per_page', 15)));

        $query = Tour::query();

        $status = $this->getQuery('status');
        if ($status && in_array($status, ['upcoming', 'ongoing', 'completed', 'cancelled'])) {
            $query->andWhere('status', $status);
        }

        $query->orderByRaw('start_date DESC');
        $result = $query->paginate($page, $perPage);

        Response::paginated($result['data'], $result['total'], $result['page'], $result['per_page']);
    }

    public function show(string $id): void
    {
        $tour = Tour::findOrFail((int) $id);
        $this->json($tour);
    }

    public function store(): void
    {
        $user = $this->getAuthUser();
        $data = $this->getJsonBody();

        $v = new Validator($data, [
            'title'            => 'required|string|min:5|max:255',
            'destination'      => 'required|string|max:255',
            'description'      => 'nullable|string',
            'featured_image'   => 'nullable|string|max:255',
            'start_date'       => 'required|string',
            'end_date'         => 'required|string',
            'price_per_person' => 'required|numeric',
            'max_seats'        => 'required|integer',
            'status'           => 'nullable|in:upcoming,ongoing,completed,cancelled',
            'is_active'        => 'nullable|boolean',
        ]);

        if ($v->fails()) {
            Response::error(422, 'VALIDATION_ERROR', 'Invalid input', $v->errors());
        }

        $slug = SlugService::slugify($data['title'], 'tours');

        $tour = Tour::create([
            'title'            => $data['title'],
            'slug'             => $slug,
            'destination'      => $data['destination'],
            'description'      => $data['description'] ?? null,
            'title_ta'         => $data['title_ta'] ?? null,
            'destination_ta'   => $data['destination_ta'] ?? null,
            'description_ta'   => $data['description_ta'] ?? null,
            'featured_image'   => $data['featured_image'] ?? null,
            'start_date'       => $data['start_date'],
            'end_date'         => $data['end_date'],
            'price_per_person' => $data['price_per_person'],
            'max_seats'        => $data['max_seats'],
            'booked_seats'     => 0,
            'status'           => $data['status'] ?? 'upcoming',
            'is_active'        => $data['is_active'] ?? 1,
        ]);

        ActivityLogger::log((int) $user->id, 'create', 'tour', (int) $tour->id, "Created tour: {$tour->title}");
        $this->json($tour, 201);
    }

    public function update(string $id): void
    {
        $user = $this->getAuthUser();
        $tour = Tour::findOrFail((int) $id);
        $data = $this->getJsonBody();

        $updateData = [];
        foreach (['title', 'title_ta', 'destination', 'destination_ta', 'description', 'description_ta', 'featured_image', 'start_date', 'end_date', 'price_per_person', 'max_seats', 'status', 'is_active'] as $field) {
            if (array_key_exists($field, $data)) {
                $updateData[$field] = $data[$field];
            }
        }

        if (isset($updateData['title']) && $updateData['title'] !== $tour->title) {
            $updateData['slug'] = SlugService::slugify($updateData['title'], 'tours');
        }

        if (!empty($updateData)) {
            Tour::update((int) $id, $updateData);
        }

        ActivityLogger::log((int) $user->id, 'update', 'tour', (int) $id, "Updated tour: {$tour->title}");
        $this->json(['message' => 'Tour updated successfully']);
    }

    public function destroy(string $id): void
    {
        $user = $this->getAuthUser();
        $tour = Tour::findOrFail((int) $id);
        Tour::destroy((int) $id);
        ActivityLogger::log((int) $user->id, 'delete', 'tour', (int) $id, "Deleted tour: {$tour->title}");
        $this->json(['message' => 'Tour deleted successfully']);
    }
}
