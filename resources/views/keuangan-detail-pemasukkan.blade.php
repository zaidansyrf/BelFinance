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
                        <h1 class="text-xl font-semibold text-[#2B7A78] mb-4">Halaman Detail Pemasukkan</h1>

                        <div class="flex justify-center w-full px-8">
                            <div class="card text-primary-content bg-white mt-4 w-full">
                                <div class="card-body">
                                    <h2 class="card-title text-black">Tabel Pemasukkan</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- sidebar content -->
            <div class="drawer-side">
                <label for="my-drawer-2" aria-label="close sidebar" class="drawer-overlay"></label>
                <ul class="menu text-black min-h-full w-80 p-4 bg-white">
                    <div class="lg:hidden flex justify-end mb-4">
                        <label for="my-drawer-2" class="cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500 hover:text-gray-700"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </label>
                    </div>
                    <!-- Sidebar Logo -->
                    <div class="text-[#2B7A78] text-center mb-4 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="mt-10 mb-6 h-8 w-8 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <h1 class="mt-10 mb-6 text-xl font-bold">BelFinance</h1>
                    </div>
                    <!-- Sidebar Menu Links -->
                    <li>
                        <a href="{{ url('/keuangan/dashboard') }}"
                            class=" text-black  hover:bg-[#2B7A78] hover:text-[#DEF2F1] mb-2 block w-full px-4 py-2">
                            Dashboard
                        </a>
                    </li>
                    <li class="relative">
                        <button id="dropdownPemasukkanButton" onclick="dropdownPemasukkan()"
                            class="bg-[#116A71] text-white hover:bg-[#2B7A78] hover:text-[#DEF2F1] mt-2 mb-2 block w-full px-4 py-2 text-left">
                            Pemasukkan
                        </button>
                        <ul id="dropdownPemasukkanMenu"
                            class="hidden bg-[#116A71] rounded text-white shadow-lg left-0 m-0 pl-0">
                            <li class="px-0 py-0 cursor-pointer"><a href="{{ url('/keuangan/pembayaran') }}"
                                    class="hover:bg-[#3A9B98] hover:rounded-none">Pembayaran</a></li>
                            <li class="px-0 py-0 cursor-pointer"><a href="{{ url('/keuangan/detail-pemasukkan') }}"
                                    class="hover:bg-[#3A9B98] hover:rounded-none">Detail Pemasukkan</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="{{ url('/keuangan/pengeluaran') }}"
                            class=" text-black hover:bg-[#2B7A78] hover:text-[#DEF2F1] mb-4 mt-2 block w-full px-4 py-2">
                            Pengeluaran
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/keuangan/menu') }}"
                            class=" text-black hover:bg-[#2B7A78] hover:text-[#DEF2F1] mb-4 block w-full px-4 py-2">
                            Menu
                        </a>
                    </li>
                    <li class="relative">
                        <!-- Dropdown Kategori -->
                        <button id="dropdownKategoriButton" onclick="dropdownKategori()"
                            class="text-black hover:bg-[#2B7A78] hover:text-[#DEF2F1] mt-2 mb-2 block w-full px-4 py-2 text-left">
                            Kategori
                        </button>
                        <!-- Dropdown Menu -->
                        <ul id="dropdownKategoriMenu"
                            class="hidden bg-[#116A71] rounded text-white shadow-lg left-0 m-0 pl-0">
                            <li class="px-0 py-0 cursor-pointer"><a href="{{ url('/keuangan/kategori/sumber-masuk') }}"
                                    class="hover:bg-[#3A9B98] hover:rounded-none">Sumber Masuk</a></li>
                            <li class="px-0 py-0 cursor-pointer"><a href="{{ url('/keuangan/kategori/sumber-keluar') }}"
                                    class="hover:bg-[#3A9B98] hover:rounded-none">Sumber Keluar</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <script>
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

        function toggleAdditionalFields() {
            const paymentSource = document.getElementById('paymentSource').value;

            // Hide all additional fields first
            document.getElementById('orderMenu').classList.add('hidden');

            // Show relevant fields based on the selected income source
            if (paymentSource === 'shopee') {
                document.getElementById('orderMenu').classList.remove('hidden');
            }
        }

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
