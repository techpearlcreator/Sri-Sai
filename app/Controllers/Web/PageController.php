<?php

namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\Page;

class PageController extends Controller
{
    public function show(string $slug): void
    {
        $page = Page::findBy('slug', $slug);

        if (!$page || ($page->status ?? 'draft') !== 'published') {
            http_response_code(404);
            $this->render('errors.404', [
                'pageTitle' => 'Not Found — Sri Sai Mission',
                'pageClass' => 'error',
            ]);
            return;
        }

        $this->render('pages.show', [
            'pageTitle' => htmlspecialchars($page->title) . ' — Sri Sai Mission',
            'pageClass' => 'page',
            'page'      => $page,
        ]);
    }
}
