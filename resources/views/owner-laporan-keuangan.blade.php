@section('title', 'Laporan Untung Rugi')
@php
    use Carbon\Carbon;
    $awal = request('tanggal_awal');
    $akhir = request('tanggal_akhir');
    $currentType = request('type');
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
<x-panel plain>
    <div class="min-h-full bg-[#D1DDD5] px-4 sm:px-8 pt-6 pb-12 space-y-6">

        {{-- 1. Hero Header & Quick Actions --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2.5">
                    <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                        Laporan Untung Rugi
                    </h1>
                </div>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">
                    Rekapitulasi komprehensif arus pemasukan, beban pengeluaran, dan performa finansial Belindo Kitchen
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

        {{-- 2. Card Filter Periode Transaksi & Tipe --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 p-5 sm:p-6">
            <form method="GET" action="{{ url()->current() }}">
                @if ($currentType)
                    <input type="hidden" name="type" value="{{ $currentType }}">
                @endif
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 pb-4 border-b border-gray-100 mb-4">
                    <div>
                        <h3 class="text-sm sm:text-base font-bold text-gray-800 flex items-center gap-2">
                            Filter Periode &amp; Jenis Transaksi
                        </h3>
                    </div>

                    {{-- Preset Filter Cepat & Dropdown Bulan/Tahun/Sort --}}
                    <div class="flex flex-wrap items-center gap-1.5">
                        <span class="text-xs font-semibold text-gray-500 mr-1">Periode:</span>
                        <a href="{{ route('laporan-owner', request()->only('type')) }}"
                            class="px-2.5 py-1 text-xs font-medium rounded-lg transition {{ (!request('tanggal_awal') && !request('tanggal_akhir') && !request('bulan') && !request('tahun')) ? 'bg-[#2B7A78] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                            Semua
                        </a>
                        <a href="{{ route('laporan-owner', array_merge(request()->except(['page', 'tanggal_awal', 'tanggal_akhir', 'bulan', 'tahun']), ['tanggal_awal' => $hariIni, 'tanggal_akhir' => $hariIni])) }}"
                            class="px-2.5 py-1 text-xs font-medium rounded-lg transition {{ ($awal === $hariIni && $akhir === $hariIni) ? 'bg-[#2B7A78] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                            Hari Ini
                        </a>
                        <a href="{{ route('laporan-owner', array_merge(request()->except(['page', 'tanggal_awal', 'tanggal_akhir', 'bulan', 'tahun']), ['tanggal_awal' => $tujuhHariLalu, 'tanggal_akhir' => $hariIni])) }}"
                            class="px-2.5 py-1 text-xs font-medium rounded-lg transition {{ ($awal === $tujuhHariLalu && $akhir === $hariIni) ? 'bg-[#2B7A78] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                            7 Hari Terakhir
                        </a>
                        <a href="{{ route('laporan-owner', array_merge(request()->except(['page', 'tanggal_awal', 'tanggal_akhir', 'bulan', 'tahun']), ['tanggal_awal' => $awalBulanIni, 'tanggal_akhir' => $hariIni])) }}"
                            class="px-2.5 py-1 text-xs font-medium rounded-lg transition {{ ($awal === $awalBulanIni && $akhir === $hariIni) ? 'bg-[#2B7A78] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                            Bulan Ini
                        </a>
                        <a href="{{ route('laporan-owner', array_merge(request()->except(['page', 'tanggal_awal', 'tanggal_akhir', 'bulan', 'tahun']), ['tanggal_awal' => $awalTahunIni, 'tanggal_akhir' => $hariIni])) }}"
                            class="px-2.5 py-1 text-xs font-medium rounded-lg transition {{ ($awal === $awalTahunIni && $akhir === $hariIni) ? 'bg-[#2B7A78] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                            Tahun Ini
                        </a>
                        @if ($awal || $akhir || request('bulan') || request('tahun') || (request('sort') && request('sort') !== 'desc') || $currentType)
                            <a href="{{ route('laporan-owner') }}"
                                class="px-2.5 py-1 text-xs font-medium rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition">
                                Reset Semua
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Filter Tabs Jenis Transaksi (Semua, Pemasukan, Pengeluaran) --}}
                <div class="flex items-center gap-2 mb-4 bg-slate-100 p-1 rounded-xl w-fit">
                    <a href="{{ route('laporan-owner', array_merge(request()->except('type'), ['page' => 1])) }}"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ empty($currentType) ? 'bg-white text-[#2B7A78] shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                        Semua Transaksi
                    </a>
                    <a href="{{ route('laporan-owner', array_merge(request()->except('type'), ['type' => 'pemasukan', 'page' => 1])) }}"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $currentType === 'pemasukan' ? 'bg-white text-emerald-700 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                        Hanya Pemasukan
                    </a>
                    <a href="{{ route('laporan-owner', array_merge(request()->except('type'), ['type' => 'pengeluaran', 'page' => 1])) }}"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $currentType === 'pengeluaran' ? 'bg-white text-rose-600 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                        Hanya Pengeluaran
                    </a>
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

                        @if(request('tanggal_awal') || request('tanggal_akhir') || request('bulan') || request('tahun') || (request('sort') && request('sort') !== 'desc') || $currentType)
                            <a href="{{ route('laporan-owner', request()->only('type')) }}"
                                title="Reset Filter"
                                class="inline-flex justify-center items-center px-3 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs sm:text-sm font-semibold rounded-xl transition">
                                ✕
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        {{-- 3. Ringkasan Mini Finansial (Halaman Ini) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Total Pemasukan --}}
            <div class="bg-white rounded-2xl p-4 sm:p-5 shadow-sm border border-emerald-100/80 bg-gradient-to-br from-white via-white to-emerald-50/40">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-emerald-700">Pemasukan (Hal. Ini)</span>
                    <span class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-sm font-bold">
                        +
                    </span>
                </div>
                <p class="text-xl sm:text-2xl font-black text-emerald-700 mt-2">
                    Rp {{ number_format($totalPemasukanHal, 0, ',', '.') }}
                </p>
                <span class="text-[11px] text-gray-500 mt-1 block">Total omset kas di halaman ini</span>
            </div>

            {{-- Total Pengeluaran --}}
            <div class="bg-white rounded-2xl p-4 sm:p-5 shadow-sm border border-rose-100/80 bg-gradient-to-br from-white via-white to-rose-50/40">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-rose-700">Pengeluaran (Hal. Ini)</span>
                    <span class="w-8 h-8 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center text-sm font-bold">
                        -
                    </span>
                </div>
                <p class="text-xl sm:text-2xl font-black text-rose-600 mt-2">
                    Rp {{ number_format($totalPengeluaranHal, 0, ',', '.') }}
                </p>
                <span class="text-[11px] text-gray-500 mt-1 block">Total biaya operasional di halaman ini</span>
            </div>

            {{-- Laba / Rugi Bersih (Net) --}}
            <div class="bg-white rounded-2xl p-4 sm:p-5 shadow-sm border border-slate-200/80 bg-gradient-to-br from-white via-white to-slate-50/60">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-700">Laba Bersih (Hal. Ini)</span>
                    <span class="w-8 h-8 rounded-xl bg-slate-100 text-gray-700 flex items-center justify-center text-sm font-bold">
                        ⚖️
                    </span>
                </div>
                <p class="text-xl sm:text-2xl font-black {{ $saldoHal >= 0 ? 'text-[#2B7A78]' : 'text-rose-600' }} mt-2">
                    {{ $saldoHal >= 0 ? '+Rp ' : '-Rp ' }}{{ number_format(abs($saldoHal), 0, ',', '.') }}
                </p>
                <span class="text-[11px] text-gray-500 mt-1 block">Surplus kas setelah pengeluaran</span>
            </div>

            {{-- Total Catatan --}}
            <div class="bg-white rounded-2xl p-4 sm:p-5 shadow-sm border border-sky-100/80 bg-gradient-to-br from-white via-white to-sky-50/40">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-sky-700">Total Transaksi</span>
                    <span class="w-8 h-8 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center text-sm font-bold">
                        📋
                    </span>
                </div>
                <p class="text-xl sm:text-2xl font-black text-gray-800 mt-2">
                    {{ number_format($laporan->total(), 0, ',', '.') }} <span class="text-sm font-medium text-gray-500">data</span>
                </p>
                <span class="text-[11px] text-gray-500 mt-1 block">Halaman {{ $laporan->currentPage() }} dari {{ $laporan->lastPage() }}</span>
            </div>
        </div>

        {{-- 4. Tabel Rincian Transaksi --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
            {{-- Header Tabel & Status Filter --}}
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

                    @if ($currentType)
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $currentType === 'pemasukan' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                            {{ ucfirst($currentType) }} Saja
                        </span>
                    @endif
                </div>

                <div class="text-xs text-gray-500">
                    Menampilkan <span class="font-semibold text-gray-800">{{ $laporan->firstItem() ?? 0 }}</span> - <span class="font-semibold text-gray-800">{{ $laporan->lastItem() ?? 0 }}</span> dari <span class="font-semibold text-gray-800">{{ $laporan->total() }}</span> transaksi
                </div>
            </div>

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

                                {{-- Sumber / Saluran --}}
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
                                            Tidak ditemukan transaksi yang cocok pada filter periode atau jenis yang dipilih.
                                        </p>
                                        @if(request('tanggal_awal') || request('tanggal_akhir') || request('type'))
                                            <a href="{{ route('laporan-owner') }}"
                                                class="mt-3 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                                                Reset Semua Filter
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
</x-panel>
</x-app-layout>
