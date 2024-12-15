<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class KeuanganCreatePembayaranController extends Controller
{
    public function view(){
        $allItems = Item::all(); // Fetch all items from the 'items' table
        return view('keuangan-create-pembayaran', compact('allItems'));
    }
}
