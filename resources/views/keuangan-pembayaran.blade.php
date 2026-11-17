@section('title', 'Pemasukan | Pembayaran')
@php
    use Carbon\Carbon;
    $hariIniStr = Carbon::today()->toDateString();
    $awalBulanIniStr = Carbon::today()->startOfMonth()->toDateString();
    $awalTahunIniStr = Carbon::today()->startOfYear()->toDateString();
    $tujuhHariLaluStr = Carbon::today()->subDays(6)->toDateString();
@endphp
<x-app-layout>
    <x-panel plain>
        <div class="min-h-full bg-[#D1DDD5] px-4 sm:px-8 pt-6 pb-12 space-y-6">

            {{-- Header & Tombol Tambah --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-xl font-bold text-[#2B7A78]">Halaman Pembayaran</h1>
                    <p class="text-xs sm:text-sm text-gray-600 mt-0.5">
                        Daftar seluruh pemasukan dari transaksi penjualan kasir
                    </p>
                </div>
                <div>
                    <button class="inline-flex items-center gap-1.5 bg-[#2B7A78] text-white font-semibold py-2 px-4 rounded-lg hover:bg-[#205C5D] shadow-sm transition text-sm"
                        onclick="window.location.href='{{ url('/keuangan/pembayaran/create') }}'">
                        + <span>Tambah</span>
                    </button>
                </div>
            </div>

            {{-- Container Filter & Search --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5">
                <form method="GET" action="{{ route('pembayaran.index') }}" class="space-y-4">
                    {{-- Row 1: Preset Filter Cepat & Search Box --}}
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 pb-3 border-b border-gray-100">
                        <div class="flex flex-wrap items-center gap-1.5">
                            <span class="text-xs font-semibold text-gray-500 mr-1">Periode:</span>
                            <a href="{{ route('pembayaran.index') }}"
                                class="px-3 py-1.5 text-xs font-medium rounded-lg transition {{ (!request('tanggal_awal') && !request('tanggal_akhir') && !request('bulan') && !request('tahun')) ? 'bg-[#2B7A78] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                Semua
                            </a>
                            <a href="{{ route('pembayaran.index', array_merge(request()->except(['page', 'tanggal_awal', 'tanggal_akhir', 'bulan', 'tahun']), ['tanggal_awal' => $hariIniStr, 'tanggal_akhir' => $hariIniStr])) }}"
                                class="px-3 py-1.5 text-xs font-medium rounded-lg transition {{ (request('tanggal_awal') === $hariIniStr && request('tanggal_akhir') === $hariIniStr) ? 'bg-[#2B7A78] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                Hari Ini
                            </a>
                            <a href="{{ route('pembayaran.index', array_merge(request()->except(['page', 'tanggal_awal', 'tanggal_akhir', 'bulan', 'tahun']), ['tanggal_awal' => $tujuhHariLaluStr, 'tanggal_akhir' => $hariIniStr])) }}"
                                class="px-3 py-1.5 text-xs font-medium rounded-lg transition {{ (request('tanggal_awal') === $tujuhHariLaluStr && request('tanggal_akhir') === $hariIniStr) ? 'bg-[#2B7A78] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                7 Hari Terakhir
                            </a>
                            <a href="{{ route('pembayaran.index', array_merge(request()->except(['page', 'tanggal_awal', 'tanggal_akhir', 'bulan', 'tahun']), ['tanggal_awal' => $awalBulanIniStr, 'tanggal_akhir' => $hariIniStr])) }}"
                                class="px-3 py-1.5 text-xs font-medium rounded-lg transition {{ (request('tanggal_awal') === $awalBulanIniStr && request('tanggal_akhir') === $hariIniStr) ? 'bg-[#2B7A78] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                Bulan Ini
                            </a>
                            <a href="{{ route('pembayaran.index', array_merge(request()->except(['page', 'tanggal_awal', 'tanggal_akhir', 'bulan', 'tahun']), ['tanggal_awal' => $awalTahunIniStr, 'tanggal_akhir' => $hariIniStr])) }}"
                                class="px-3 py-1.5 text-xs font-medium rounded-lg transition {{ (request('tanggal_awal') === $awalTahunIniStr && request('tanggal_akhir') === $hariIniStr) ? 'bg-[#2B7A78] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                Tahun Ini
                            </a>

                            <div class="h-4 w-px bg-gray-200 mx-1 hidden sm:block"></div>

                            {{-- Dropdown Bulan, Tahun, dan Sort --}}
                            <div class="flex items-center gap-1.5">
                                <select name="bulan" onchange="clearCustomDates(this.form); this.form.submit()"
                                    class="px-2.5 py-1.5 text-xs font-medium rounded-lg border border-gray-300 bg-white text-gray-700 hover:border-[#2B7A78] focus:outline-none focus:ring-1 focus:ring-[#2B7A78] transition cursor-pointer">
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
                                    class="px-2.5 py-1.5 text-xs font-medium rounded-lg border border-gray-300 bg-white text-gray-700 hover:border-[#2B7A78] focus:outline-none focus:ring-1 focus:ring-[#2B7A78] transition cursor-pointer">
                                    <option value="">Semua Tahun</option>
                                    @foreach($years as $yr)
                                        <option value="{{ $yr }}" @selected(request('tahun') == $yr)>{{ $yr }}</option>
                                    @endforeach
                                </select>

                                <select name="sort" onchange="this.form.submit()"
                                    class="px-2.5 py-1.5 text-xs font-medium rounded-lg border border-gray-300 bg-white text-gray-700 hover:border-[#2B7A78] focus:outline-none focus:ring-1 focus:ring-[#2B7A78] transition cursor-pointer"
                                    title="Urutan Transaksi">
                                    <option value="desc" @selected(request('sort', 'desc') === 'desc')>Terbaru</option>
                                    <option value="asc" @selected(request('sort') === 'asc' || request('sort') === 'terlama')>Terlama</option>
                                </select>
                            </div>
                        </div>

                        {{-- Search Box --}}
                        <div class="relative w-full md:w-80">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari nama, sumber..."
                                class="w-full pl-10 pr-9 py-2 text-xs sm:text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2B7A78] focus:border-[#2B7A78] transition text-gray-800">
                            @if(request('search'))
                                <a href="{{ route('pembayaran.index', request()->except('search')) }}"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-rose-500" title="Hapus kata kunci">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- Sumber & Tanggal Filter --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-end">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Sumber</label>
                            <select name="source_id" class="w-full px-3 py-2 text-xs sm:text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2B7A78] bg-white text-gray-800">
                                <option value="">Semua Sumber</option>
                                @foreach (($sources ?? []) as $src)
                                    <option value="{{ $src->id }}" @selected(request('source_id') == $src->id)>
                                        {{ $src->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Mulai Tanggal</label>
                            <input type="date" name="tanggal_awal" value="{{ request('tanggal_awal') }}" max="{{ date('Y-m-d') }}" onchange="clearMonthYear(this.form)" style="color-scheme: light;"
                                class="w-full px-3 py-2 text-xs sm:text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2B7A78] bg-white text-gray-800 cursor-pointer">
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex-1">
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Sampai Tanggal</label>
                                <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}" max="{{ date('Y-m-d') }}" onchange="clearMonthYear(this.form)" style="color-scheme: light;"
                                    class="w-full px-3 py-2 text-xs sm:text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2B7A78] bg-white text-gray-800 cursor-pointer">
                            </div>
                            <button type="submit"
                                class="mt-5 px-4 py-2 bg-[#2B7A78] hover:bg-[#205C5D] text-white text-xs sm:text-sm font-semibold rounded-xl shadow-sm transition shrink-0">
                                Filter
                            </button>
                            @if(request()->hasAny(['search', 'source_id', 'tanggal_awal', 'tanggal_akhir', 'bulan', 'tahun', 'sort']))
                                <a href="{{ route('pembayaran.index') }}"
                                    class="mt-5 px-3 py-2 bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-semibold rounded-xl transition shrink-0">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            {{-- Tabel Pembayaran --}}
            <div id="tableContainer" class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
               <div class="card-body">  
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="text-xs uppercase tracking-wider text-gray-500 bg-slate-50/80 border-b border-slate-100">
                                    <th class="py-3.5 px-4 text-center">No</th>
                                    <th class="py-3.5 px-4">Jenis Pesanan</th>
                                    <th class="py-3.5 px-4">Sumber</th>
                                    <th class="py-3.5 px-4 text-right">Omset Kotor</th>
                                    <th class="py-3.5 px-4 text-right">Net Payout</th>
                                    <th class="py-3.5 px-4 text-center">Tanggal</th>
                                    <th class="py-3.5 px-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($pembayarans as $pembayaran)
                                    <tr class="hover:bg-slate-50/70 transition">
                                        <td class="py-3.5 px-4 text-center text-gray-400 font-medium text-xs">
                                            {{ ($pembayarans->currentPage() - 1) * $pembayarans->perPage() + $loop->iteration }}
                                        </td>
                                        <td class="py-3.5 px-4 font-medium text-gray-900">
                                            <span>{{ $pembayaran->name }}</span>
                                            @if ($pembayaran->platformPromo)
                                                <span class="text-[11px] bg-pink-100 text-pink-700 font-semibold px-2 py-0.5 rounded block mt-1 w-fit border border-pink-200">
                                                    🎟️ Voucher: {{ $pembayaran->platformPromo->name }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-4 text-gray-800">
                                            <div class="flex items-center gap-2">
                                                <span class="font-semibold">{{ $pembayaran->source->name ?? 'Tidak Diketahui' }}</span>
                                                @if ($pembayaran->source && $pembayaran->source->is_online)
                                                    <span class="ml-1 px-1.5 py-0.5 text-[10px] font-semibold rounded bg-blue-50 text-blue-700 border border-blue-200">Online</span>
                                                @else
                                                    <span class="px-2 py-0.5 text-[10px] font-semibold rounded bg-gray-100 text-gray-700 border border-gray-300">Offline</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-3.5 px-4 text-right text-gray-600">
                                            Rp {{ number_format($pembayaran->gross_amount ?: $pembayaran->amount, 0, ',', '.') }}
                                        </td>
                                        <td class="py-3.5 px-4 text-right font-bold text-[#2B7A78]">
                                            Rp {{ number_format($pembayaran->amount, 0, ',', '.') }}
                                        </td>
                                        <td class="py-3.5 px-4 text-center text-gray-700 text-sm whitespace-nowrap">
                                            {{ $pembayaran->date ? $pembayaran->date->format('d-m-Y') : '-' }}
                                        </td>
                                        <td class="py-3.5 px-4 text-center">
                                            <div class="flex items-center justify-center gap-1.5">
                                                <a href="{{ url('/keuangan/pembayaran/' . $pembayaran->id) }}"
                                                    class="bg-[#468585] text-white px-3 py-1.5 rounded-md text-xs font-medium hover:bg-[#2B7A78] transition shadow-sm">
                                                    Detail
                                                </a>
                                                <form action="{{ url('/keuangan/pembayaran/' . $pembayaran->id) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="bg-rose-500 hover:bg-rose-600 text-white px-3 py-1.5 rounded-md text-xs font-medium transition shadow-sm"
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus pembayaran ini?')">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-12 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <p class="text-base font-semibold text-gray-700">Belum ada data pembayaran</p>
                                                <p class="text-xs text-gray-400 mt-1 max-w-sm">
                                                    Tidak ditemukan data pembayaran yang sesuai dengan filter yang dipilih.
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if ($pembayarans->hasPages())
                        <div class="p-4 border-t border-gray-100 bg-slate-50/50">
                            {{ $pembayarans->links() }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </x-panel>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: @json(session('success')),
                showConfirmButton: false,
                timer: 2000
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: @json(session('error')),
                confirmButtonText: 'OK'
            });
        @endif

        function clearCustomDates(form) {
            if (form.tanggal_awal) form.tanggal_awal.value = '';
            if (form.tanggal_akhir) form.tanggal_akhir.value = '';
        }
        function clearMonthYear(form) {
            if (form.bulan) form.bulan.value = '';
            if (form.tahun) form.tahun.value = '';
        }

        function initAjaxPagination() {
            const container = document.getElementById('tableContainer');
            if (!container) return;

            container.addEventListener('click', function(e) {
                const link = e.target.closest('a');
                if (link && link.href && (link.closest('nav') || link.closest('.pagination') || link.href.includes('page='))) {
                    e.preventDefault();
                    loadTablePage(link.href);
                }
            });

            window.addEventListener('popstate', function() {
                loadTablePage(window.location.href, false);
            });
        }

        function loadTablePage(url, pushState = true) {
            const container = document.getElementById('tableContainer');
            if (!container) return;

            container.style.opacity = '0.5';
            container.style.pointerEvents = 'none';

            fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newContainer = doc.getElementById('tableContainer');
                if (newContainer) {
                    container.innerHTML = newContainer.innerHTML;
                    if (pushState) {
                        window.history.pushState({}, '', url);
                    }
                } else {
                    window.location.href = url;
                }
            })
            .catch(() => {
                window.location.href = url;
            })
            .finally(() => {
                container.style.opacity = '1';
                container.style.pointerEvents = 'auto';
            });
        }

        document.addEventListener('DOMContentLoaded', initAjaxPagination);
    </script>
</x-app-layout>
