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
        $bulan = $request->bulan ? (int) $request->bulan : null;
        $tahun = $request->tahun ? (int) $request->tahun : null;
        $sort = $request->input('sort', 'desc');

        $laporan = $laporanService->query($tanggalAwal, $tanggalAkhir, null, $bulan, $tahun, $sort)
            ->paginate(10)
            ->withQueryString();

        return view('laporan.laporan-index', compact('laporan', 'tanggalAwal', 'tanggalAkhir', 'bulan', 'tahun', 'sort'));
    }

    public function exportPdf(Request $request, LaporanKeuanganService $laporanService)
    {
        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;
        $bulan = $request->bulan ? (int) $request->bulan : null;
        $tahun = $request->tahun ? (int) $request->tahun : null;
        $sort = $request->input('sort', 'desc');

        // Validate date range if both are provided
        if ($tanggalAwal && $tanggalAkhir && $tanggalAwal > $tanggalAkhir) {
            return back()->with('error', 'Tanggal awal tidak boleh lebih besar dari tanggal akhir');
        }

        // Get all results (no pagination for PDF)
        $laporan = $laporanService->query($tanggalAwal, $tanggalAkhir, $request->query('type'), $bulan, $tahun, $sort)->get();

        // Calculate totals
        $totalPemasukan = $laporan->where('jenis', 'pemasukan')->sum('jumlah');
        $totalPengeluaran = $laporan->where('jenis', 'pengeluaran')->sum('jumlah');
        $saldo = $totalPemasukan - $totalPengeluaran;

        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        if ($bulan && $tahun) {
            $displayPeriode = ($namaBulan[$bulan] ?? $bulan) . ' ' . $tahun;
        } elseif ($bulan) {
            $displayPeriode = 'Bulan ' . ($namaBulan[$bulan] ?? $bulan);
        } elseif ($tahun) {
            $displayPeriode = 'Tahun ' . $tahun;
        } elseif ($tanggalAwal || $tanggalAkhir) {
            $displayAwal = $tanggalAwal ? \Carbon\Carbon::parse($tanggalAwal)->translatedFormat('d F Y') : 'Semua Data';
            $displayAkhir = $tanggalAkhir ? \Carbon\Carbon::parse($tanggalAkhir)->translatedFormat('d F Y') : 'Semua Data';
            $displayPeriode = "{$displayAwal} s/d {$displayAkhir}";
        } else {
            $displayPeriode = 'Semua Periode';
        }

        // Format currency
        $formatCurrency = function ($value) {
            if (class_exists(\NumberFormatter::class)) {
                $formatter = new \NumberFormatter('id_ID', \NumberFormatter::CURRENCY);
                return $formatter->formatCurrency($value, 'IDR');
            }
            return 'Rp ' . number_format($value, 0, ',', '.');
        };

        $pdf = Pdf::loadView('laporan.export-pdf', [
            'laporan' => $laporan,
            'displayPeriode' => $displayPeriode,
            'tanggalAwal' => $tanggalAwal ? \Carbon\Carbon::parse($tanggalAwal)->translatedFormat('d F Y') : 'Semua Data',
            'tanggalAkhir' => $tanggalAkhir ? \Carbon\Carbon::parse($tanggalAkhir)->translatedFormat('d F Y') : 'Semua Data',
            'totalPemasukan' => $formatCurrency($totalPemasukan),
            'totalPengeluaran' => $formatCurrency($totalPengeluaran),
            'saldo' => $formatCurrency($saldo),
            'formatCurrency' => $formatCurrency,
            'user' => auth()->user(),
            'generatedAt' => now()->translatedFormat('d F Y H:i:s'),
        ])->setPaper('a4', 'landscape');

        $suffix = $bulan && $tahun ? "{$tahun}-{$bulan}" : ($tahun ? "{$tahun}" : (($tanggalAwal ?: 'all').'-sd-'.($tanggalAkhir ?: 'all')));
        $filename = "Laporan-Keuangan-{$suffix}.pdf";

        return $pdf->download($filename);
    }
}
