<?php

namespace App\Services;

use App\Models\Bill;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Ringkasan dashboard yang dipakai bersama oleh admin keuangan dan owner.
 *
 * Semua perhitungan memakai kolom `date` (tanggal transaksi), bukan `created_at`
 * (waktu input), supaya transaksi yang dicatat mundur masuk ke hari/bulan yang benar.
 */
class DashboardKeuanganService
{
    public function ringkasan(?Carbon $hariIni = null): array
    {
        $hariIni = ($hariIni ?? now())->copy()->startOfDay();
        $tahun = $hariIni->year;

        // Ringkasan pemasukan hari ini per sumber
        $summary = Income::select('source_id', DB::raw('COUNT(*) as jumlah'), DB::raw('SUM(amount) as total'))
            ->whereDate('date', $hariIni)
            ->groupBy('source_id')
            ->with('source')
            ->get()
            ->map(function ($item) {
                return [
                    'sumber' => $item->source->name ?? 'Tidak diketahui',
                    'jumlah' => $item->jumlah,
                    'total' => $item->total,
                ];
            });

        // Data bulanan untuk chart
        $monthlyIncome = Income::selectRaw('MONTH(date) as month, SUM(amount) as total')
            ->whereYear('date', $tahun)
            ->groupBy(DB::raw('MONTH(date)'))
            ->pluck('total', 'month');

        $monthlyExpense = Expense::selectRaw('MONTH(date) as month, SUM(amount) as total')
            ->whereYear('date', $tahun)
            ->groupBy(DB::raw('MONTH(date)'))
            ->pluck('total', 'month');

        $months = [];
        $incomeData = [];
        $expenseData = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[] = Carbon::create($tahun, $i, 1)->locale('id')->translatedFormat('F');
            $incomeData[] = (int) ($monthlyIncome[$i] ?? 0);
            $expenseData[] = (int) ($monthlyExpense[$i] ?? 0);
        }

        // Transaksi hari ini
        $income = Income::with('source')->whereDate('date', $hariIni)->get();
        $expenses = Expense::with(['source', 'bill'])->whereDate('date', $hariIni)->get();

        $total_income = $income->sum('amount');
        $total_expenses = $expenses->sum('amount');
        $profit = $total_income - $total_expenses;

        // Jumlah pesanan (pembayaran) hari ini
        $total_orders = $income->where('type', 'payment')->count();

        // Keuangan tahunan
        $yearlyIncome = Income::whereYear('date', $tahun)->sum('amount');
        $yearlyExpense = Expense::whereYear('date', $tahun)->sum('amount');
        $yearlyProfit = $yearlyIncome - $yearlyExpense;

        // Gabungkan transaksi pemasukan dan pengeluaran (jenis disimpan di atribut terpisah
        // agar kolom `type` milik income tidak tertimpa)
        $transactions = collect()
            ->merge($income->map(fn ($item) => tap($item)->setAttribute('jenis', 'income')))
            ->merge($expenses->map(fn ($item) => tap($item)->setAttribute('jenis', 'expense')))
            ->sortByDesc(fn ($item) => $item->date->format('YmdHis').$item->created_at?->format('YmdHis'))
            ->values();

        return [
            'income' => $income,
            'expenses' => $expenses,
            'total_income' => $total_income,
            'total_expenses' => $total_expenses,
            'profit' => $profit,
            'months' => $months,
            'incomeData' => $incomeData,
            'expenseData' => $expenseData,
            'total_orders' => $total_orders,
            'summary' => $summary,
            'yearlyIncome' => $yearlyIncome,
            'yearlyExpense' => $yearlyExpense,
            'yearlyProfit' => $yearlyProfit,
            'currentYear' => $tahun,
            'transactions' => $transactions,
            'bills' => Bill::pluck('name', 'id'),
        ];
    }

    /**
     * Data menu terlaris untuk beranda owner.
     */
    public function menuTerlaris(int $limit = 5): array
    {
        $topItems = Item::orderByDesc('quantity')->take($limit)->get();

        return [
            'chartData' => [
                'labels' => $topItems->pluck('name'),
                'quantities' => $topItems->pluck('quantity'),
            ],
            'totalSold' => Item::sum('quantity'),
            'topSellingMenu' => $topItems->first(),
        ];
    }
}
