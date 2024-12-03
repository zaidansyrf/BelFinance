<?php

namespace App\Http\Controllers;

use App\Models\SumberSeluruh;
use App\Models\MetodeTransaksi;
use Illuminate\Http\Request;

class SumberSeluruhController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = SumberSeluruh::with('metodeTransaksi')->get();
        return view('sumber_seluruh.index', compact('data'));
    }

    public function detailByMetode($metode_transaksi)
    {
        // Ambil data berdasarkan metode transaksi
        $data = SumberSeluruh::whereHas('metodeTransaksi', function ($query) use ($metode_transaksi) {
            $query->where('metode_transaksi', $metode_transaksi);
        })->get();

        // Kirim data ke view
        return view('sumber_seluruh.detail', compact('data', 'metode_transaksi'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $metodeTransaksis = MetodeTransaksi::all();
        return view('sumber_seluruh.create', compact('metodeTransaksis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_metode_transaksis' => 'required|exists:metode_transaksis,id',
            'pemasukkan' => 'required|numeric',
            'pengeluaran' => 'required|numeric',
            'tanggal' => 'required|date',
        ]);

        SumberSeluruh::create($request->all());

        return redirect()->route('sumber-seluruh.index')->with('success', 'Data berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SumberSeluruh $sumberSeluruh)
    {
        return view('sumber_seluruh.show', compact('sumberSeluruh'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SumberSeluruh $sumberSeluruh)
    {
        $metodeTransaksis = MetodeTransaksi::all();
        return view('sumber_seluruh.edit', compact('sumberSeluruh', 'metodeTransaksis'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SumberSeluruh $sumberSeluruh)
    {
        $request->validate([
            'id_metode_transaksis' => 'required|exists:metode_transaksis,id',
            'pemasukkan' => 'required|numeric',
            'pengeluaran' => 'required|numeric',
            'tanggal' => 'required|date',
        ]);

        $sumberSeluruh->update($request->all());

        return redirect()->route('sumber-seluruh.index')->with('success', 'Data berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SumberSeluruh $sumberSeluruh)
    {
        $sumberSeluruh->delete();
        return redirect()->route('sumber-seluruh.index')->with('success', 'Data berhasil dihapus.');
    }
}
