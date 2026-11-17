<?php

namespace App\Http\Controllers;

use App\Services\LaporanKeuanganService;
use Illuminate\Http\Request;

class OwnerLaporanKeuanganController extends Controller
{
    public function view(Request $request, LaporanKeuanganService $laporanService)
    {
        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;
        $type = $request->query('type'); // 'pemasukan', 'pengeluaran', atau kosong
        $bulan = $request->bulan ? (int) $request->bulan : null;
        $tahun = $request->tahun ? (int) $request->tahun : null;
        $sort = $request->input('sort', 'desc');

        $laporan = $laporanService->query($tanggalAwal, $tanggalAkhir, $type, $bulan, $tahun, $sort)
            ->paginate(10)
            ->withQueryString();

        return view('owner-laporan-keuangan', compact('laporan', 'tanggalAwal', 'tanggalAkhir', 'type', 'bulan', 'tahun', 'sort'));
    }
}
