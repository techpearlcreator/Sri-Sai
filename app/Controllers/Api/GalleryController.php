<?php

namespace App\Controllers\Api;

use App\Core\Controller;
use App\Helpers\Response;
use App\Helpers\Validator;
use App\Models\GalleryAlbum;
use App\Models\GalleryImage;
use App\Services\ActivityLogger;
use App\Services\ImageService;
use App\Services\SlugService;

class GalleryController extends Controller
{
    // ========== ALBUMS ==========

    public function index(): void
    {
        $page = max(1, (int) $this->getQuery('page', 1));
        $perPage = min(50, max(1, (int) $this->getQuery('per_page', 15)));

        $query = GalleryAlbum::query()->orderBy('sort_order')->orderBy('created_at', 'DESC');

        $status = $this->getQuery('status');
        if ($status && in_array($status, ['draft', 'published', 'archived'])) {
            $query->where('status', '=', $status);
        }

        $result = $query->paginate($page, $perPage);

        $items = array_map(fn($a) => [
            'id'          => (int) $a->id,
            'title'       => $a->title,
            'slug'        => $a->slug,
            'description' => $a->description,
            'cover_image' => $a->cover_image,
            'status'      => $a->status,
            'sort_order'  => (int) $a->sort_order,
            'image_count' => (int) $a->image_count,
            'created_at'  => $a->created_at,
        ], $result['data']);

        Response::paginated($items, $result['total'], $result['page'], $result['per_page']);
    }

    public function show(string $id): void
    {
        $album = GalleryAlbum::find((int) $id);
        if (!$album) {
            Response::error(404, 'NOT_FOUND', 'Album not found');
        }

        $images = GalleryImage::byAlbum((int) $id);

        $this->json([
            'id'          => (int) $album->id,
            'title'       => $album->title,
            'slug'        => $album->slug,
            'description'    => $album->description,
            'title_ta'       => $album->title_ta,
            'description_ta' => $album->description_ta,
            'cover_image' => $album->cover_image,
            'status'      => $album->status,
            'sort_order'  => (int) $album->sort_order,
            'image_count' => (int) $album->image_count,
            'category_id' => $album->category_id ? (int) $album->category_id : null,
            'created_at'  => $album->created_at,
            'images'      => array_map(fn($img) => [
                'id'         => (int) $img->id,
                'file_path'  => $img->file_path,
                'thumbnail'  => $img->thumbnail,
                'caption'    => $img->caption,
                'alt_text'   => $img->alt_text,
                'sort_order' => (int) $img->sort_order,
            ], $images),
        ]);
    }

    public function store(): void
    {
        $user = $this->getAuthUser();
        $data = $this->getJsonBody();

        $v = new Validator($data, [
            'title'       => 'required|string|min:3|max:255',
            'description' => 'nullable|string|max:1000',
            'cover_image' => 'nullable|string|max:255',
            'category_id' => 'nullable|integer',
            'status'      => 'nullable|in:draft,published,archived',
            'sort_order'  => 'nullable|integer',
        ]);

        if ($v->fails()) {
            Response::error(422, 'VALIDATION_ERROR', 'Invalid input', $v->errors());
        }

        $slug = SlugService::slugify($data['title'], 'gallery_albums');

        $album = GalleryAlbum::create([
            'title'       => $data['title'],
            'slug'        => $slug,
            'description'    => $data['description'] ?? null,
            'title_ta'       => $data['title_ta'] ?? null,
            'description_ta' => $data['description_ta'] ?? null,
            'cover_image' => $data['cover_image'] ?? null,
            'category_id' => $data['category_id'] ?? null,
            'created_by'  => (int) $user->id,
            'status'      => $data['status'] ?? 'draft',
            'sort_order'  => $data['sort_order'] ?? 0,
        ]);

        ActivityLogger::log((int) $user->id, 'create', 'gallery_album', (int) $album->id, "Created album: {$album->title}");
        $this->json(['id' => (int) $album->id, 'title' => $album->title, 'slug' => $album->slug], 201);
    }

    public function update(string $id): void
    {
        $user = $this->getAuthUser();
        $album = GalleryAlbum::find((int) $id);
        if (!$album) {
            Response::error(404, 'NOT_FOUND', 'Album not found');
        }

        $data = $this->getJsonBody();
        $updateData = [];
        foreach (['title', 'title_ta', 'description', 'description_ta', 'cover_image', 'category_id', 'status', 'sort_order'] as $field) {
            if (array_key_exists($field, $data)) {
                $updateData[$field] = $data[$field];
            }
        }

        if (isset($updateData['title']) && $updateData['title'] !== $album->title) {
            $updateData['slug'] = SlugService::slugify($updateData['title'], 'gallery_albums');
        }

        if (!empty($updateData)) {
            GalleryAlbum::update((int) $id, $updateData);
        }

        ActivityLogger::log((int) $user->id, 'update', 'gallery_album', (int) $id, "Updated album: {$album->title}");
        $this->json(['message' => 'Album updated successfully']);
    }

    public function destroy(string $id): void
    {
        $user = $this->getAuthUser();
        $album = GalleryAlbum::find((int) $id);
        if (!$album) {
            Response::error(404, 'NOT_FOUND', 'Album not found');
        }

        // Images cascade-delete via FK, but also clean up files
        $images = GalleryImage::byAlbum((int) $id);
        foreach ($images as $img) {
            ImageService::delete($img->file_path, $img->thumbnail);
        }

        GalleryAlbum::destroy((int) $id);
        ActivityLogger::log((int) $user->id, 'delete', 'gallery_album', (int) $id, "Deleted album: {$album->title}");
        $this->json(['message' => 'Album and all images deleted successfully']);
    }

    // ========== IMAGES ==========

    /**
     * POST /api/v1/gallery/{albumId}/images
     * Multipart: file (required), caption, alt_text
     */
    public function uploadImage(string $albumId): void
    {
        $user = $this->getAuthUser();
        $album = GalleryAlbum::find((int) $albumId);
        if (!$album) {
            Response::error(404, 'NOT_FOUND', 'Album not found');
        }

        $result = ImageService::upload('file', 'gallery');

        // Get next sort order
        $db = \App\Core\Database::getInstance();
        $maxSort = $db->fetch(
            "SELECT MAX(sort_order) as max_sort FROM `gallery_images` WHERE `album_id` = ?",
            [(int) $albumId]
        );
        $nextSort = ($maxSort->max_sort ?? 0) + 1;

        $image = GalleryImage::create([
            'album_id'   => (int) $albumId,
            'file_path'  => $result['file_path'],
            'thumbnail'  => $result['thumbnail_path'],
            'caption'    => $_POST['caption'] ?? null,
            'alt_text'   => $_POST['alt_text'] ?? null,
            'sort_order' => $nextSort,
            'file_size'  => $result['file_size'],
            'width'      => $result['width'],
            'height'     => $result['height'],
        ]);

        GalleryAlbum::refreshImageCount((int) $albumId);

        ActivityLogger::log((int) $user->id, 'upload', 'gallery_image', (int) $image->id, "Uploaded image to: {$album->title}");

        $this->json([
            'id'        => (int) $image->id,
            'file_path' => $image->file_path,
            'thumbnail' => $image->thumbnail,
        ], 201);
    }

    /**
     * PUT /api/v1/gallery/images/{id}
     */
    public function updateImage(string $id): void
    {
        $image = GalleryImage::find((int) $id);
        if (!$image) {
            Response::error(404, 'NOT_FOUND', 'Image not found');
        }

        $data = $this->getJsonBody();
        $updateData = [];
        foreach (['caption', 'alt_text', 'sort_order'] as $field) {
            if (array_key_exists($field, $data)) {
                $updateData[$field] = $data[$field];
            }
        }

        if (!empty($updateData)) {
            GalleryImage::update((int) $id, $updateData);
        }

        $this->json(['message' => 'Image updated successfully']);
    }

    /**
     * DELETE /api/v1/gallery/images/{id}
     */
    public function deleteImage(string $id): void
    {
        $user = $this->getAuthUser();
        $image = GalleryImage::find((int) $id);
        if (!$image) {
            Response::error(404, 'NOT_FOUND', 'Image not found');
        }

        $albumId = (int) $image->album_id;
        ImageService::delete($image->file_path, $image->thumbnail);
        GalleryImage::destroy((int) $id);
        GalleryAlbum::refreshImageCount($albumId);

        ActivityLogger::log((int) $user->id, 'delete', 'gallery_image', (int) $id, 'Deleted gallery image');
        $this->json(['message' => 'Image deleted successfully']);
    }

    /**
     * PUT /api/v1/gallery/{albumId}/images/reorder
     * Body: { "order": [3, 1, 5, 2] }  — array of image IDs in new order
     */
    public function reorderImages(string $albumId): void
    {
        $album = GalleryAlbum::find((int) $albumId);
        if (!$album) {
            Response::error(404, 'NOT_FOUND', 'Album not found');
        }

        $data = $this->getJsonBody();
        if (!isset($data['order']) || !is_array($data['order'])) {
            Response::error(422, 'VALIDATION_ERROR', 'Order array is required');
        }

        $db = \App\Core\Database::getInstance();
        foreach ($data['order'] as $position => $imageId) {
            $db->query(
                "UPDATE `gallery_images` SET `sort_order` = ? WHERE `id` = ? AND `album_id` = ?",
                [$position + 1, (int) $imageId, (int) $albumId]
            );
        }

        $this->json(['message' => 'Images reordered successfully']);
    }
}
