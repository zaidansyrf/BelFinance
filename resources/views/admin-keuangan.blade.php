@section('title', 'Dashboard')
<x-app-layout>
    <div class="h-screen w-full bg-[#F4F7F6] flex overflow-hidden">
        <!-- sidebar -->
        <div class="drawer lg:drawer-open">
            <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />

            <!-- drawer content -->
            <div class="drawer-content flex flex-col h-screen">
                <!-- navbar -->
                @include('layouts.navbar')

                <!-- main content -->
                <div class="flex-1 bg-[#D1DDD5] overflow-y-auto px-4 sm:px-8 py-8 space-y-8">
                    
                    {{-- 1. Hero Header & Quick Actions --}}
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <h1 class="text-2xl font-bold text-gray-800">
                                    Halo, {{ auth()->user()->name }} 👋
                                </h1>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">
                                Overview aktivitas dan performa keuangan Belindo Kitchen per 
                                <span class="font-medium text-gray-700">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</span>
                            </p>
                        </div>

                    </div>

                    {{-- 2. Ringkasan Akumulasi Tahunan (Tahun Ini) --}}
                    <div class="bg-white rounded-2xl p-4 sm:p-5 shadow-sm border border-slate-200/60">
                        <div class="flex items-center justify-between mb-3 border-b border-gray-100 pb-2">
                            <div class="flex items-center gap-2">
                                <h3 class="text-xs sm:text-sm font-bold uppercase tracking-wider text-gray-500">
                                    Akumulasi Keuangan Tahun {{ $currentYear }}
                                </h3>
                            </div>
                            <a href="{{ route('laporan-keuangan') }}" class="text-xs font-semibold text-[#2B7A78] hover:underline">
                                Buka Laporan Lengkap &rarr;
                            </a>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                                <p class="text-xs text-gray-500 font-medium">Total Pemasukan {{ $currentYear }}</p>
                                <p class="text-lg sm:text-xl font-bold text-gray-800 mt-0.5">
                                    Rp {{ number_format($yearlyIncome, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                                <p class="text-xs text-gray-500 font-medium">Total Pengeluaran {{ $currentYear }}</p>
                                <p class="text-lg sm:text-xl font-bold text-gray-800 mt-0.5">
                                    Rp {{ number_format($yearlyExpense, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                                <p class="text-xs text-gray-500 font-medium">Estimasi Laba/Rugi {{ $currentYear }}</p>
                                <p class="text-lg sm:text-xl font-bold {{ $yearlyProfit >= 0 ? 'text-emerald-700' : 'text-rose-600' }} mt-0.5">
                                    Rp {{ number_format($yearlyProfit, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- 3. Kartu KPI Utama Hari Ini --}}
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                Ringkasan Hari Ini
                            </h2>
                            <span class="text-xs font-medium text-gray-400">Pembaruan otomatis dari kasir &amp; pembayaran</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            {{-- Card 1: Pemasukan Hari Ini --}}
                            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center justify-between hover:shadow-md transition">
                                <div>
                                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Pemasukan Hari Ini</p>
                                    <p class="text-xl sm:text-2xl font-extrabold text-gray-900 mt-1">
                                        Rp {{ number_format($income->sum('amount'), 0, ',', '.') }}
                                    </p>
                                    <p class="text-xs text-emerald-600 mt-1 flex items-center gap-1 font-semibold">
                                        <span>●</span> Uang masuk tercatat
                                    </p>
                                </div>
                                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                    </svg>
                                </div>
                            </div>

                            {{-- Card 2: Pengeluaran Hari Ini --}}
                            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center justify-between hover:shadow-md transition">
                                <div>
                                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Pengeluaran Hari Ini</p>
                                    <p class="text-xl sm:text-2xl font-extrabold text-gray-900 mt-1">
                                        Rp {{ number_format($expenses->sum('amount'), 0, ',', '.') }}
                                    </p>
                                    <p class="text-xs text-rose-500 mt-1 flex items-center gap-1 font-semibold">
                                        <span>●</span> {{ $expenses->count() }} transaksi keluar
                                    </p>
                                </div>
                                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                                    </svg>
                                </div>
                            </div>

                            {{-- Card 3: Laba Bersih Hari Ini --}}
                            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center justify-between hover:shadow-md transition">
                                <div>
                                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Laba Bersih Hari Ini</p>
                                    <p class="text-xl sm:text-2xl font-extrabold {{ $profit >= 0 ? 'text-[#2B7A78]' : 'text-rose-600' }} mt-1">
                                        Rp {{ number_format($profit, 0, ',', '.') }}
                                    </p>
                                    <div class="mt-1">
                                        @if ($profit > 0)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                                Surplus / Laba
                                            </span>
                                        @elseif ($profit < 0)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">
                                                Defisit
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-gray-100 text-gray-600">
                                                Imbang
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="w-12 h-12 rounded-2xl bg-teal-50 text-[#2B7A78] flex items-center justify-center shrink-0">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>

                            {{-- Card 4: Jumlah Pesanan --}}
                            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center justify-between hover:shadow-md transition">
                                <div>
                                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Jumlah Pesanan</p>
                                    <p class="text-xl sm:text-2xl font-extrabold text-gray-900 mt-1">
                                        {{ $total_orders }}
                                    </p>
                                    <p class="text-xs text-blue-600 mt-1 flex items-center gap-1 font-semibold">
                                        <span>●</span> Penjualan selesai
                                    </p>
                                </div>
                                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 4. AI Insight (Khusus jika role Owner membuka dashboard ini) --}}
                    @if (auth()->user()->isOwner())
                        <div>
                            @include('ai._insight')
                        </div>
                    @endif

                    {{-- 5. Grid Analitik: Ringkasan Sumber Masuk & Grafik Tren Bulanan --}}
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                        
                        {{-- Ringkasan Sumber Masuk Hari Ini --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-5 lg:col-span-5 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-4">
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-bold text-gray-800 text-sm">Pemasukan per Saluran</h3>
                                    </div>
                                    <span class="px-2 py-0.5 text-[11px] font-semibold rounded-full bg-slate-100 text-slate-600">
                                        Hari Ini
                                    </span>
                                </div>

                                <div class="space-y-3.5 max-h-[360px] overflow-y-auto pr-1">
                                    @php
                                        $totalSum = $summary->sum('total') ?: 1;
                                    @endphp
                                    @forelse ($summary as $item)
                                        @php
                                            $percent = round(($item['total'] / $totalSum) * 100);
                                        @endphp
                                        <div class="p-3 rounded-xl bg-slate-50 hover:bg-slate-100/70 border border-slate-100 transition">
                                            <div class="flex items-center justify-between text-xs font-semibold mb-1.5">
                                                <div class="flex items-center gap-2">
                                                    <span class="w-2.5 h-2.5 rounded-full bg-[#2B7A78]"></span>
                                                    <span class="text-gray-800">{{ $item['sumber'] }}</span>
                                                    <span class="text-gray-400 font-normal">({{ $item['jumlah'] }} trx)</span>
                                                </div>
                                                <span class="text-gray-900 font-bold">
                                                    Rp {{ number_format($item['total'], 0, ',', '.') }}
                                                </span>
                                            </div>
                                            {{-- Progress Bar --}}
                                            <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                                                <div class="bg-[#2B7A78] h-1.5 rounded-full" style="width: {{ $percent }}%"></div>
                                            </div>
                                            <div class="text-[10px] text-gray-400 text-right mt-1">
                                                {{ $percent }}% dari total pemasukan hari ini
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-10">
                                            <div class="w-12 h-12 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center mx-auto mb-2 text-xl">
                                                ☕
                                            </div>
                                            <p class="text-sm font-medium text-gray-600">Belum ada pemasukan hari ini</p>
                                            <p class="text-xs text-gray-400 mt-0.5">Transaksi penjualan yang dicatat kasir akan muncul di sini.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            @if ($summary->isNotEmpty())
                                <div class="mt-4 pt-3 border-t border-gray-100 flex justify-between items-center text-xs">
                                    <span class="text-gray-500 font-medium">Total Terkumpul:</span>
                                    <span class="text-sm font-bold text-[#2B7A78]">
                                        Rp {{ number_format($summary->sum('total'), 0, ',', '.') }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        {{-- Grafik Tren Bulanan --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-5 lg:col-span-7 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-4">
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-bold text-gray-800 text-sm">Tren Keuangan Bulanan</h3>
                                    </div>
                                    <span class="px-2.5 py-0.5 text-[11px] font-semibold rounded-full bg-slate-100 text-slate-700">
                                        Tahun {{ $currentYear }}
                                    </span>
                                </div>
                                <div class="relative h-[300px] w-full">
                                    <canvas id="combinedChart"></canvas>
                                </div>
                            </div>
                            <p class="text-[11px] text-gray-400 text-center mt-2">
                                Garis kurva membandingkan pemasukan dan pengeluaran operasional per bulan sepanjang tahun {{ $currentYear }}.
                            </p>
                        </div>
                    </div>

                    {{-- 6. Tabel Laporan Keuangan Transaksi Hari Ini (dengan Filter Buttons) --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-5 sm:p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-gray-100 mb-4">
                            <div>
                                <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                                    Transaksi Keuangan Hari Ini
                                </h3>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    Daftar transaksi kasir dan pengeluaran pada tanggal {{ now()->format('d/m/Y') }}
                                </p>
                            </div>

                            {{-- Filter Buttons Modern --}}
                            <div class="flex items-center gap-2 bg-slate-100 p-1 rounded-xl w-fit">
                                <button id="btn-all" onclick="filterTable('all')"
                                    class="filter-btn px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                                    Semua
                                </button>
                                <button id="btn-income" onclick="filterTable('income')"
                                    class="filter-btn px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                                    Pemasukan
                                </button>
                                <button id="btn-expense" onclick="filterTable('expense')"
                                    class="filter-btn px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                                    Pengeluaran
                                </button>
                            </div>
                        </div>

                        {{-- Tabel Transaksi --}}
                        <div class="overflow-x-auto">
                            @isset($transactions)
                                @if ($transactions->count() > 0)
                                    <table class="w-full text-left text-sm">
                                        <thead>
                                            <tr class="text-xs uppercase tracking-wider text-gray-500 bg-slate-50/80 border-y border-slate-100">
                                                <th class="py-3 px-4">No</th>
                                                <th class="py-3 px-4">Nama Transaksi</th>
                                                <th class="py-3 px-4">Sumber / Metode</th>
                                                <th class="py-3 px-4 text-right" id="th-income">Pemasukan</th>
                                                <th class="py-3 px-4 text-right" id="th-expense">Pengeluaran</th>
                                                <th class="py-3 px-4 text-center">Waktu</th>
                                                <th class="py-3 px-4">Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody id="transaction-table-body" class="divide-y divide-gray-100">
                                            @foreach ($transactions as $index => $transaction)
                                                <tr class="hover:bg-slate-50/70 transition" data-type="{{ $transaction->jenis }}">
                                                    <td class="py-3 px-4 text-gray-400 font-medium text-xs nomor-urut">{{ $loop->iteration }}</td>
                                                    
                                                    {{-- Nama Transaksi --}}
                                                    <td class="py-3 px-4">
                                                        @if ($transaction->jenis === 'income')
                                                            <div class="flex items-center gap-2">
                                                                <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs shrink-0 font-bold">
                                                                    +
                                                                </span>
                                                                <div>
                                                                    <p class="font-semibold text-gray-800">{{ $transaction->name }}</p>
                                                                    <span class="text-[10px] text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded font-medium">Pemasukan</span>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="flex items-center gap-2">
                                                                <span class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center text-xs shrink-0 font-bold">
                                                                    -
                                                                </span>
                                                                <div>
                                                                    <p class="font-semibold text-gray-800">{{ $transaction->bill->name ?? 'Pengeluaran' }}</p>
                                                                    <span class="text-[10px] text-rose-700 bg-rose-50 px-1.5 py-0.5 rounded font-medium">Pengeluaran</span>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </td>

                                                    {{-- Sumber --}}
                                                    <td class="py-3 px-4">
                                                        <span class="font-medium text-gray-700">{{ $transaction->source->name ?? '-' }}</span>
                                                        @if ($transaction->source && $transaction->source->is_online)
                                                            <span class="ml-1 px-1.5 py-0.5 text-[10px] font-semibold rounded bg-blue-50 text-blue-700 border border-blue-200">Online</span>
                                                        @endif
                                                    </td>

                                                    {{-- Pemasukan Col --}}
                                                    <td class="py-3 px-4 text-right income-col">
                                                        @if ($transaction->jenis === 'income')
                                                            <span class="font-bold text-emerald-700">
                                                                +Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                                            </span>
                                                        @else
                                                            <span class="text-gray-300">-</span>
                                                        @endif
                                                    </td>

                                                    {{-- Pengeluaran Col --}}
                                                    <td class="py-3 px-4 text-right expense-col">
                                                        @if ($transaction->jenis === 'expense')
                                                            <span class="font-bold text-rose-600">
                                                                -Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                                            </span>
                                                        @else
                                                            <span class="text-gray-300">-</span>
                                                        @endif
                                                    </td>

                                                    {{-- Tanggal --}}
                                                    <td class="py-3 px-4 text-center text-xs text-gray-500">
                                                        {{ $transaction->created_at ? $transaction->created_at->format('H:i') : $transaction->date->format('d/m/Y') }}
                                                    </td>

                                                    {{-- Keterangan --}}
                                                    <td class="py-3 px-4 text-xs text-gray-500 max-w-xs truncate">
                                                        {{ $transaction->description ?: '-' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="text-center py-8 text-gray-400">
                                        Tidak ada transaksi pemasukan maupun pengeluaran untuk hari ini.
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-8 text-gray-400">
                                    Data transaksi tidak tersedia.
                                </div>
                            @endisset
                        </div>
                    </div>

                </div>
            </div>

            <!-- sidebar content -->
            @include('layouts.sidebar')
        </div>
    </div>

    {{-- Script Chart.js dengan Style Modern Smooth Curve & Soft Fill --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('combinedChart');
            if (!canvas) return;

            const ctx = canvas.getContext('2d');

            // Gradient untuk Pemasukan (Teal / Emerald)
            const gradientIncome = ctx.createLinearGradient(0, 0, 0, 300);
            gradientIncome.addColorStop(0, 'rgba(43, 122, 120, 0.28)');
            gradientIncome.addColorStop(1, 'rgba(43, 122, 120, 0.01)');

            // Gradient untuk Pengeluaran (Rose / Red)
            const gradientExpense = ctx.createLinearGradient(0, 0, 0, 300);
            gradientExpense.addColorStop(0, 'rgba(225, 29, 72, 0.22)');
            gradientExpense.addColorStop(1, 'rgba(225, 29, 72, 0.01)');

            const combinedChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($months) !!},
                    datasets: [
                        {
                            label: 'Pemasukan',
                            data: {!! json_encode($incomeData) !!},
                            borderColor: '#2B7A78',
                            backgroundColor: gradientIncome,
                            borderWidth: 2.5,
                            fill: true,
                            tension: 0.38,
                            pointRadius: 3.5,
                            pointBackgroundColor: '#2B7A78',
                            pointHoverRadius: 6,
                        },
                        {
                            label: 'Pengeluaran',
                            data: {!! json_encode($expenseData) !!},
                            borderColor: '#E11D48',
                            backgroundColor: gradientExpense,
                            borderWidth: 2.5,
                            fill: true,
                            tension: 0.38,
                            pointRadius: 3.5,
                            pointBackgroundColor: '#E11D48',
                            pointHoverRadius: 6,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            align: 'end',
                            labels: {
                                usePointStyle: true,
                                pointStyle: 'circle',
                                boxWidth: 8,
                                padding: 16,
                                font: {
                                    size: 12,
                                    weight: 'bold',
                                    family: 'system-ui, sans-serif'
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.92)',
                            padding: 12,
                            cornerRadius: 8,
                            titleFont: { size: 13, weight: 'bold' },
                            bodyFont: { size: 12 },
                            callbacks: {
                                label: function (context) {
                                    const val = context.parsed.y || 0;
                                    return ' ' + context.dataset.label + ': Rp ' + val.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false,
                            },
                            ticks: {
                                font: { size: 11 },
                                color: '#64748B'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(226, 232, 240, 0.8)',
                                borderDash: [4, 4]
                            },
                            ticks: {
                                color: '#64748B',
                                font: { size: 11 },
                                callback: function (value) {
                                    if (value >= 1000000) return 'Rp ' + (value / 1000000).toFixed(1) + ' jt';
                                    if (value >= 1000) return 'Rp ' + (value / 1000).toFixed(0) + ' rb';
                                    return 'Rp ' + value;
                                }
                            }
                        }
                    }
                }
            });

            window.addEventListener('resize', () => {
                combinedChart.resize();
            });

            // Set default filter transaksi
            filterTable('all');
        });

        function filterTable(type) {
            const rows = document.querySelectorAll('#transaction-table-body tr');
            const incomeCols = document.querySelectorAll('.income-col');
            const expenseCols = document.querySelectorAll('.expense-col');
            const thIncome = document.getElementById('th-income');
            const thExpense = document.getElementById('th-expense');

            let count = 1;

            rows.forEach(row => {
                const rowType = row.getAttribute('data-type');
                const noCell = row.querySelector('.nomor-urut');

                if (type === 'all' || rowType === type) {
                    row.style.display = '';
                    if (noCell) noCell.textContent = count++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (type === 'income') {
                incomeCols.forEach(col => col.style.display = '');
                expenseCols.forEach(col => col.style.display = 'none');
                if (thIncome) thIncome.style.display = '';
                if (thExpense) thExpense.style.display = 'none';
            } else if (type === 'expense') {
                incomeCols.forEach(col => col.style.display = 'none');
                expenseCols.forEach(col => col.style.display = '');
                if (thIncome) thIncome.style.display = 'none';
                if (thExpense) thExpense.style.display = '';
            } else {
                incomeCols.forEach(col => col.style.display = '');
                expenseCols.forEach(col => col.style.display = '');
                if (thIncome) thIncome.style.display = '';
                if (thExpense) thExpense.style.display = '';
            }

            // Highlight tombol aktif
            const buttons = {
                all: document.getElementById('btn-all'),
                income: document.getElementById('btn-income'),
                expense: document.getElementById('btn-expense')
            };

            Object.values(buttons).forEach(btn => {
                if (!btn) return;
                btn.className = 'filter-btn px-3 py-1.5 rounded-lg text-xs font-semibold text-gray-600 hover:text-gray-900 transition';
            });

            if (buttons[type]) {
                buttons[type].className = 'filter-btn px-3 py-1.5 rounded-lg text-xs font-semibold bg-white text-[#2B7A78] shadow-sm transition';
            }
        }

        // Dropdown Sidebar Helpers
        function dropdownLaporan() {
            document.getElementById('dropdownLaporanMenu')?.classList.toggle('hidden');
        }
        function dropdownKategori() {
            document.getElementById('dropdownKategoriMenu')?.classList.toggle('hidden');
        }
        function dropdownPemasukkan() {
            document.getElementById('dropdownPemasukkanMenu')?.classList.toggle('hidden');
        }

        document.addEventListener("click", function(event) {
            const laporanMenu = document.getElementById("dropdownLaporanMenu");
            const laporanBtn = document.getElementById("dropdownLaporanButton");
            if (laporanMenu && laporanBtn && !laporanMenu.contains(event.target) && !laporanBtn.contains(event.target)) {
                laporanMenu.classList.add("hidden");
            }

            const kategoriMenu = document.getElementById("dropdownKategoriMenu");
            const kategoriBtn = document.getElementById("dropdownKategoriButton");
            if (kategoriMenu && kategoriBtn && !kategoriMenu.contains(event.target) && !kategoriBtn.contains(event.target)) {
                kategoriMenu.classList.add("hidden");
            }

            const pemasukkanMenu = document.getElementById("dropdownPemasukkanMenu");
            const pemasukkanBtn = document.getElementById("dropdownPemasukkanButton");
            if (pemasukkanMenu && pemasukkanBtn && !pemasukkanMenu.contains(event.target) && !pemasukkanBtn.contains(event.target)) {
                pemasukkanMenu.classList.add("hidden");
            }
        });
    </script>
</x-app-layout>
