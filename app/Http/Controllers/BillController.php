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
    public function destroy(Bill $sumber_keluar)
    {
        // Pastikan data ditemukan
        if (!$sumber_keluar) {
            return redirect()->route('sumber-keluar.index')->with('error', 'Data tidak ditemukan.');
        }
    
        // Hapus data
        $sumber_keluar->delete();
    
        return redirect()->route('sumber-keluar.index')->with('success', 'Data berhasil dihapus.');
    }
    public function edit(string $id)
    {
        $bills = bill::findOrFail($id);
        

        return view('keuangan-bill-edit', compact('bills'));
    }
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);
        $bills = bill::findOrFail($id);
        $bills->update($request->all());
        return redirect()->route('sumber-keluar.index')->with('success', 'Sumber berhasil diupdate.');
    }
}