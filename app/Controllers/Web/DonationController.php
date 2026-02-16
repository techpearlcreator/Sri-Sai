<?php

namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\Page;
use App\Models\Setting;

class DonationController extends Controller
{
    public function index(): void
    {
        $settings = Setting::allAsArray();
        $donationPage = Page::query()->where('slug', 'donations')->where('status', 'published')->first();

        $this->render('donations.index', [
            'pageTitle'    => 'Donate — Sri Sai Mission',
            'pageClass'    => 'donations',
            'settings'     => $settings,
            'donationPage' => $donationPage,
        ]);
    }
}
