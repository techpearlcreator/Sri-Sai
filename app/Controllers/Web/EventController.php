<?php

namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\Event;

class EventController extends Controller
{
    public function index(): void
    {
        $page = max(1, (int) ($this->getQuery('page', 1)));
        $result = Event::where('status', 'upcoming')
            ->orderBy('event_date')
            ->paginate($page, 12);

        // Also get past events
        $past = Event::where('status', 'completed')
            ->orderBy('event_date', 'DESC')
            ->limit(6)
            ->get();

        $this->render('events.index', [
            'pageTitle'  => 'Events — Sri Sai Mission',
            'pageClass'  => 'events',
            'events'     => $result['data'],
            'pastEvents' => $past,
            'pagination' => [
                'page'        => $result['page'],
                'total_pages' => (int) ceil($result['total'] / $result['per_page']),
                'total'       => $result['total'],
            ],
        ]);
    }

    public function show(string $slug): void
    {
        $event = Event::findBySlug($slug);

        if (!$event) {
            http_response_code(404);
            $this->render('errors.404', [
                'pageTitle' => 'Not Found — Sri Sai Mission',
                'pageClass' => 'error',
            ]);
            return;
        }

        $this->render('events.show', [
            'pageTitle' => htmlspecialchars($event->title) . ' — Events',
            'pageClass' => 'events',
            'event'     => $event,
        ]);
    }
}
