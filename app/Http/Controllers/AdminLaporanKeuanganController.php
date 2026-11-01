<?php

namespace App\Http\Controllers;

use App\Services\LaporanKeuanganService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AdminLaporanKeuanganController extends Controller
{
    public function index(Request $request, LaporanKeuanganService $laporanService)
    {
        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;

        $laporan = $laporanService->query($tanggalAwal, $tanggalAkhir)
            ->paginate(10)
            ->withQueryString();

        return view('laporan.laporan-index', compact('laporan', 'tanggalAwal', 'tanggalAkhir'));
    }

    public function exportPdf(Request $request, LaporanKeuanganService $laporanService)
    {
        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;

        // Validate date range if both are provided
        if ($tanggalAwal && $tanggalAkhir && $tanggalAwal > $tanggalAkhir) {
            return back()->with('error', 'Tanggal awal tidak boleh lebih besar dari tanggal akhir');
        }

        // Get all results (no pagination for PDF)
        $laporan = $laporanService->query($tanggalAwal, $tanggalAkhir, $request->query('type'))->get();

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
            'generatedAt' => now()->translatedFormat('d F Y H:i:s'),
        ])->setPaper('a4', 'landscape');

        $filename = 'Laporan-Keuangan-'
            .($tanggalAwal ? date('Y-m-d', strtotime($tanggalAwal)) : 'all')
            .'-sd-'
            .($tanggalAkhir ? date('Y-m-d', strtotime($tanggalAkhir)) : 'all')
            .'.pdf';

        return $pdf->download($filename);
    }
}
