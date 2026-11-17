<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\Income;
use Illuminate\Database\Eloquent\Builder;

/**
 * Query laporan gabungan pemasukan + pengeluaran.
 * Dipakai oleh laporan admin keuangan, laporan owner, dan export PDF.
 */
class LaporanKeuanganService
{
    /**
     * @param  string|null  $jenis  'pemasukan', 'pengeluaran', atau null untuk keduanya
     * @param  int|null  $bulan  1-12
     * @param  int|null  $tahun  tahun 4 digit
     * @param  string  $sort  'desc' atau 'asc'
     */
    public function query(
        ?string $tanggalAwal = null,
        ?string $tanggalAkhir = null,
        ?string $jenis = null,
        ?int $bulan = null,
        ?int $tahun = null,
        string $sort = 'desc'
    ): Builder {
        $incomeQuery = Income::with('source')
            ->selectRaw("'pemasukan' as jenis, name as nama, source_id, amount as jumlah, date as tanggal, description as keterangan, NULL as bill_id");

        $expenseQuery = Expense::with(['source', 'bill'])
            ->selectRaw("'pengeluaran' as jenis,
                        COALESCE(bills.name, '') as nama,
                        expenses.source_id,
                        expenses.amount as jumlah,
                        expenses.date as tanggal,
                        expenses.description as keterangan,
                        expenses.bill_id")
            ->leftJoin('bills', 'expenses.bill_id', '=', 'bills.id');

        if ($tanggalAwal) {
            $incomeQuery->whereDate('date', '>=', $tanggalAwal);
            $expenseQuery->whereDate('expenses.date', '>=', $tanggalAwal);
        }
        if ($tanggalAkhir) {
            $incomeQuery->whereDate('date', '<=', $tanggalAkhir);
            $expenseQuery->whereDate('expenses.date', '<=', $tanggalAkhir);
        }

        if ($bulan) {
            $incomeQuery->whereMonth('date', $bulan);
            $expenseQuery->whereMonth('expenses.date', $bulan);
        }

        if ($tahun) {
            $incomeQuery->whereYear('date', $tahun);
            $expenseQuery->whereYear('expenses.date', $tahun);
        }

        $query = match ($jenis) {
            'pemasukan' => $incomeQuery,
            'pengeluaran' => $expenseQuery,
            default => $incomeQuery->unionAll($expenseQuery),
        };

        $sortDirection = ($sort === 'asc' || $sort === 'terlama') ? 'asc' : 'desc';

        return $query->orderBy('tanggal', $sortDirection);
    }
}
