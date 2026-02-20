<?php

namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\Setting;

class DonationController extends Controller
{
    public function index(): void
    {
        $settings = Setting::allAsArray();

        $this->render('donations.index', [
            'pageTitle' => 'Donate — Sri Sai Mission',
            'pageClass' => 'donations',
            'settings'  => $settings,
        ]);
    }
}
