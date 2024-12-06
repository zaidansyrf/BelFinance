<?php

namespace App\Http\Controllers;

use App\Models\MetodeTransaksi;
use Illuminate\Http\Request;

class MetodeTransaksiController extends Controller
{
    public function index()
    {
        $metodeTransaksi = MetodeTransaksi::all();
        return view('metode_transaksi.index', compact('metodeTransaksi'));
    }

    public function create()
    {
        return view('metode_transaksi.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'metode_transaksi' => 'required|string|max:50',
        ]);
    
        // Simpan data ke database
        MetodeTransaksi::create([
            'metode_transaksi' => $request->metode_transaksi,
        ]);
    
        return redirect()->route('metode-transaksi.index')->with('success', 'Metode Transaksi berhasil ditambahkan.');
    }
    
    public function edit($id)
    {
        $metodeTransaksi = MetodeTransaksi::findOrFail($id);
        return view('metode_transaksi.edit', compact('metodeTransaksi'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'metode_transaksi' => 'required|string|max:50',
        ]);

        $metodeTransaksi = MetodeTransaksi::findOrFail($id);
        $metodeTransaksi->update($request->only('metode_transaksi'));

        return redirect()->route('metode-transaksi.index')->with('success', 'Metode transaksi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        MetodeTransaksi::findOrFail($id)->delete();
        return redirect()->route('metode-transaksi.index')->with('success', 'Metode transaksi berhasil dihapus.');
    }
}
