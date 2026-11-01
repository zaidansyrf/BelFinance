<?php

namespace App\Http\Controllers;

use App\Services\DashboardKeuanganService;

class AdminKeuanganController extends Controller
{
    public function view(DashboardKeuanganService $dashboard)
    {
        return view('admin-keuangan', $dashboard->ringkasan());
    }
}
