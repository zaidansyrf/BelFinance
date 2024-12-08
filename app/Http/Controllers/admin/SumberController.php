<?php

namespace App\Http\Controllers\admin;

use App\Models\Sumber;
use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;

class SumberController extends Controller
{
    public function index()
    {
        $sumbers = Sumber::all();
        return view('kategori.sumber-masuk.index', compact('sumbers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:255',
        ]);

        Sumber::create($request->all());
        return redirect()->route('sumber-masuk.index')->with('success', 'Sumber berhasil ditambahkan.');
    }

    public function update(Request $request, Sumber $sumber)
    {
        $request->validate([
            'nama' => 'required|max:255',
        ]);

        $sumber->update($request->all());
        return redirect()->route('admin.sumber.index')->with('success', 'Sumber berhasil diperbarui.');
    }

    public function destroy(Sumber $sumber)
    {
        $sumber->delete();
        return redirect()->route('admin.sumber.index')->with('success', 'Sumber berhasil dihapus.');
    }
}

