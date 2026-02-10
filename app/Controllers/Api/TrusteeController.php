<?php

namespace App\Controllers\Api;

use App\Core\Controller;
use App\Core\Database;
use App\Helpers\Response;
use App\Helpers\Validator;
use App\Models\Trustee;
use App\Services\ActivityLogger;

class TrusteeController extends Controller
{
    public function index(): void
    {
        $query = Trustee::query()->orderBy('sort_order')->orderBy('name');

        $type = $this->getQuery('type');
        if ($type && in_array($type, ['main', 'co-opted'])) {
            $query->where('trustee_type', '=', $type);
        }

        if ($this->getQuery('active_only', '0') === '1') {
            $query->andWhere('is_active', '=', 1);
        }

        $trustees = $query->get();

        $items = array_map(fn($t) => [
            'id'            => (int) $t->id,
            'name'          => $t->name,
            'designation'   => $t->designation,
            'trustee_type'  => $t->trustee_type,
            'bio'           => $t->bio,
            'photo'         => $t->photo,
            'phone'         => $t->phone,
            'email'         => $t->email,
            'qualification' => $t->qualification,
            'sort_order'    => (int) $t->sort_order,
            'is_active'     => (bool) $t->is_active,
        ], $trustees);

        $this->json($items);
    }

    public function show(string $id): void
    {
        $trustee = Trustee::find((int) $id);
        if (!$trustee) {
            Response::error(404, 'NOT_FOUND', 'Trustee not found');
        }

        $this->json([
            'id'            => (int) $trustee->id,
            'name'          => $trustee->name,
            'designation'   => $trustee->designation,
            'trustee_type'  => $trustee->trustee_type,
            'bio'           => $trustee->bio,
            'photo'         => $trustee->photo,
            'phone'         => $trustee->phone,
            'email'         => $trustee->email,
            'qualification' => $trustee->qualification,
            'sort_order'    => (int) $trustee->sort_order,
            'is_active'     => (bool) $trustee->is_active,
            'created_at'    => $trustee->created_at,
            'updated_at'    => $trustee->updated_at,
        ]);
    }

    public function store(): void
    {
        $user = $this->getAuthUser();
        $data = $this->getJsonBody();

        $v = new Validator($data, [
            'name'          => 'required|string|min:2|max:150',
            'designation'   => 'required|string|max:100',
            'trustee_type'  => 'required|in:main,co-opted',
            'bio'           => 'nullable|string',
            'photo'         => 'nullable|string|max:255',
            'phone'         => 'nullable|phone',
            'email'         => 'nullable|email|max:150',
            'qualification' => 'nullable|string|max:255',
            'sort_order'    => 'nullable|integer',
            'is_active'     => 'nullable|boolean',
        ]);

        if ($v->fails()) {
            Response::error(422, 'VALIDATION_ERROR', 'Invalid input', $v->errors());
        }

        $trustee = Trustee::create([
            'name'          => $data['name'],
            'designation'   => $data['designation'],
            'trustee_type'  => $data['trustee_type'],
            'bio'           => $data['bio'] ?? null,
            'photo'         => $data['photo'] ?? null,
            'phone'         => $data['phone'] ?? null,
            'email'         => $data['email'] ?? null,
            'qualification' => $data['qualification'] ?? null,
            'sort_order'    => $data['sort_order'] ?? 0,
            'is_active'     => $data['is_active'] ?? 1,
        ]);

        ActivityLogger::log((int) $user->id, 'create', 'trustee', (int) $trustee->id, "Created trustee: {$trustee->name}");
        $this->json(['id' => (int) $trustee->id, 'name' => $trustee->name], 201);
    }

    public function update(string $id): void
    {
        $user = $this->getAuthUser();
        $trustee = Trustee::find((int) $id);
        if (!$trustee) {
            Response::error(404, 'NOT_FOUND', 'Trustee not found');
        }

        $data = $this->getJsonBody();
        $updateData = [];
        foreach (['name', 'designation', 'trustee_type', 'bio', 'photo', 'phone', 'email', 'qualification', 'sort_order', 'is_active'] as $field) {
            if (array_key_exists($field, $data)) {
                $updateData[$field] = $data[$field];
            }
        }

        if (!empty($updateData)) {
            Trustee::update((int) $id, $updateData);
        }

        ActivityLogger::log((int) $user->id, 'update', 'trustee', (int) $id, "Updated trustee: {$trustee->name}");
        $this->json(['message' => 'Trustee updated successfully']);
    }

    public function destroy(string $id): void
    {
        $user = $this->getAuthUser();
        $trustee = Trustee::find((int) $id);
        if (!$trustee) {
            Response::error(404, 'NOT_FOUND', 'Trustee not found');
        }

        Trustee::destroy((int) $id);
        ActivityLogger::log((int) $user->id, 'delete', 'trustee', (int) $id, "Deleted trustee: {$trustee->name}");
        $this->json(['message' => 'Trustee deleted successfully']);
    }

    /**
     * PUT /api/v1/trustees/reorder
     * Body: { "order": [{ "id": 3, "sort_order": 1 }, { "id": 1, "sort_order": 2 }] }
     */
    public function reorder(): void
    {
        $user = $this->getAuthUser();
        $data = $this->getJsonBody();

        if (!isset($data['order']) || !is_array($data['order'])) {
            Response::error(422, 'VALIDATION_ERROR', 'Order array is required');
        }

        $db = Database::getInstance();
        foreach ($data['order'] as $item) {
            if (isset($item['id'], $item['sort_order'])) {
                $db->query(
                    "UPDATE `trustees` SET `sort_order` = ? WHERE `id` = ?",
                    [(int) $item['sort_order'], (int) $item['id']]
                );
            }
        }

        ActivityLogger::log((int) $user->id, 'reorder', 'trustee', null, 'Reordered trustees');
        $this->json(['message' => 'Trustees reordered successfully']);
    }
}
