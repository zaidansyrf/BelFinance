<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KeuanganCreatePengeluaranController extends Controller
{
    public function index()
    {
        return view('keuangan-create-pengeluaran');
    }
}
