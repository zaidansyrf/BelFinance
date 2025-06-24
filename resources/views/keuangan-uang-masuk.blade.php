@section('title', 'Pemasukkan | Uang masuk')
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
                        <h1 class="text-xl font-semibold text-[#2B7A78] mb-4">Halaman Uang masuk</h1>
                        <div class="flex items-center space-x-4">
                            <button
                                class="bg-[#2B7A78] text-white font-semibold py-2 px-4 rounded-lg hover:bg-[#205C5D]"
                                onclick="openModal()">
                                + <span class="hidden sm:inline">Tambah</span>
                            </button>
                            <form action="{{ route('uang-masuk.search') }}" method="GET"
                                class="flex items-center max-w-md">
                                <div class="relative w-full">
                                    <label for="search"
                                        class="hidden mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Cari
                                    </label>
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
                                    <a href="{{ url('/keuangan/uang-masuk') }}"
                                        class="py-3 px-5 text-sm font-medium text-white bg-red-600 border border-red-500 rounded-r-lg hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-800">
                                        Clear
                                    </a>
                                @endif
                            </form>
                        </div>
                    </div>
                    <div class="flex justify-center w-full px-8">
                        <div class="card text-primary-content bg-white mt-4 w-full">
                            <div class="card-body">
                                <!-- <h2 class="card-title text-black">Tabel Uang masuk</h2> -->
                                <div class="overflow-x-auto">
                                    <table class="table w-full table-auto">
                                        <thead>
                                            <tr class="text-left">
                                                <th class="py-2 px-4 border-b text-left text-gray-800">No</th>
                                                <th class="py-2 px-4 border-b text-left text-gray-800">Nama</th>
                                                <th class="py-2 px-4 border-b text-left text-gray-800">Sumber</th>
                                                <th class="py-2 px-4 border-b text-left text-gray-800">Nominal</th>
                                                <th class="py-2 px-4 border-b text-left text-gray-800">Tanggal</th>
                                                <th class="py-2 px-4 border-b text-left text-gray-800">Deskripsi</th>
                                                <th class="py-2 px-4 border-b text-left text-gray-800">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($income->sortByDesc('date')->values() as $i)
                                                <tr class="hover:bg-gray-100/50">
                                                    <td class="text-black px-4 py-2">{{ $loop->iteration }}</td>
                                                    <td class="text-black px-4 py-2">{{ $i->name }}</td>
                                                    <td class="text-black px-4 py-2">{{ $i->source->name }}</td>
                                                    <td class="text-black px-4 py-2">Rp
                                                        {{ number_format($i->amount, 0, ',', '.') }}</td>
                                                    <td class="text-black px-4 py-2">{{ $i->date->format('d-m-Y') }}
                                                    </td>
                                                    <td class="text-black px-4 py-2">{{ $i->description }}</td>
                                                    <td class="text-black px-4 py-2">
                                                        <form action="{{ route('income.destroy', $i->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Apakah yakin ingin menghapus data ini?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="text-white btn btn-sm bg-red-500 hover:bg-red-600 px-3 py-1 rounded">
                                                                Hapus
                                                            </button>
                                                        </form>
                                                    </td>


                                                </tr>
                                            @endforeach
                                            @if ($income->isEmpty())
                                                <tr>
                                                    <td colspan="6" class="px-4 py-2 text-center text-black">Tidak
                                                        ada data tersedia.</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                {{ $income->links() }}
                            </div>
                        </div>
                    </div>
                </div>
                <div id="menuOverlay"
                    class="px-4 py-2 hidden fixed inset-0 bg-black bg-opacity-50 z-20 flex justify-center items-center">
                    <!-- Form Menu -->
                    <div id="uangMasukModal"
                        class="hidden bg-white w-[400px] h-auto max-w-[400px] rounded-lg shadow-lg p-6">
                        <h3 class="text-xl font-semibold mb-4">Uang Masuk</h3>
                        <form method="POST" action="{{ route('uang-masuk.store') }}">
                            @csrf
                            <div class="mb-4">
                                <label for="tagihanName" class="block text-sm font-medium text-gray-700">Nama</label>
                                <input type="text" name="name" id="tagihanName"
                                    class="w-full p-2 border border-gray-300 rounded-md" placeholder="Masukkan Nama"
                                    required>
                            </div>
                            <div class="mb-4">
                                <label for="source_id" class="block text-sm font-medium text-gray-700">Sumber</label>
                                <select name="source_id" id="source"
                                    class="text-gray-500 w-full p-2 border border-gray-300 rounded-md form-select"
                                    required>
                                    <option class="text-black" value="" selected disabled>Pilih Sumber</option>
                                    @foreach ($sources as $source)
                                        <option class="text-black" value="{{ $source->id }}">{{ $source->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal</label>
                                <input type="date" name="date" id="tanggal"
                                    class="w-full p-2 border border-gray-300 rounded-md" required>
                            </div>
                            <div class="mb-4">
                                <label for="nominal" class="block text-sm font-medium text-gray-700">Nominal</label>
                                <input type="number" name="amount" id="nominal"
                                    class="w-full p-2 border border-gray-300 rounded-md" placeholder="Rp. " required>
                            </div>
                            <div class="mb-4">
                                <label for="deskripsi"
                                    class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                <textarea name="description" id="deskripsi" rows="4" class="w-full p-2 border border-gray-300 rounded-md"
                                    placeholder="Masukkan Deskripsi"></textarea>
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
            <!-- sidebar content -->
            @include('layouts.sidebar')
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
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
                confirmButtonText: 'OK'
            });
        @endif
        // Fungsi untuk menampilkan modal
        function openModal() {
            const menuOverlay = document.getElementById('menuOverlay');
            const uangMasukModal = document.getElementById('uangMasukModal');

            menuOverlay.classList.remove('hidden');
            uangMasukModal.classList.remove('hidden');
        }

        // Fungsi untuk menutup modal
        function closeModal() {
            const menuOverlay = document.getElementById('menuOverlay');
            const uangMasukModal = document.getElementById('uangMasukModal');

            menuOverlay.classList.add('hidden');
            uangMasukModal.classList.add('hidden');
        }

        // Menutup modal ketika area overlay di-klik
        document.getElementById('menuOverlay').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
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
