<?php

namespace App\Http\Controllers;

use App\Services\DashboardKeuanganService;

class OwnerBerandaController extends Controller
{
    public function view(DashboardKeuanganService $dashboard)
    {
        return view('owner-beranda', array_merge(
            $dashboard->ringkasan(),
            $dashboard->menuTerlaris()
        ));
    }
}
