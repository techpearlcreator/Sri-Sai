<?php

namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\Blog;

class BlogController extends Controller
{
    public function index(): void
    {
        $page = max(1, (int) ($this->getQuery('page', 1)));
        $result = Blog::where('status', 'published')
            ->orderBy('created_at', 'DESC')
            ->paginate($page, 9);

        $this->render('blog.index', [
            'pageTitle'  => 'Blog — Sri Sai Mission',
            'pageClass'  => 'blog',
            'blogs'      => $result['data'],
            'pagination' => [
                'page'        => $result['page'],
                'total_pages' => (int) ceil($result['total'] / $result['per_page']),
                'total'       => $result['total'],
            ],
        ]);
    }

    public function show(string $slug): void
    {
        $blog = Blog::findBySlug($slug);

        if (!$blog) {
            http_response_code(404);
            $this->render('errors.404', [
                'pageTitle' => 'Not Found — Sri Sai Mission',
                'pageClass' => 'error',
            ]);
            return;
        }

        Blog::increment($blog->id, 'view_count');

        $this->render('blog.show', [
            'pageTitle' => htmlspecialchars($blog->title) . ' — Sri Sai Mission',
            'pageClass' => 'blog',
            'blog'      => $blog,
        ]);
    }
}
