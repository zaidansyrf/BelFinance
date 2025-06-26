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
                    <div class="sticky justify-between items-center mt-12 px-8">
                        <h1 class="text-xl font-semibold text-[#2B7A78] mb-4">Menu</h1>
                        <div class="flex items-center space-x-4">
                            <button onclick="openMenuForm()"
                                class="bg-[#2B7A78] text-white font-semibold py-2 px-4 rounded-lg hover:bg-[#205C5D]">
                                + <span class="hidden sm:inline">Tambah</span>
                            </button>
                            <form action="{{ route('menu.search') }}" method="GET" class="flex items-center max-w-md">
                                <div class="relative w-full">
                                    <label for="search"
                                        class="hidden mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Cari
                                        Menu</label>
                                    <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                                                d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                                        </svg>
                                    </div>
                                    <input type="search" id="search" name="search" placeholder="Cari"
                                        value="{{ request('search') }}"
                                        class="block p-3 pl-10 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                </div>

                                @if (request('search'))
                                    <!-- Tombol Clear Jika Ada Input -->
                                    <a href="{{ url('/keuangan/menu') }}"
                                        class="py-3 px-5 text-sm font-medium text-white bg-red-600 border border-red-500 rounded-r-lg hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-800">
                                        Clear
                                    </a>
                                @endif
                            </form>

                        </div>
                        <div class="py-2 px-4 card text-primary-content bg-white mt-4 w-full">
                            <div class="card-body">
                                <!-- <h2 class="card-title text-black">Tabel Menu</h2> -->
                                <div class="overflow-x-auto">
                                    <table class="table w-full table-auto">
                                        <thead>
                                            <tr>
                                                <th class="py-2 px-4 border-b text-left text-gray-800">No</th>
                                                <th class="py-2 px-4 border-b text-left text-gray-800">Kode Menu</th>
                                                <th class="py-2 px-4 border-b text-left text-gray-800">Nama Menu</th>
                                                <th class="py-2 px-4 border-b text-left text-gray-800">Jumlah (Terjual)
                                                </th>
                                                <th class="py-2 px-4 border-b text-left text-gray-800">Harga</th>
                                                <th class="py-2 px-4 border-b text-left text-gray-800">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($items as $menu)
                                                <tr class="hover:bg-gray-100/50">
                                                    <td class="py-2 px-4 border-b text-black">{{ $loop->iteration }}
                                                    </td>
                                                    <td class="py-2 px-4 border-b text-black">{{ $menu->code }}</td>
                                                    <td class="py-2 px-4 border-b text-black">{{ $menu->name }}</td>
                                                    <td class="py-2 px-4 border-b text-black">{{ $menu->quantity }}
                                                    </td>
                                                    <td class="py-2 px-4 border-b text-black">Rp
                                                        {{ number_format($menu->price, 0, ',', '.') }}</td>
                                                    <td class="py-2 px-4 border-b text-black">
                                                        <a href="{{ route('menu.edit', $menu->id) }}"
                                                            class="text-white bg-[#468585] hover:bg-[#376e6e] px-3 py-1 rounded hover:underline">Edit</a>
                                                        <form action="{{ route('menu.destroy', $menu->id) }}"
                                                            method="POST" class="inline-block">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 ml-2"
                                                                onclick="return confirm('Apakah Anda yakin ingin menghapus menu {{ $menu->name }}?')">Hapus</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @if ($items->isEmpty())
                                                <tr>
                                                    <td colspan="6" class="px-4 py-2 text-center text-black">Tidak
                                                        ada data tersedia.</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                {{ $items->links() }}
                            </div>
                        </div>
                        <div class="mb-4 card text-primary-content bg-white mt-4 w-full">
                            <div class="card-body">
                                <div class="overflow-hidden">
                                      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                                        <div class="bg-[#2B7A78] text-white p-4 rounded-lg">
                                            <h3 class="text-sm">Total Menu Terjual</h3>
                                            <p class="text-2xl font-bold">{{ $totalSold }} porsi</p>
                                        </div>
                                        <div class="bg-[#3AAFA9] text-white p-4 rounded-lg">
                                            <h3 class="text-sm">Menu Terlaris</h3>
                                            <p class="text-lg font-bold">{{ $topSellingMenu->name ?? '-' }}</p>
                                        </div>
                                    </div>
                                    <div class="mt-2 flex flex-col lg:flex-row gap-6">
                                        <div class="mt-4 w-72 h-72 mx-auto">
                                            <canvas id="menuPieChart"></canvas>
                                        </div>
                                        <div class="bg-white rounded-lg p-4 w-full lg:w-[48%]">
                                        
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal untuk forms -->
                    <div id="menuOverlay"
                        class="hidden fixed inset-0 bg-black bg-opacity-50 z-20 flex justify-center items-center">
                        <!-- Form Menu -->
                        <div id="menuModal"
                            class="hidden bg-white w-[400px] h-auto max-w-[400px] rounded-lg shadow-lg p-6">
                            <h3 class="text-xl font-semibold mb-4">Tambah Menu</h3>
                            <form method="POST" action="{{ route('menu.store') }}">
                                @csrf
                                <div class="mb-4">
                                    <label for="menuName" class="block text-sm font-medium text-gray-700">Nama
                                        Menu</label>
                                    <input type="text" name="name" id="menuName"
                                        class="w-full p-2 border border-gray-300 rounded-md"
                                        placeholder="cth. Ayam Goreng" required>
                                </div>
                                <input type="hidden" name="code" id="menuCode" />
                                <div class="mb-4">
                                    <label for="menuJumlah"
                                        class="block text-sm font-medium text-gray-700">Jumlah</label>
                                    <input type="number" name="quantity" id="menuJumlah"
                                        class="w-full p-2 border border-gray-300 rounded-md" placeholder="cth. 10"
                                        required value="0">
                                </div>
                                <div class="mb-4">
                                    <label for="menuPrice"
                                        class="block text-sm font-medium text-gray-700">Harga</label>
                                    <input type="number" name="price" id="menuPrice"
                                        class="w-full p-2 border border-gray-300 rounded-md" placeholder="Rp "
                                        required step="1" min="0" inputmode="numeric">
                                </div>
                                <div class="flex justify-end mt-4">
                                    <button type="button" onclick="closeModal()"
                                        class="bg-[#db5461] text-white font-semibold py-2 px-6 rounded-lg hover:bg-gray-600">Batal</button>
                                    <button type="submit"
                                        class="bg-[#2B7A78] text-white font-semibold py-2 px-6 rounded-lg hover:bg-[#205C5D] ml-4">Simpan</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

            <!-- sidebar content -->
            @include('layouts.sidebar')

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const data = {
            labels: @json($chartData['labels']),
            datasets: [{
                label: 'Terjual',
                data: @json($chartData['quantities']),
                backgroundColor: [
                    '#819A91',
                    '#537D5D',
                    '#73946B',
                    '#9EBC8A',
                    '#AEC8A4'
                ],
                borderWidth: 1
            }]
        };

        const config = {
            type: 'pie',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 20,
                            padding: 15
                        }
                    }
                }
            }
        };

        const myChart = new Chart(
            document.getElementById('menuPieChart'),
            config
        );
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 2000
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                showConfirmButton: true
            });
        @endif
        function contoh(event) {
            event.preventDefault(); // Mencegah pengiriman form

            // Menampilkan SweetAlert untuk konfirmasi sukses
            swal({
                title: "Berhasil!",
                text: "Data berhasil disimpan",
                icon: "success",
                button: "OK"
            }).then((willProceed) => {
                if (willProceed) {
                    // Jika pengguna mengklik OK, kirim form
                    event.target.submit();
                }
            }).catch((error) => {
                // Menampilkan SweetAlert untuk kesalahan
                swal({
                    title: "Gagal!",
                    text: "Terjadi kesalahan saat menyimpan data.",
                    icon: "error",
                    button: "Coba Lagi"
                });
            });
        }

        // Open Menu Form
        function openMenuForm() {
            const modalOverlay = document.getElementById('menuOverlay');
            const menuModal = document.getElementById('menuModal');
            modalOverlay.classList.remove('hidden');
            menuModal.classList.remove('hidden');
            closeDropdown(); // Menutup dropdown saat membuka form
        }
        // Close Modal
        function closeModal() {
            document.getElementById('menuOverlay').classList.add('hidden');
        }
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
    </script>
</x-app-layout>
