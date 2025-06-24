@section('title', 'Pembayaran | Detail')
<x-app-layout>
    <div class="h-screen w-full bg-gray-100 flex overflow-hidden">
        <!-- sidebar -->
        <div class="drawer lg:drawer-open">
            <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />

            <!-- drawer content -->
            <div class="drawer-content flex flex-col h-screen">
                <!-- navbar -->
                @include('layouts.navbar')
                <!-- main content -->
                <div class="py-4 flex-1 bg-[#D1DDD5] overflow-auto">
                    <div class="sticky justify-between items-center mt-12 px-8">
                        <div class="mb-4">
                            <!-- Tombol Kembali -->
                            <button type="button"
                                class="bg-[#2B7A78] text-white font-semibold py-2 px-6 rounded-lg hover:bg-[#205C5D]"
                                onclick="window.location.href='{{ url('/keuangan/pembayaran') }}'">
                                &larr; Kembali
                            </button>
                        </div>
                        <div class="container">
                            <h1 class="text-xl font-semibold text-[#2B7A78] mb-4">Detail Pembayaran</h1>
                            <div class="card text-primary-content bg-white mt-4 w-full">
                                <div class="card-body">
                                    <!-- Informasi Utama Pembayaran -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                        <div>
                                            <h2 class="text-lg font-semibold text-[#2B7A78]">Informasi Transaksi</h2>
                                            <div class="mt-2">
                                                <p class="text-sm text-gray-600">Nomor Transaksi:</p>
                                                <p class="font-medium text-black">{{ $pembayaran->id }}</p>
                                            </div>
                                            <div class="mt-2">
                                                <p class="text-sm text-gray-600">Tanggal:</p>
                                                <p class="font-medium text-black">
                                                    {{ $pembayaran->created_at->format('d F Y H:i') }}
                                                </p>
                                            </div>

                                        </div>
                                        <div>
                                            <h2 class="text-lg font-semibold text-[#2B7A78]">Informasi Pembayaran</h2>
                                            <div class="mt-2">
                                                <p class="text-sm text-gray-600">Tipe Pesanana:</p>
                                                <p class="font-medium text-black">{{ $pembayaran->name }}</p>
                                            </div>
                                            <div class="mt-2">
                                                <p class="text-sm text-gray-600">Sumber:</p>
                                                <p class="font-medium text-black">{{ $pembayaran->source->name }}</p>
                                            </div>

                                        </div>
                                    </div>

                                    <!-- Daftar Item -->
                                    <h2 class="text-lg font-semibold text-[#2B7A78] mb-3">Item Pembayaran</h2>
                                    <div class="overflow-x-auto">
                                        <table
                                            class="min-w-full bg-white rounded-lg overflow-hidden border border-gray-300 shadow">
                                            <thead class="bg-[#2B7A78] text-white">
                                                <tr>
                                                    <th class="py-2 px-4 text-left">Nama Item</th>
                                                    <th class="py-2 px-4 text-right">Harga Satuan</th>
                                                    <th class="py-2 px-4 text-right">Jumlah</th>
                                                    <th class="py-2 px-4 text-right">Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200">
                                                @foreach ($pembayaran->incomeDetails ?? [] as $detail)
                                                    <tr>
                                                        <td class="py-3 px-4 text-black">
                                                            {{ $detail->item->name ?? '-' }}</td>
                                                        <td class="py-3 px-4 text-right text-black">
                                                            Rp
                                                            {{ number_format($detail->item->price ?? 0, 0, ',', '.') }}
                                                        </td>
                                                        <td class="py-3 px-4 text-right text-black">
                                                            {{ $detail->quantity ?? 0 }}</td>
                                                        <td class="py-3 px-4 text-right text-black">
                                                            Rp
                                                            {{ number_format(($detail->item->price ?? 0) * ($detail->quantity ?? 0), 0, ',', '.') }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot class="bg-gray-50">
                                                <tr>
                                                    <td class="py-3 px-4 font-semibold text-right text-black"
                                                        colspan="3">Total:
                                                    </td>
                                                    <td class="py-3 px-4 text-right text-black">
                                                        Rp
                                                        {{ number_format($pembayaran->amount, 0, ',', '.') }}
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>



                                    <!-- Tombol Aksi -->
                                    <div class="flex justify-end gap-4 mt-6">
                                        @if ($pembayaran->status === 'pending')
                                            <form action="{{ route('pembayaran.approve', $pembayaran->id) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="bg-green-500 text-white font-semibold py-2 px-6 rounded-lg hover:bg-green-600">
                                                    Setujui Pembayaran
                                                </button>
                                            </form>
                                            <form action="{{ route('pembayaran.reject', $pembayaran->id) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="bg-red-500 text-white font-semibold py-2 px-6 rounded-lg hover:bg-red-600">
                                                    Tolak Pembayaran
                                                </button>
                                            </form>
                                        @endif


                                    </div>
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

    <script>
        // Fungsi untuk toggle dropdown kategori
        function dropdownKategori() {
            const dropdownKategoriMenu = document.getElementById('dropdownKategoriMenu');
            dropdownKategoriMenu.classList.toggle('hidden');
        }

        // Menutup dropdown ketika klik di luar
        document.addEventListener("click", function(event) {
            const dropdownKategoriMenu = document.getElementById("dropdownKategoriMenu");
            const dropdownKategoriButton = document.getElementById("dropdownKategoriButton");

            if (!dropdownKategoriMenu.contains(event.target) && !dropdownKategoriButton.contains(event.target)) {
                dropdownKategoriMenu.classList.add("hidden");
            }
        });

        // Fungsi untuk toggle dropdown pemasukkan
        function dropdownPemasukkan() {
            const dropdownPemasukkanMenu = document.getElementById('dropdownPemasukkanMenu');
            dropdownPemasukkanMenu.classList.toggle('hidden');
        }

        // Menutup dropdown ketika klik di luar
        document.addEventListener("click", function(event) {
            const dropdownPemasukkanMenu = document.getElementById("dropdownPemasukkanMenu");
            const dropdownPemasukkanButton = document.getElementById("dropdownPemasukkanButton");

            if (!dropdownPemasukkanMenu.contains(event.target) && !dropdownPemasukkanButton.contains(event
                    .target)) {
                dropdownPemasukkanMenu.classList.add("hidden");
            }
        });
    </script>
</x-app-layout>
