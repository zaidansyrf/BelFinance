<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Source;
use App\Models\Bill;
use App\Models\Income;
use App\Models\IncomeDetail;

class AdminKeuanganController extends Controller
{
    public function view()
    {
        // Ambil pemasukan dan pengeluaran hanya untuk hari ini
        $income = Income::with(['source'])
                    ->whereDate('created_at', now()->toDateString())
                    ->get();
    
        $expenses = Expense::with(['source', 'bill'])
                    ->whereDate('created_at', now()->toDateString())
                    ->get();

        $count = IncomeDetail::whereDate('created_at', now()->toDateString())->count();
    
        // Hitung total pemasukan dan pengeluaran hari ini
        $total_income = $income->sum('amount');
        $total_expenses = $expenses->sum('amount');
    
        // Hitung keuntungan (laba)
        $profit = $total_income - $total_expenses;
    
        return view('admin-keuangan', compact('income', 'expenses', 'total_income', 'total_expenses', 'profit', 'count'));
    }
    
    
}