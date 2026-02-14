<?php

namespace App\Controllers\Api;

use App\Core\Controller;
use App\Helpers\Response;
use App\Helpers\Validator;
use App\Models\Page;
use App\Services\ActivityLogger;
use App\Services\SlugService;

class PageController extends Controller
{
    public function index(): void
    {
        $query = Page::query()->orderBy('menu_position')->orderBy('title');

        $status = $this->getQuery('status');
        if ($status && in_array($status, ['draft', 'published'])) {
            $query->where('status', '=', $status);
        }

        if ($this->getQuery('menu_only') === '1') {
            $query->andWhere('show_in_menu', '=', 1);
        }

        $pages = $query->get();

        $items = array_map(fn($p) => [
            'id'            => (int) $p->id,
            'title'         => $p->title,
            'slug'          => $p->slug,
            'template'      => $p->template,
            'status'        => $p->status,
            'show_in_menu'  => (bool) $p->show_in_menu,
            'menu_position' => (int) $p->menu_position,
            'parent_id'     => $p->parent_id ? (int) $p->parent_id : null,
            'sort_order'    => (int) $p->sort_order,
        ], $pages);

        $this->json($items);
    }

    public function show(string $id): void
    {
        $page = Page::find((int) $id);
        if (!$page) {
            Response::error(404, 'NOT_FOUND', 'Page not found');
        }

        $this->json([
            'id'             => (int) $page->id,
            'title'          => $page->title,
            'slug'           => $page->slug,
            'content'        => $page->content,
            'featured_image' => $page->featured_image,
            'template'       => $page->template,
            'status'         => $page->status,
            'sort_order'     => (int) $page->sort_order,
            'show_in_menu'   => (bool) $page->show_in_menu,
            'menu_position'  => (int) $page->menu_position,
            'parent_id'      => $page->parent_id ? (int) $page->parent_id : null,
            'created_by'     => (int) $page->created_by,
            'created_at'     => $page->created_at,
            'updated_at'     => $page->updated_at,
        ]);
    }

    public function store(): void
    {
        $user = $this->getAuthUser();
        $data = $this->getJsonBody();

        $v = new Validator($data, [
            'title'         => 'required|string|min:2|max:255',
            'content'       => 'required|string',
            'featured_image'=> 'nullable|string|max:255',
            'template'      => 'nullable|string|max:50',
            'status'        => 'nullable|in:draft,published',
            'show_in_menu'  => 'nullable|boolean',
            'menu_position' => 'nullable|integer',
            'parent_id'     => 'nullable|integer',
            'sort_order'    => 'nullable|integer',
        ]);

        if ($v->fails()) {
            Response::error(422, 'VALIDATION_ERROR', 'Invalid input', $v->errors());
        }

        $slug = SlugService::slugify($data['title'], 'pages');

        $page = Page::create([
            'title'          => $data['title'],
            'slug'           => $slug,
            'content'        => $data['content'],
            'featured_image' => $data['featured_image'] ?? null,
            'template'       => $data['template'] ?? 'default',
            'created_by'     => (int) $user->id,
            'status'         => $data['status'] ?? 'draft',
            'show_in_menu'   => $data['show_in_menu'] ?? 0,
            'menu_position'  => $data['menu_position'] ?? 0,
            'parent_id'      => $data['parent_id'] ?? null,
            'sort_order'     => $data['sort_order'] ?? 0,
        ]);

        ActivityLogger::log((int) $user->id, 'create', 'page', (int) $page->id, "Created page: {$page->title}");
        $this->json(['id' => (int) $page->id, 'title' => $page->title, 'slug' => $page->slug], 201);
    }

    public function update(string $id): void
    {
        $user = $this->getAuthUser();
        $page = Page::find((int) $id);
        if (!$page) {
            Response::error(404, 'NOT_FOUND', 'Page not found');
        }

        $data = $this->getJsonBody();
        $updateData = [];
        foreach (['title', 'content', 'featured_image', 'template', 'status', 'show_in_menu', 'menu_position', 'parent_id', 'sort_order'] as $field) {
            if (array_key_exists($field, $data)) {
                $updateData[$field] = $data[$field];
            }
        }

        if (isset($updateData['title']) && $updateData['title'] !== $page->title) {
            $updateData['slug'] = SlugService::slugify($updateData['title'], 'pages');
        }

        if (!empty($updateData)) {
            Page::update((int) $id, $updateData);
        }

        ActivityLogger::log((int) $user->id, 'update', 'page', (int) $id, "Updated page: {$page->title}");
        $this->json(['message' => 'Page updated successfully']);
    }

    public function destroy(string $id): void
    {
        $user = $this->getAuthUser();
        $page = Page::find((int) $id);
        if (!$page) {
            Response::error(404, 'NOT_FOUND', 'Page not found');
        }

        Page::destroy((int) $id);
        ActivityLogger::log((int) $user->id, 'delete', 'page', (int) $id, "Deleted page: {$page->title}");
        $this->json(['message' => 'Page deleted successfully']);
    }
}
