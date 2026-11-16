@section('title', 'Halaman Pengeluaran')
@php
    use Carbon\Carbon;
    $hariIniStr = Carbon::today()->toDateString();
    $awalBulanIniStr = Carbon::today()->startOfMonth()->toDateString();
    $tujuhHariLaluStr = Carbon::today()->subDays(6)->toDateString();
@endphp
<x-app-layout>
    <x-panel plain>
        <div class="min-h-full bg-[#D1DDD5] px-4 sm:px-8 pt-6 pb-12 space-y-6">

            {{-- Header & Tombol Tambah --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-xl font-bold text-[#2B7A78]">Halaman Pengeluaran</h1>
                    <p class="text-xs sm:text-sm text-gray-600 mt-0.5">
                        Daftar seluruh transaksi pengeluaran kas dan beban operasional usaha
                    </p>
                </div>

                <div>
                    <a href="{{ route('pengeluaran.create') }}"
                        class="inline-flex items-center gap-1.5 bg-[#2B7A78] text-white font-semibold py-2 px-4 rounded-lg hover:bg-[#205C5D] shadow-sm transition text-sm">
                        + <span>Tambah</span>
                    </a>
                </div>
            </div>

            {{-- Container Filter & Search --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5">
                <form method="GET" action="{{ route('expenses.search') }}" class="space-y-4">
                    {{-- Row 1: Preset Filter Cepat & Search Box --}}
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 pb-3 border-b border-gray-100">
                        <div class="flex flex-wrap items-center gap-1.5">
                            <span class="text-xs font-semibold text-gray-500 mr-1">Periode:</span>
                            <a href="{{ route('expenses.search') }}"
                                class="px-3 py-1.5 text-xs font-medium rounded-lg transition {{ (!request('tanggal_awal') && !request('tanggal_akhir')) ? 'bg-[#2B7A78] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                Semua
                            </a>
                            <a href="{{ route('expenses.search', array_merge(request()->except(['page', 'tanggal_awal', 'tanggal_akhir']), ['tanggal_awal' => $hariIniStr, 'tanggal_akhir' => $hariIniStr])) }}"
                                class="px-3 py-1.5 text-xs font-medium rounded-lg transition {{ (request('tanggal_awal') === $hariIniStr && request('tanggal_akhir') === $hariIniStr) ? 'bg-[#2B7A78] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                Hari Ini
                            </a>
                            <a href="{{ route('expenses.search', array_merge(request()->except(['page', 'tanggal_awal', 'tanggal_akhir']), ['tanggal_awal' => $tujuhHariLaluStr, 'tanggal_akhir' => $hariIniStr])) }}"
                                class="px-3 py-1.5 text-xs font-medium rounded-lg transition {{ (request('tanggal_awal') === $tujuhHariLaluStr && request('tanggal_akhir') === $hariIniStr) ? 'bg-[#2B7A78] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                7 Hari Terakhir
                            </a>
                            <a href="{{ route('expenses.search', array_merge(request()->except(['page', 'tanggal_awal', 'tanggal_akhir']), ['tanggal_awal' => $awalBulanIniStr, 'tanggal_akhir' => $hariIniStr])) }}"
                                class="px-3 py-1.5 text-xs font-medium rounded-lg transition {{ (request('tanggal_awal') === $awalBulanIniStr && request('tanggal_akhir') === $hariIniStr) ? 'bg-[#2B7A78] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                Bulan Ini
                            </a>
                        </div>

                        {{-- Search Box di dalam Container Filter --}}
                        <div class="relative w-full md:w-80">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari kategori, sumber, atau catatan..."
                                class="w-full pl-10 pr-9 py-2 text-xs sm:text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2B7A78] focus:border-[#2B7A78] transition text-gray-800">
                            @if(request('search'))
                                <a href="{{ route('expenses.search', request()->except('search')) }}"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-rose-500" title="Hapus kata kunci">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- Filter Dropdown & Tanggal --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 items-end">
                        {{-- Kategori Tagihan --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Kategori Tagihan</label>
                            <select name="bill_id" class="w-full px-3 py-2 text-xs sm:text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2B7A78] bg-white text-gray-800">
                                <option value="">Semua Kategori</option>
                                @foreach (($bills ?? []) as $bill)
                                    <option value="{{ $bill->id }}" @selected(request('bill_id') == $bill->id)>
                                        {{ $bill->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Sumber Dana --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Sumber</label>
                            <select name="source_id" class="w-full px-3 py-2 text-xs sm:text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2B7A78] bg-white text-gray-800">
                                <option value="">Semua Sumber Kas</option>
                                @foreach (($sources ?? []) as $src)
                                    <option value="{{ $src->id }}" @selected(request('source_id') == $src->id)>
                                        {{ $src->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tanggal Mulai --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Mulai Tanggal</label>
                            <input type="date" name="tanggal_awal" value="{{ request('tanggal_awal') }}" max="{{ date('Y-m-d') }}"
                                class="w-full px-3 py-2 text-xs sm:text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2B7A78] bg-white text-gray-800">
                        </div>

                        {{-- Tanggal Selesai & Action --}}
                        <div class="flex items-center gap-2">
                            <div class="flex-1">
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Sampai Tanggal</label>
                                <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}" max="{{ date('Y-m-d') }}"
                                    class="w-full px-3 py-2 text-xs sm:text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2B7A78] bg-white text-gray-800">
                            </div>
                            <button type="submit"
                                class="mt-5 px-4 py-2 bg-[#2B7A78] hover:bg-[#205C5D] text-white text-xs sm:text-sm font-semibold rounded-xl shadow-sm transition shrink-0">
                                Filter
                            </button>
                            @if(request()->hasAny(['search', 'bill_id', 'source_id', 'tanggal_awal', 'tanggal_akhir']))
                                <a href="{{ route('expenses.search') }}"
                                    class="mt-5 px-3 py-2 bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-semibold rounded-xl transition shrink-0" title="Reset filter">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            {{-- Tabel Transaksi Pengeluaran Modern --}}
            <div id="tableContainer" class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                <div class="card-body">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="text-xs uppercase tracking-wider text-gray-500 bg-slate-50/80 border-b border-slate-100">
                                    <th class="py-3.5 px-4 text-center">No</th>
                                    <th class="py-3.5 px-4">Kategori Tagihan</th>
                                    <th class="py-3.5 px-4">Sumber Kas</th>
                                    <th class="py-3.5 px-4 text-right">Nominal</th>
                                    <th class="py-3.5 px-4 text-center">Tanggal</th>
                                    <th class="py-3.5 px-4">Keterangan</th>
                                    <th class="py-3.5 px-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($expenses as $expense)
                                    <tr class="hover:bg-slate-50/70 transition">
                                        {{-- No Urut --}}
                                        <td class="py-3.5 px-4 text-center text-gray-400 font-medium text-xs">
                                            {{ ($expenses->currentPage() - 1) * $expenses->perPage() + $loop->iteration }}
                                        </td>

                                        {{-- Kategori Tagihan --}}
                                        <td class="py-3.5 px-4">
                                            <div class="flex items-center gap-2.5">
                                                <span class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-xs shrink-0 font-bold">
                                                    -
                                                </span>
                                                <div>
                                                    <p class="font-semibold text-gray-800 leading-tight">
                                                        {{ $expense->bill->name ?? 'Pengeluaran' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Sumber Kas --}}
                                        <td class="py-3.5 px-4 text-gray-800">
                                            <div class="flex items-center gap-1.5">
                                                <span class="font-semibold">
                                                    {{ $expense->source->name ?? '-' }}
                                                </span>
                                                @if ($expense->source && $expense->source->is_online)
                                                    <span class="px-1.5 py-0.5 text-[10px] font-semibold rounded bg-emerald-50 text-emerald-700 border border-emerald-200">Online</span>
                                                @endif
                                            </div>
                                        </td>

                                        {{-- Nominal --}}
                                        <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                            <span class="font-bold text-rose-600">
                                                -Rp {{ number_format($expense->amount, 0, ',', '.') }}
                                            </span>
                                        </td>

                                        {{-- Tanggal --}}
                                        <td class="py-3.5 px-4 text-center text-xs text-gray-500 whitespace-nowrap">
                                            {{ $expense->date ? $expense->date->translatedFormat('d M Y') : '-' }}
                                        </td>

                                        {{-- Keterangan --}}
                                        <td class="py-3.5 px-4 text-xs text-gray-600 max-w-xs truncate" title="{{ $expense->description }}">
                                            {{ $expense->description ?: '-' }}
                                        </td>

                                        {{-- Aksi --}}
                                        <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                            <a href="{{ route('expenses.show', $expense->id) }}"
                                                class="text-white px-3 py-1 rounded bg-[#2B7A78] hover:bg-[#205C5D] ml-2">Edit</a>
                                            <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')"
                                                    class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 ml-2">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-12 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <p class="text-base font-semibold text-gray-700">Tidak ada catatan pengeluaran</p>
                                                <p class="text-xs text-gray-400 mt-1 max-w-sm">
                                                    Tidak ditemukan data pengeluaran yang sesuai dengan filter atau kata kunci yang dipilih.
                                                </p>
                                                <a href="{{ route('pengeluaran.create') }}"
                                                    class="mt-4 inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-[#2B7A78] hover:bg-[#205C5D] text-white transition">
                                                    + Tambah Pengeluaran
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if ($expenses->hasPages())
                        <div class="p-4 border-t border-gray-100 bg-slate-50/50">
                            {{ $expenses->links() }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </x-panel>
</x-app-layout>

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
            confirmButtonText: 'OK',
            confirmButtonColor: '#2B7A78'
        });
    @endif

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
