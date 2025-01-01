<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Income;
use App\Models\Source;
use App\Models\IncomeDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    public function index()
    {
        return view('keuangan-pembayaran');
    }

    public function store(Request $request)
{
    // Validasi input
    // dd($request->all());
    $request->validate([
        'name' => 'required|string|max:255',
        'source_id' => 'required|exists:sources,id',
        'items' => 'required|array',
        'items.*.item_id' => 'required|exists:items,id',
        'items.*.quantity' => 'required|integer|min:1',
    ]);

    

    // Persiapan data
    $selectedItems = $request->items;
    $totalAmount = 0;
    $items = [];

    // Ambil data item dari database
    $itemIds = collect($selectedItems)->pluck('item_id');
    $itemDataMap = Item::whereIn('id', $itemIds)->get()->keyBy('id');

    foreach ($selectedItems as $item) {
        $itemData = $itemDataMap->get($item['item_id']);
        if ($itemData) {
            $subtotal = $itemData->price * $item['quantity'];
            $totalAmount += $subtotal;

            // Tambahkan quantity ke item
            $itemData->increment('quantity', $item['quantity']);

            $items[] = [
                'item_id' => $item['item_id'],
                'quantity' => $item['quantity'],
                'subtotal' => $subtotal,
            ];
        }
    }

    // Gunakan transaksi untuk menjamin konsistensi data
    DB::beginTransaction();
    try {
        // Simpan data ke tabel `incomes`
        $income = Income::create([
            'name' => $request->name,
            'amount' => $totalAmount,
            'date' => now(),
            'description' => 'Pesanan baru',
            'source_id' => $request->source_id,
        ]);

        // Simpan data ke tabel `income_details`
        foreach ($items as $detail) {
            IncomeDetail::create([
                'income_id' => $income->id, // Hubungkan ke income
                'item_id' => $detail['item_id'], // Hubungkan ke item
                'quantity' => $detail['quantity'], // Jumlah item
                'subtotal' => $detail['subtotal'], // Subtotal
            ]);
        }

        // Commit transaksi
        DB::commit();

        // Redirect ke halaman lain dengan pesan sukses
        return redirect()->route('pembayaran.view')->with('success', 'Pesanan berhasil disimpan!');
    } catch (\Exception $e) {
        // Rollback transaksi jika ada error
        DB::rollBack();

        // Log error dan kembalikan ke halaman sebelumnya dengan pesan error
        \Log::error('Gagal menyimpan data pembayaran: ' . $e->getMessage());
        return redirect()->back()->withErrors(['error' => 'Gagal menyimpan data. Silakan coba lagi.']);
    }
}

    public function create(Request $request)
    {
        $allItems = Item::all(); // Ambil semua data item
        $allSources = Source::all(); // Ambil semua sumber pendapatan

        return view('keuangan-create-pembayaran', compact('allItems', 'allSources'));
    }
    public function deleteItem($incomeDetailId)
{
    // Temukan income detail berdasarkan ID
    $incomeDetail = IncomeDetail::findOrFail($incomeDetailId);

    // Hapus income detail
    $incomeDetail->delete();

    return redirect()->route('pembayaran.view')->with('success', 'Item berhasil dihapus');
}
}
