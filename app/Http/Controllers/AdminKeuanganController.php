<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Source;
use App\Models\Bill;
use App\Models\Income;
use App\Models\IncomeDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminKeuanganController extends Controller
{
    public function view()
    {
        // Ringkasan pemasukan hari ini
        $summary = Income::select('source_id', DB::raw('COUNT(*) as jumlah'), DB::raw('SUM(amount) as total'))
            ->whereDate('date', today())
            ->groupBy('source_id')
            ->with('source')
            ->paginate(6)
            ->map(function ($item) {
                return [
                    'sumber' => $item->source->name ?? 'Tidak diketahui',
                    'jumlah' => $item->jumlah,
                    'total' => $item->total
                ];
            });

        // Data bulanan untuk chart
        $monthlyIncome = Income::selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->whereYear('created_at', now()->year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('total', 'month');

        $monthlyExpense = Expense::selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->whereYear('created_at', now()->year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('total', 'month');

        // Persiapan data chart
        $months = [];
        $incomeData = [];
        $expenseData = [];

        for ($i = 1; $i <= 12; $i++) {
            $months[] = Carbon::create()->month($i)->locale('id')->translatedFormat('F');
            $incomeData[] = $monthlyIncome[$i] ?? 0;
            $expenseData[] = $monthlyExpense[$i] ?? 0;
        }

        // Data hari ini
        $income = Income::with(['source'])
            ->whereDate('created_at', now()->toDateString())
            ->get();

        $expenses = Expense::with(['source', 'bill']) // Pastikan relasi bill di-load
            ->whereDate('created_at', now()->toDateString())
            ->get();

        // Perhitungan total
        $total_income = $income->sum('amount');
        $total_expenses = $expenses->sum('amount');
        $profit = $total_income - $total_expenses;

        // Pesanan hari ini
        $total_orders = IncomeDetail::whereDate('created_at', now()->toDateString())->count();

        // Keuangan tahunan
        $currentYear = now()->year;
        $yearlyIncome = Income::whereYear('created_at', $currentYear)->sum('amount');
        $yearlyExpense = Expense::whereYear('created_at', $currentYear)->sum('amount');
        $yearlyProfit = $yearlyIncome - $yearlyExpense;

        // Gabungkan transaksi pemasukan dan pengeluaran
        $transactions = collect()
            ->merge(
                $income->map(function($item) {
                    $item->type = 'income';
                    return $item;
                })
            )
            ->merge(
                $expenses->map(function($item) {
                    $item->type = 'expense';
                    return $item;
                })
            )
            ->sortByDesc('created_at');

        // Data bills untuk dropdown
        $bills = Bill::all()->pluck('name', 'id');

        return view('admin-keuangan', compact(
            'income', 'expenses', 'total_income', 'total_expenses', 'profit',
            'months', 'incomeData', 'expenseData', 'total_orders', 'summary',
            'yearlyIncome', 'yearlyExpense', 'yearlyProfit', 'currentYear',
            'transactions', 'bills'
        ));
    }
}
