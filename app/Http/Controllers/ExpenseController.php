<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Expense;
use App\Models\Source;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        return $this->search($request);
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $sourceId = $request->input('source_id');
        $billId = $request->input('bill_id');
        $tanggalAwal = $request->input('tanggal_awal');
        $tanggalAkhir = $request->input('tanggal_akhir');
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $sort = $request->input('sort', 'desc');

        $query = Expense::with(['source', 'bill']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('amount', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%")
                    ->orWhere('date', 'LIKE', "%{$search}%")
                    ->orWhereHas('source', fn ($s) => $s->where('name', 'LIKE', "%{$search}%"))
                    ->orWhereHas('bill', fn ($b) => $b->where('name', 'LIKE', "%{$search}%"));
            });
        }

        if ($sourceId) {
            $query->where('source_id', $sourceId);
        }

        if ($billId) {
            $query->where('bill_id', $billId);
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

        $expenses = $query->paginate(10)->withQueryString();

        $sources = Source::orderBy('name')->get();
        $bills = Bill::orderBy('name')->get();

        return view('expenses.index', compact('expenses', 'search', 'sources', 'bills'));
    }

    public function create()
    {
        $bills = Bill::all();
        $sources = Source::all();

        return view('keuangan-create-pengeluaran', compact('sources', 'bills'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'source_id' => 'required',
            'bill_id' => 'required',
            'date' => 'required|date|before_or_equal:today',
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        Expense::create($validatedData);

        return redirect()->route('expenses.index')->with('success', 'Data pengeluaran berhasil ditambahkan');
    }

    public function edit(Expense $expense)
    {
        $sources = Source::all();
        $bills = Bill::all();

        return view('keuangan-pengeluaran-edit', compact('expense', 'sources', 'bills'));
    }

    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'source_id' => 'required',
            'bill_id' => 'required',
            'date' => 'required|date|before_or_equal:today',
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        $expense->update([
            'source_id' => $request->source_id,
            'bill_id' => $request->bill_id,
            'date' => $request->date,
            'amount' => $request->amount,
            'description' => $request->description,
        ]);

        return redirect()->route('expenses.index')->with('success', 'Data berhasil diperbarui');
    }

    public function destroy(Expense $expense)
    {
        $expense->hapus();

        return redirect()->route('expenses.index')->with('success', 'Data berhasil dihapus');
    }
}
