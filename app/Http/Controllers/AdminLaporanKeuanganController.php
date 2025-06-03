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

        $incomeQuery = Income::with('source')
            ->selectRaw("'pemasukan' as jenis, name as nama, source_id, amount as jumlah, date as tanggal, description as keterangan");

        $expenseQuery = Expense::with('source')
            ->selectRaw("'pengeluaran' as jenis, '' as nama, source_id, amount as jumlah, date as tanggal, description as keterangan");

        if ($tanggalAwal && $tanggalAkhir) {
            $incomeQuery->whereBetween('date', [$tanggalAwal, $tanggalAkhir]);
            $expenseQuery->whereBetween('date', [$tanggalAwal, $tanggalAkhir]);
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

    $incomeQuery = Income::with('source')
        ->selectRaw("'pemasukan' as jenis, name as nama, source_id, amount as jumlah, date as tanggal, description as keterangan");

    $expenseQuery = Expense::with('source')
        ->selectRaw("'pengeluaran' as jenis, '' as nama, source_id, amount as jumlah, date as tanggal, description as keterangan");

    if ($tanggalAwal && $tanggalAkhir) {
        $incomeQuery->whereBetween('date', [$tanggalAwal, $tanggalAkhir]);
        $expenseQuery->whereBetween('date', [$tanggalAwal, $tanggalAkhir]);
    } elseif ($tanggalAwal) {
        $incomeQuery->where('date', '>=', $tanggalAwal);
        $expenseQuery->where('date', '>=', $tanggalAwal);
    } elseif ($tanggalAkhir) {
        $incomeQuery->where('date', '<=', $tanggalAkhir);
        $expenseQuery->where('date', '<=', $tanggalAkhir);
    }

    $laporan = $incomeQuery->unionAll($expenseQuery)
              ->orderBy('tanggal', 'asc')
              ->get();

    // Calculate totals
    $totalPemasukan = $laporan->where('jenis', 'pemasukan')->sum('jumlah');
    $totalPengeluaran = $laporan->where('jenis', 'pengeluaran')->sum('jumlah');
    $saldo = $totalPemasukan - $totalPengeluaran;

    // Format dates for display
    $displayAwal = $tanggalAwal ? \Carbon\Carbon::parse($tanggalAwal)->translatedFormat('d F Y') : 'Semua Data';
    $displayAkhir = $tanggalAkhir ? \Carbon\Carbon::parse($tanggalAkhir)->translatedFormat('d F Y') : 'Semua Data';
    ini_set('memory_limit', '1024M');
    $pdf = Pdf::loadView('laporan.export-pdf', [
        'laporan' => $laporan,
        'tanggalAwal' => $displayAwal,
        'tanggalAkhir' => $displayAkhir,
        'totalPemasukan' => $totalPemasukan,
        'totalPengeluaran' => $totalPengeluaran,
        'saldo' => $saldo,
        'user' => auth()->user(),
        'generatedAt' => now()->translatedFormat('d F Y H:i:s')
    ])->setPaper('a4', 'landscape');

    $filename = 'Laporan-Keuangan-'
        . ($tanggalAwal ?: 'all')
        . '-sd-'
        . ($tanggalAkhir ?: 'all')
        . '.pdf';

    return $pdf->download($filename);
}

}
