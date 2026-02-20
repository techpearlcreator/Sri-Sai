<?php

namespace App\Controllers\Api;

use App\Core\Controller;
use App\Helpers\Response;
use App\Helpers\Validator;
use App\Models\PoojaType;
use App\Services\ActivityLogger;

class PoojaTypeController extends Controller
{
    public function index(): void
    {
        $page = max(1, (int) $this->getQuery('page', 1));
        $perPage = min(50, max(1, (int) $this->getQuery('per_page', 15)));

        $query = PoojaType::query();

        $temple = $this->getQuery('temple');
        if ($temple && in_array($temple, ['perungalathur', 'keerapakkam', 'both'])) {
            $query->andWhere('temple', $temple);
        }

        $query->orderBy('sort_order');
        $result = $query->paginate($page, $perPage);

        Response::paginated($result['data'], $result['total'], $result['page'], $result['per_page']);
    }

    public function show(string $id): void
    {
        $pt = PoojaType::findOrFail((int) $id);
        $this->json($pt);
    }

    public function store(): void
    {
        $user = $this->getAuthUser();
        $data = $this->getJsonBody();

        $v = new Validator($data, [
            'name'        => 'required|string|min:2|max:255',
            'temple'      => 'required|in:perungalathur,keerapakkam,both',
            'price'       => 'required|numeric',
            'description' => 'nullable|string',
            'duration'    => 'nullable|string|max:100',
            'is_active'   => 'nullable|boolean',
            'sort_order'  => 'nullable|integer',
        ]);

        if ($v->fails()) {
            Response::error(422, 'VALIDATION_ERROR', 'Invalid input', $v->errors());
        }

        $pt = PoojaType::create([
            'name'        => $data['name'],
            'temple'      => $data['temple'],
            'description'    => $data['description'] ?? null,
            'name_ta'        => $data['name_ta'] ?? null,
            'description_ta' => $data['description_ta'] ?? null,
            'duration'    => $data['duration'] ?? null,
            'price'       => $data['price'],
            'is_active'   => $data['is_active'] ?? 1,
            'sort_order'  => $data['sort_order'] ?? 0,
        ]);

        ActivityLogger::log((int) $user->id, 'create', 'pooja_type', (int) $pt->id, "Created pooja type: {$pt->name}");
        $this->json($pt, 201);
    }

    public function update(string $id): void
    {
        $user = $this->getAuthUser();
        $pt = PoojaType::findOrFail((int) $id);
        $data = $this->getJsonBody();

        $updateData = [];
        foreach (['name', 'name_ta', 'temple', 'description', 'description_ta', 'duration', 'price', 'is_active', 'sort_order'] as $field) {
            if (array_key_exists($field, $data)) {
                $updateData[$field] = $data[$field];
            }
        }

        if (!empty($updateData)) {
            PoojaType::update((int) $id, $updateData);
        }

        ActivityLogger::log((int) $user->id, 'update', 'pooja_type', (int) $id, "Updated pooja type: {$pt->name}");
        $this->json(['message' => 'Pooja type updated successfully']);
    }

    public function destroy(string $id): void
    {
        $user = $this->getAuthUser();
        $pt = PoojaType::findOrFail((int) $id);
        PoojaType::destroy((int) $id);
        ActivityLogger::log((int) $user->id, 'delete', 'pooja_type', (int) $id, "Deleted pooja type: {$pt->name}");
        $this->json(['message' => 'Pooja type deleted successfully']);
    }
}
