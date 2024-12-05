<?php

namespace App\Http\Controllers;

use App\Models\Sumber;
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
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        // Menyimpan data ke database
        Sumber::create($request->all());

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
}
