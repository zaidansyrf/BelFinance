@section('title', 'Dashboard')
<x-app-layout>
    <x-slot name="header">
        <!-- Sticky header with white background and subtle shadow -->
        <header class="sticky top-0 z-50 ">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <!-- Logo section - left side -->
                    <div class="flex items-center">
                        <a href="/" class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="#468585" class="h-8 w-8 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            <span class="text-xl font-bold text-[#468585]">BelFinance</span>
                        </a>
                    </div>

                    <!-- Navigation menu - center -->
                    <nav class="hidden md:flex items-center space-x-1 bg-white p-1 rounded-lg border border-gray-200">
                        <a href="#"
                            class="px-4 py-2 text-sm font-medium text-white bg-gray-50 rounded-md transition-colors duration-200"
                            style="background-color: #468585;">
                            Beranda
                        </a>
                        <a href="#"
                            class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-[#468585] hover:bg-gray-50 rounded-md transition-colors duration-200">
                            Laporan Keuangan
                        </a>
                    </nav>

                    <!-- Profile section - right side -->
                    <div class="flex items-center">
                        <button type="button"
                            class="relative flex rounded-full bg-gray-100 p-1 text-gray-400 hover:text-[#468585] focus:outline-none focus:ring-2 focus:ring-[#468585] focus:ring-offset-2 transition-colors duration-200">
                            <span class="absolute -inset-1.5"></span>
                            <span class="sr-only">View profile</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </header>
    </x-slot>


    <!-- main content -->
    <div class="flex-1 bg-[#D1DDD5] overflow-auto">
        <div class="sticky justify-between items-center mt-12 px-8">
            <h1 class="text-xl font-semibold text-[#2B7A78] mb-4">Selamat Datang</h1>
            <!-- keuangan tahunan -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-6">
                <!--  pemasukan tahunan -->
                <div
                    class="bg-gradient-to-r from-green-500 to-green-700 text-white p-4 rounded-lg text-center shadow-md w-full">
                    <p class="text-sm">Total Pemasukan Tahun </p>
                    <p class="text-lg font-bold">Rp. </p>
                </div>

                <!--  pengeluaran tahunan -->
                <div
                    class="bg-gradient-to-r from-red-500 to-red-700 text-white p-4 rounded-lg text-center shadow-md w-full">
                    <p class="text-sm">Total Pengeluaran Tahun </p>
                    <p class="text-lg font-bold">Rp. </p>
                </div>

                <!-- laba/rugi tahunan -->
                <div class="bg-gradient-to-r  text-white p-4 rounded-lg text-center shadow-md w-full">
                    <p class="text-sm">Laba/Rugi Tahun </p>
                    <p class="text-lg font-bold">Rp. </p>
                </div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mt-4">
                <!-- card pemasukkan total -->
                <div
                    class="relative bg-gradient-to-r from-green-500 to-green-700 text-white p-4 rounded-lg w-full text-center shadow-md overflow-hidden">
                    <div class="absolute inset-0 opacity-50 pointer-events-none">
                        <svg viewBox="0 0 122.88 68.04" xmlns="http://www.w3.org/2000/svg"
                            class="w-full h-full object-contain">
                            <path d="M2.03,56.52c-2.66,2.58-2.72,6.83-0.13,9.49c2.58,2.66,6.83,2.72,9.49,0.13l27.65-26.98l23.12,22.31
          c2.67,2.57,6.92,2.49,9.49-0.18l37.77-38.22v19.27c0,3.72,3.01,6.73,6.73,6.73s6.73-3.01,6.73-6.73V6.71h-0.02
          c0-1.74-0.67-3.47-2-4.78c-1.41-1.39-3.29-2.03-5.13-1.91H82.4c-3.72,0-6.73,3.01-6.73,6.73c0,3.72,3.01,6.73,6.73,6.73h17.63
          L66.7,47.2L43.67,24.97c-2.6-2.5-6.73-2.51-9.33,0.03L2.03,56.52z" fill="white" fill-opacity="0.3" />
                        </svg>
                    </div>
                    <p class="text-sm relative z-10">Pemasukan Hari Ini</p>
                    <p class="text-lg font-bold relative z-10">Rp.
                    </p>
                </div>
                <!-- card pengeluaran total -->
                <div
                    class="relative bg-gradient-to-r from-red-500 to-red-700 text-white p-4 rounded-lg w-full text-center shadow-md overflow-hidden">
                    <div class="absolute inset-0 opacity-50 pointer-events-none">
                        <svg viewBox="0 0 122.88 68.04" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
                            <path d="M2.03,11.52C-0.63,8.94-0.68,4.69,1.9,2.03c2.58-2.66,6.83-2.72,9.49-0.13l27.65,26.98L62.16,6.57
          c2.67-2.57,6.92-2.49,9.49,0.18l37.77,38.22V25.7c0-3.72,3.01-6.73,6.73-6.73s6.73,3.01,6.73,6.73v35.63h-0.02
          c0,1.74-0.67,3.47-2,4.78c-1.41,1.39-3.29,2.03-5.13,1.91H82.4c-3.72,0-6.73-3.01-6.73-6.73c0-3.72,3.01-6.73,6.73-6.73h17.63
          L66.7,20.84L43.67,43.07c-2.6,2.5-6.73,2.51-9.33-0.03L2.03,11.52z" fill="white" fill-opacity="0.3" />
                        </svg>
                    </div>
                    <p class="text-sm relative z-10">Pengeluaran Hari Ini</p>
                    <p class="text-lg font-bold relative z-10">Rp.
                    </p>
                </div>
                <!-- card jumlah transaksi -->
                <div
                    class="bg-gradient-to-r from-orange-400 to-orange-600 text-white p-4 rounded-lg w-full text-center shadow-md">
                    <p class="text-sm">Jumlah Pesanan</p>
                    <p class="text-lg font-bold"></p>
                </div>
                <div class="bg-gradient-to-r  text-white p-4 rounded-lg w-full text-center shadow-md">
                    <p class="text-sm">Keuntungan Hari Ini</p>
                    <p class="text-lg font-bold">Rp. </p>
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

                                <tr class="text-center">
                                    <td class="px-4 py-2 border"></td>
                                    <td class="px-4 py-2 border"></td>
                                    <td class="px-4 py-2 border"></td>
                                    <td class="px-4 py-2 border">Rp
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="4" class="text-center px-4 py-4">Belum ada
                                        pesanan hari ini.</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Section  grafik-->
                <div class="bg-white shadow-md rounded-lg p-6 w-full lg:w-1/2 relative">
                    <h2 class="text-lg font-semibold text-black mb-4"></h2>
                    <div class="overflow-auto">
                        <canvas id="combinedChart" class="w-full h-full"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Laporan Keuangan Hari Ini -->
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
                            {{-- @forelse ($dailyReports as $index => $report)
                                <tr class="text-center">
                                    <td class="px-4 py-2 border">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2 border">{{ $report->user->name ?? '-' }}</td>
                                    <td class="px-4 py-2 border">{{ $report->sumber ?? '-' }}</td>
                                    <td class="px-4 py-2 border">
                                        {{ $report->type == 'income' ? 'Rp. ' . number_format($report->amount, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="px-4 py-2 border">
                                        {{ $report->type == 'expense' ? 'Rp. ' . number_format($report->amount, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="px-4 py-2 border">
                                        {{ \Carbon\Carbon::parse($report->tanggal)->format('d-m-Y') }}</td>
                                    <td class="px-4 py-2 border">{{ $report->keterangan ?? '-' }}</td>
                                </tr>
                            @empty --}}
                            {{-- <tr>
                                <td colspan="7" class="text-center px-4 py-4">Belum ada transaksi hari
                                    ini.</td>
                            </tr>
                            @endforelse --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
