@section('title', 'Dashboard')
<x-app-layout>
    <div class="h-screen w-full bg-gray-100 flex overflow-hidden">
        <!-- sidebar -->
        <div class="drawer lg:drawer-open">
            <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />

            <!-- drawer content -->
            <div class="drawer-content flex flex-col h-screen">
                <!-- wrapper for spacing -->
                @include('layouts.navbar')


                <!-- main content -->
                <div class="py-2 flex-1 bg-[#D1DDD5] overflow-auto">
                    <div class="sticky justify-between items-center mt-12 px-8">
                        <h1 class="text-xl font-semibold text-[#2B7A78] mb-4">Selamat Datang</h1>
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
                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 mt-4">
                            <!-- card pemasukkan total -->
                            <div class="relative text-white p-4 rounded-lg w-full shadow-md overflow-hidden flex items-center justify-start space-x-4" style="background-color: #55AD9B;">
                                <div class="bg-white p-3 rounded-xl z-10">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-black" fill="none" viewBox="0 0 24 24" stroke="green" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 17l6-6 4 4 8-8M14 5h7v7" />
                                    </svg>
                                </div>
                                <div class="z-10">
                                    <p class="text-lg font-bold">Rp. {{ number_format($income->sum('amount'), 0, ',', '.') }}</p>
                                    <p class="text-sm">Pemasukan Hari Ini</p>
                                </div>
                            </div>
                            <!-- card pengeluaran total -->
                            <div class="relative text-white p-4 rounded-lg w-full shadow-md overflow-hidden flex items-center space-x-4" style="background-color: #D35D6E;">
                                <div class="bg-white p-3 rounded-xl z-10">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7l6 6 4-4 8 8M14 17h7v-7" />
                                    </svg>
                                </div>

                                <div class="z-10">
                                    <p class="text-lg font-bold">Rp. {{ number_format($expenses->sum('amount'), 0, ',', '.') }}</p>
                                    <p class="text-sm">Pengeluaran Hari Ini</p>
                                </div>
                            </div>

                            <!-- card jumlah transaksi -->
                            <div class="relative text-white p-4 rounded-lg w-full shadow-md overflow-hidden flex items-center space-x-4" style="background-color: #6A8CAF;">
                                <div class="bg-white p-3 rounded-xl z-10">
                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-black" viewBox="0 0 92.04 122.88" fill="none" stroke="blue">
                                        <path d="M21.74,33.56h48.65c0.24,0,0.44,0.2,0.44,0.44c0,3.15,0,1.05,0,4.19c0,0.24-0.2,0.44-0.44,0.44H21.74 c-0.29,0-0.53-0.24-0.53-0.53c0-3.09,0-0.93,0-4.02C21.21,33.8,21.45,33.56,21.74,33.56L21.74,33.56z M9.25,7.23h7.81V2.33 c0-1.28,1.28-2.33,2.85-2.33h0c1.57,0,2.85,1.05,2.85,2.33v4.89h12.4V2.33C35.16,1.05,36.44,0,38,0h0c1.57,0,2.85,1.05,2.85,2.33 v4.89h12.4V2.33C53.25,1.05,54.53,0,56.1,0h0c1.57,0,2.85,1.05,2.85,2.33v4.89h12.4V2.33c0-1.28,1.28-2.33,2.85-2.33h0 c1.57,0,2.85,1.05,2.85,2.33v4.89h5.74c5.09,0,9.25,4.16,9.25,9.25v97.15c0,5.09-4.16,9.25-9.25,9.25H9.25 c-5.09,0-9.25-4.16-9.25-9.25V16.48C0,11.39,4.16,7.23,9.25,7.23L9.25,7.23z M9.99,15.1h7.07v3.47c0,1.28,1.28,2.33,2.85,2.33h0 c1.57,0,2.85-1.05,2.85-2.33V15.1h12.4v3.47c0,1.28,1.28,2.33,2.85,2.33h0c1.57,0,2.85-1.05,2.85-2.33V15.1h12.4v3.47 c0,1.28,1.28,2.33,2.85,2.33h0c1.57,0,2.85-1.05,2.85-2.33V15.1h12.4v3.47c0,1.28,1.28,2.33,2.85,2.33h0 c1.57,0,2.85-1.05,2.85-2.33V15.1h5c1.43,0,2.61,1.18,2.61,2.61v94.68c0,1.42-1.18,2.61-2.61,2.61H9.99 c-1.42,0-2.61-1.17-2.61-2.61V17.71C7.38,16.28,8.56,15.1,9.99,15.1L9.99,15.1z M21.74,104.89h48.65c0.24,0,0.44-0.2,0.44-0.44 c0-3.15,0-1.05,0-4.19c0-0.24-0.2-0.44-0.44-0.44H21.74c-0.29,0-0.53,0.24-0.53,0.53c0,2.73,0,1.29,0,4.02 C21.21,104.65,21.45,104.89,21.74,104.89L21.74,104.89z M21.74,88.33h48.65c0.24,0,0.44-0.2,0.44-0.44c0-3.15,0-0.57,0-3.71 c0-0.24-0.2-0.44-0.44-0.44H21.74c-0.29,0-0.53,0.24-0.53,0.53c0,3.09,0,0.45,0,3.54C21.21,88.09,21.45,88.33,21.74,88.33 L21.74,88.33z M21.74,71.76h48.65c0.24,0,0.44-0.2,0.44-0.44c0-3.15,0-1.53,0-4.67c0-0.24-0.2-0.44-0.44-0.44H21.74 c-0.29,0-0.53,0.24-0.53,0.53c0,3.09,0,1.41,0,4.5C21.21,71.52,21.45,71.76,21.74,71.76L21.74,71.76z M21.74,55.2h48.65 c0.24,0,0.44-0.2,0.44-0.44c0-3.15,0-1.05,0-4.19c0-0.24-0.2-0.44-0.44-0.44H21.74c-0.29,0-0.53,0.24-0.53,0.53 c0,3.09,0,0.93,0,4.02C21.21,54.96,21.45,55.2,21.74,55.2L21.74,55.2z"/>
                                    </svg>
                                </div>
                                <div class="z-10">
                                    <p class="text-lg font-bold">{{ $total_orders }}</p>
                                    <p class="text-sm">Jumlah Pesanan</p>
                                </div>
                            </div>
                            <!-- card profit -->
                            <div class="bg-gradient-to-r {{ $profit >= 0 ? 'from-profit-green to-profit-green' : 'from-loss-red to-loss-red' }} text-white p-4 rounded-lg w-full shadow-md flex items-center space-x-4">
                                <div class="bg-white p-3 rounded-xl z-10">
                                    @if($profit >= 0)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 17l6-6 4 4 8-8M14 5h7v7" />
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7l6 6 4-4 8 8M14 17h7v-7" />
                                        </svg>
                                    @endif
                                </div>
                                <div class="z-10">
                                    <p class="text-sm">Keuntungan Hari Ini</p>
                                    <p class="text-lg font-bold">Rp. {{ number_format($profit, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex-1 bg-[#D1DDD5] overflow-auto px-8 mt-12 mb-4">
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
                    </div>

                    <div class="flex-1 bg-[#D1DDD5] overflow-auto px-8 mt-5 mb-4">
                        <div class="bg-white shadow-md rounded-lg p-10 mt-8 ">
                            <h2 class="text-lg font-semibold text-[#2B7A78] mb-4">Laporan Keuangan Hari Ini</h2>
                            <div class="overflow-x-auto">
                                @isset($transactions)
                                    @if ($transactions->count() > 0)
                                        <table class="min-w-full border border-gray-300">
                                            <thead class="bg-gray-100">
                                                <tr>
                                                    <th class="px-4 py-2 border">No</th>
                                                    <th class="px-4 py-2 border">Nama</th>
                                                    <th class="px-4 py-2 border">Sumber</th>
                                                    <th class="px-4 py-2 border">Pemasukan</th>
                                                    <th class="px-4 py-2 border">Pengeluaran</th>
                                                    <th class="px-4 py-2 border">Tanggal</th>
                                                    <th class="px-4 py-2 border">Keterangan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($transactions as $index => $transaction)
                                                    <tr class="text-center">
                                                        @php static $no = 1; @endphp
                                                        <td class="px-4 py-2 border">{{ $no++ }}</td>
                                                        <td class="px-4 py-2 border">
                                                            @if ($transaction->type === 'income')
                                                                {{ $transaction->name }}
                                                            @else
                                                                {{ $transaction->bill->name ?? 'Tidak ada nama' }}
                                                            @endif
                                                        </td>
                                                        <td class="px-4 py-2 border">
                                                            {{ $transaction->source->name ?? '-' }}</td>
                                                        <td class="px-4 py-2 border">
                                                            @if ($transaction->type === 'income')
                                                                Rp. {{ number_format($transaction->amount, 0, ',', '.') }}
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td class="px-4 py-2 border">
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

                    {{-- <!-- Laporan Keuangan Hari Ini -->
                    <div class="flex-1 bg-[#D1DDD5] overflow-auto px-8 mt-5 mb-4">
                        <div class="bg-white shadow-md rounded-lg p-10 mt-8 ">
                            <h2 class="text-lg font-semibold text-[#2B7A78] mb-4">Laporan Keuangan Hari Ini</h2>
                            <div class="overflow-x-auto">
                                <table class="min-w-full border border-gray-300">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-4 py-2 border">No</th>
                                            <th class="px-4 py-2 border">Nama</th>
                                            <th class="px-4 py-2 border">Sumber</th>
                                            <th class="px-4 py-2 border">Pemasukan</th>
                                            <th class="px-4 py-2 border">Pengeluaran</th>
                                            <th class="px-4 py-2 border">Tanggal</th>
                                            <th class="px-4 py-2 border">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($transactions as $index => $transaction)
                                            <tr class="text-center">
                                                <td class="px-4 py-2 border">{{ $index + 1 }}</td>
                                                <td class="px-4 py-2 border">{{ $transaction->name }}</td>
                                                <td class="px-4 py-2 border">{{ $transaction->source }}</td>
                                                <td class="px-4 py-2 border">
                                                    @if ($transaction->type === 'income')
                                                        Rp. {{ number_format($transaction->amount, 0, ',', '.') }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="px-4 py-2 border">
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
                                        @if ($transactions->isEmpty())
                                            <tr>
                                                <td colspan="7" class="text-center px-4 py-4">Belum ada transaksi
                                                    hari
                                                    ini.</td>
                                            </tr>
                                        @endif

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
            <!-- sidebar content -->
            @include('layouts.sidebar')
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
                            position:'bottom',
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
    </script>

</x-app-layout>
