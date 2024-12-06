<?php

namespace App\Http\Controllers\admin;

use App\Models\Sumber;
use App\Http\Controllers\Controller;  // pastikan Controller di-extend dengan benar
use Illuminate\Http\Request;

class SumberController extends Controller
{
    // Menampilkan semua data
    public function index()
    {
        $sumber = Sumber::all(); // Mengambil semua data sumber
        return view('sumber.index', compact('sumber'));
    }

    // Menampilkan form untuk menambahkan data baru
    public function create()
    {
        return view('sumber.create');
    }

    // Menyimpan data baru
    public function store(Request $request)
    {
        // Validate input data
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);
    
        // Create a new Sumber entry
        Sumber::create([
            'nama' => $request->nama,
        ]);
    
        // Redirect back with success message
        return redirect()->route('sumber.index')->with('success', 'Sumber berhasil ditambahkan!');
    }

    // Menampilkan form untuk mengedit data
    public function edit(Sumber $sumber)
    {
        return view('sumber.edit', compact('sumber'));
    }

    // Mengupdate data yang ada
    public function update(Request $request, Sumber $sumber)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        // Update data
        $sumber->update($request->all());

        return redirect()->route('sumber.index')->with('success', 'Sumber berhasil diperbarui!');
    }

    // Menghapus data
    public function destroy(Sumber $sumber)
    {
        $sumber->delete();
        return redirect()->route('sumber.index')->with('success', 'Sumber berhasil dihapus!');
    }

    // Menampilkan data berdasarkan filter
    public function filter(Request $request)
    {
        $sumber = Sumber::where('nama', 'LIKE', '%' . $request->cari . '%')->get();
        return view('sumber.index', compact('sumber'));
    }
}

