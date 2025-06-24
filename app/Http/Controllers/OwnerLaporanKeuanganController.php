<?php

namespace App\Http\Controllers;
use App\Models\Income;
use App\Models\Expense;


use Illuminate\Http\Request;

class OwnerLaporanKeuanganController extends Controller
{
    public function view(Request $request)
    {
        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;
        $type = $request->query('type'); // Tambahkan ini

        $incomeQuery = Income::with('source')
            ->selectRaw("'pemasukan' as jenis, name as nama, source_id, amount as jumlah, date as tanggal, description as keterangan, NULL as bill_id");

        $expenseQuery = Expense::with(['source', 'bill'])
            ->selectRaw("'pengeluaran' as jenis,
                        CASE WHEN bills.name IS NOT NULL THEN bills.name ELSE '' END as nama,
                        expenses.source_id,
                        expenses.amount as jumlah,
                        expenses.date as tanggal,
                        expenses.description as keterangan,
                        expenses.bill_id")
            ->leftJoin('bills', 'expenses.bill_id', '=', 'bills.id');

        if ($tanggalAwal && $tanggalAkhir) {
            $incomeQuery->whereBetween('date', [$tanggalAwal, $tanggalAkhir]);
            $expenseQuery->whereBetween('expenses.date', [$tanggalAwal, $tanggalAkhir]);
        }

        // Tambahkan logika filter berdasarkan jenis transaksi
        if ($type === 'pemasukan') {
            $laporan = $incomeQuery->orderBy('tanggal', 'desc')->paginate(10);
        } elseif ($type === 'pengeluaran') {
            $laporan = $expenseQuery->orderBy('tanggal', 'desc')->paginate(10);
        } else {
            $laporan = $incomeQuery->unionAll($expenseQuery)
                ->orderBy('tanggal', 'desc')
                ->paginate(10);
        }

        return view('owner-laporan-keuangan', compact('laporan', 'tanggalAwal', 'tanggalAkhir', 'type'));
    }

}
