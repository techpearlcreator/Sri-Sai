<?php

namespace App\Controllers\Api;

use App\Core\Controller;
use App\Helpers\Response;
use App\Helpers\Validator;
use App\Models\Magazine;
use App\Services\ActivityLogger;
use App\Services\SlugService;

class MagazineController extends Controller
{
    public function index(): void
    {
        $page = max(1, (int) $this->getQuery('page', 1));
        $perPage = min(50, max(1, (int) $this->getQuery('per_page', 15)));

        $query = Magazine::query()->orderBy('issue_date', 'DESC');

        $status = $this->getQuery('status');
        if ($status && in_array($status, ['draft', 'published', 'archived'])) {
            $query->where('status', '=', $status);
        }

        $catId = $this->getQuery('category_id');
        if ($catId) {
            $query->andWhere('category_id', '=', (int) $catId);
        }

        $search = $this->getQuery('search');
        if ($search) {
            $query->search(['title', 'excerpt'], $search);
        }

        $result = $query->paginate($page, $perPage);

        $items = array_map(fn($m) => [
            'id'             => (int) $m->id,
            'title'          => $m->title,
            'slug'           => $m->slug,
            'excerpt'        => $m->excerpt,
            'featured_image' => $m->featured_image,
            'issue_number'   => $m->issue_number,
            'issue_date'     => $m->issue_date,
            'pdf_file'       => $m->pdf_file,
            'status'         => $m->status,
            'is_featured'    => (bool) $m->is_featured,
            'view_count'     => (int) $m->view_count,
            'created_at'     => $m->created_at,
        ], $result['data']);

        Response::paginated($items, $result['total'], $result['page'], $result['per_page']);
    }

    public function show(string $id): void
    {
        $mag = Magazine::findFull((int) $id);
        if (!$mag) {
            Response::error(404, 'NOT_FOUND', 'Magazine not found');
        }

        $this->json([
            'id'             => (int) $mag->id,
            'title'          => $mag->title,
            'slug'           => $mag->slug,
            'excerpt'        => $mag->excerpt,
            'content'        => $mag->content,
            'title_ta'       => $mag->title_ta,
            'excerpt_ta'     => $mag->excerpt_ta,
            'content_ta'     => $mag->content_ta,
            'featured_image' => $mag->featured_image,
            'issue_number'   => $mag->issue_number,
            'issue_date'     => $mag->issue_date,
            'pdf_file'       => $mag->pdf_file,
            'status'         => $mag->status,
            'is_featured'    => (bool) $mag->is_featured,
            'view_count'     => (int) $mag->view_count,
            'category_id'    => $mag->category_id ? (int) $mag->category_id : null,
            'category_name'  => $mag->category_name ?? null,
            'author_name'    => $mag->author_name ?? null,
            'created_by'     => (int) $mag->created_by,
            'published_at'   => $mag->published_at,
            'created_at'     => $mag->created_at,
            'updated_at'     => $mag->updated_at,
        ]);
    }

    public function store(): void
    {
        $user = $this->getAuthUser();
        $data = $this->getJsonBody();

        $v = new Validator($data, [
            'title'          => 'required|string|min:3|max:255',
            'content'        => 'nullable|string|max:5000',
            'excerpt'        => 'nullable|string|max:500',
            'featured_image' => 'nullable|string|max:255',
            'issue_number'   => 'nullable|string|max:50',
            'issue_date'     => 'nullable|date',
            'pdf_file'       => 'nullable|string|max:255',
            'category_id'    => 'nullable|integer',
            'status'         => 'nullable|in:draft,published,archived',
            'is_featured'    => 'nullable|boolean',
        ]);

        if ($v->fails()) {
            Response::error(422, 'VALIDATION_ERROR', 'Invalid input', $v->errors());
        }

        $slug = SlugService::slugify($data['title'], 'magazines');
        $status = $data['status'] ?? 'draft';

        $mag = Magazine::create([
            'title'          => $data['title'],
            'slug'           => $slug,
            'content'        => $data['content'] ?? '',
            'excerpt'        => $data['excerpt'] ?? null,
            'title_ta'       => $data['title_ta'] ?? null,
            'excerpt_ta'     => $data['excerpt_ta'] ?? null,
            'content_ta'     => $data['content_ta'] ?? null,
            'featured_image' => $data['featured_image'] ?? null,
            'issue_number'   => $data['issue_number'] ?? null,
            'issue_date'     => $data['issue_date'] ?? null,
            'pdf_file'       => $data['pdf_file'] ?? null,
            'category_id'    => $data['category_id'] ?? null,
            'created_by'     => (int) $user->id,
            'status'         => $status,
            'is_featured'    => $data['is_featured'] ?? 0,
            'published_at'   => $status === 'published' ? date('Y-m-d H:i:s') : null,
        ]);

        ActivityLogger::log((int) $user->id, 'create', 'magazine', (int) $mag->id, "Created magazine: {$mag->title}");

        $this->json(['id' => (int) $mag->id, 'title' => $mag->title, 'slug' => $mag->slug], 201);
    }

    public function update(string $id): void
    {
        $user = $this->getAuthUser();
        $mag = Magazine::find((int) $id);
        if (!$mag) {
            Response::error(404, 'NOT_FOUND', 'Magazine not found');
        }

        $data = $this->getJsonBody();

        $v = new Validator($data, [
            'title'          => 'nullable|string|min:5|max:255',
            'content'        => 'nullable|string|min:10',
            'excerpt'        => 'nullable|string|max:500',
            'featured_image' => 'nullable|string|max:255',
            'issue_number'   => 'nullable|string|max:50',
            'issue_date'     => 'nullable|date',
            'pdf_file'       => 'nullable|string|max:255',
            'category_id'    => 'nullable|integer',
            'status'         => 'nullable|in:draft,published,archived',
            'is_featured'    => 'nullable|boolean',
        ]);

        if ($v->fails()) {
            Response::error(422, 'VALIDATION_ERROR', 'Invalid input', $v->errors());
        }

        $updateData = [];
        foreach (['title', 'title_ta', 'content', 'content_ta', 'excerpt', 'excerpt_ta', 'featured_image', 'issue_number', 'issue_date', 'pdf_file', 'category_id', 'status', 'is_featured'] as $field) {
            if (array_key_exists($field, $data)) {
                $updateData[$field] = $data[$field];
            }
        }

        if (isset($updateData['title']) && $updateData['title'] !== $mag->title) {
            $updateData['slug'] = SlugService::slugify($updateData['title'], 'magazines');
        }

        if (isset($updateData['status']) && $updateData['status'] === 'published' && !$mag->published_at) {
            $updateData['published_at'] = date('Y-m-d H:i:s');
        }

        if (!empty($updateData)) {
            Magazine::update((int) $id, $updateData);
        }

        ActivityLogger::log((int) $user->id, 'update', 'magazine', (int) $id, "Updated magazine: {$mag->title}");
        $this->json(['message' => 'Magazine updated successfully']);
    }

    public function destroy(string $id): void
    {
        $user = $this->getAuthUser();
        $mag = Magazine::find((int) $id);
        if (!$mag) {
            Response::error(404, 'NOT_FOUND', 'Magazine not found');
        }

        Magazine::destroy((int) $id);
        ActivityLogger::log((int) $user->id, 'delete', 'magazine', (int) $id, "Deleted magazine: {$mag->title}");
        $this->json(['message' => 'Magazine deleted successfully']);
    }
}
