<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminTransaksiPembayaranController extends Controller
{
    public function view(){
        return view('admin-transaksi-pembayaran');
    }
}
