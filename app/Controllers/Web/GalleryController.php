<?php

namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\GalleryAlbum;
use App\Models\GalleryImage;

class GalleryController extends Controller
{
    public function index(): void
    {
        $page = max(1, (int) ($this->getQuery('page', 1)));
        $result = GalleryAlbum::where('status', 'published')
            ->orderBy('sort_order')
            ->paginate($page, 12);

        $this->render('gallery.index', [
            'pageTitle'  => 'Gallery — Sri Sai Mission',
            'pageClass'  => 'gallery',
            'albums'     => $result['data'],
            'pagination' => [
                'page'        => $result['page'],
                'total_pages' => (int) ceil($result['total'] / $result['per_page']),
                'total'       => $result['total'],
            ],
        ]);
    }

    public function show(string $slug): void
    {
        $album = GalleryAlbum::findBy('slug', $slug);

        if (!$album || $album->status !== 'published') {
            http_response_code(404);
            $this->render('errors.404', [
                'pageTitle' => 'Not Found — Sri Sai Mission',
                'pageClass' => 'error',
            ]);
            return;
        }

        $images = GalleryImage::where('album_id', $album->id)
            ->orderBy('sort_order')
            ->get();

        $this->render('gallery.show', [
            'pageTitle' => htmlspecialchars($album->title) . ' — Gallery',
            'pageClass' => 'gallery',
            'album'     => $album,
            'images'    => $images,
        ]);
    }
}
