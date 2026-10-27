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

        $laporan = $laporanService->query($tanggalAwal, $tanggalAkhir, $type)
            ->paginate(10)
            ->withQueryString();

        return view('owner-laporan-keuangan', compact('laporan', 'tanggalAwal', 'tanggalAkhir', 'type'));
    }
}
