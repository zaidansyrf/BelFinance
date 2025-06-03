@section('title', 'Pemasukkan | Transaksi')
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
                        <h1 class="text-xl font-semibold text-[#2B7A78] mb-4">Halaman Pembayaran</h1>
                        <button class="bg-[#2B7A78] text-white font-semibold py-2 px-4 rounded-lg hover:bg-[#205C5D]"
                            onclick="window.location.href='{{ url('/keuangan/pembayaran/create') }}'">
                            + <span class="hidden sm:inline">Tambah</span>
                        </button>
                    </div>
                    <div class="flex justify-center w-full px-8">
                        <div class="card text-primary-content bg-white mt-4 w-full">
                            <div class="card-body">
                                <!-- <h2 class="card-title text-black">Tabel Pembayaran</h2> -->
                                <div class="overflow-x-auto">
                                    <table class="table w-full table-auto">
                                        <thead>
                                            <tr>
                                                <th class="py-2 px4 border-b text-left text-gray-800">No</th>
                                                <th class="py-2 px4 border-b text-left text-gray-800">Nama</th>
                                                <th class="py-2 px4 border-b text-left text-gray-800">Sumber</th>
                                                <th class="py-2 px4 border-b text-left text-gray-800">Total</th>
                                                <th class="py-2 px4 border-b text-left text-gray-800">Detail</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($pembayarans as $pembayaran)
                                                <tr>
                                                    <td class="py-2 px-4 border-b text-gray-800">{{ $loop->iteration }}
                                                    </td>
                                                    <td class="py-2 px-4 border-b text-gray-800">
                                                        {{ $pembayaran->name }}
                                                    </td>
                                                    <td class="py-2 px-4 border-b text-gray-800">
                                                        {{ $pembayaran->source_id ? $pembayaran->source->name : 'Tidak Diketahui' }}
                                                    </td>
                                                    <td class="py-2 px-4 border-b text-gray-800">
                                                        {{ number_format($pembayaran->amount, 0, ',', '.') }}
                                                    </td>
                                                    <td class="py-2 px-4 border-b text-gray-800">
                                                        <a href="{{ url('/keuangan/pembayaran/' . $pembayaran->id) }}"
                                                            class="bg-[#468585] text-white px-3 py-1 rounded hover:bg-[#2B7A78] transition duration-200">
                                                            Detail
                                                        </a>
                                                        {{-- hapus --}}
                                                        <form
                                                            action="{{ url('/keuangan/pembayaran/' . $pembayaran->id) }}"
                                                            method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="bg-red-500 text-white px-3 py-1 ml-3 rounded hover:bg-red-600 transition duration-200"
                                                                onclick="return confirm('Apakah Anda yakin ingin menghapus pembayaran ini?')">
                                                                Hapus
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
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
