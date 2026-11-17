@section('title', 'Laporan Keuangan')
@php
    use Carbon\Carbon;
    $awal = request('tanggal_awal');
    $akhir = request('tanggal_akhir');
    $hariIni = Carbon::today()->toDateString();
    $awalBulanIni = Carbon::today()->startOfMonth()->toDateString();
    $awalTahunIni = Carbon::today()->startOfYear()->toDateString();
    $tujuhHariLalu = Carbon::today()->subDays(6)->toDateString();

    // Ringkasan untuk data yang sedang tampil di halaman
    $itemsCol = collect($laporan->items());
    $totalPemasukanHal = $itemsCol->where('jenis', 'pemasukan')->sum('jumlah');
    $totalPengeluaranHal = $itemsCol->where('jenis', 'pengeluaran')->sum('jumlah');
    $saldoHal = $totalPemasukanHal - $totalPengeluaranHal;
@endphp
<x-app-layout>
    <div class="h-screen w-full bg-slate-100/70 flex overflow-hidden">
        <!-- sidebar -->
        <div class="drawer lg:drawer-open">
            <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />

            <!-- drawer content -->
            <div class="drawer-content flex flex-col h-screen">
                <!-- navbar -->
                @include('layouts.navbar')

                <!-- main content -->
                <div class="flex-1 bg-[#D1DDD5] overflow-auto px-4 sm:px-8 pt-6 pb-12 space-y-6">

                    {{-- Hero Header & Quick Actions --}}
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2.5">
                                <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                                     Laporan Keuangan
                                </h1>
                            </div>
                            <p class="text-xs sm:text-sm text-gray-500 mt-1">
                                Rekapitulasi seluruh arus kas pemasukan dan pengeluaran kas usaha
                            </p>
                        </div>

                        {{-- Tombol Export PDF --}}
                        <div class="flex items-center gap-2">
                            <a href="{{ route('laporan.export-pdf', request()->query()) }}"
                                class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#2B7A78] hover:bg-[#205C5D] text-white text-xs sm:text-sm font-semibold rounded-xl shadow-sm hover:shadow transition">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span>Export PDF</span>
                            </a>
                        </div>
                    </div>

                    {{-- Card Filter Periode Transaksi --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-5 sm:p-6">
                        <form method="GET" action="{{ url()->current() }}">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 pb-4 border-b border-gray-100 mb-4">
                                <div>
                                    <h3 class="text-sm sm:text-base font-bold text-gray-800 flex items-center gap-2">
                                         Filter Periode Transaksi
                                    </h3>
                                </div>

                                {{-- Preset Filter Cepat & Dropdown Bulan/Tahun/Sort --}}
                                <div class="flex flex-wrap items-center gap-1.5">
                                    <span class="text-xs font-semibold text-gray-500 mr-1">Periode:</span>
                                    <a href="{{ route('laporan-keuangan') }}"
                                        class="px-2.5 py-1 text-xs font-medium rounded-lg transition {{ (!request('tanggal_awal') && !request('tanggal_akhir') && !request('bulan') && !request('tahun')) ? 'bg-[#2B7A78] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                        Semua
                                    </a>
                                    <a href="{{ route('laporan-keuangan', array_merge(request()->except(['page', 'tanggal_awal', 'tanggal_akhir', 'bulan', 'tahun']), ['tanggal_awal' => $hariIni, 'tanggal_akhir' => $hariIni])) }}"
                                        class="px-2.5 py-1 text-xs font-medium rounded-lg transition {{ ($awal === $hariIni && $akhir === $hariIni) ? 'bg-[#2B7A78] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                        Hari Ini
                                    </a>
                                    <a href="{{ route('laporan-keuangan', array_merge(request()->except(['page', 'tanggal_awal', 'tanggal_akhir', 'bulan', 'tahun']), ['tanggal_awal' => $tujuhHariLalu, 'tanggal_akhir' => $hariIni])) }}"
                                        class="px-2.5 py-1 text-xs font-medium rounded-lg transition {{ ($awal === $tujuhHariLalu && $akhir === $hariIni) ? 'bg-[#2B7A78] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                        7 Hari Terakhir
                                    </a>
                                    <a href="{{ route('laporan-keuangan', array_merge(request()->except(['page', 'tanggal_awal', 'tanggal_akhir', 'bulan', 'tahun']), ['tanggal_awal' => $awalBulanIni, 'tanggal_akhir' => $hariIni])) }}"
                                        class="px-2.5 py-1 text-xs font-medium rounded-lg transition {{ ($awal === $awalBulanIni && $akhir === $hariIni) ? 'bg-[#2B7A78] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                        Bulan Ini
                                    </a>
                                    <a href="{{ route('laporan-keuangan', array_merge(request()->except(['page', 'tanggal_awal', 'tanggal_akhir', 'bulan', 'tahun']), ['tanggal_awal' => $awalTahunIni, 'tanggal_akhir' => $hariIni])) }}"
                                        class="px-2.5 py-1 text-xs font-medium rounded-lg transition {{ ($awal === $awalTahunIni && $akhir === $hariIni) ? 'bg-[#2B7A78] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                        Tahun Ini
                                    </a>

                                    <div class="h-4 w-px bg-gray-200 mx-1 hidden sm:block"></div>

                                    {{-- Dropdown Bulan, Tahun, dan Sort --}}
                                    <div class="flex items-center gap-1.5">
                                        <select name="bulan" onchange="clearCustomDates(this.form); this.form.submit()"
                                            class="px-2.5 py-1 text-xs font-medium rounded-lg border border-gray-300 bg-white text-gray-700 hover:border-[#2B7A78] focus:outline-none focus:ring-1 focus:ring-[#2B7A78] transition cursor-pointer">
                                            <option value="">Semua Bulan</option>
                                            @foreach([
                                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                            ] as $mNum => $mName)
                                                <option value="{{ $mNum }}" @selected(request('bulan') == $mNum)>{{ $mName }}</option>
                                            @endforeach
                                        </select>

                                        @php
                                            $currentYear = (int) date('Y');
                                            $years = range($currentYear, $currentYear - 4);
                                        @endphp
                                        <select name="tahun" onchange="clearCustomDates(this.form); this.form.submit()"
                                            class="px-2.5 py-1 text-xs font-medium rounded-lg border border-gray-300 bg-white text-gray-700 hover:border-[#2B7A78] focus:outline-none focus:ring-1 focus:ring-[#2B7A78] transition cursor-pointer">
                                            <option value="">Semua Tahun</option>
                                            @foreach($years as $yr)
                                                <option value="{{ $yr }}" @selected(request('tahun') == $yr)>{{ $yr }}</option>
                                            @endforeach
                                        </select>

                                        <select name="sort" onchange="this.form.submit()"
                                            class="px-2.5 py-1 text-xs font-medium rounded-lg border border-gray-300 bg-white text-gray-700 hover:border-[#2B7A78] focus:outline-none focus:ring-1 focus:ring-[#2B7A78] transition cursor-pointer"
                                            title="Urutan Transaksi">
                                            <option value="desc" @selected(request('sort', 'desc') === 'desc')>Terbaru</option>
                                            <option value="asc" @selected(request('sort') === 'asc' || request('sort') === 'terlama')>Terlama</option>
                                        </select>
                                    </div>

                                    @if ($awal || $akhir || request('bulan') || request('tahun') || (request('sort') && request('sort') !== 'desc'))
                                        <a href="{{ route('laporan-keuangan') }}"
                                            class="px-2.5 py-1 text-xs font-medium rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition">
                                            Reset
                                        </a>
                                    @endif
                                </div>
                            </div>

                            {{-- Form Filter Tanggal Custom --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-4 items-end">
                                <div class="lg:col-span-5">
                                    <label for="tanggal_awal" class="block text-xs font-semibold text-gray-700 mb-1.5">
                                        Mulai Tanggal
                                    </label>
                                    <div class="relative">
                                        <input type="date" name="tanggal_awal" id="tanggal_awal"
                                            class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#2B7A78] focus:border-[#2B7A78] transition text-gray-800 bg-white cursor-pointer"
                                            style="color-scheme: light;"
                                            onchange="clearMonthYear(this.form)"
                                            max="{{ date('Y-m-d') }}"
                                            value="{{ request('tanggal_awal') }}">
                                    </div>
                                </div>

                                <div class="lg:col-span-5">
                                    <label for="tanggal_akhir" class="block text-xs font-semibold text-gray-700 mb-1.5">
                                        Sampai Tanggal
                                    </label>
                                    <div class="relative">
                                        <input type="date" name="tanggal_akhir" id="tanggal_akhir"
                                            class="w-full px-3.5 py-2.5 text-sm border border-slate-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#2B7A78] focus:border-[#2B7A78] transition text-gray-800 bg-white cursor-pointer"
                                            style="color-scheme: light;"
                                            onchange="clearMonthYear(this.form)"
                                            max="{{ date('Y-m-d') }}"
                                            value="{{ request('tanggal_akhir') }}">
                                    </div>
                                </div>

                                <div class="lg:col-span-2 flex items-center gap-2">
                                    <button type="submit"
                                        class="w-full inline-flex justify-center items-center gap-1.5 px-4 py-2.5 bg-[#2B7A78] hover:bg-[#205C5D] text-white text-xs sm:text-sm font-semibold rounded-xl shadow-sm hover:shadow transition">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                        </svg>
                                        <span>Terapkan</span>
                                    </button>

                                    @if(request('tanggal_awal') || request('tanggal_akhir') || request('bulan') || request('tahun') || (request('sort') && request('sort') !== 'desc'))
                                        <a href="{{ route('laporan-keuangan') }}"
                                            title="Reset Filter"
                                            class="inline-flex justify-center items-center px-3 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs sm:text-sm font-semibold rounded-xl transition">
                                            ✕
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- Tabel Rincian Laporan Keuangan --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
                        {{-- Header Tabel & Status Periode --}}
                        <div class="p-5 sm:p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-3">
                            <div class="flex flex-wrap items-center gap-2.5">
                                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Periode:
                                </span>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                    @php
                                        $namaBulan = [
                                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                        ];
                                    @endphp
                                    @if (request('bulan') && request('tahun'))
                                        {{ $namaBulan[(int)request('bulan')] ?? request('bulan') }} {{ request('tahun') }}
                                    @elseif (request('bulan'))
                                        Bulan {{ $namaBulan[(int)request('bulan')] ?? request('bulan') }}
                                    @elseif (request('tahun'))
                                        Tahun {{ request('tahun') }}
                                    @elseif ($awal === $hariIni && $akhir === $hariIni)
                                        Hari Ini ({{ Carbon::parse($awal)->translatedFormat('d F Y') }})
                                    @elseif ($awal && $akhir)
                                        {{ Carbon::parse($awal)->translatedFormat('d M Y') }} s/d {{ Carbon::parse($akhir)->translatedFormat('d M Y') }}
                                    @elseif ($awal)
                                        Dari {{ Carbon::parse($awal)->translatedFormat('d M Y') }}
                                    @elseif ($akhir)
                                        Sampai {{ Carbon::parse($akhir)->translatedFormat('d M Y') }}
                                    @else
                                        Semua Data
                                    @endif
                                </span>
                            </div>

                            <div class="text-xs text-gray-500">
                                Menampilkan <span class="font-semibold text-gray-800">{{ $laporan->firstItem() ?? 0 }}</span> - <span class="font-semibold text-gray-800">{{ $laporan->lastItem() ?? 0 }}</span> dari <span class="font-semibold text-gray-800">{{ $laporan->total() }}</span> transaksi
                            </div>
                        </div>
                        <div class="card-body">
                            {{-- Tabel Transaksi --}}
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left text-sm">
                                        <thead>
                                            <tr class="text-xs uppercase tracking-wider text-gray-500 bg-slate-50/80 border-b border-slate-100">
                                                <th class="py-3.5 px-4 font-semibold w-14 text-center">No</th>
                                                <th class="py-3.5 px-4 font-semibold">Nama Transaksi</th>
                                                <th class="py-3.5 px-4 font-semibold">Sumber</th>
                                                <th class="py-3.5 px-4 font-semibold text-right">Pemasukan</th>
                                                <th class="py-3.5 px-4 font-semibold text-right">Pengeluaran</th>
                                                <th class="py-3.5 px-4 font-semibold text-center">Tanggal</th>
                                                <th class="py-3.5 px-4 font-semibold">Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100 text-gray-800">
                                            @forelse ($laporan as $index => $item)
                                                <tr class="hover:bg-slate-50/70 transition">
                                                    {{-- No --}}
                                                    <td class="py-3.5 px-4 text-center text-xs font-medium text-gray-400">
                                                        {{ $laporan->firstItem() + $index }}
                                                    </td>

                                                    {{-- Nama Transaksi & Jenis Badge --}}
                                                    <td class="py-3.5 px-4">
                                                        <div class="flex items-center gap-2.5">
                                                            @if ($item->jenis == 'pemasukan')
                                                                <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs shrink-0 font-bold">
                                                                    +
                                                                </span>
                                                                <div>
                                                                    <p class="font-semibold text-gray-900 leading-tight">
                                                                        {{ $item->nama ?: 'Pemasukan Kas' }}
                                                                    </p>
                                                                    <span class="text-[10px] text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded font-medium">
                                                                        Pemasukan
                                                                    </span>
                                                                </div>
                                                            @else
                                                                <span class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center text-xs shrink-0 font-bold">
                                                                    -
                                                                </span>
                                                                <div>
                                                                    <p class="font-semibold text-gray-900 leading-tight">
                                                                        {{ $item->nama ?: 'Pengeluaran Kas' }}
                                                                    </p>
                                                                    <span class="text-[10px] text-rose-700 bg-rose-50 px-1.5 py-0.5 rounded font-medium">
                                                                        Pengeluaran
                                                                    </span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </td>

                                                    {{-- Sumber --}}
                                                    <td class="py-3.5 px-4">
                                                        <div class="flex items-center gap-1.5">
                                                            <span class="font-medium text-gray-700">
                                                                {{ $item->source->name ?? '-' }}
                                                            </span>
                                                            @if (isset($item->source) && $item->source->is_online)
                                                                <span class="px-1.5 py-0.5 text-[10px] font-semibold rounded bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                                    Online
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </td>

                                                    {{-- Kolom Pemasukan --}}
                                                    <td class="py-3.5 px-4 text-right">
                                                        @if ($item->jenis == 'pemasukan')
                                                            <span class="font-bold text-emerald-700">
                                                                +Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                                                            </span>
                                                        @else
                                                            <span class="text-gray-300">-</span>
                                                        @endif
                                                    </td>

                                                    {{-- Kolom Pengeluaran --}}
                                                    <td class="py-3.5 px-4 text-right">
                                                        @if ($item->jenis == 'pengeluaran')
                                                            <span class="font-bold text-rose-600">
                                                                -Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                                                            </span>
                                                        @else
                                                            <span class="text-gray-300">-</span>
                                                        @endif
                                                    </td>

                                                    {{-- Tanggal --}}
                                                    <td class="py-3.5 px-4 text-center text-xs text-gray-500 whitespace-nowrap">
                                                        {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                                                    </td>

                                                    {{-- Keterangan --}}
                                                    <td class="py-3.5 px-4 text-xs text-gray-500 max-w-xs truncate">
                                                        {{ $item->keterangan ?: '-' }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="py-12 text-center">
                                                        <div class="flex flex-col items-center justify-center">
                                                            <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center text-2xl mb-3 text-gray-400">
                                                                📄
                                                            </div>
                                                            <p class="text-base font-semibold text-gray-700">Tidak ada catatan transaksi</p>
                                                            <p class="text-xs text-gray-400 mt-1">
                                                                Tidak ditemukan transaksi pemasukan atau pengeluaran pada rentang tanggal yang dipilih.
                                                            </p>
                                                            @if(request('tanggal_awal') || request('tanggal_akhir'))
                                                                <a href="{{ url()->current() }}"
                                                                    class="mt-3 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                                                                    Reset Filter
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                {{-- Pagination Bar --}}
                                @if ($laporan->hasPages())
                                    <div class="p-4 sm:p-5 border-t border-gray-100 bg-slate-50/50">
                                        {{ $laporan->appends(request()->except('page'))->links() }}
                                    </div>
                                @endif
                        </div>
                    </div>

                </div>
            </div>

            <!-- sidebar content -->
            @include('layouts.sidebar')
        </div>
    </div>

    <script>
        function dropdownLaporan() {
            const dropdownLaporanMenu = document.getElementById('dropdownLaporanMenu');
            dropdownLaporanMenu?.classList.toggle('hidden');
        }
        document.addEventListener("click", function(event) {
            const dropdownLaporanMenu = document.getElementById("dropdownLaporanMenu");
            const dropdownLaporanButton = document.getElementById("dropdownLaporanButton");

            if (
                dropdownLaporanMenu && dropdownLaporanButton &&
                !dropdownLaporanMenu.contains(event.target) &&
                !dropdownLaporanButton.contains(event.target)
            ) {
                dropdownLaporanMenu.classList.add("hidden");
            }
        });

        function dropdownKategori() {
            const dropdownKategoriMenu = document.getElementById('dropdownKategoriMenu');
            dropdownKategoriMenu?.classList.toggle('hidden');
        }
        document.addEventListener("click", function(event) {
            const dropdownKategoriMenu = document.getElementById("dropdownKategoriMenu");
            const dropdownKategoriButton = document.getElementById("dropdownKategoriButton");

            if (
                dropdownKategoriMenu && dropdownKategoriButton &&
                !dropdownKategoriMenu.contains(event.target) &&
                !dropdownKategoriButton.contains(event.target)
            ) {
                dropdownKategoriMenu.classList.add("hidden");
            }
        });
    </script>
    <script>
        function clearCustomDates(form) {
            if (form.tanggal_awal) form.tanggal_awal.value = '';
            if (form.tanggal_akhir) form.tanggal_akhir.value = '';
        }
        function clearMonthYear(form) {
            if (form.bulan) form.bulan.value = '';
            if (form.tahun) form.tahun.value = '';
        }
    </script>
</x-app-layout>
