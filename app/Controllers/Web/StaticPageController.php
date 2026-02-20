<?php

namespace App\Controllers\Web;

use App\Core\Controller;

/**
 * Serves hardcoded static pages (About Us, Vision, Mission).
 * Content is maintained directly in the view files:
 *   views/pages/about.php
 *   views/pages/vision.php
 *   views/pages/mission.php
 */
class StaticPageController extends Controller
{
    public function about(): void
    {
        $this->render('pages.about', [
            'pageTitle' => __('page.about_title') . ' — Sri Sai Mission',
            'pageClass' => 'page',
        ]);
    }

    public function mission(): void
    {
        $this->render('pages.mission', [
            'pageTitle' => __('page.mission_title') . ' — Sri Sai Mission',
            'pageClass' => 'page',
        ]);
    }

    public function vision(): void
    {
        $this->render('pages.vision', [
            'pageTitle' => __('page.vision_title') . ' — Sri Sai Mission',
            'pageClass' => 'page',
        ]);
    }
}
