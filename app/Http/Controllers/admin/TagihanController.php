<?php

namespace App\Http\Controllers\admin;

use App\Models\Tagihan;
use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    public function index()
    {
        $tagihans = Tagihan::all();
        return view('kategori.sumber-keluar.index', compact('tagihans'));
    }

    public function store(Request $request)
    {


        $request->validate([
            'nama' => 'required|max:255',
        ]);
        Tagihan::create($request->all());
        return redirect()->route('sumber-keluar.index')->with('success', 'Sumber berhasil ditambahkan.');
    }

}

