@section('title', 'Halaman Uang masuk')
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
                    <h1 class="text-xl font-bold text-[#2B7A78]">Halaman Uang masuk</h1>
                    <p class="text-xs sm:text-sm text-gray-600 mt-0.5">
                        Pencatatan kas masuk non-penjualan (tambahan modal, transfer, dll)
                    </p>
                </div>

                <div>
                    <button type="button" onclick="openModal()"
                        class="inline-flex items-center gap-1.5 bg-[#2B7A78] text-white font-semibold py-2 px-4 rounded-lg hover:bg-[#205C5D] shadow-sm transition text-sm">
                        + <span>Tambah</span>
                    </button>
                </div>
            </div>

            {{-- Container Filter & Search --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5">
                <form method="GET" action="{{ route('uang-masuk.search') }}" class="space-y-4">
                    {{-- Row 1: Preset Filter Cepat & Search Box --}}
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 pb-3 border-b border-gray-100">
                        <div class="flex flex-wrap items-center gap-1.5">
                            <span class="text-xs font-semibold text-gray-500 mr-1">Periode:</span>
                            <a href="{{ route('uang-masuk.search') }}"
                                class="px-3 py-1.5 text-xs font-medium rounded-lg transition {{ (!request('tanggal_awal') && !request('tanggal_akhir')) ? 'bg-[#2B7A78] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                Semua
                            </a>
                            <a href="{{ route('uang-masuk.search', array_merge(request()->except(['page', 'tanggal_awal', 'tanggal_akhir']), ['tanggal_awal' => $hariIniStr, 'tanggal_akhir' => $hariIniStr])) }}"
                                class="px-3 py-1.5 text-xs font-medium rounded-lg transition {{ (request('tanggal_awal') === $hariIniStr && request('tanggal_akhir') === $hariIniStr) ? 'bg-[#2B7A78] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                Hari Ini
                            </a>
                            <a href="{{ route('uang-masuk.search', array_merge(request()->except(['page', 'tanggal_awal', 'tanggal_akhir']), ['tanggal_awal' => $tujuhHariLaluStr, 'tanggal_akhir' => $hariIniStr])) }}"
                                class="px-3 py-1.5 text-xs font-medium rounded-lg transition {{ (request('tanggal_awal') === $tujuhHariLaluStr && request('tanggal_akhir') === $hariIniStr) ? 'bg-[#2B7A78] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                7 Hari Terakhir
                            </a>
                            <a href="{{ route('uang-masuk.search', array_merge(request()->except(['page', 'tanggal_awal', 'tanggal_akhir']), ['tanggal_awal' => $awalBulanIniStr, 'tanggal_akhir' => $hariIniStr])) }}"
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
                                placeholder="Cari nama, sumber, atau keterangan..."
                                class="w-full pl-10 pr-9 py-2 text-xs sm:text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2B7A78] focus:border-[#2B7A78] transition text-gray-800">
                            @if(request('search'))
                                <a href="{{ route('uang-masuk.search', request()->except('search')) }}"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-rose-500" title="Hapus kata kunci">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- Filter Dropdown & Tanggal --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-end">
                        {{-- Sumber Dana --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Tujuan Kas / Rekening</label>
                            <select name="source_id" class="w-full px-3 py-2 text-xs sm:text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2B7A78] bg-white text-gray-800">
                                <option value="">Semua Sumber</option>
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
                            @if(request()->hasAny(['search', 'source_id', 'tanggal_awal', 'tanggal_akhir']))
                                <a href="{{ route('uang-masuk.search') }}"
                                    class="mt-5 px-3 py-2 bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-semibold rounded-xl transition shrink-0" title="Reset filter">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            {{-- Tabel Transaksi Uang Masuk --}}
            <div id="tableContainer" class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                <div class="card-body">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="text-xs uppercase tracking-wider text-gray-500 bg-slate-50/80 border-b border-slate-100">
                                    <th class="py-3.5 px-4 text-center">No</th>
                                    <th class="py-3.5 px-4">Nama Transaksi</th>
                                    <th class="py-3.5 px-4">Sumber / Rekening</th>
                                    <th class="py-3.5 px-4 text-right">Nominal</th>
                                    <th class="py-3.5 px-4 text-center">Tanggal</th>
                                    <th class="py-3.5 px-4">Keterangan</th>
                                    <th class="py-3.5 px-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($income as $i)
                                    <tr class="hover:bg-slate-50/70 transition">
                                        {{-- No Urut --}}
                                        <td class="py-3.5 px-4 text-center text-gray-400 font-medium text-xs">
                                            {{ ($income->currentPage() - 1) * $income->perPage() + $loop->iteration }}
                                        </td>

                                        {{-- Nama Transaksi --}}
                                        <td class="py-3.5 px-4">
                                            <div class="flex items-center gap-2.5">
                                                <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs shrink-0 font-bold">
                                                    +
                                                </span>
                                                <div>
                                                    <p class="font-semibold text-gray-800 leading-tight">
                                                        {{ $i->name }}
                                                    </p>
                                                    @if ($i->type === 'payment')
                                                        <span class="text-[10px] text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded font-medium">
                                                            Penjualan
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Sumber Kas --}}
                                        <td class="py-3.5 px-4 text-gray-800">
                                            <div class="flex items-center gap-1.5">
                                                <span class="font-semibold">
                                                    {{ $i->source->name ?? '-' }}
                                                </span>
                                                @if ($i->source && $i->source->is_online)
                                                    <span class="ml-1 px-1.5 py-0.5 text-[10px] font-semibold rounded bg-blue-50 text-blue-700 border border-blue-200">Online</span>
                                                @else
                                                    <span class="px-2 py-0.5 text-[10px] font-semibold rounded bg-gray-100 text-gray-700 border border-gray-300">Offline</span>
                                                @endif
                                            </div>
                                        </td>

                                        {{-- Nominal --}}
                                        <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                            <span class="font-bold text-emerald-700">
                                                +Rp {{ number_format($i->amount, 0, ',', '.') }}
                                            </span>
                                        </td>

                                        {{-- Tanggal --}}
                                        <td class="py-3.5 px-4 text-center text-xs text-gray-500 whitespace-nowrap">
                                            {{ $i->date ? $i->date->translatedFormat('d M Y') : '-' }}
                                        </td>

                                        {{-- Deskripsi --}}
                                        <td class="py-3.5 px-4 text-xs text-gray-600 max-w-xs truncate" title="{{ $i->description }}">
                                            {{ $i->description ?: '-' }}
                                        </td>

                                        {{-- Aksi --}}
                                        <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                            <form action="{{ route('income.destroy', $i->id) }}" method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus data uang masuk ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="bg-rose-500 text-white px-3 py-1.5 rounded text-xs font-medium hover:bg-rose-600 transition shadow-sm">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-12 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <p class="text-base font-semibold text-gray-700">Tidak ada catatan uang masuk</p>
                                                <p class="text-xs text-gray-400 mt-1 max-w-sm">
                                                    Belum ada uang masuk non-penjualan yang tercatat untuk filter ini.
                                                </p>
                                                <button type="button" onclick="openModal()"
                                                    class="mt-4 inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-[#2B7A78] hover:bg-[#205C5D] text-white transition">
                                                    + Tambah Uang Masuk
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if ($income->hasPages())
                        <div class="p-4 border-t border-gray-100 bg-slate-50/50">
                            {{ $income->links() }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </x-panel>

    {{-- 4. Modal Tambah Uang Masuk Modern (Diletakkan di luar panel agar menutupi seluruh layar tanpa celah) --}}
    <div id="uangMasukOverlay"
        class="hidden fixed inset-0 top-0 left-0 right-0 bottom-0 w-full h-full bg-slate-900/60 backdrop-blur-sm z-[9999] flex justify-center items-center p-4 transition-opacity">
        <div id="uangMasukModal"
            class="bg-white w-full max-w-md rounded-2xl shadow-xl overflow-hidden transform transition-all">
            
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-slate-50/50">
                <div class="flex items-center gap-2.5">
                    <h3 class="text-base font-bold text-gray-800">
                        Catat Uang Masuk
                    </h3>
                </div>
                <button type="button" onclick="closeModal()"
                    class="text-gray-400 hover:text-gray-600 focus:outline-none">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('uang-masuk.store') }}" class="p-6 space-y-4">
                @csrf

                <div>
                    <label for="tagihanName" class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Nama <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" id="tagihanName" required
                        placeholder="Nama uang masuk"
                        class="w-full px-3.5 py-2.5 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2B7A78] text-gray-800">
                </div>

                <div>
                    <label for="source" class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Sumber <span class="text-rose-500">*</span>
                    </label>
                    <select name="source_id" id="source" required
                        class="w-full px-3.5 py-2.5 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2B7A78] bg-white text-gray-800">
                        <option value="" disabled selected>Pilih sumber kas</option>
                        @foreach ($sources as $source)
                            <option value="{{ $source->id }}">{{ $source->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="nominal" class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Nominal <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-xs font-bold text-gray-400">
                            Rp
                        </span>
                        <input type="number" name="amount" id="nominal" min="1" required
                            placeholder="0"
                            class="w-full pl-10 pr-3.5 py-2.5 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2B7A78] text-gray-800">
                    </div>
                </div>

                <div>
                    <label for="tanggal" class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Tanggal <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="date" id="tanggal" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}" required
                        class="w-full px-3.5 py-2.5 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2B7A78] text-gray-800 bg-white">
                </div>

                <div>
                    <label for="deskripsi" class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Deskripsi (Opsional)
                    </label>
                    <textarea name="description" id="deskripsi" rows="2"
                        placeholder="Catatan tambahan..."
                        class="w-full px-3.5 py-2.5 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2B7A78] text-gray-800"></textarea>
                </div>

                <div class="pt-3 border-t border-gray-100 flex items-center justify-end gap-2.5">
                    <button type="button" onclick="closeModal()"
                        class="px-4 py-2.5 rounded-xl border border-gray-300 text-xs sm:text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-[#2B7A78] hover:bg-[#205C5D] text-xs sm:text-sm font-semibold text-white shadow-sm transition">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function openModal() {
        const overlay = document.getElementById('uangMasukOverlay');
        if (overlay) {
            overlay.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
    }

    function closeModal() {
        const overlay = document.getElementById('uangMasukOverlay');
        if (overlay) {
            overlay.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }

    // Tutup modal jika klik di luar box
    document.addEventListener('click', function(event) {
        const overlay = document.getElementById('uangMasukOverlay');
        const modal = document.getElementById('uangMasukModal');
        if (overlay && modal && !overlay.classList.contains('hidden')) {
            if (event.target === overlay) {
                closeModal();
            }
        }
    });

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
