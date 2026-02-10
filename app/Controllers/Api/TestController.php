<?php

namespace App\Controllers\Api;

use App\Core\Controller;
use App\Core\Database;
use App\Models\Trustee;
use App\Models\Page;
use App\Models\Setting;
use App\Models\Category;
use App\Models\TempleTiming;
use App\Services\SlugService;

class TestController extends Controller
{
    /**
     * GET /api/v1/test — Health check + DB connection test.
     */
    public function index(): void
    {
        $db = Database::getInstance();
        $result = $db->fetch("SELECT VERSION() as version, NOW() as server_time");

        $this->json([
            'message'       => 'Sri Sai Mission API is running!',
            'environment'   => $_ENV['APP_ENV'] ?? 'unknown',
            'php_version'   => PHP_VERSION,
            'mysql_version' => $result->version ?? 'unknown',
            'server_time'   => $result->server_time ?? 'unknown',
            'status'        => 'healthy',
        ]);
    }

    /**
     * GET /api/v1/test/models — Test query builder with real data.
     */
    public function models(): void
    {
        // Test: Model::all()
        $trustees = Trustee::allActive();

        // Test: Model::where()->paginate()
        $pages = Page::where('status', 'published')
            ->orderBy('menu_position')
            ->paginate(1, 5);

        // Test: Model::findBy()
        $siteName = Setting::getValue('site_name', 'Unknown');

        // Test: Model::where()->get()
        $blogCategories = Category::byType('blog');

        // Test: Model::where()->get()
        $timings = TempleTiming::allActive();

        // Test: SlugService
        $testSlug = SlugService::slugify('Sri Sai Dharisanam Magazine — Vol 5, Issue 3!');

        $this->json([
            'tests' => [
                'trustees_count'     => count($trustees),
                'trustees_first'     => $trustees[0]->name ?? null,
                'pages_total'        => $pages['total'],
                'pages_data_count'   => count($pages['data']),
                'pages_first_title'  => $pages['data'][0]->title ?? null,
                'site_name'          => $siteName,
                'blog_categories'    => count($blogCategories),
                'temple_timings'     => count($timings),
                'slug_test'          => $testSlug,
            ],
            'message' => 'All models and query builder working correctly!',
        ]);
    }
}
