<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\Expense;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class AdminLaporanKeuanganController extends Controller
{
    public function index(Request $request)
    {
        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;

        // Query untuk pemasukan
        $incomeQuery = Income::with('source')
            ->selectRaw("'pemasukan' as jenis, name as nama, source_id, amount as jumlah, date as tanggal, description as keterangan, NULL as bill_id");

        // Query untuk pengeluaran - tambahkan eager loading bill dan ambil nama dari relasi
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

        $laporan = $incomeQuery->unionAll($expenseQuery)
                ->orderBy('tanggal', 'desc')
                ->paginate(10);

        return view('laporan.laporan-index', compact('laporan', 'tanggalAwal', 'tanggalAkhir'));
    }


    public function exportPdf(Request $request)
    {
        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;

        // Validate date range if both are provided
        if ($tanggalAwal && $tanggalAkhir && $tanggalAwal > $tanggalAkhir) {
            return back()->with('error', 'Tanggal awal tidak boleh lebih besar dari tanggal akhir');
        }

        // Income query (matches index method)
        $incomeQuery = Income::with('source')
            ->selectRaw("'pemasukan' as jenis, name as nama, source_id, amount as jumlah, date as tanggal, description as keterangan, NULL as bill_id");

        // Expense query (matches index method with bill name)
        $expenseQuery = Expense::with(['source', 'bill'])
            ->selectRaw("'pengeluaran' as jenis,
                        CASE WHEN bills.name IS NOT NULL THEN bills.name ELSE '' END as nama,
                        expenses.source_id,
                        expenses.amount as jumlah,
                        expenses.date as tanggal,
                        expenses.description as keterangan,
                        expenses.bill_id")
            ->leftJoin('bills', 'expenses.bill_id', '=', 'bills.id');

        // Apply date filters
        if ($tanggalAwal && $tanggalAkhir) {
            $incomeQuery->whereBetween('date', [$tanggalAwal, $tanggalAkhir]);
            $expenseQuery->whereBetween('expenses.date', [$tanggalAwal, $tanggalAkhir]);
        } elseif ($tanggalAwal) {
            $incomeQuery->where('date', '>=', $tanggalAwal);
            $expenseQuery->where('expenses.date', '>=', $tanggalAwal);
        } elseif ($tanggalAkhir) {
            $incomeQuery->where('date', '<=', $tanggalAkhir);
            $expenseQuery->where('expenses.date', '<=', $tanggalAkhir);
        }

        // Get all results (no pagination for PDF)
        $laporan = $incomeQuery->unionAll($expenseQuery)
                ->orderBy('tanggal', 'desc')
                ->get();

        // Calculate totals
        $totalPemasukan = $laporan->where('jenis', 'pemasukan')->sum('jumlah');
        $totalPengeluaran = $laporan->where('jenis', 'pengeluaran')->sum('jumlah');
        $saldo = $totalPemasukan - $totalPengeluaran;

        // Format dates for display
        $displayAwal = $tanggalAwal ? \Carbon\Carbon::parse($tanggalAwal)->translatedFormat('d F Y') : 'Semua Data';
        $displayAkhir = $tanggalAkhir ? \Carbon\Carbon::parse($tanggalAkhir)->translatedFormat('d F Y') : 'Semua Data';

        // Format currency
        $formatter = new \NumberFormatter('id_ID', \NumberFormatter::CURRENCY);
        $formatCurrency = function ($value) use ($formatter) {
            return $formatter->formatCurrency($value, 'IDR');
        };

        $pdf = Pdf::loadView('laporan.export-pdf', [
            'laporan' => $laporan,
            'tanggalAwal' => $displayAwal,
            'tanggalAkhir' => $displayAkhir,
            'totalPemasukan' => $formatCurrency($totalPemasukan),
            'totalPengeluaran' => $formatCurrency($totalPengeluaran),
            'saldo' => $formatCurrency($saldo),
            'formatCurrency' => $formatCurrency,
            'user' => auth()->user(),
            'generatedAt' => now()->translatedFormat('d F Y H:i:s')
        ])->setPaper('a4', 'landscape');

        $filename = 'Laporan-Keuangan-'
            . ($tanggalAwal ? date('Y-m-d', strtotime($tanggalAwal)) : 'all')
            . '-sd-'
            . ($tanggalAkhir ? date('Y-m-d', strtotime($tanggalAkhir)) : 'all')
            . '.pdf';

        return $pdf->download($filename);
    }

}
