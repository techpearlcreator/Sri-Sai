<?php

namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\Magazine;

class MagazineController extends Controller
{
    public function index(): void
    {
        $page = max(1, (int) ($this->getQuery('page', 1)));
        $result = Magazine::where('status', 'published')
            ->orderBy('issue_date', 'DESC')
            ->paginate($page, 12);

        $this->render('magazine.index', [
            'pageTitle'  => 'Sri Sai Dharisanam — Magazine',
            'pageClass'  => 'magazine',
            'magazines'  => $result['data'],
            'pagination' => [
                'page'        => $result['page'],
                'total_pages' => (int) ceil($result['total'] / $result['per_page']),
                'total'       => $result['total'],
            ],
        ]);
    }

    public function show(string $slug): void
    {
        $magazine = Magazine::findBySlug($slug);

        if (!$magazine) {
            http_response_code(404);
            $this->render('errors.404', [
                'pageTitle' => 'Not Found — Sri Sai Mission',
                'pageClass' => 'error',
            ]);
            return;
        }

        $this->render('magazine.show', [
            'pageTitle' => htmlspecialchars($magazine->title) . ' — Sri Sai Dharisanam',
            'pageClass' => 'magazine',
            'magazine'  => $magazine,
        ]);
    }
}
