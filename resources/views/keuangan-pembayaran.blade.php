@section('title', 'Pemasukan | Pembayaran')
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
                    <div class="flex justify-center w-full px-8 pb-10">
                        <div class="card text-primary-content bg-white mt-4 w-full shadow-sm rounded-xl">
                            <div class="card-body">
                                <div class="overflow-x-auto">
                                    <table class="table w-full table-auto">
                                        <thead>
                                            <tr class="bg-gray-50/70 border-b border-gray-200">
                                                <th class="py-3 px-4 border-b text-left text-gray-800 font-semibold">No</th>
                                                <th class="py-3 px-4 border-b text-left text-gray-800 font-semibold">Jenis Pesanan</th>
                                                <th class="py-3 px-4 border-b text-left text-gray-800 font-semibold">Sumber</th>
                                                <th class="py-3 px-4 border-b text-right text-gray-800 font-semibold">Omset Kotor</th>
                                                <th class="py-3 px-4 border-b text-right text-gray-800 font-semibold">Net Payout</th>
                                                <th class="py-3 px-4 border-b text-center text-gray-800 font-semibold">Tanggal</th>
                                                <th class="py-3 px-4 border-b text-center text-gray-800 font-semibold">Aksi</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @forelse ($pembayarans as $pembayaran)
                                                <tr class="hover:bg-gray-50 transition border-b border-gray-100">
                                                    <td class="py-3 px-4 text-gray-700">{{ $loop->iteration }}</td>
                                                    <td class="py-3 px-4 font-medium text-gray-900">
                                                        <span>{{ $pembayaran->name }}</span>
                                                        @if ($pembayaran->platformPromo)
                                                            <span class="text-[11px] bg-blue-100 text-blue-700 font-semibold px-2 py-0.5 rounded block mt-1 w-fit border border-blue-200">
                                                                🎟️ Voucher: {{ $pembayaran->platformPromo->name }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="py-3 px-4 text-gray-800">
                                                        <div class="flex items-center gap-2">
                                                            <span class="font-semibold">{{ $pembayaran->source->name ?? 'Tidak Diketahui' }}</span>
                                                            @if ($pembayaran->source && $pembayaran->source->is_online)
                                                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200">Online</span>
                                                            @else
                                                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-gray-100 text-gray-700 border border-gray-300">Offline</span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="py-3 px-4 text-right text-gray-600">
                                                        Rp {{ number_format($pembayaran->gross_amount ?: $pembayaran->amount, 0, ',', '.') }}
                                                    </td>
                                                    <td class="py-3 px-4 text-right font-bold text-[#2B7A78]">
                                                        Rp {{ number_format($pembayaran->amount, 0, ',', '.') }}
                                                    </td>
                                                    <td class="py-3 px-4 text-center text-gray-700 text-sm">
                                                        {{ $pembayaran->date ? $pembayaran->date->format('d-m-Y') : '-' }}
                                                    </td>
                                                    <td class="py-3 px-4 text-center">
                                                        <div class="flex items-center justify-center gap-1.5">
                                                            <a href="{{ url('/keuangan/pembayaran/' . $pembayaran->id) }}"
                                                                class="bg-[#468585] text-white px-3 py-1.5 rounded text-xs font-medium hover:bg-[#2B7A78] transition shadow-sm">
                                                                Detail
                                                            </a>
                                                            <form action="{{ url('/keuangan/pembayaran/' . $pembayaran->id) }}"
                                                                method="POST" class="inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="bg-rose-500 text-white px-3 py-1.5 rounded text-xs font-medium hover:bg-rose-600 transition shadow-sm"
                                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus pembayaran ini?')">
                                                                    Hapus
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="py-8 text-center text-gray-500">
                                                        Belum ada data pembayaran.
                                                    </td>
                                                </tr>
                                            @endforelse
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

        function dropdownLaporan() {
            const dropdownLaporanMenu = document.getElementById('dropdownLaporanMenu');
            dropdownLaporanMenu?.classList.toggle('hidden');
        }
        document.addEventListener("click", function(event) {
            const dropdownLaporanMenu = document.getElementById("dropdownLaporanMenu");
            const dropdownLaporanButton = document.getElementById("dropdownLaporanButton");

            if (
                dropdownLaporanMenu && dropdownLaporanButton &&
                !dropdownLaporanMenu.contains(event.target) &&
                !dropdownLaporanButton.contains(event.target)
            ) {
                dropdownLaporanMenu.classList.add("hidden");
            }
        });

        function dropdownKategori() {
            const dropdownKategoriMenu = document.getElementById('dropdownKategoriMenu');
            dropdownKategoriMenu?.classList.toggle('hidden');
        }
        document.addEventListener("click", function(event) {
            const dropdownKategoriMenu = document.getElementById("dropdownKategoriMenu");
            const dropdownKategoriButton = document.getElementById("dropdownKategoriButton");

            if (
                dropdownKategoriMenu && dropdownKategoriButton &&
                !dropdownKategoriMenu.contains(event.target) &&
                !dropdownKategoriButton.contains(event.target)
            ) {
                dropdownKategoriMenu.classList.add("hidden");
            }
        });

        function dropdownPemasukkan() {
            const dropdownPemasukkanMenu = document.getElementById('dropdownPemasukkanMenu');
            dropdownPemasukkanMenu?.classList.toggle('hidden');
        }
        document.addEventListener("click", function(event) {
            const dropdownPemasukkanMenu = document.getElementById("dropdownPemasukkanMenu");
            const dropdownPemasukkanButton = document.getElementById("dropdownPemasukkanButton");

            if (
                dropdownPemasukkanMenu && dropdownPemasukkanButton &&
                !dropdownPemasukkanMenu.contains(event.target) &&
                !dropdownPemasukkanButton.contains(event.target)
            ) {
                dropdownPemasukkanMenu.classList.add("hidden");
            }
        });
    </script>
</x-app-layout>
