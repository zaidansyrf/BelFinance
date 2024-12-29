<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use Illuminate\Http\Request;

class BillController extends Controller
{
    public function index()
    {
        $Bills = Bill::all();
        return view('kategori.bill.index', compact('Bills'));
    }
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|max:255',
        ]);
        Bill::create($request->all());
        return redirect()->route('sumber-keluar.index')->with('success', 'Bill berhasil ditambahkan.');
    }
    public function edit(string $id)
    {
        
    }

}

