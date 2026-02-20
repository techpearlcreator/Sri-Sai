<?php

namespace App\Controllers\Api;

use App\Core\Controller;
use App\Helpers\Response;
use App\Helpers\Validator;
use App\Models\Blog;
use App\Services\ActivityLogger;
use App\Services\SlugService;

class BlogController extends Controller
{
    /**
     * GET /api/v1/blogs
     * Query: page, per_page, status, category_id, search, is_featured
     */
    public function index(): void
    {
        $page = max(1, (int) $this->getQuery('page', 1));
        $perPage = min(50, max(1, (int) $this->getQuery('per_page', 15)));

        $query = Blog::query();

        $status = $this->getQuery('status');
        if ($status && in_array($status, ['draft', 'published', 'archived'])) {
            $query->where('status', '=', $status);
        }

        $catId = $this->getQuery('category_id');
        if ($catId) {
            $query->andWhere('category_id', '=', (int) $catId);
        }

        if ($this->getQuery('is_featured') === '1') {
            $query->andWhere('is_featured', '=', 1);
        }

        $search = $this->getQuery('search');
        if ($search) {
            $query->search(['title', 'excerpt'], $search);
        }

        $query->orderBy('created_at', 'DESC');
        $result = $query->paginate($page, $perPage);

        $items = array_map(fn($b) => [
            'id'             => (int) $b->id,
            'title'          => $b->title,
            'slug'           => $b->slug,
            'excerpt'        => $b->excerpt,
            'featured_image' => $b->featured_image,
            'status'         => $b->status,
            'is_featured'    => (bool) $b->is_featured,
            'view_count'     => (int) $b->view_count,
            'category_id'    => $b->category_id ? (int) $b->category_id : null,
            'published_at'   => $b->published_at,
            'created_at'     => $b->created_at,
        ], $result['data']);

        Response::paginated($items, $result['total'], $result['page'], $result['per_page']);
    }

    /**
     * GET /api/v1/blogs/{id}
     */
    public function show(string $id): void
    {
        $blog = Blog::findFull((int) $id);
        if (!$blog) {
            Response::error(404, 'NOT_FOUND', 'Blog post not found');
        }

        $this->json([
            'id'             => (int) $blog->id,
            'title'          => $blog->title,
            'slug'           => $blog->slug,
            'excerpt'        => $blog->excerpt,
            'content'        => $blog->content,
            'title_ta'       => $blog->title_ta,
            'excerpt_ta'     => $blog->excerpt_ta,
            'content_ta'     => $blog->content_ta,
            'featured_image' => $blog->featured_image,
            'status'         => $blog->status,
            'is_featured'    => (bool) $blog->is_featured,
            'view_count'     => (int) $blog->view_count,
            'category_id'    => $blog->category_id ? (int) $blog->category_id : null,
            'category_name'  => $blog->category_name ?? null,
            'author_name'    => $blog->author_name ?? null,
            'created_by'     => (int) $blog->created_by,
            'published_at'   => $blog->published_at,
            'created_at'     => $blog->created_at,
            'updated_at'     => $blog->updated_at,
        ]);
    }

    /**
     * POST /api/v1/blogs
     */
    public function store(): void
    {
        $user = $this->getAuthUser();
        $data = $this->getJsonBody();

        $v = new Validator($data, [
            'title'          => 'required|string|min:5|max:255',
            'content'        => 'required|string|min:10',
            'excerpt'        => 'nullable|string|max:500',
            'featured_image' => 'nullable|string|max:255',
            'category_id'    => 'nullable|integer',
            'status'         => 'nullable|in:draft,published,archived',
            'is_featured'    => 'nullable|boolean',
        ]);

        if ($v->fails()) {
            Response::error(422, 'VALIDATION_ERROR', 'Invalid input', $v->errors());
        }

        $slug = SlugService::slugify($data['title'], 'blogs');
        $status = $data['status'] ?? 'draft';

        $blog = Blog::create([
            'title'          => $data['title'],
            'slug'           => $slug,
            'content'        => $data['content'],
            'excerpt'        => $data['excerpt'] ?? null,
            'title_ta'       => $data['title_ta'] ?? null,
            'excerpt_ta'     => $data['excerpt_ta'] ?? null,
            'content_ta'     => $data['content_ta'] ?? null,
            'featured_image' => $data['featured_image'] ?? null,
            'category_id'    => $data['category_id'] ?? null,
            'created_by'     => (int) $user->id,
            'status'         => $status,
            'is_featured'    => $data['is_featured'] ?? 0,
            'published_at'   => $status === 'published' ? date('Y-m-d H:i:s') : null,
        ]);

        ActivityLogger::log((int) $user->id, 'create', 'blog', (int) $blog->id, "Created blog: {$blog->title}");

        $this->json([
            'id'    => (int) $blog->id,
            'title' => $blog->title,
            'slug'  => $blog->slug,
        ], 201);
    }

    /**
     * PUT /api/v1/blogs/{id}
     */
    public function update(string $id): void
    {
        $user = $this->getAuthUser();
        $blog = Blog::find((int) $id);
        if (!$blog) {
            Response::error(404, 'NOT_FOUND', 'Blog post not found');
        }

        $data = $this->getJsonBody();

        $v = new Validator($data, [
            'title'          => 'nullable|string|min:5|max:255',
            'content'        => 'nullable|string|min:10',
            'excerpt'        => 'nullable|string|max:500',
            'featured_image' => 'nullable|string|max:255',
            'category_id'    => 'nullable|integer',
            'status'         => 'nullable|in:draft,published,archived',
            'is_featured'    => 'nullable|boolean',
        ]);

        if ($v->fails()) {
            Response::error(422, 'VALIDATION_ERROR', 'Invalid input', $v->errors());
        }

        $updateData = [];
        foreach (['title', 'title_ta', 'content', 'content_ta', 'excerpt', 'excerpt_ta', 'featured_image', 'category_id', 'status', 'is_featured'] as $field) {
            if (array_key_exists($field, $data)) {
                $updateData[$field] = $data[$field];
            }
        }

        if (isset($updateData['title']) && $updateData['title'] !== $blog->title) {
            $updateData['slug'] = SlugService::slugify($updateData['title'], 'blogs');
        }

        // Auto-set published_at when status changes to published
        if (isset($updateData['status']) && $updateData['status'] === 'published' && !$blog->published_at) {
            $updateData['published_at'] = date('Y-m-d H:i:s');
        }

        if (!empty($updateData)) {
            Blog::update((int) $id, $updateData);
        }

        ActivityLogger::log((int) $user->id, 'update', 'blog', (int) $id, "Updated blog: {$blog->title}");

        $this->json(['message' => 'Blog updated successfully']);
    }

    /**
     * DELETE /api/v1/blogs/{id}
     */
    public function destroy(string $id): void
    {
        $user = $this->getAuthUser();
        $blog = Blog::find((int) $id);
        if (!$blog) {
            Response::error(404, 'NOT_FOUND', 'Blog post not found');
        }

        Blog::destroy((int) $id);
        ActivityLogger::log((int) $user->id, 'delete', 'blog', (int) $id, "Deleted blog: {$blog->title}");

        $this->json(['message' => 'Blog deleted successfully']);
    }
}
