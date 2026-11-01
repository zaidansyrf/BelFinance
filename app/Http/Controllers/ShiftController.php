<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    /**
     * Halaman shift kasir: form buka shift, atau ringkasan + form tutup shift bila sedang berjalan.
     */
    public function show(Request $request)
    {
        $shift = Shift::aktifUntuk($request->user());

        return view('kasir.shift', [
            'shift' => $shift,
            'penjualan' => $shift?->totalPenjualan() ?? 0,
            'tunai' => $shift?->penjualanTunai() ?? 0,
            'kasDiharapkan' => $shift?->kasDiharapkan() ?? 0,
            'terakhir' => Shift::where('user_id', $request->user()->id)->whereNotNull('closed_at')->latest('closed_at')->first(),
        ]);
    }

    public function buka(Request $request)
    {
        if (Shift::aktifUntuk($request->user())) {
            return redirect()->route('kasir.shift')->with('error', 'Shift kamu masih berjalan.');
        }

        $data = $request->validate([
            'opening_cash' => 'required|integer|min:0|max:100000000',
        ], [
            'opening_cash.required' => 'Isi modal awal di laci kas (isi 0 bila kosong).',
        ]);

        Shift::create([
            'user_id' => $request->user()->id,
            'opened_at' => now(),
            'opening_cash' => $data['opening_cash'],
        ]);

        return redirect()->route('kasir.pos')->with('success', 'Shift dibuka. Selamat bekerja!');
    }

    public function tutup(Request $request)
    {
        $shift = Shift::aktifUntuk($request->user());
        abort_unless($shift, 404, 'Tidak ada shift yang berjalan.');

        $data = $request->validate([
            'closing_cash' => 'required|integer|min:0|max:100000000',
            'note' => 'nullable|string|max:255',
        ], [
            'closing_cash.required' => 'Isi jumlah uang di laci kas hasil hitung.',
        ]);

        $shift->tutup((int) $data['closing_cash'], $data['note'] ?? null);

        $selisih = $shift->difference;
        $rp = fn (int $n) => 'Rp '.number_format(abs($n), 0, ',', '.');
        $pesan = match (true) {
            $selisih === 0 => 'Shift ditutup. Kas pas, tidak ada selisih.',
            $selisih > 0 => "Shift ditutup. Kas lebih {$rp($selisih)}.",
            default => "Shift ditutup. Kas kurang {$rp($selisih)}.",
        };

        return redirect()->route('kasir.shift')->with('success', $pesan);
    }

    /**
     * Riwayat shift semua kasir, untuk keuangan dan owner.
     */
    public function index()
    {
        $shifts = Shift::with('user')
            ->withSum('incomes as total_penjualan', 'amount')
            ->latest('opened_at')
            ->paginate(15);

        return view('shift.index', compact('shifts'));
    }
}
