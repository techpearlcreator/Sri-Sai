<?php

namespace App\Controllers\Web;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index(): void
    {
        $this->view('home.index', [
            'pageTitle' => 'Sri Sai Mission — Home',
        ]);
    }
}
