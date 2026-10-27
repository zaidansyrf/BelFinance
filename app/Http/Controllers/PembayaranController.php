<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\Item;
use App\Models\Source;
use App\Services\PenjualanService;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index()
    {
        // Ambil data income bertipe 'payment' (kolom type ada di tabel incomes)
        $pembayarans = Income::with('source')
            ->where('type', 'payment')
            ->get();

        return view('keuangan-pembayaran', compact('pembayarans'));
    }

    public function store(Request $request, PenjualanService $penjualan)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'source_id' => 'required|exists:sources,id',
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            $penjualan->simpan($data);

            return redirect()->route('pembayaran.index')->with('success', 'Pesanan berhasil disimpan!');
        } catch (\Exception $e) {
            \Log::error('Gagal menyimpan data pembayaran: '.$e->getMessage());

            return redirect()->back()->with('error', 'Gagal menyimpan data. Silakan coba lagi.');
        }
    }

    public function create(Request $request)
    {
        $allItems = Item::all(); // Ambil semua data item
        $allSources = Source::all(); // Ambil semua sumber pendapatan

        return view('keuangan-create-pembayaran', compact('allItems', 'allSources'));
    }

    public function destroy($id)
    {
        $pembayaran = Income::findOrFail($id);

        try {
            $pembayaran->hapus();

            return redirect()->route('pembayaran.index')->with('success', 'Pembayaran berhasil dihapus.');
        } catch (\Exception $e) {
            \Log::error('Gagal menghapus pembayaran: '.$e->getMessage());

            return redirect()->back()->with('error', 'Gagal menghapus data. Silakan coba lagi.');
        }
    }

    public function show($id)
    {
        // Ambil data income berdasarkan ID
        $pembayaran = Income::with(['source', 'incomeDetails.item', 'creator'])->findOrFail($id);

        return view('pembayaran.detail-pembayaran', compact('pembayaran'));
    }
}
