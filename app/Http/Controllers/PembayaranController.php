<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function view(){
        return view('keuangan-pembayaran');
    }
}
