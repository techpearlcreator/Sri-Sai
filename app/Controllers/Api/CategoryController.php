<?php

namespace App\Controllers\Api;

use App\Core\Controller;
use App\Helpers\Response;
use App\Helpers\Validator;
use App\Models\Category;
use App\Services\ActivityLogger;
use App\Services\SlugService;

class CategoryController extends Controller
{
    /**
     * GET /api/v1/categories
     * Query: type (blog|magazine|gallery|event), active_only (1|0)
     */
    public function index(): void
    {
        $query = Category::query()->orderBy('sort_order')->orderBy('name');

        $type = $this->getQuery('type');
        if ($type && in_array($type, ['blog', 'magazine', 'gallery', 'event'])) {
            $query->where('type', '=', $type);
        }

        if ($this->getQuery('active_only', '0') === '1') {
            $query->andWhere('is_active', '=', 1);
        }

        $categories = $query->get();

        $items = array_map(fn($c) => [
            'id'          => (int) $c->id,
            'name'        => $c->name,
            'slug'        => $c->slug,
            'type'        => $c->type,
            'description' => $c->description,
            'parent_id'   => $c->parent_id ? (int) $c->parent_id : null,
            'sort_order'  => (int) $c->sort_order,
            'is_active'   => (bool) $c->is_active,
        ], $categories);

        $this->json($items);
    }

    /**
     * GET /api/v1/categories/{id}
     */
    public function show(string $id): void
    {
        $cat = Category::find((int) $id);
        if (!$cat) {
            Response::error(404, 'NOT_FOUND', 'Category not found');
        }

        $this->json([
            'id'          => (int) $cat->id,
            'name'        => $cat->name,
            'slug'        => $cat->slug,
            'type'        => $cat->type,
            'description' => $cat->description,
            'parent_id'   => $cat->parent_id ? (int) $cat->parent_id : null,
            'sort_order'  => (int) $cat->sort_order,
            'is_active'   => (bool) $cat->is_active,
            'created_at'  => $cat->created_at,
            'updated_at'  => $cat->updated_at,
        ]);
    }

    /**
     * POST /api/v1/categories
     */
    public function store(): void
    {
        $user = $this->getAuthUser();
        $data = $this->getJsonBody();

        $v = new Validator($data, [
            'name'        => 'required|string|min:2|max:100',
            'type'        => 'required|in:blog,magazine,gallery,event',
            'description' => 'nullable|string|max:500',
            'parent_id'   => 'nullable|integer',
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'nullable|boolean',
        ]);

        if ($v->fails()) {
            Response::error(422, 'VALIDATION_ERROR', 'Invalid input', $v->errors());
        }

        $slug = SlugService::slugify($data['name'], 'categories');

        $cat = Category::create([
            'name'        => $data['name'],
            'slug'        => $slug,
            'type'        => $data['type'],
            'description' => $data['description'] ?? null,
            'parent_id'   => $data['parent_id'] ?? null,
            'sort_order'  => $data['sort_order'] ?? 0,
            'is_active'   => $data['is_active'] ?? 1,
        ]);

        ActivityLogger::log((int) $user->id, 'create', 'category', (int) $cat->id, "Created category: {$cat->name}");

        $this->json([
            'id'   => (int) $cat->id,
            'name' => $cat->name,
            'slug' => $cat->slug,
            'type' => $cat->type,
        ], 201);
    }

    /**
     * PUT /api/v1/categories/{id}
     */
    public function update(string $id): void
    {
        $user = $this->getAuthUser();
        $cat = Category::find((int) $id);
        if (!$cat) {
            Response::error(404, 'NOT_FOUND', 'Category not found');
        }

        $data = $this->getJsonBody();

        $v = new Validator($data, [
            'name'        => 'nullable|string|min:2|max:100',
            'description' => 'nullable|string|max:500',
            'parent_id'   => 'nullable|integer',
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'nullable|boolean',
        ]);

        if ($v->fails()) {
            Response::error(422, 'VALIDATION_ERROR', 'Invalid input', $v->errors());
        }

        $updateData = [];
        if (isset($data['name']) && $data['name'] !== $cat->name) {
            $updateData['name'] = $data['name'];
            $updateData['slug'] = SlugService::slugify($data['name'], 'categories');
        }
        foreach (['description', 'parent_id', 'sort_order', 'is_active'] as $field) {
            if (array_key_exists($field, $data)) {
                $updateData[$field] = $data[$field];
            }
        }

        if (!empty($updateData)) {
            Category::update((int) $id, $updateData);
        }

        ActivityLogger::log((int) $user->id, 'update', 'category', (int) $id, "Updated category: {$cat->name}");

        $this->json(['message' => 'Category updated successfully']);
    }

    /**
     * DELETE /api/v1/categories/{id}
     */
    public function destroy(string $id): void
    {
        $user = $this->getAuthUser();
        $cat = Category::find((int) $id);
        if (!$cat) {
            Response::error(404, 'NOT_FOUND', 'Category not found');
        }

        Category::destroy((int) $id);
        ActivityLogger::log((int) $user->id, 'delete', 'category', (int) $id, "Deleted category: {$cat->name}");

        $this->json(['message' => 'Category deleted successfully']);
    }
}
