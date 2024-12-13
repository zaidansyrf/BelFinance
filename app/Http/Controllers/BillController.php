<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use Illuminate\Http\Request;

class BillController extends Controller
{
    public function index()
    {
        $Bills = Bill::all();
        return view('kategori.sumber-keluar.index', compact('Bills'));
    }
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|max:255',
        ]);
        Bill::create($request->all());
        return redirect()->route('sumber-keluar.index')->with('success', 'Tagihan berhasil ditambahkan.');
    }

}
