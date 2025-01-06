<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Source;
use App\Models\Bill;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expenses = Expense::with(['source', 'bill'])->get();
        return view('expenses.index', compact('expenses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bills = Bill::all();
        $sources = Source::all();
        return view('keuangan-create-pengeluaran', compact('sources', 'bills'));
    }

    /**
     * Store a newly created resource in storage.
     */
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
            return redirect()->route('expenses.index')->with('success', 'Data pengeluaran berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan data pengeluaran!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
    try {
        $expense = Expense::findOrFail($id);
        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Data pengeluaran berhasil dihapus!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Gagal menghapus data pengeluaran!');
    }

    }
}
