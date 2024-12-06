<?php

namespace App\Http\Controllers;

use App\Models\Pemasukan;
use App\Models\MetodeTransaksi;
use App\Models\User;
use Illuminate\Http\Request;

class PemasukanController extends Controller
{
    public function index()
    {
        $pemasukan = Pemasukan::with(['user', 'metodeTransaksi'])->get(); // Eager load relations
        return view('pemasukan.index', compact('pemasukan'));
    }
// PemasukanController.php

public function showByMetode($metode)
{
    // Ambil data pemasukan dengan metode transaksi tertentu (misal Gopay)
    $pemasukan = Pemasukan::whereHas('metodeTransaksi', function ($query) use ($metode) {
        $query->where('metode_transaksi', $metode);
    })->get();

    // Kembalikan view dengan data pemasukan sesuai metode transaksi
    return view('pemasukan.metode', compact('pemasukan', 'metode'));
}


    public function create()
    {
        // $users = User::all();
        $metodeTransaksi = MetodeTransaksi::all();
        return view('pemasukan.create', compact( 'metodeTransaksi'));
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            // 'id_user' => 'required|exists:users,id',
            'id_metode_transaksis' => 'required|exists:metode_transaksis,id',
            'nominal' => 'required|numeric',
            'keterangan' => 'nullable|string',
            'tanggal' => 'required|date',
        ]);

        // Create new Pemasukan record
        Pemasukan::create([
            // 'id_user' => $request->id_user,
            'id_metode_transaksis' => $request->id_metode_transaksis,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
            'tanggal' => $request->tanggal,
        ]);

        return redirect()->route('pemasukan.index')->with('success', 'Pemasukan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pemasukan = Pemasukan::findOrFail($id);
        // $users = User::all();
        $metodeTransaksi = MetodeTransaksi::all();
        return view('pemasukan.edit', compact('pemasukan', 'metodeTransaksi'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            // 'id_user' => 'required|exists:users,id',
            'id_metode_transaksis' => 'required|exists:metode_transaksis,id',
            'nominal' => 'required|numeric',
            'keterangan' => 'nullable|string',
            'tanggal' => 'required|date',
        ]);

        $pemasukan = Pemasukan::findOrFail($id);
        $pemasukan->update([
            // 'id_user' => $request->id_user,
            'id_metode_transaksis' => $request->id_metode_transaksis,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
            'tanggal' => $request->tanggal,
        ]);

        return redirect()->route('pemasukan.index')->with('success', 'Pemasukan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Pemasukan::findOrFail($id)->delete();
        return redirect()->route('pemasukan.index')->with('success', 'Pemasukan berhasil dihapus.');
    }
}
