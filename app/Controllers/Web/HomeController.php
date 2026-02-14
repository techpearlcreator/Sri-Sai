<?php

namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\Blog;
use App\Models\Event;
use App\Models\Trustee;
use App\Models\TempleTiming;
use App\Models\Page;

class HomeController extends Controller
{
    public function index(): void
    {
        $blogs = Blog::where('status', 'published')
            ->orderBy('created_at', 'DESC')
            ->limit(3)
            ->get();

        $events = Event::upcoming(3);

        $trustees = Trustee::allActive();

        $timings = TempleTiming::where('is_active', 1)
            ->orderBy('sort_order')
            ->get();

        $aboutPage = Page::findBy('slug', 'about');

        $this->render('home.index', [
            'pageTitle' => 'Sri Sai Mission — Religious & Charitable Trust',
            'pageClass' => 'home',
            'blogs'     => $blogs,
            'events'    => $events,
            'trustees'  => $trustees,
            'timings'   => $timings,
            'aboutPage' => $aboutPage,
        ]);
    }
}
