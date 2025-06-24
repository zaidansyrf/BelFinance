@section('title', 'Dashboard')
<x-app-layout>

    @include('layouts.navbar-owner')

    <!-- main content -->
    <div class="flex-1 bg-[#D1DDD5] overflow-auto pt-10">
        <div class="sticky justify-between items-center  mt-12 px-8">
            <h1 class="text-xl font-semibold text-[#2B7A78] mb-4">Selamat Datang, {{ Auth::user()->name }}</h1>
            <p class="text-sm text-grey">berikut overview dari keuangan Belindo Kitchen</p>
            <!-- keuangan tahunan -->
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-4 mt-6">
                            <div
                                class="text-white p-4 rounded-lg text-center shadow-md w-full"
                                style="background-color: #2B7A78;">
                                <p class="text-lg font-bold">Rp. {{ number_format($yearlyIncome, 0, ',', '.') }}</p>
                                <p class="text-sm">Total Pemasukan Tahun {{ $currentYear }}</p>
                            </div>

                            <div
                                class="text-white p-4 rounded-md text-center shadow-md w-full"
                                style="background-color: #2B7A78;">
                                <p class="text-lg font-bold">Rp. {{ number_format($yearlyExpense, 0, ',', '.') }}</p>
                                <p class="text-sm">Total Pengeluaran Tahun {{ $currentYear }}</p>
                            </div>

                            <div
                                class="text-white p-4 rounded-lg text-center shadow-md w-full"
                                style="background-color: #2B7A78;">
                                <p class="text-lg font-bold">Rp. {{ number_format($yearlyProfit, 0, ',', '.') }}</p>
                                <p class="text-sm">Laba/Rugi Tahun {{ $currentYear }}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mt-4">
                            <!-- card pemasukkan total -->
                            <div
                                class="relative text-white p-4 rounded-lg w-full text-center shadow-md overflow-hidden"
                                style="background-color: #55AD9B;">
                                <div class="absolute inset-0 opacity-50 pointer-events-none">
                                    <svg viewBox="0 0 122.88 68.04" xmlns="http://www.w3.org/2000/svg"
                                        class="w-full h-full object-contain">
                                        <path d="M2.03,56.52c-2.66,2.58-2.72,6.83-0.13,9.49c2.58,2.66,6.83,2.72,9.49,0.13l27.65-26.98l23.12,22.31 c2.67,2.57,6.92,2.49,9.49-0.18l37.77-38.22v19.27c0,3.72,3.01,6.73,6.73,6.73s6.73-3.01,6.73-6.73V6.71h-0.02 c0-1.74-0.67-3.47-2-4.78c-1.41-1.39-3.29-2.03-5.13-1.91H82.4c-3.72,0-6.73,3.01-6.73,6.73c0,3.72,3.01,6.73,6.73,6.73h17.63 L66.7,47.2L43.67,24.97c-2.6-2.5-6.73-2.51-9.33,0.03L2.03,56.52z" 
                                            fill="white"
                                            fill-opacity="0.3" />
                                    </svg>
                                </div>
                                <p class="text-lg font-bold relative z-10">Rp.
                                    {{ number_format($income->sum('amount'), 0, ',', '.') }}</p>
                                <p class="text-sm relative z-10">Pemasukan Hari Ini</p>
                            </div>
                            <!-- card pengeluaran total -->
                            <div
                                 class="relative text-white p-4 rounded-lg w-full text-center shadow-md overflow-hidden"
                                 style="background-color: #D35D6E;">
                                <div class="absolute inset-0 opacity-50 pointer-events-none">
                                    <svg viewBox="0 0 122.88 68.04" xmlns="http://www.w3.org/2000/svg"
                                        class="w-full h-full">
                                        <path d="M2.03,11.52C-0.63,8.94-0.68,4.69,1.9,2.03c2.58-2.66,6.83-2.72,9.49-0.13l27.65,26.98L62.16,6.57 c2.67-2.57,6.92-2.49,9.49,0.18l37.77,38.22V25.7c0-3.72,3.01-6.73,6.73-6.73s6.73,3.01,6.73,6.73v35.63h-0.02 c0,1.74-0.67,3.47-2,4.78c-1.41,1.39-3.29,2.03-5.13,1.91H82.4c-3.72,0-6.73-3.01-6.73-6.73c0-3.72,3.01-6.73,6.73-6.73h17.63 L66.7,20.84L43.67,43.07c-2.6,2.5-6.73,2.51-9.33-0.03L2.03,11.52z"
                                         fill="white" 
                                         fill-opacity="0.3" />
                                    </svg>
                                </div>
                                <p class="text-lg font-bold relative z-10">Rp.
                                    {{ number_format($expenses->sum('amount'), 0, ',', '.') }}</p>
                                <p class="text-sm relative z-10">Pengeluaran Hari Ini</p>
                            </div>
                            <!-- card jumlah transaksi -->
                            <div
                                class="relative text-white p-4 rounded-lg w-full text-center shadow-md overflow-hidden"
                                 style="background-color: #6A8CAF;">
                                <p class="text-lg font-bold">{{ $total_orders }}</p>
                                <p class="text-sm">Jumlah Pesanan</p>
                            </div>
                            <div
                                class="bg-gradient-to-r {{ $profit >= 0 ? 'from-profit-green to-profit-green' : 'from-loss-red to-loss-red' }} text-white p-4 rounded-lg w-full text-center shadow-md">
                                <p class="text-lg font-bold">Rp. {{ number_format($profit, 0, ',', '.') }}</p>
                                <p class="text-sm">Keuntungan Hari Ini</p>
                            </div>
                        </div>
        </div>
        <div class="flex-1 bg-[#D1DDD5] overflow-hidden px-8 mt-12 mb-4">
            <h1 class="text-xl font-semibold text-[#2B7A78] mb-4">Analytic</h1>
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- section Pesanan Baru -->
                <div class="bg-white shadow-md rounded-lg p-4 w-full lg:w-[48%]">
                    <h2 class="text-lg font-semibold text-black mb-4"></h2>
                    <div class="overflow-y-auto max-h-[400px]">
                        <table class="min-w-full border border-gray-300">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 border">No</th>
                                    <th class="px-4 py-2 border">Sumber</th>
                                    <th class="px-4 py-2 border">Jumlah </th>
                                    <th class="px-4 py-2 border">Total masuk</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($summary as $index => $data)
                                    <tr class="text-center">
                                        <td class="px-4 py-2 border">{{ $index + 1 }}</td>
                                        <td class="px-4 py-2 border">{{ $data['sumber'] }}</td>
                                        <td class="px-4 py-2 border">{{ $data['jumlah'] }}</td>
                                        <td class="px-4 py-2 border">Rp
                                            {{ number_format($data['total'], 0, ',', '.') }}</td>
                                    </tr>
                                    @if ($summary->isEmpty())
                                        <tr>
                                            <td colspan="4" class="text-center px-4 py-4">Belum ada
                                                pesanan hari ini.</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Section  grafik-->
                <div class="bg-white shadow-md rounded-lg p-4 w-full relative">
                    <h2 class="text-lg font-semibold text-black mb-4"></h2>
                    <div class="overflow-auto h-[300px] sm:h-[400px] lg:h-auto">
                        <canvas id="combinedChart" class="w-full h-full"></canvas>
                    </div>
                    
                </div>

            </div>
            <div class="mb-4 card text-primary-content bg-white mt-4 w-full">
                            <div class="card-body">
                                <div class="overflow-hidden">
                                      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                                        <div class="bg-[#2B7A78] text-white p-4 rounded-lg">
                                            <h3 class="text-sm">Total Menu Terjual</h3>
                                            <p class="text-2xl font-bold">{{$totalSold}}</p>
                                        </div>
                                        <div class="bg-[#3AAFA9] text-white p-4 rounded-lg">
                                            <h3 class="text-sm">Menu Terlaris</h3>
                                            <p class="text-lg font-bold">{{ $topSellingMenu->name ?? '-' }}</p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
            </div>

        <div class="flex-1 bg-[#D1DDD5] overflow-auto px-8 mt-5 mb-4">
            <div class="bg-white shadow-md rounded-lg p-10 mt-8">

                {{-- Tombol Filter --}}
                <div class="mb-4 space-x-2">
                    <button id="btn-income" onclick="filterTable('income')"
                        class="filter-btn px-4 py-2 rounded-md text-sm font-medium bg-white text-[#468585] border border-[#468585] transition-colors duration-200">
                        Pemasukan
                    </button>
                    <button id="btn-expense" onclick="filterTable('expense')"
                        class="filter-btn px-4 py-2 rounded-md text-sm font-medium bg-white text-[#468585] border border-[#468585] transition-colors duration-200">
                        Pengeluaran
                    </button>
                    <button id="btn-all" onclick="filterTable('all')"
                        class="filter-btn px-4 py-2 rounded-md text-sm font-medium bg-white text-[#468585] border border-[#468585] transition-colors duration-200">
                        Semua
                    </button>
                </div>

                    {{-- Tabel Transaksi --}}
                    <div class="overflow-x-auto pt-6">
                        @isset($transactions)
                            @if ($transactions->count() > 0)
                                <table class="min-w-full border border-gray-300">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-4 py-2 border">No</th>
                                            <th class="px-4 py-2 border">Nama</th>
                                            <th class="px-4 py-2 border">Sumber</th>
                                            <th class="px-4 py-2 border" id="th-income">Pemasukan</th>
                                            <th class="px-4 py-2 border" id="th-expense">Pengeluaran</th>
                                            <th class="px-4 py-2 border">Tanggal</th>
                                            <th class="px-4 py-2 border">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody id="transaction-table-body">
                                        @foreach ($transactions as $index => $transaction)
                                            <tr class="text-center bg-white hover:bg-gray-50"
                                                data-type="{{ $transaction->type }}">
                                                <td class="px-4 py-2 border nomor-urut">{{ $loop->iteration }}</td>
                                                <td class="px-4 py-2 border">
                                                    @if ($transaction->type === 'income')
                                                        {{ $transaction->name }}
                                                    @else
                                                        {{ $transaction->bill->name ?? 'Tidak ada nama' }}
                                                    @endif
                                                </td>
                                                <td class="px-4 py-2 border">{{ $transaction->source->name ?? '-' }}</td>
                                                <td class="px-4 py-2 border income-col">
                                                    @if ($transaction->type === 'income')
                                                        Rp. {{ number_format($transaction->amount, 0, ',', '.') }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="px-4 py-2 border expense-col">
                                                    @if ($transaction->type === 'expense')
                                                        Rp. {{ number_format($transaction->amount, 0, ',', '.') }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="px-4 py-2 border">
                                                    {{ $transaction->created_at->format('d-m-Y') }}
                                                </td>
                                                <td class="px-4 py-2 border">{{ $transaction->description }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p class="text-center py-4">Tidak ada transaksi hari ini.</p>
                            @endif
                        @else
                            <p class="text-center py-4">Data transaksi tidak tersedia.</p>
                        @endisset
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('combinedChart')?.getContext('2d');
        if (ctx) {
            const combinedChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($months) !!},
                    datasets: [{
                            label: 'Pemasukkan',
                            data: {!! json_encode($incomeData) !!},
                            backgroundColor: 'rgba(75, 192, 192, 0.5)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Pengeluaran',
                            data: {!! json_encode($expenseData) !!},
                            backgroundColor: 'rgba(255, 99, 132, 0.5)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false, 
                    plugins: {
                        legend: {
                            labels: {
                                usePointStyle: true,     
                                pointStyle: 'circle',    
                                boxWidth: 10,             
                                padding: 20               
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function (value) {
                                    return 'Rp' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });

            window.addEventListener('resize', () => {
                combinedChart.resize();
            });
        }

        function dropdownLaporan() {
            const dropdownLaporanMenu = document.getElementById('dropdownLaporanMenu');
            dropdownLaporanMenu?.classList.toggle('hidden');
        }
        document.addEventListener("click", function(event) {
            const dropdownLaporanMenu = document.getElementById("dropdownLaporanMenu");
            const dropdownLaporanButton = document.getElementById("dropdownLaporanButton");

            if (dropdownLaporanMenu && dropdownLaporanButton) {
                if (
                    !dropdownLaporanMenu.contains(event.target) &&
                    !dropdownLaporanButton.contains(event.target)
                ) {
                    dropdownLaporanMenu.classList.add("hidden");
                }
            }
        });

        function dropdownKategori() {
            const dropdownKategoriMenu = document.getElementById('dropdownKategoriMenu');
            dropdownKategoriMenu?.classList.toggle('hidden');
        }
        document.addEventListener("click", function(event) {
            const dropdownKategoriMenu = document.getElementById("dropdownKategoriMenu");
            const dropdownKategoriButton = document.getElementById("dropdownKategoriButton");

            if (dropdownKategoriMenu && dropdownKategoriButton) {
                if (
                    !dropdownKategoriMenu.contains(event.target) &&
                    !dropdownKategoriButton.contains(event.target)
                ) {
                    dropdownKategoriMenu.classList.add("hidden");
                }
            }
        });

        function toggleAdditionalFields() {
            const paymentSource = document.getElementById('paymentSource')?.value;

            if (!paymentSource) return;

            // Hide all additional fields first
            const orderMenu = document.getElementById('orderMenu');
            orderMenu?.classList.add('hidden');

            // Show relevant fields based on the selected income source
            if (paymentSource === 'shopee') {
                orderMenu?.classList.remove('hidden');
            }
        }

        function dropdownPemasukkan() {
            const dropdownPemasukkanMenu = document.getElementById('dropdownPemasukkanMenu');
            dropdownPemasukkanMenu?.classList.toggle('hidden');
        }
        document.addEventListener("click", function(event) {
            const dropdownPemasukkanMenu = document.getElementById("dropdownPemasukkanMenu");
            const dropdownPemasukkanButton = document.getElementById("dropdownPemasukkanButton");

            if (dropdownPemasukkanMenu && dropdownPemasukkanButton) {
                if (
                    !dropdownPemasukkanMenu.contains(event.target) &&
                    !dropdownPemasukkanButton.contains(event.target)
                ) {
                    dropdownPemasukkanMenu.classList.add("hidden");
                }
            }
        });


        function filterTable(type) {
            const rows = document.querySelectorAll('#transaction-table-body tr');
            const incomeCols = document.querySelectorAll('.income-col');
            const expenseCols = document.querySelectorAll('.expense-col');
            const thIncome = document.getElementById('th-income');
            const thExpense = document.getElementById('th-expense');
            const nomorUrut = document.querySelectorAll('.nomor-urut');

            let count = 1;

            rows.forEach(row => {
                const rowType = row.getAttribute('data-type');
                const noCell = row.querySelector('.nomor-urut');

                if (type === 'all' || rowType === type) {
                    row.style.display = '';
                    noCell.textContent = count++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (type === 'income') {
                incomeCols.forEach(col => col.style.display = '');
                expenseCols.forEach(col => col.style.display = 'none');
                thIncome.style.display = '';
                thExpense.style.display = 'none';
            } else if (type === 'expense') {
                incomeCols.forEach(col => col.style.display = 'none');
                expenseCols.forEach(col => col.style.display = '');
                thIncome.style.display = 'none';
                thExpense.style.display = '';
            } else {
                incomeCols.forEach(col => col.style.display = '');
                expenseCols.forEach(col => col.style.display = '');
                thIncome.style.display = '';
                thExpense.style.display = '';
            }

            // Highlight tombol aktif
            const buttons = {
                income: document.getElementById('btn-income'),
                expense: document.getElementById('btn-expense'),
                all: document.getElementById('btn-all')
            };

            Object.values(buttons).forEach(btn => {
                btn.classList.remove('bg-[#468585]', 'text-white');
                btn.classList.add('bg-white', 'text-[#468585]');
            });

            if (buttons[type]) {
                buttons[type].classList.remove('bg-white', 'text-[#468585]');
                buttons[type].classList.add('bg-[#468585]', 'text-white');
            }
        }

        // Set default filter
        window.addEventListener('DOMContentLoaded', () => filterTable('all'));
    </script>

</x-app-layout>
