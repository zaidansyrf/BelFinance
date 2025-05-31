<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Source;
use App\Models\Bill;

class ExpenseController extends Controller
{
    public function chartExpense()
    {
        $monthlyExpense = Expense::selectRaw('MONTH(date) as month, SUM(amount) as total')
        ->groupBy('month')
        ->orderBy('month')
        ->get()
        ->keyBy('month');

    // Buat array dengan semua bulan (1-12) dengan nilai default 0
    $months = [];
    for ($i = 1; $i <= 12; $i++) {
        $months[] = [
            'month' => $i,
            'total' => $monthlyExpense[$i]->total ?? 0  // Jika tidak ada transaksi, nilai = 0
        ];
    }

    return response()->json($months);
    }
    public function index()
    {
        $expenses = Expense::with(['sources', 'bills'])
        ->orderBy('date', 'desc')
        ->paginate(10);
        return view('expenses.index', compact('expenses'));
    }

    public function search(Request $request)
    {
        $search = $request->input('search');

        $expenses = Expense::query()
        ->join('sources', 'expenses.source_id', '=', 'sources.id')
        ->join('bills', 'expenses.bill_id', '=', 'bills.id') // JOIN ke tabel tagihan
        ->where(function ($query) use ($search) {
            $query->where('sources.name', 'LIKE', "%{$search}%")
                ->orWhere('bills.name', 'LIKE', "%{$search}%")
                ->orWhere('expenses.amount', 'LIKE', "%{$search}%")
                ->orWhere('expenses.description', 'LIKE', "%{$search}%")
                ->orWhere('expenses.date', 'LIKE', "%{$search}%");
        })
        ->select('expenses.*')
        ->orderBy('expenses.date', 'asc')
        ->paginate(10);

        return view('expenses.index', compact('expenses', 'search'));
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
            'source_id' => 'required|exists:sources,id',
            'bill_id' => 'required|exists:bills,id',
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255',
            'date' => 'required|date',
        ]);

        try {
            Expense::create($validatedData);
            return redirect()->route('expenses.search')->with('success', 'Data pengeluaran berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan data pengeluaran!');
        }
    }

    public function show(string $id)
    {
        $expense = Expense::with(['source', 'bill'])->findOrFail($id);
        $sources = Source::all();
        $bills = Bill::all();
        return view('keuangan-pengeluaran-edit', compact('expense', 'sources', 'bills'));

    }

    public function edit(string $id)
    {
        $expense = Expense::with(['source', 'bill'])->findOrFail($id);
        $bills = Bill::all();
        $sources = Source::all();
        return view('keuangan-pengeluaran-edit', compact('expense', 'sources', 'bills'));
    }

    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'source_id' => 'required|exists:sources,id',
            'bill_id' => 'required|exists:bills,id',
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255',
            'date' => 'required|date',
        ]);

        try {
            $expense = Expense::findOrFail($id);
            $expense->update($validatedData);
            return redirect()->route('expenses.search')->with('success', 'Data pengeluaran berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui data pengeluaran!');
        }
    }

    public function destroy($id)
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();
        return redirect()->route('expenses.search')->with('success', 'Data pengeluaran berhasil dihapus!');
    }




}
