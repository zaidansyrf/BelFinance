<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    public function view(){
        return view('keuangan-pengeluaran');
    }
}
