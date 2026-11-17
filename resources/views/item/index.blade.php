@section('title', 'Menu')
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
                <div class="flex-1 bg-[#D1DDD5] overflow-auto">
                    <div class="justify-between items-center mt-8 px-4 sm:px-8 pb-12">
                        <h1 class="text-xl font-semibold text-[#2B7A78] mb-4">Menu</h1>
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <button onclick="openMenuForm()"
                                    class="bg-[#2B7A78] text-white font-semibold py-2 px-4 rounded-lg hover:bg-[#205C5D] transition">
                                    + <span class="hidden sm:inline">Tambah</span>
                                </button>
                            </div>
                            {{-- search --}}
                            <div class="w-full sm:w-72 md:w-80">
                                <form action="{{ route('menu.search') }}" method="GET">
                                    <div class="relative w-full">
                                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                        <input type="text" id="search" name="search" value="{{ request('search') }}"
                                            placeholder="Cari kode, nama, dan harga menu"
                                            class="w-full pl-10 pr-9 py-2 text-xs sm:text-sm bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2B7A78] focus:border-[#2B7A78] transition text-gray-800 shadow-sm">
                                        @if (request('search'))
                                            <a href="{{ route('menu.search', request()->except('search')) }}"
                                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-rose-500" title="Hapus kata kunci">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div id="tableContainer" class="py-2 px-4 card text-primary-content bg-white mt-4 mb-8 w-full shadow-sm rounded-2xl">
                            <div class="card-body">
                                <!-- <h2 class="card-title text-black">Tabel Menu</h2> -->
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left text-sm">
                                        <thead>
                                            <tr>
                                                <th class="py-2 px-4 border-b text-left text-gray-800">No</th>
                                                <th class="py-2 px-4 border-b text-left text-gray-800">Gambar</th>
                                                <th class="py-2 px-4 border-b text-left text-gray-800">Kode Menu</th>
                                                <th class="py-2 px-4 border-b text-left text-gray-800">Nama Menu</th>
                                                <th class="py-2 px-4 border-b text-left text-gray-800">Jumlah (Terjual)
                                                </th>
                                                <th class="py-2 px-4 border-b text-left text-gray-800">Stok</th>
                                                <th class="py-2 px-4 border-b text-left text-gray-800">Harga</th>
                                                <th class="py-2 px-4 border-b text-left text-gray-800">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($items as $menu)
                                                <tr class="hover:bg-gray-100/50">
                                                    <td class="py-2 px-4 border-b text-black">{{ ($items->currentPage() - 1) * $items->perPage() + $loop->iteration }}
                                                    </td>
                                                    <td class="py-2 px-4 border-b">
                                                        <x-menu-image :item="$menu" />
                                                    </td>
                                                    <td class="py-2 px-4 border-b text-black">{{ $menu->code }}</td>
                                                    <td class="py-2 px-4 border-b text-black">
                                                        <span class="font-medium">{{ $menu->name }}</span>
                                                        @if ($menu->promo && $menu->promo->is_active)
                                                            <span class="text-[11px] bg-pink-100 text-pink-800 font-semibold px-2 py-0.5 rounded block mt-0.5 w-fit border border-pink-200">
                                                                🏷️ Promo: {{ $menu->promo->name }}
                                                                ({{ $menu->promo->discount_type === 'percent' ? $menu->promo->discount_value . '%' : 'Rp ' . number_format($menu->promo->discount_value, 0, ',', '.') }})
                                                            </span>
                                                        @endif
                                                        @if ($menu->description)
                                                            <p class="text-xs text-gray-500 max-w-xs truncate">{{ $menu->description }}</p>
                                                        @endif
                                                    </td>
                                                    <td class="py-2 px-4 border-b text-black">{{ $menu->quantity }}
                                                    </td>
                                                    <td class="py-2 px-4 border-b text-black">
                                                        @if ($menu->lacakStok())
                                                            <span class="{{ $menu->stock < 1 ? 'text-red-600 font-semibold' : ($menu->stokMenipis() ? 'text-amber-600 font-semibold' : '') }}">{{ $menu->stock }}</span>
                                                            @if ($menu->stokMenipis())
                                                                <span class="text-xs text-amber-600">{{ $menu->stock < 1 ? '(habis)' : '(menipis)' }}</span>
                                                            @endif
                                                        @else
                                                            <span class="text-gray-400">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="py-2 px-4 border-b text-black">Rp
                                                        {{ number_format($menu->price, 0, ',', '.') }}</td>
                                                    <td class="py-2 px-4 border-b text-black">
                                                        <a href="{{ route('menu.edit', $menu->id) }}"
                                                            class="text-white bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded-md hover:underline">Edit</a>
                                                        <form action="{{ route('menu.destroy', $menu->id) }}"
                                                            method="POST" class="inline-block"
                                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus menu {{ $menu->name }}?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600 ml-2">Hapus</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @if ($items->isEmpty())
                                                <tr>
                                                    <td colspan="7" class="px-4 py-2 text-center text-black">Tidak
                                                        ada data tersedia.</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                {{ $items->links() }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- sidebar content -->
            @include('layouts.sidebar')

        </div>
    </div>

    <!-- Modal Form Menu (Blur Backdrop & Fullscreen) -->
    <div id="menuOverlay"
        class="hidden fixed inset-0 top-0 left-0 right-0 bottom-0 w-full h-full bg-slate-900/60 backdrop-blur-sm z-[9999] flex justify-center items-center p-4 transition-opacity overflow-y-auto">
        <div id="menuModal"
            class="hidden bg-white w-full max-w-lg rounded-2xl shadow-xl overflow-hidden transform transition-all my-8 max-h-[90vh] flex flex-col">
            
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-slate-50/50 shrink-0">
                <h3 class="text-base font-bold text-gray-800">
                    Tambah Menu
                </h3>
                <button type="button" onclick="closeModal()"
                    class="text-gray-400 hover:text-gray-600 focus:outline-none">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('menu.store') }}" enctype="multipart/form-data" class="p-6 space-y-4 overflow-y-auto">
                @csrf

                <div>
                    <label for="menuName" class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Nama Menu <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" id="menuName" required
                        placeholder="cth. Ayam Goreng"
                        class="w-full px-3.5 py-2.5 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2B7A78] text-gray-800">
                </div>

                <input type="hidden" name="code" id="menuCode" />

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="menuJumlah" class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Jumlah terjual <span class="text-gray-400 font-normal">(opsional)</span>
                        </label>
                        <input type="number" name="quantity" id="menuJumlah" required value="0"
                            placeholder="cth. 10"
                            class="w-full px-3.5 py-2.5 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2B7A78] text-gray-800">
                    </div>
                    <div>
                        <label for="menuStock" class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Stok <span class="text-gray-400 font-normal">(opsional)</span>
                        </label>
                        <input type="number" name="stock" id="menuStock" min="0"
                            placeholder="cth. 20"
                            class="w-full px-3.5 py-2.5 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2B7A78] text-gray-800">
                    </div>
                </div>

                <div>
                    <label for="menuPrice" class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Harga <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-xs font-bold text-gray-400">
                            Rp
                        </span>
                        <input type="number" name="price" id="menuPrice" min="0" step="1" inputmode="numeric" required
                            placeholder="0"
                            class="w-full pl-10 pr-3.5 py-2.5 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2B7A78] text-gray-800">
                    </div>
                </div>

                <div>
                    @include('ai._deskripsi-menu-field', ['id' => 'menuDescription'])
                </div>

                <div>
                    <label for="menuImage" class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Gambar Menu <span class="text-gray-400 font-normal">(opsional, maks. 2 MB)</span>
                    </label>
                    <input type="file" name="image" id="menuImage" accept="image/png,image/jpeg,image/webp"
                        class="file-input file-input-bordered file-input-sm w-full rounded-xl">
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

        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Data menu belum valid',
                html: @json(implode('<br>', array_map('e', $errors->all()))),
                confirmButtonColor: '#2B7A78'
            });
        @endif

        // Open Menu Form
        function openMenuForm() {
            const modalOverlay = document.getElementById('menuOverlay');
            const menuModal = document.getElementById('menuModal');
            modalOverlay.classList.remove('hidden');
            menuModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            if (typeof closeDropdown === 'function') {
                closeDropdown();
            }
        }

        // Close Modal
        function closeModal() {
            const modalOverlay = document.getElementById('menuOverlay');
            const menuModal = document.getElementById('menuModal');
            modalOverlay.classList.add('hidden');
            menuModal.classList.add('hidden');
            document.body.style.overflow = '';
        }

        // Tutup saat klik overlay di luar modal
        document.addEventListener('click', function(event) {
            const overlay = document.getElementById('menuOverlay');
            const modal = document.getElementById('menuModal');
            if (overlay && modal && !overlay.classList.contains('hidden')) {
                if (event.target === overlay) {
                    closeModal();
                }
            }
        });
        // Menutup dropdown ketika klik luar area
        window.onclick = function(event) {
            const dropdown = document.getElementById('menuDropdown');
            const dropdownButton = document.getElementById('dropdownButton');
            if (!dropdown.contains(event.target) && !dropdownButton.contains(event.target)) {
                closeDropdown();
            }
        };
        // Event handler saat pilihan dropdown diklik
        function selectDropdownOption() {
            closeDropdown();
        }
        // Close Dropdown When Click Outside
        window.onclick = function(event) {
            if (!event.target.matches('button') && !event.target.matches('.block')) {
                closeDropdown();
            }
        };

        function dropdownLaporan() {
            const dropdownLaporanMenu = document.getElementById('dropdownLaporanMenu');
            dropdownLaporanMenu.classList.toggle('hidden');
        }
        document.addEventListener("click", function(event) {
            const dropdownLaporanMenu = document.getElementById("dropdownLaporanMenu");
            const dropdownLaporanButton = document.getElementById("dropdownLaporanButton");

            // Jika elemen yang diklik bukan bagian dari dropdown
            if (
                !dropdownLaporanMenu.contains(event.target) &&
                !dropdownLaporanButton.contains(event.target)
            ) {
                dropdownLaporanMenu.classList.add("hidden");
            }
        });

        function dropdownKategori() {
            const dropdownKategoriMenu = document.getElementById('dropdownKategoriMenu');
            dropdownKategoriMenu.classList.toggle('hidden');
        }
        document.addEventListener("click", function(event) {
            const dropdownKategoriMenu = document.getElementById("dropdownKategoriMenu");
            const dropdownKategoriButton = document.getElementById("dropdownKategoriButton");

            // Jika elemen yang diklik bukan bagian dari dropdown
            if (
                !dropdownKategoriMenu.contains(event.target) &&
                !dropdownKategoriButton.contains(event.target)
            ) {
                dropdownKategoriMenu.classList.add("hidden");
            }
        });

        function dropdownPemasukkan() {
            const dropdownPemasukkanMenu = document.getElementById('dropdownPemasukkanMenu');
            dropdownPemasukkanMenu.classList.toggle('hidden');
        }
        document.addEventListener("click", function(event) {
            const dropdownPemasukkanMenu = document.getElementById("dropdownPemasukkanMenu");
            const dropdownPemasukkanButton = document.getElementById("dropdownPemasukkanButton");

            // Jika elemen yang diklik bukan bagian dari dropdown
            if (
                !dropdownPemasukkanMenu.contains(event.target) &&
                !dropdownPemasukkanButton.contains(event.target)
            ) {
                dropdownPemasukkanMenu.classList.add("hidden");
            }
        });

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
