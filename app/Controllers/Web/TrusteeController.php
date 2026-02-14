<?php

namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\Trustee;

class TrusteeController extends Controller
{
    public function index(): void
    {
        $main = Trustee::mainTrustees();
        $coopted = Trustee::where('trustee_type', 'co-opted')
            ->andWhere('is_active', 1)
            ->orderBy('sort_order')
            ->get();

        $this->render('trustees.index', [
            'pageTitle'      => 'Our Trustees — Sri Sai Mission',
            'pageClass'      => 'trustees',
            'mainTrustees'   => $main,
            'cooptedTrustees'=> $coopted,
        ]);
    }
}
