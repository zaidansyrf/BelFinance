<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Income;
use App\Models\Source;
use Illuminate\Support\Facades\Log;


class UangMasukController extends Controller
{
    public function index()
    {
        $income = Income::with(['source'])
                        ->orderBy('created_at', 'desc') // Urutkan berdasarkan tanggal masuk (terbaru dulu)
                        ->paginate(10); // Menampilkan 10 item per halaman

        $sources = Source::all();

        return view('keuangan-uang-masuk', compact('income', 'sources'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'source_id' => 'required|exists:sources,id',
            'date' => 'required|date',
            'amount' => 'required|integer',
            'description' => 'nullable|string'
        ]);

        try {
            Income::create([
                'name' => $request->name,
                'source_id' => $request->source_id,
                'date' => $request->date,
                'amount' => $request->amount,
                'description' => $request->description
            ]);

            return redirect()->back()->with('success', 'Data berhasil disimpan');
        } catch (\Exception $e) {
            \Log::error('Gagal menyimpan data income: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }
    public function search(Request $request)
    {
        $search = $request->input('search');

        $query = Income::with('source')->orderBy('created_at', 'desc');

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

        $income = $query->paginate(10)->appends(['search' => $search]);
        $sources = Source::all();

        return view('keuangan-uang-masuk', compact('income', 'sources', 'search'));
    }

    public function show(Income $income)
    {
        return view('keuangan-uang-masuk', compact('income'));
    }

    public function destroy(Income $income)
    {
        try {
            // Hapus data income_details terkait dulu
            $income->incomeDetails()->delete();

            // Baru hapus data income
            $income->delete();

            return redirect()->back()->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            \Log::error('Gagal menghapus data income: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }



}
