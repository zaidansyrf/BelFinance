<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\Source;
use Illuminate\Http\Request;

class UangMasukController extends Controller
{
    public function index(Request $request)
    {
        return $this->search($request);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'source_id' => 'required|exists:sources,id',
            'date' => 'required|date|before_or_equal:today',
            'amount' => 'required|integer',
            'description' => 'nullable|string',
        ]);

        try {
            Income::create([
                'name' => $request->name,
                'source_id' => $request->source_id,
                'date' => $request->date,
                'amount' => $request->amount,
                'description' => $request->description,
                'type' => 'income', 
            ]);

            return redirect()->back()->with('success', 'Data berhasil disimpan');
        } catch (\Exception $e) {
            \Log::error('Gagal menyimpan data income: '.$e->getMessage());

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $sourceId = $request->input('source_id');
        $tanggalAwal = $request->input('tanggal_awal');
        $tanggalAkhir = $request->input('tanggal_akhir');
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $sort = $request->input('sort', 'desc');

        $query = Income::with('source');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('amount', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('date', 'like', "%{$search}%")
                    ->orWhereHas('source', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($sourceId) {
            $query->where('source_id', $sourceId);
        }

        if ($tanggalAwal) {
            $query->whereDate('date', '>=', $tanggalAwal);
        }

        if ($tanggalAkhir) {
            $query->whereDate('date', '<=', $tanggalAkhir);
        }

        if ($bulan) {
            $query->whereMonth('date', $bulan);
        }

        if ($tahun) {
            $query->whereYear('date', $tahun);
        }

        if ($sort === 'asc' || $sort === 'terlama') {
            $query->orderBy('date', 'asc')->orderBy('id', 'asc');
        } else {
            $query->orderBy('date', 'desc')->orderBy('id', 'desc');
        }

        $income = $query->paginate(10)->withQueryString();
        $sources = Source::all();

        return view('keuangan-uang-masuk', compact('income', 'sources', 'search'));
    }

    public function destroy(Income $income)
    {
        // pengecekan untuk menghapus income
        try {
            $income->hapus();

            return redirect()->back()->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            \Log::error('Gagal menghapus data income: '.$e->getMessage());

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}
