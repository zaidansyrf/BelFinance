@section('title', 'Pengeluaran')
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
                        <h1 class="text-xl font-semibold text-[#2B7A78] mb-4">Halaman Pengeluaran</h1>
                        <div class="flex items-center space-x-4">
                            <button
                                class="bg-[#2B7A78] text-white font-semibold py-2 px-4 rounded-lg hover:bg-[#205C5D]"
                                onclick="window.location.href='{{ url('/keuangan/pengeluaran/create') }}'">
                                + <span class="hidden sm:inline">Tambah</span>
                            </button>
                            <form action="{{ route('expenses.search') }}" method="GET"
                                class="flex items-center max-w-md">
                                <div class="relative w-full">
                                    <label for="search"
                                        class="hidden mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Cari
                                        pengeluaran</label>
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
                                    <a href="{{ url('/keuangan/pengeluaran') }}"
                                        class="py-3 px-5 text-sm font-medium text-white bg-red-600 border border-red-500 rounded-r-lg hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-800">
                                        Clear
                                    </a>
                                @endif
                            </form>

                        </div>
                        <div class="card text-primary-content bg-white mt-4 w-full">
                            <div class="card-body">
                                <!-- <h2 class="card-title text-black">Tabel Pengeluaran</h2> -->
                                <div class="overflow-x-auto">
                                    <table class="table w-full">
                                        <thead>
                                            <tr class="text-left">
                                                <th class="py-2 px-4 border-b text-left text-gray-800">No</th>
                                                <th class="py-2 px-4 border-b text-left text-gray-800">Sumber</th>
                                                <th class="py-2 px-4 border-b text-left text-gray-800">Tagihan</th>
                                                <th class="py-2 px-4 border-b text-left text-gray-800">Nominal</th>
                                                <th class="py-2 px-4 border-b text-left text-gray-800">Keterangan</th>
                                                <th class="py-2 px-4 border-b text-left text-gray-800">Tanggal</th>
                                                <th class="py-2 px-4 border-b text-left text-gray-800">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($expenses->sortByDesc('date')->values() as $expense)
                                                <tr class="hover:bg-gray-100/50">
                                                    <td class="text-black px-4 py-2">{{ $loop->iteration }}</td>
                                                    <td class="text-black px-4 py-2">{{ $expense->source->name }}</td>
                                                    <td class="text-black px-4 py-2">{{ $expense->bill->name }}</td>
                                                    <td class="text-black px-4 py-2">Rp.
                                                        {{ number_format($expense->amount, 0, ',', '.') }}</td>
                                                    <td class="text-black px-4 py-2">{{ $expense->description }}</td>
                                                    <td class="text-black px-4 py-2">
                                                        {{ $expense->date->format('d-m-Y') }}</td>
                                                    <td class="text-black px-4 py-2">

                                                        <a href="{{ route('expenses.show', $expense->id) }}"
                                                            class="text-white px-3 py-1 rounded bg-[#2B7A78] hover:bg-[#205C5D] ml-2">Edit</a>
                                                        <form action="{{ route('expenses.destroy', $expense->id) }}"
                                                            method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')"
                                                                class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 ml-2">Hapus</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @if ($expenses->isEmpty())
                                                <tr>
                                                    <td colspan="6" class="px-4 py-2 text-center text-black">Tidak
                                                        ada data tersedia.</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                {{ $expenses->links() }}
                            </div>
                        </div>
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
