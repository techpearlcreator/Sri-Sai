<?php

namespace App\Controllers\Api;

use App\Core\Controller;
use App\Helpers\Response;
use App\Helpers\Validator;
use App\Models\Event;
use App\Services\ActivityLogger;
use App\Services\SlugService;

class EventController extends Controller
{
    public function index(): void
    {
        $page = max(1, (int) $this->getQuery('page', 1));
        $perPage = min(50, max(1, (int) $this->getQuery('per_page', 15)));

        $query = Event::query()->orderBy('event_date', 'DESC');

        $status = $this->getQuery('status');
        if ($status && in_array($status, ['upcoming', 'ongoing', 'completed', 'cancelled'])) {
            $query->where('status', '=', $status);
        }

        if ($this->getQuery('is_featured') === '1') {
            $query->where('is_featured', '=', 1);
        }

        $search = $this->getQuery('search');
        if ($search) {
            $query->search(['title', 'location'], $search);
        }

        $result = $query->paginate($page, $perPage);

        $items = array_map(fn($e) => [
            'id'             => (int) $e->id,
            'title'          => $e->title,
            'slug'           => $e->slug,
            'featured_image' => $e->featured_image,
            'event_date'     => $e->event_date,
            'event_time'     => $e->event_time,
            'end_date'       => $e->end_date,
            'location'       => $e->location,
            'status'         => $e->status,
            'is_featured'    => (bool) $e->is_featured,
            'is_recurring'   => (bool) $e->is_recurring,
        ], $result['data']);

        Response::paginated($items, $result['total'], $result['page'], $result['per_page']);
    }

    public function show(string $id): void
    {
        $event = Event::find((int) $id);
        if (!$event) {
            Response::error(404, 'NOT_FOUND', 'Event not found');
        }

        $this->json([
            'id'              => (int) $event->id,
            'title'           => $event->title,
            'slug'            => $event->slug,
            'description'     => $event->description,
            'featured_image'  => $event->featured_image,
            'event_date'      => $event->event_date,
            'event_time'      => $event->event_time,
            'end_date'        => $event->end_date,
            'end_time'        => $event->end_time,
            'location'        => $event->location,
            'is_recurring'    => (bool) $event->is_recurring,
            'recurrence_rule' => $event->recurrence_rule,
            'status'          => $event->status,
            'is_featured'     => (bool) $event->is_featured,
            'created_by'      => (int) $event->created_by,
            'created_at'      => $event->created_at,
            'updated_at'      => $event->updated_at,
        ]);
    }

    public function store(): void
    {
        $user = $this->getAuthUser();
        $data = $this->getJsonBody();

        $v = new Validator($data, [
            'title'           => 'required|string|min:5|max:255',
            'description'     => 'nullable|string',
            'featured_image'  => 'nullable|string|max:255',
            'event_date'      => 'required|date',
            'event_time'      => 'nullable|time',
            'end_date'        => 'nullable|date',
            'end_time'        => 'nullable|time',
            'location'        => 'nullable|string|max:255',
            'is_recurring'    => 'nullable|boolean',
            'recurrence_rule' => 'nullable|string|max:100',
            'status'          => 'nullable|in:upcoming,ongoing,completed,cancelled',
            'is_featured'     => 'nullable|boolean',
        ]);

        if ($v->fails()) {
            Response::error(422, 'VALIDATION_ERROR', 'Invalid input', $v->errors());
        }

        $slug = SlugService::slugify($data['title'], 'events');

        $event = Event::create([
            'title'           => $data['title'],
            'slug'            => $slug,
            'description'     => $data['description'] ?? null,
            'featured_image'  => $data['featured_image'] ?? null,
            'event_date'      => $data['event_date'],
            'event_time'      => $data['event_time'] ?? null,
            'end_date'        => $data['end_date'] ?? null,
            'end_time'        => $data['end_time'] ?? null,
            'location'        => $data['location'] ?? null,
            'is_recurring'    => $data['is_recurring'] ?? 0,
            'recurrence_rule' => $data['recurrence_rule'] ?? null,
            'created_by'      => (int) $user->id,
            'status'          => $data['status'] ?? 'upcoming',
            'is_featured'     => $data['is_featured'] ?? 0,
        ]);

        ActivityLogger::log((int) $user->id, 'create', 'event', (int) $event->id, "Created event: {$event->title}");
        $this->json(['id' => (int) $event->id, 'title' => $event->title, 'slug' => $event->slug], 201);
    }

    public function update(string $id): void
    {
        $user = $this->getAuthUser();
        $event = Event::find((int) $id);
        if (!$event) {
            Response::error(404, 'NOT_FOUND', 'Event not found');
        }

        $data = $this->getJsonBody();
        $updateData = [];
        foreach (['title', 'description', 'featured_image', 'event_date', 'event_time', 'end_date', 'end_time', 'location', 'is_recurring', 'recurrence_rule', 'status', 'is_featured'] as $field) {
            if (array_key_exists($field, $data)) {
                $updateData[$field] = $data[$field];
            }
        }

        if (isset($updateData['title']) && $updateData['title'] !== $event->title) {
            $updateData['slug'] = SlugService::slugify($updateData['title'], 'events');
        }

        if (!empty($updateData)) {
            Event::update((int) $id, $updateData);
        }

        ActivityLogger::log((int) $user->id, 'update', 'event', (int) $id, "Updated event: {$event->title}");
        $this->json(['message' => 'Event updated successfully']);
    }

    public function destroy(string $id): void
    {
        $user = $this->getAuthUser();
        $event = Event::find((int) $id);
        if (!$event) {
            Response::error(404, 'NOT_FOUND', 'Event not found');
        }

        Event::destroy((int) $id);
        ActivityLogger::log((int) $user->id, 'delete', 'event', (int) $id, "Deleted event: {$event->title}");
        $this->json(['message' => 'Event deleted successfully']);
    }
}
