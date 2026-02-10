<?php

namespace App\Controllers\Api;

use App\Core\Controller;
use App\Helpers\Response;
use App\Models\Media;
use App\Services\ActivityLogger;
use App\Services\ImageService;

class MediaController extends Controller
{
    /**
     * POST /api/v1/media/upload
     * Multipart form: file (required), module (optional), alt_text (optional)
     * Requires: AuthMiddleware
     */
    public function upload(): void
    {
        $user = $this->getAuthUser();
        $module = $_POST['module'] ?? 'general';
        $altText = $_POST['alt_text'] ?? null;

        // Whitelist allowed module directories
        $allowedModules = ['general', 'blogs', 'magazines', 'gallery', 'events', 'pages', 'trustees', 'avatars'];
        if (!in_array($module, $allowedModules, true)) {
            $module = 'general';
        }

        $result = ImageService::upload('file', $module);

        $media = Media::create([
            'uploaded_by'    => (int) $user->id,
            'file_name'      => $result['file_name'],
            'file_path'      => $result['file_path'],
            'thumbnail_path' => $result['thumbnail_path'],
            'file_type'      => $result['file_type'],
            'file_size'      => $result['file_size'],
            'width'          => $result['width'],
            'height'         => $result['height'],
            'alt_text'       => $altText,
            'used_in'        => $module,
        ]);

        ActivityLogger::log((int) $user->id, 'upload', 'media', (int) $media->id, "Uploaded: {$result['file_name']}");

        $appUrl = rtrim($_ENV['APP_URL'] ?? 'http://localhost/srisai/public', '/');

        $this->json([
            'id'             => (int) $media->id,
            'file_name'      => $media->file_name,
            'file_path'      => $media->file_path,
            'file_url'       => $appUrl . '/storage/uploads/' . $media->file_path,
            'thumbnail_path' => $media->thumbnail_path,
            'thumbnail_url'  => $media->thumbnail_path ? $appUrl . '/storage/uploads/' . $media->thumbnail_path : null,
            'file_type'      => $media->file_type,
            'file_size'      => (int) $media->file_size,
            'width'          => $media->width ? (int) $media->width : null,
            'height'         => $media->height ? (int) $media->height : null,
            'alt_text'       => $media->alt_text,
            'used_in'        => $media->used_in,
        ], 201);
    }

    /**
     * GET /api/v1/media
     * Query: page, per_page, type (image/pdf), module
     * Requires: AuthMiddleware
     */
    public function index(): void
    {
        $page = max(1, (int) $this->getQuery('page', 1));
        $perPage = min(50, max(1, (int) $this->getQuery('per_page', 20)));

        $query = Media::query()->orderBy('created_at', 'DESC');

        // Filter by file type group
        $type = $this->getQuery('type');
        if ($type === 'image') {
            $query->where('file_type', 'LIKE', 'image/%');
        } elseif ($type === 'pdf') {
            $query->where('file_type', '=', 'application/pdf');
        }

        // Filter by module
        $module = $this->getQuery('module');
        if ($module) {
            $query->andWhere('used_in', '=', $module);
        }

        $result = $query->paginate($page, $perPage);

        $appUrl = rtrim($_ENV['APP_URL'] ?? 'http://localhost/srisai/public', '/');

        $items = array_map(function ($item) use ($appUrl) {
            return [
                'id'             => (int) $item->id,
                'file_name'      => $item->file_name,
                'file_path'      => $item->file_path,
                'file_url'       => $appUrl . '/storage/uploads/' . $item->file_path,
                'thumbnail_path' => $item->thumbnail_path,
                'thumbnail_url'  => $item->thumbnail_path ? $appUrl . '/storage/uploads/' . $item->thumbnail_path : null,
                'file_type'      => $item->file_type,
                'file_size'      => (int) $item->file_size,
                'width'          => $item->width ? (int) $item->width : null,
                'height'         => $item->height ? (int) $item->height : null,
                'alt_text'       => $item->alt_text,
                'used_in'        => $item->used_in,
                'created_at'     => $item->created_at,
            ];
        }, $result['data']);

        Response::paginated($items, $result['total'], $result['page'], $result['per_page']);
    }

    /**
     * GET /api/v1/media/{id}
     * Requires: AuthMiddleware
     */
    public function show(string $id): void
    {
        $media = Media::find((int) $id);
        if (!$media) {
            Response::error(404, 'NOT_FOUND', 'Media file not found');
        }

        $appUrl = rtrim($_ENV['APP_URL'] ?? 'http://localhost/srisai/public', '/');

        $this->json([
            'id'             => (int) $media->id,
            'file_name'      => $media->file_name,
            'file_path'      => $media->file_path,
            'file_url'       => $appUrl . '/storage/uploads/' . $media->file_path,
            'thumbnail_path' => $media->thumbnail_path,
            'thumbnail_url'  => $media->thumbnail_path ? $appUrl . '/storage/uploads/' . $media->thumbnail_path : null,
            'file_type'      => $media->file_type,
            'file_size'      => (int) $media->file_size,
            'width'          => $media->width ? (int) $media->width : null,
            'height'         => $media->height ? (int) $media->height : null,
            'alt_text'       => $media->alt_text,
            'used_in'        => $media->used_in,
            'created_at'     => $media->created_at,
        ]);
    }

    /**
     * PUT /api/v1/media/{id}
     * Body: { "alt_text": "..." }
     * Requires: AuthMiddleware
     */
    public function update(string $id): void
    {
        $media = Media::find((int) $id);
        if (!$media) {
            Response::error(404, 'NOT_FOUND', 'Media file not found');
        }

        $data = $this->getJsonBody();
        $altText = $data['alt_text'] ?? $media->alt_text;

        Media::update((int) $id, ['alt_text' => $altText]);

        $this->json(['message' => 'Media updated successfully']);
    }

    /**
     * DELETE /api/v1/media/{id}
     * Requires: AuthMiddleware
     */
    public function destroy(string $id): void
    {
        $user = $this->getAuthUser();
        $media = Media::find((int) $id);

        if (!$media) {
            Response::error(404, 'NOT_FOUND', 'Media file not found');
        }

        // Delete physical files
        ImageService::delete($media->file_path, $media->thumbnail_path);

        // Delete DB record
        Media::destroy((int) $id);

        ActivityLogger::log((int) $user->id, 'delete', 'media', (int) $id, "Deleted: {$media->file_name}");

        $this->json(['message' => 'Media deleted successfully']);
    }
}
