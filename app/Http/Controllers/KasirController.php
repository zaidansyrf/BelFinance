<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\IncomeDetail;
use App\Models\Item;
use App\Models\Source;
use App\Services\PenjualanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class KasirController extends Controller
{
    public const JENIS_PESANAN = ['Pesanan Offline', 'Pesanan Online'];

    /**
     * Halaman POS: pilih menu, atur jumlah, lalu bayar.
     */
    public function pos()
    {
        $items = Item::orderBy('name')->get();
        $sources = Source::orderBy('id')->get();

        return view('kasir.pos', [
            'items' => $items,
            'sources' => $sources,
            'jenisPesanan' => self::JENIS_PESANAN,
            'cashSourceIds' => $sources->filter(fn ($s) => $this->isCash($s))->pluck('id')->values(),
        ]);
    }

    public function checkout(Request $request, PenjualanService $penjualan)
    {
        $data = $request->validate([
            'name' => ['required', Rule::in(self::JENIS_PESANAN)],
            'source_id' => 'required|exists:sources,id',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1|max:999',
            'paid_amount' => 'nullable|integer|min:0',
        ], [
            'items.required' => 'Keranjang masih kosong.',
            'source_id.required' => 'Pilih metode pembayaran.',
        ]);

        // Pembayaran tunai wajib mengisi uang diterima; non-tunai dianggap pas
        if ($this->isCash(Source::find($data['source_id']))) {
            $request->validate(['paid_amount' => 'required'], ['paid_amount.required' => 'Isi uang yang diterima dari pelanggan.']);
        } else {
            $data['paid_amount'] = null;
        }

        $income = $penjualan->simpan($data + ['description' => 'Transaksi POS']);

        return redirect()->route('kasir.struk', $income)->with('success', 'Transaksi berhasil disimpan.');
    }

    /**
     * Dashboard harian kasir: ringkasan penjualan hari ini (tanpa pengeluaran).
     */
    public function beranda()
    {
        $today = today();
        $penjualanHariIni = Income::with(['source', 'creator'])
            ->where('type', 'payment')
            ->whereDate('date', $today)
            ->latest('date')
            ->get();

        $milikSaya = $penjualanHariIni->where('created_by', auth()->id());

        $menuTerlaris = IncomeDetail::query()
            ->join('incomes', 'incomes.id', '=', 'income_details.income_id')
            ->join('items', 'items.id', '=', 'income_details.item_id')
            ->where('incomes.type', 'payment')
            ->whereNull('incomes.deleted_at')
            ->whereDate('incomes.date', $today)
            ->groupBy('items.id', 'items.name')
            ->orderByDesc(DB::raw('SUM(income_details.quantity)'))
            ->limit(5)
            ->get(['items.name', DB::raw('SUM(income_details.quantity) as jumlah'), DB::raw('SUM(income_details.subtotal) as total')]);

        return view('kasir.beranda', [
            'totalPenjualan' => $penjualanHariIni->sum('amount'),
            'jumlahTransaksi' => $penjualanHariIni->count(),
            'perSumber' => $penjualanHariIni->groupBy(fn ($i) => $i->source->name ?? '-')
                ->map(fn ($rows) => ['jumlah' => $rows->count(), 'total' => $rows->sum('amount')])
                ->sortByDesc('total'),
            'menuTerlaris' => $menuTerlaris,
            'transaksiSaya' => $milikSaya,
            'totalSaya' => $milikSaya->sum('amount'),
            'tunaiSaya' => $milikSaya->filter(fn ($i) => $this->isCash($i->source))->sum('amount'),
        ]);
    }

    /**
     * Struk 58mm. Kasir hanya bisa membuka struk transaksinya sendiri.
     */
    public function struk(Income $income)
    {
        abort_unless($income->type === 'payment', 404);
        abort_if(auth()->user()->isKasir() && $income->created_by !== auth()->id(), 403);

        $income->load(['source', 'incomeDetails.item', 'creator']);

        return view('kasir.struk', compact('income'));
    }

    private function isCash(?Source $source): bool
    {
        return $source && in_array(strtoupper(trim($source->name)), ['CASH', 'TUNAI'], true);
    }
}
