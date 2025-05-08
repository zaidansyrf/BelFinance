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
        $summary = Income::select('source_id', DB::raw('COUNT(*) as jumlah'), DB::raw('DATE(date) as tanggal'))
        ->whereDate('date', today())
        ->groupBy('source_id', DB::raw('DATE(date)'))
        ->with('source')
        ->get()
        ->groupBy(function ($item) {
            // Mapping source_id ke kategori online/offline
            $onlineSources = [3, 4]; // Contoh ID online
            return in_array($item->source_id, $onlineSources) ? 'Pesanan Online' : 'Pesanan Offline';
        })
        ->map(function ($group, $key) {
            return [
                'tipe' => $key,
                'jumlah' => $group->sum('jumlah'),
                'tanggal' => $group->first()->tanggal,
                'source_ids' => $group->pluck('source_id')->toArray()
            ];
        })->values(); // Reset index


        // Ambil pemasukan dan pengeluaran per bulan (dari awal tahun)
        $monthlyIncome = Income::selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->whereYear('created_at', now()->year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('total', 'month');
    
        $monthlyExpense = Expense::selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->whereYear('created_at', now()->year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('total', 'month');
    
        // Inisialisasi array bulan dan data
        $months = [];
        $incomeData = [];
        $expenseData = [];
    
        for ($i = 1; $i <= 12; $i++) {
            $months[] = Carbon::create()->month($i)->locale('id')->translatedFormat('F'); // Nama bulan dalam Bahasa Indonesia
            $incomeData[] = $monthlyIncome[$i] ?? 0;
            $expenseData[] = $monthlyExpense[$i] ?? 0;
        }
    
        // Data hari ini
        $income = Income::with(['source'])
            ->whereDate('created_at', now()->toDateString())
            ->get();
    
        $expenses = Expense::with(['source', 'bill'])
            ->whereDate('created_at', now()->toDateString())
            ->get();
    
        $total_income = $income->sum('amount');
        $total_expenses = $expenses->sum('amount');
        $profit = $total_income - $total_expenses;
    
        // jumlah pesanan
        $total_orders = IncomeDetail::whereDate('created_at', now()->toDateString())->count();
    
        return view('admin-keuangan', compact(
            'income', 'expenses', 'total_income', 'total_expenses', 'profit',
            'months', 'incomeData', 'expenseData', 'total_orders', 'summary'
        ));
    }
    public function dailySummary()
    {
        $summary = Income::select('source_id', DB::raw('COUNT(*) as jumlah'))
            ->whereDate('created_at', today())
            ->groupBy('source_id')
            ->with('source') // Eager load relasi source untuk menghindari N+1
            ->get();
    
        return view('keuangan-detail-pesanan', compact('summary'));
    }
    
    public function detailPesanan(Request $request)
    {
        $sourceId = $request->query('source_id');
    
        // Validasi apakah source_id dikirim
        if (!$sourceId) {
            return redirect()->back()->with('error', 'Tipe pesanan tidak ditemukan.');
        }
    
        // Ambil data income detail berdasarkan source_id
        $incomes = Income::with('details', 'source')
            ->where('source_id', $sourceId)
            ->whereDate('date', today())
            ->get();
    
        return view('keuangan-detail-pesanan', compact('incomes'));
    }
    

}