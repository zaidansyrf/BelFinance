<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\Item;
use App\Models\Source;
use App\Services\PenjualanService;
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
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[] = [
                'month' => $i,
                'total' => $monthlyIncome[$i]->total ?? 0,
            ];
        }

        return response()->json($months);
    }

    // method tampil
    public function index()
    {
        // Ambil data income bertipe 'payment' (kolom type ada di tabel incomes)
        $pembayarans = Income::with('source')
            ->where('type', 'payment')
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        return view('keuangan-pembayaran', compact('pembayarans'));
    }

    public function store(Request $request, PenjualanService $penjualan)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'nullable|date',
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

    public function create()
    {
        $allItems = Item::all();
        $allSources = Source::all();

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

    // method menampilkan detail income
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
        $pembayaran = Income::with(['source', 'incomeDetails.item', 'creator'])->findOrFail($id);

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
