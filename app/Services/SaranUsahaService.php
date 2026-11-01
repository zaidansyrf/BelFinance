<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\Item;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Saran usaha dari data penjualan: menu laris/sepi, harga, stok, hari ramai, paket menu, dan pengeluaran.
 * Memakai aturan sederhana yang bisa dijelaskan (bukan AI), supaya hasilnya konsisten dan bisa diperiksa.
 */
class SaranUsahaService
{
    public const PERIODE_HARI = 30;

    // Di bawah jumlah transaksi ini saran dianggap belum bisa diandalkan
    public const MIN_TRANSAKSI = 10;

    public const PRIORITAS = ['peringatan' => 0, 'peluang' => 1, 'info' => 2];

    /**
     * @return array{saran: Collection<int, array{tipe: string, judul: string, detail: string, menu?: string}>, transaksi: int, cukup_data: bool, periode: string}
     */
    public function analisis(?Carbon $sekarang = null): array
    {
        $sekarang ??= now();
        $akhir = $sekarang->copy()->endOfDay();
        $mulai = $akhir->copy()->subDays(self::PERIODE_HARI)->startOfDay();
        $mulaiLalu = $mulai->copy()->subDays(self::PERIODE_HARI);

        $transaksi = $this->penjualan()->whereBetween('incomes.date', [$mulai, $akhir])->distinct()->count('incomes.id');
        $cukupData = $transaksi >= self::MIN_TRANSAKSI;

        $items = Item::all();
        $terjual = $this->terjualPerMenu($mulai, $akhir);
        $terjualLalu = $this->terjualPerMenu($mulaiLalu, $mulai->copy()->subSecond());

        $saran = collect();
        if ($cukupData) {
            $saran = $saran
                ->merge($this->menuLaris($items, $terjual))
                ->merge($this->menuSepi($items, $terjual))
                ->merge($this->perubahanPenjualan($items, $terjual, $terjualLalu))
                ->merge($this->hargaTerlaluTinggi($items, $terjual))
                ->merge($this->paketMenu($mulai, $akhir))
                ->merge($this->hariRamai($mulai, $akhir))
                ->merge($this->pengeluaran($mulai, $akhir, $mulaiLalu));
        }
        $saran = $saran->merge($this->stokMenipis($items, $terjual));

        return [
            'saran' => $saran->sortBy(fn ($s) => self::PRIORITAS[$s['tipe']])->values(),
            'transaksi' => $transaksi,
            'cukup_data' => $cukupData,
            'periode' => $mulai->locale('id')->translatedFormat('d M').' - '.$akhir->locale('id')->translatedFormat('d M Y'),
        ];
    }

    /**
     * Data per menu untuk bahan analisis AI: harga, terjual 30 hari terakhir dan 30 hari sebelumnya, stok.
     *
     * @return array<int, array{menu: string, harga: int, terjual_30_hari: int, terjual_30_hari_sebelumnya: int, stok: int|null}>
     */
    public function dataMenu(?Carbon $sekarang = null): array
    {
        $sekarang ??= now();
        $akhir = $sekarang->copy()->endOfDay();
        $mulai = $akhir->copy()->subDays(self::PERIODE_HARI)->startOfDay();
        $terjual = $this->terjualPerMenu($mulai, $akhir);
        $terjualLalu = $this->terjualPerMenu($mulai->copy()->subDays(self::PERIODE_HARI), $mulai->copy()->subSecond());

        return Item::orderBy('name')->get()->map(fn ($item) => [
            'menu' => $item->name,
            'harga' => (int) $item->price,
            'terjual_30_hari' => $terjual[$item->id] ?? 0,
            'terjual_30_hari_sebelumnya' => $terjualLalu[$item->id] ?? 0,
            'stok' => $item->stock,
        ])->all();
    }

    private function penjualan()
    {
        return DB::table('incomes')->where('incomes.type', 'payment')->whereNull('incomes.deleted_at');
    }

    /**
     * @return Collection<int, int> jumlah porsi terjual per item_id
     */
    private function terjualPerMenu(Carbon $dari, Carbon $sampai): Collection
    {
        return $this->penjualan()
            ->join('income_details', 'income_details.income_id', '=', 'incomes.id')
            ->whereBetween('incomes.date', [$dari, $sampai])
            ->groupBy('income_details.item_id')
            ->selectRaw('income_details.item_id, SUM(income_details.quantity) as jumlah')
            ->pluck('jumlah', 'item_id')
            ->map(fn ($n) => (int) $n);
    }

    private function menuLaris(Collection $items, Collection $terjual): Collection
    {
        $top = $terjual->sortDesc()->take(3);
        if ($top->isEmpty()) {
            return collect();
        }

        $nama = $top->map(fn ($n, $id) => ($items->firstWhere('id', $id)?->name ?? '?')." ({$n} porsi)")->implode(', ');

        return collect([[
            'tipe' => 'info',
            'judul' => 'Menu paling laris',
            'detail' => "{$nama}. Pastikan bahan selalu tersedia dan tampilkan di urutan atas.",
        ]]);
    }

    /**
     * Menu yang tidak laku sama sekali dalam periode ini.
     */
    private function menuSepi(Collection $items, Collection $terjual): Collection
    {
        $sepi = $items->filter(fn ($item) => ($terjual[$item->id] ?? 0) === 0);
        if ($sepi->isEmpty()) {
            return collect();
        }

        $contoh = $sepi->take(5)->pluck('name')->implode(', ');
        $lebih = $sepi->count() > 5 ? ' dan '.($sepi->count() - 5).' lainnya' : '';

        return collect([[
            'tipe' => 'peringatan',
            'judul' => "{$sepi->count()} menu tidak terjual sama sekali dalam ".self::PERIODE_HARI.' hari',
            'detail' => "{$contoh}{$lebih}. Coba beri promo atau turunkan harga, atau pertimbangkan menghapusnya dari daftar supaya pilihan tidak terlalu banyak.",
        ]]);
    }

    /**
     * Bandingkan penjualan 30 hari terakhir dengan 30 hari sebelumnya.
     */
    private function perubahanPenjualan(Collection $items, Collection $terjual, Collection $terjualLalu): Collection
    {
        $hasil = collect();

        foreach ($items as $item) {
            $sekarang = $terjual[$item->id] ?? 0;
            $lalu = $terjualLalu[$item->id] ?? 0;
            if ($lalu < 5) {
                continue; // data pembanding terlalu sedikit
            }

            $persen = (int) round(($sekarang - $lalu) / $lalu * 100);
            if ($persen <= -40) {
                $hasil->push([
                    'tipe' => 'peringatan',
                    'judul' => "{$item->name} turun {$this->abs($persen)}%",
                    'detail' => "Terjual {$sekarang} porsi, sebelumnya {$lalu}. Cek apakah harga masih sesuai, rasa/porsinya tetap, atau coba beri promo.",
                    'menu' => $item->name,
                ]);
            } elseif ($persen >= 40) {
                $hasil->push([
                    'tipe' => 'peluang',
                    'judul' => "{$item->name} naik {$persen}%",
                    'detail' => "Terjual {$sekarang} porsi, sebelumnya {$lalu}. Pastikan stok cukup; peminatnya naik, jadi harga boleh dinaikkan sedikit bila perlu.",
                    'menu' => $item->name,
                ]);
            }
        }

        return $hasil;
    }

    /**
     * Menu yang jauh lebih mahal dari menu sejenis (awalan nama yang sama, mis. "ALC ...") dan penjualannya rendah.
     */
    private function hargaTerlaluTinggi(Collection $items, Collection $terjual): Collection
    {
        $hasil = collect();

        foreach ($items->groupBy(fn ($item) => $this->kelompok($item->name))->filter(fn ($g, $k) => $k !== '' && $g->count() >= 3) as $kelompok => $anggota) {
            $median = $anggota->pluck('price')->sort()->values()->median();
            $rataTerjual = $anggota->avg(fn ($item) => $terjual[$item->id] ?? 0);

            foreach ($anggota as $item) {
                $jual = $terjual[$item->id] ?? 0;
                if ($median > 0 && $item->price >= $median * 1.3 && $jual < $rataTerjual * 0.5) {
                    $hasil->push([
                        'tipe' => 'peluang',
                        'judul' => "Harga {$item->name} mungkin terlalu tinggi",
                        'detail' => 'Harganya '.$this->rp($item->price).", sementara menu {$kelompok} lain rata-rata {$this->rp((int) $median)} dan penjualannya hanya {$jual} porsi. Coba turunkan mendekati {$this->rp((int) $median)} lalu lihat apakah penjualan naik.",
                        'menu' => $item->name,
                    ]);
                }
            }
        }

        return $hasil;
    }

    private function stokMenipis(Collection $items, Collection $terjual): Collection
    {
        $hasil = collect();

        foreach ($items as $item) {
            if (! $item->lacakStok() || ! $item->stokMenipis()) {
                continue;
            }

            $laris = ($terjual[$item->id] ?? 0) >= 5;
            $hasil->push([
                'tipe' => 'peringatan',
                'judul' => $item->stock < 1 ? "Stok {$item->name} habis" : "Stok {$item->name} tinggal {$item->stock}",
                'detail' => $laris ? 'Menu ini laku, segera tambah stok agar tidak kehilangan penjualan.' : 'Tambah stok bila masih mau dijual.',
                'menu' => $item->name,
            ]);
        }

        return $hasil;
    }

    /**
     * Pasangan menu yang sering dibeli dalam satu transaksi, cocok dijadikan paket hemat.
     */
    private function paketMenu(Carbon $dari, Carbon $sampai): Collection
    {
        $baris = $this->penjualan()
            ->join('income_details', 'income_details.income_id', '=', 'incomes.id')
            ->join('items', 'items.id', '=', 'income_details.item_id')
            ->whereBetween('incomes.date', [$dari, $sampai])
            ->get(['incomes.id as income_id', 'items.name']);

        $pasangan = [];
        foreach ($baris->groupBy('income_id') as $isi) {
            $nama = $isi->pluck('name')->unique()->sort()->values();
            for ($i = 0; $i < $nama->count(); $i++) {
                for ($j = $i + 1; $j < $nama->count(); $j++) {
                    $kunci = $nama[$i].' + '.$nama[$j];
                    $pasangan[$kunci] = ($pasangan[$kunci] ?? 0) + 1;
                }
            }
        }

        arsort($pasangan);
        $teratas = array_slice(array_filter($pasangan, fn ($n) => $n >= 3), 0, 2, true);

        return collect($teratas)->map(fn ($n, $kunci) => [
            'tipe' => 'peluang',
            'judul' => "Paket hemat: {$kunci}",
            'detail' => "Dibeli bersamaan {$n} kali. Coba tawarkan sebagai paket dengan harga sedikit lebih murah agar pelanggan membeli keduanya.",
        ])->values();
    }

    private function hariRamai(Carbon $dari, Carbon $sampai): Collection
    {
        $perHari = $this->penjualan()
            ->whereBetween('incomes.date', [$dari, $sampai])
            ->get(['incomes.date', 'incomes.amount'])
            ->groupBy(fn ($row) => Carbon::parse($row->date)->dayOfWeekIso)
            ->map(fn ($rows) => $rows->sum('amount'));

        if ($perHari->count() < 2) {
            return collect();
        }

        $ramai = $perHari->sortDesc()->keys()->first();
        $sepi = $perHari->sort()->keys()->first();
        $hari = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'];

        return collect([[
            'tipe' => 'info',
            'judul' => "Hari paling ramai: {$hari[$ramai]}",
            'detail' => "Penjualan {$hari[$ramai]} tertinggi ({$this->rp((int) $perHari[$ramai])}), {$hari[$sepi]} paling sepi ({$this->rp((int) $perHari[$sepi])}). Siapkan stok dan pegawai lebih banyak di hari ramai, dan beri promo di hari sepi.",
        ]]);
    }

    private function pengeluaran(Carbon $dari, Carbon $sampai, Carbon $dariLalu): Collection
    {
        $sekarang = (int) Expense::whereBetween('date', [$dari->toDateString(), $sampai->toDateString()])->sum('amount');
        $lalu = (int) Expense::whereBetween('date', [$dariLalu->toDateString(), $dari->copy()->subDay()->toDateString()])->sum('amount');

        if ($lalu < 100000 || $sekarang < $lalu * 1.3) {
            return collect();
        }

        $persen = (int) round(($sekarang - $lalu) / $lalu * 100);
        $terbesar = Expense::query()
            ->join('bills', 'bills.id', '=', 'expenses.bill_id')
            ->whereBetween('expenses.date', [$dari->toDateString(), $sampai->toDateString()])
            ->groupBy('bills.name')
            ->orderByDesc(DB::raw('SUM(expenses.amount)'))
            ->selectRaw('bills.name, SUM(expenses.amount) as total')
            ->first();

        $tambahan = $terbesar ? " Pos terbesar: {$terbesar->name} ({$this->rp((int) $terbesar->total)})." : '';

        return collect([[
            'tipe' => 'peringatan',
            'judul' => "Pengeluaran naik {$persen}%",
            'detail' => "{$this->rp($sekarang)} dalam ".self::PERIODE_HARI." hari terakhir, sebelumnya {$this->rp($lalu)}.{$tambahan} Periksa apakah ada yang bisa dihemat.",
        ]]);
    }

    /**
     * Awalan kapital pada nama menu (mis. "ALC", "KO") dipakai sebagai kelompok menu sejenis.
     */
    private function kelompok(string $nama): string
    {
        $kata = preg_split('/\s+/', trim($nama));

        return count($kata) > 1 && preg_match('/^[A-Z]{2,4}$/', $kata[0]) ? $kata[0] : '';
    }

    private function rp(int $nilai): string
    {
        return 'Rp '.number_format($nilai, 0, ',', '.');
    }

    private function abs(int $nilai): int
    {
        return abs($nilai);
    }
}
