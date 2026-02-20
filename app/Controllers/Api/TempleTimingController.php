<?php

namespace App\Controllers\Api;

use App\Core\Controller;
use App\Helpers\Response;
use App\Helpers\Validator;
use App\Models\TempleTiming;
use App\Services\ActivityLogger;

class TempleTimingController extends Controller
{
    public function index(): void
    {
        $query = TempleTiming::query()->orderBy('sort_order')->orderBy('start_time');

        if ($this->getQuery('active_only', '0') === '1') {
            $query->where('is_active', '=', 1);
        }

        $location = $this->getQuery('location');
        if ($location) {
            $query->andWhere('location', '=', $location);
        }

        $timings = $query->get();

        $items = array_map(fn($t) => [
            'id'          => (int) $t->id,
            'title'       => $t->title,
            'day_type'    => $t->day_type,
            'start_time'  => $t->start_time,
            'end_time'    => $t->end_time,
            'description' => $t->description,
            'location'    => $t->location,
            'is_active'   => (bool) $t->is_active,
            'sort_order'  => (int) $t->sort_order,
        ], $timings);

        $this->json($items);
    }

    public function show(string $id): void
    {
        $timing = TempleTiming::find((int) $id);
        if (!$timing) {
            Response::error(404, 'NOT_FOUND', 'Temple timing not found');
        }

        $this->json([
            'id'          => (int) $timing->id,
            'title'          => $timing->title,
            'title_ta'       => $timing->title_ta,
            'day_type'    => $timing->day_type,
            'start_time'  => $timing->start_time,
            'end_time'    => $timing->end_time,
            'description'    => $timing->description,
            'description_ta' => $timing->description_ta,
            'location'    => $timing->location,
            'is_active'   => (bool) $timing->is_active,
            'sort_order'  => (int) $timing->sort_order,
            'created_at'  => $timing->created_at,
            'updated_at'  => $timing->updated_at,
        ]);
    }

    public function store(): void
    {
        $user = $this->getAuthUser();
        $data = $this->getJsonBody();

        $v = new Validator($data, [
            'title'       => 'required|string|min:3|max:100',
            'day_type'    => 'required|in:daily,monday,tuesday,wednesday,thursday,friday,saturday,sunday,special',
            'start_time'  => 'required|time',
            'end_time'    => 'required|time',
            'description' => 'nullable|string|max:255',
            'location'    => 'nullable|string|max:150',
            'is_active'   => 'nullable|boolean',
            'sort_order'  => 'nullable|integer',
        ]);

        if ($v->fails()) {
            Response::error(422, 'VALIDATION_ERROR', 'Invalid input', $v->errors());
        }

        $timing = TempleTiming::create([
            'title'       => $data['title'],
            'day_type'    => $data['day_type'],
            'start_time'  => $data['start_time'],
            'end_time'    => $data['end_time'],
            'description'    => $data['description'] ?? null,
            'title_ta'       => $data['title_ta'] ?? null,
            'description_ta' => $data['description_ta'] ?? null,
            'location'    => $data['location'] ?? null,
            'is_active'   => $data['is_active'] ?? 1,
            'sort_order'  => $data['sort_order'] ?? 0,
        ]);

        ActivityLogger::log((int) $user->id, 'create', 'temple_timing', (int) $timing->id, "Created timing: {$timing->title}");
        $this->json(['id' => (int) $timing->id, 'title' => $timing->title], 201);
    }

    public function update(string $id): void
    {
        $user = $this->getAuthUser();
        $timing = TempleTiming::find((int) $id);
        if (!$timing) {
            Response::error(404, 'NOT_FOUND', 'Temple timing not found');
        }

        $data = $this->getJsonBody();
        $updateData = [];
        foreach (['title', 'title_ta', 'day_type', 'start_time', 'end_time', 'description', 'description_ta', 'location', 'is_active', 'sort_order'] as $field) {
            if (array_key_exists($field, $data)) {
                $updateData[$field] = $data[$field];
            }
        }

        if (!empty($updateData)) {
            TempleTiming::update((int) $id, $updateData);
        }

        ActivityLogger::log((int) $user->id, 'update', 'temple_timing', (int) $id, "Updated timing: {$timing->title}");
        $this->json(['message' => 'Temple timing updated successfully']);
    }

    public function destroy(string $id): void
    {
        $user = $this->getAuthUser();
        $timing = TempleTiming::find((int) $id);
        if (!$timing) {
            Response::error(404, 'NOT_FOUND', 'Temple timing not found');
        }

        TempleTiming::destroy((int) $id);
        ActivityLogger::log((int) $user->id, 'delete', 'temple_timing', (int) $id, "Deleted timing: {$timing->title}");
        $this->json(['message' => 'Temple timing deleted successfully']);
    }
}
