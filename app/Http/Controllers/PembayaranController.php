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
    public function chartIncome()
{
    $monthlyIncome = Income::selectRaw('MONTH(date) as month, SUM(amount) as total')
        ->groupBy('month')
        ->orderBy('month')
        ->get()
        ->keyBy('month');

    // Buat array dengan semua bulan (1-12) dengan nilai default 0
    $months = [];
    for ($i = 1; $i <= 12; $i++) {
        $months[] = [
            'month' => $i,
            'total' => $monthlyIncome[$i]->total ?? 0  // Jika tidak ada transaksi, nilai = 0
        ];
    }

    return response()->json($months);
}

    public function index()
    {
        // Ambil data income yang memiliki detail bertipe 'payment'
        $pembayarans = Income::with('source')
            ->whereHas('incomeDetails', function ($query) {
                $query->where('type', 'payment');
            })
            ->get();

        return view('keuangan-pembayaran', compact('pembayarans'));
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
                    'type' => 'payment', // Tipe pembayaran
                ]);
            }

            // Commit transaksi
            DB::commit();

            // Redirect ke halaman lain
            return redirect()->route('pembayaran.index')->with('success', 'Pesanan berhasil disimpan!');
        } catch (\Exception $e) {
            // Rollback transaksi jika ada error
            DB::rollBack();

            // Log error dan kembalikan ke halaman sebelumnya dengan pesan error
            \Log::error('Gagal menyimpan data pembayaran: ' . $e->getMessage());
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
        $pembayaran = Income::with('incomeDetails')->findOrFail($id);

        DB::beginTransaction();
        try {
            // Kurangi stok item berdasarkan detail pembayaran
            foreach ($pembayaran->incomeDetails as $detail) {
                $item = Item::find($detail->item_id);
                if ($item) {
                    $item->decrement('quantity', $detail->quantity);
                }
            }

            // Hapus detail yang terkait
            $pembayaran->incomeDetails()->delete();

            // Hapus income (pembayaran)
            $pembayaran->delete();

            DB::commit();

            return redirect()->route('pembayaran.index')->with('success', 'Pembayaran berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Gagal menghapus pembayaran: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus data. Silakan coba lagi.');
        }
    }




    public function detail(Request $request)
    {
        $request->validate([
            'source_id' => 'required|exists:sources,id',
            'date' => 'required|date',
        ]);

        $incomes = Income::where('source_id', $request->source_id)
            ->whereDate('date', $request->date)
            ->with(['incomeDetails.item', 'source'])
            ->get();

        $details = [];

        foreach ($incomes as $income) {
            foreach ($income->incomeDetails as $detail) {
                $details[] = [
                    'sumber' => $income->source->name,
                    'menu' => $detail->item->name,
                    'jumlah' => $detail->quantity,
                    'total' => $detail->subtotal,
                ];
            }
        }

        return view('pembayaran.detail-pengbayaran', compact('details'));
    }

    public function show($id)
    {
        // Ambil data income berdasarkan ID
        $pembayaran = Income::with(['source', 'incomeDetails.item'])->findOrFail($id);

        return view('pembayaran.detail-pembayaran', compact('pembayaran'));
    }

    public function dailySummary()
{
    $summary = Income::with('source')
        ->select('source_id', DB::raw('COUNT(*) as jumlah'), DB::raw('DATE(date) as tanggal'))
        ->whereDate('date', today())
        ->groupBy('source_id', DB::raw('DATE(date)'))
        ->get()
        ->map(function ($item) {
            return [
                'tipe' => $item->source->name ?? 'Tidak diketahui',
                'jumlah' => $item->jumlah,
                'tanggal' => $item->tanggal,
                'source_id' => $item->source_id,
            ];
        });

    return view('keuangan-detail-pesanan', compact('summary'));
}


}
