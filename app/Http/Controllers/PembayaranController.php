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

    public function index(Request $request)
    {
        $search = $request->input('search');
        $sourceId = $request->input('source_id');
        $tanggalAwal = $request->input('tanggal_awal');
        $tanggalAkhir = $request->input('tanggal_akhir');
        $sort = $request->input('sort', 'desc');

        $query = Income::with(['source', 'platformPromo'])
            ->where('type', 'payment');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('amount', 'like', "%{$search}%")
                    ->orWhere('gross_amount', 'like', "%{$search}%")
                    ->orWhere('date', 'like', "%{$search}%")
                    ->orWhereHas('source', fn ($s) => $s->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('platformPromo', fn ($p) => $p->where('name', 'like', "%{$search}%"));
            });
        }

        if ($sourceId) {
            $query->where('source_id', $sourceId);
        }

        if ($tanggalAwal) {
            $query->whereDate('date', '>=', $tanggalAwal);
        }

        if ($tanggalAkhir) {
            $query->whereDate('date', '<=', $tanggalAkhir);
        }

        if ($sort === 'asc' || $sort === 'terlama') {
            $query->orderBy('date', 'asc')->orderBy('id', 'asc');
        } else {
            $query->orderBy('date', 'desc')->orderBy('id', 'desc');
        }

        $pembayarans = $query->paginate(10)->withQueryString();
        $sources = Source::all();

        return view('keuangan-pembayaran', compact('pembayarans', 'sources', 'search'));
    }

    public function create()
    {
        // Eager load promo aktif pada menu dan preset promo pada masing-masing platform
        $allItems = Item::with('promos')->get();
        $allSources = Source::with(['platformPromos' => function ($query) {
            $query->where('is_active', true);
        }])->get();

        return view('keuangan-create-pembayaran', compact('allItems', 'allSources'));
    }

    public function store(Request $request, PenjualanService $penjualan)
    {
        if ($request->input('platform_promo_id') === 'null' || $request->input('platform_promo_id') === '0' || empty($request->input('platform_promo_id')) || $request->input('name') !== 'Pesanan Online') {
            $request->merge(['platform_promo_id' => null]);
        }

        $data = $request->validate([
            'name'                     => 'required|string|max:255',
            'date'                     => 'nullable|date|before_or_equal:today',
            'description'              => 'nullable|string',
            'source_id'                => 'required|exists:sources,id',
            'shift_id'                 => 'nullable|exists:shifts,id',
            'platform_promo_id'        => 'nullable|exists:platform_promos,id',
            'paid_amount'              => 'nullable|integer|min:0',
            'change_amount'            => 'nullable|integer|min:0',
            'items'                    => 'required|array|min:1',
            'items.*.item_id'          => 'required|exists:items,id',
            'items.*.quantity'         => 'required|integer|min:1',
            'items.*.apply_item_promo' => 'nullable|boolean',
        ]);

        try {
            $penjualan->simpan($data);

            return redirect()->route('pembayaran.index')->with('success', 'Pesanan berhasil disimpan!');
        } catch (\Exception $e) {
            \Log::error('Gagal menyimpan data pembayaran: ' . $e->getMessage());

            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $pembayaran = Income::findOrFail($id);

        try {
            $pembayaran->hapus();

            return redirect()->route('pembayaran.index')->with('success', 'Pembayaran berhasil dihapus.');
        } catch (\Exception $e) {
            \Log::error('Gagal menghapus pembayaran: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Gagal menghapus data. Silakan coba lagi.');
        }
    }

    public function detail(Request $request)
    {
        $request->validate([
            'source_id' => 'required|exists:sources,id',
            'date'      => 'required|date',
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
                    'menu'   => $detail->item->name,
                    'jumlah' => $detail->quantity,
                    'total'  => $detail->subtotal,
                ];
            }
        }

        return view('pembayaran.detail-pengbayaran', compact('details'));
    }

    public function show($id)
    {
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
                    'tipe'      => $item->source->name ?? 'Tidak diketahui',
                    'jumlah'    => $item->jumlah,
                    'tanggal'   => $item->tanggal,
                    'source_id' => $item->source_id,
                ];
            });

        return view('keuangan-detail-pesanan', compact('summary'));
    }
}