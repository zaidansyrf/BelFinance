<?php

namespace App\Services\Ai;

use App\Models\Expense;
use App\Models\Income;
use App\Models\Item;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

/**
 * Menyusun ringkasan data keuangan untuk dikirim ke AI.
 *
 * Yang dikirim hanya angka agregat (total, per sumber, per kategori, per menu),
 * bukan data mentah per transaksi, supaya hemat dan tidak membocorkan detail.
 */
class KonteksKeuangan
{
    public function ringkasan(?Carbon $hariIni = null): array
    {
        $hariIni = ($hariIni ?? now())->copy()->startOfDay();
        $bulanIni = [$hariIni->copy()->startOfMonth(), $hariIni->copy()->endOfMonth()];
        $bulanLalu = [$hariIni->copy()->subMonthNoOverflow()->startOfMonth(), $hariIni->copy()->subMonthNoOverflow()->endOfMonth()];

        return [
            'usaha' => 'Belindo Kitchen (rumah makan)',
            'mata_uang' => 'IDR (Rupiah, tanpa desimal)',
            'hari_ini' => $hariIni->locale('id')->translatedFormat('l, d F Y'),
            'periode' => [
                'hari_ini' => $this->periode($hariIni, $hariIni),
                'kemarin' => $this->periode($hariIni->copy()->subDay(), $hariIni->copy()->subDay()),
                '7_hari_terakhir' => $this->periode($hariIni->copy()->subDays(6), $hariIni),
                'bulan_ini_sampai_hari_ini' => $this->periode($bulanIni[0], $hariIni),
                'bulan_lalu' => $this->periode(...$bulanLalu),
            ],
            'penjualan_harian_30_hari' => $this->penjualanHarian($hariIni->copy()->subDays(29), $hariIni),
            'bulanan_tahun_ini' => $this->bulanan($hariIni->year),
            'pemasukan_per_sumber' => [
                'bulan_ini' => $this->pemasukanPerSumber($bulanIni[0], $hariIni),
                'bulan_lalu' => $this->pemasukanPerSumber(...$bulanLalu),
            ],
            'pengeluaran_per_kategori' => [
                'bulan_ini' => $this->pengeluaranPerKategori($bulanIni[0], $hariIni),
                'bulan_lalu' => $this->pengeluaranPerKategori(...$bulanLalu),
            ],
            'menu_terjual' => [
                'bulan_ini' => $this->menuTerjual($bulanIni[0], $hariIni),
                'bulan_lalu' => $this->menuTerjual(...$bulanLalu),
            ],
            'menu_belum_terjual_bulan_ini' => $this->menuBelumTerjual($bulanIni[0], $hariIni),
            'jumlah_menu_aktif' => Item::count(),
            'daftar_menu_dan_harga' => $this->daftarMenu(),
            'catatan_data' => 'Harga pokok/HPP dan stok bahan tidak dicatat di BelFinance.',
        ];
    }

    /**
     * Semua menu dengan harga jual saat ini (urut dari termurah), agar AI bisa menjawab
     * pertanyaan soal harga untuk menu yang belum pernah terjual juga.
     */
    private function daftarMenu(): array
    {
        return Item::orderBy('price')->orderBy('name')->limit(200)->get(['name', 'price'])
            ->map(fn ($item) => ['menu' => $item->name, 'harga' => (int) $item->price])
            ->all();
    }

    private function periode(Carbon $dari, Carbon $sampai): array
    {
        $income = Income::whereDate('date', '>=', $dari)->whereDate('date', '<=', $sampai);
        $pemasukan = (int) (clone $income)->sum('amount');
        $penjualan = (int) (clone $income)->where('type', 'payment')->sum('amount');
        $pengeluaran = (int) Expense::whereDate('date', '>=', $dari)->whereDate('date', '<=', $sampai)->sum('amount');

        return [
            'dari' => $dari->toDateString(),
            'sampai' => $sampai->toDateString(),
            'total_pemasukan' => $pemasukan,
            'pemasukan_dari_penjualan' => $penjualan,
            'pemasukan_lain' => $pemasukan - $penjualan,
            'jumlah_transaksi_penjualan' => (clone $income)->where('type', 'payment')->count(),
            'total_pengeluaran' => $pengeluaran,
            'laba_rugi' => $pemasukan - $pengeluaran,
        ];
    }

    private function penjualanHarian(Carbon $dari, Carbon $sampai): array
    {
        $rows = Income::where('type', 'payment')
            ->whereDate('date', '>=', $dari)->whereDate('date', '<=', $sampai)
            ->selectRaw('DATE(date) as tgl, SUM(amount) as total, COUNT(*) as transaksi')
            ->groupBy(DB::raw('DATE(date)'))
            ->get()
            ->keyBy('tgl');

        $hasil = [];
        foreach (CarbonPeriod::create($dari, $sampai) as $tgl) {
            $row = $rows->get($tgl->toDateString());
            $hasil[$tgl->toDateString()] = [
                'hari' => $tgl->locale('id')->translatedFormat('l'),
                'penjualan' => (int) ($row->total ?? 0),
                'transaksi' => (int) ($row->transaksi ?? 0),
            ];
        }

        return $hasil;
    }

    private function bulanan(int $tahun): array
    {
        $pemasukan = Income::whereYear('date', $tahun)
            ->selectRaw('MONTH(date) as bulan, SUM(amount) as total')->groupBy(DB::raw('MONTH(date)'))->pluck('total', 'bulan');
        $pengeluaran = Expense::whereYear('date', $tahun)
            ->selectRaw('MONTH(date) as bulan, SUM(amount) as total')->groupBy(DB::raw('MONTH(date)'))->pluck('total', 'bulan');

        $hasil = [];
        foreach (range(1, 12) as $bulan) {
            if (! isset($pemasukan[$bulan]) && ! isset($pengeluaran[$bulan])) {
                continue;
            }
            $nama = Carbon::create($tahun, $bulan, 1)->locale('id')->translatedFormat('F Y');
            $hasil[$nama] = [
                'pemasukan' => (int) ($pemasukan[$bulan] ?? 0),
                'pengeluaran' => (int) ($pengeluaran[$bulan] ?? 0),
                'laba_rugi' => (int) ($pemasukan[$bulan] ?? 0) - (int) ($pengeluaran[$bulan] ?? 0),
            ];
        }

        return $hasil;
    }

    private function pemasukanPerSumber(Carbon $dari, Carbon $sampai): array
    {
        return Income::query()
            ->join('sources', 'sources.id', '=', 'incomes.source_id')
            ->whereDate('incomes.date', '>=', $dari)->whereDate('incomes.date', '<=', $sampai)
            ->groupBy('sources.name')
            ->orderByDesc(DB::raw('SUM(incomes.amount)'))
            ->get(['sources.name', DB::raw('SUM(incomes.amount) as total'), DB::raw('COUNT(*) as transaksi')])
            ->mapWithKeys(fn ($r) => [$r->name => ['total' => (int) $r->total, 'transaksi' => (int) $r->transaksi]])
            ->all();
    }

    private function pengeluaranPerKategori(Carbon $dari, Carbon $sampai): array
    {
        return Expense::query()
            ->join('bills', 'bills.id', '=', 'expenses.bill_id')
            ->whereDate('expenses.date', '>=', $dari)->whereDate('expenses.date', '<=', $sampai)
            ->groupBy('bills.name')
            ->orderByDesc(DB::raw('SUM(expenses.amount)'))
            ->get(['bills.name', DB::raw('SUM(expenses.amount) as total')])
            ->mapWithKeys(fn ($r) => [$r->name => (int) $r->total])
            ->all();
    }

    private function menuTerjual(Carbon $dari, Carbon $sampai, int $limit = 15): array
    {
        return DB::table('income_details')
            ->join('incomes', 'incomes.id', '=', 'income_details.income_id')
            ->join('items', 'items.id', '=', 'income_details.item_id')
            ->whereNull('incomes.deleted_at')
            ->where('incomes.type', 'payment')
            ->whereDate('incomes.date', '>=', $dari)->whereDate('incomes.date', '<=', $sampai)
            ->groupBy('items.id', 'items.name', 'items.price')
            ->orderByDesc(DB::raw('SUM(income_details.quantity)'))
            ->limit($limit)
            ->get(['items.name', 'items.price', DB::raw('SUM(income_details.quantity) as porsi'), DB::raw('SUM(income_details.subtotal) as omzet')])
            ->map(fn ($r) => ['menu' => $r->name, 'harga' => (int) $r->price, 'porsi' => (int) $r->porsi, 'omzet' => (int) $r->omzet])
            ->all();
    }

    private function menuBelumTerjual(Carbon $dari, Carbon $sampai): array
    {
        $terjual = DB::table('income_details')
            ->join('incomes', 'incomes.id', '=', 'income_details.income_id')
            ->whereNull('incomes.deleted_at')
            ->whereDate('incomes.date', '>=', $dari)->whereDate('incomes.date', '<=', $sampai)
            ->distinct()
            ->pluck('income_details.item_id');

        return Item::whereNotIn('id', $terjual)->orderBy('name')->limit(30)->pluck('name')->all();
    }
}
