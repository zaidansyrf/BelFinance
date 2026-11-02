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
                                                <p class="text-sm text-gray-600">Tanggal Transaksi:</p>
                                                <p class="font-medium text-black">
                                                    {{ $pembayaran->date ? $pembayaran->date->format('d F Y') : $pembayaran->created_at->format('d F Y H:i') }}
                                                </p>
                                            </div>
                                            <div class="mt-2">
                                                <p class="text-sm text-gray-600">Dicatat oleh:</p>
                                                <p class="font-medium text-black">
                                                    {{ $pembayaran->creator->name ?? '-' }}
                                                    @if ($pembayaran->creator)
                                                        <span class="text-xs text-gray-500">({{ $pembayaran->creator->role }})</span>
                                                    @endif
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
                                            @if ($pembayaran->order_source)
                                                <div class="mt-2">
                                                    <p class="text-sm text-gray-600">Platform Pesanan:</p>
                                                    <p class="font-medium text-black">{{ $pembayaran->order_source }}</p>
                                                </div>
                                            @endif
                                            @if ($pembayaran->paid_amount !== null)
                                                <div class="mt-2">
                                                    <p class="text-sm text-gray-600">Uang diterima / kembalian:</p>
                                                    <p class="font-medium text-black">
                                                        Rp {{ number_format($pembayaran->paid_amount, 0, ',', '.') }} /
                                                        Rp {{ number_format($pembayaran->change_amount, 0, ',', '.') }}
                                                    </p>
                                                </div>
                                            @endif

                                            {{-- Ringkasan Promo --}}
                                            @if ($pembayaran->gross_total && $pembayaran->gross_total != $pembayaran->net_total)
                                                <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-3">
                                                    <p class="text-sm font-semibold text-blue-800 mb-2">🏷️ Rincian Promo Aplikasi</p>
                                                    <div class="flex justify-between text-sm text-gray-700">
                                                        <span>Gross Total:</span>
                                                        <span>Rp {{ number_format($pembayaran->gross_total, 0, ',', '.') }}</span>
                                                    </div>
                                                    <div class="flex justify-between text-sm text-red-500 mt-1">
                                                        <span>
                                                            Promo Aplikasi
                                                            @if ($pembayaran->app_discount_type === 'percent')
                                                                ({{ $pembayaran->app_discount_value }}%):
                                                            @else
                                                                (Nominal):
                                                            @endif
                                                        </span>
                                                        <span>- Rp {{ number_format($pembayaran->appDiscountAmount(), 0, ',', '.') }}</span>
                                                    </div>
                                                    <div class="flex justify-between font-semibold text-gray-800 border-t mt-2 pt-2">
                                                        <span>Net Total (tagihan):</span>
                                                        <span>Rp {{ number_format($pembayaran->net_total, 0, ',', '.') }}</span>
                                                    </div>
                                                    <div class="mt-2">
                                                        @if ($pembayaran->is_app_subsidy)
                                                            <span class="inline-block bg-green-100 text-green-700 text-xs font-semibold px-2 py-1 rounded">
                                                                ✅ Subsidi Aplikasi — Piutang Rp {{ number_format($pembayaran->piutangAplikasi(), 0, ',', '.') }}
                                                            </span>
                                                        @else
                                                            <span class="inline-block bg-orange-100 text-orange-700 text-xs font-semibold px-2 py-1 rounded">
                                                                ⚠️ Beban Merchant — Rp {{ number_format($pembayaran->bebanPromosi(), 0, ',', '.') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="mt-4">
                                                <a href="{{ route('kasir.struk', $pembayaran) }}" target="_blank"
                                                    class="inline-block bg-[#468585] text-white text-sm font-semibold py-2 px-4 rounded-lg hover:bg-[#376e6e]">
                                                    Cetak struk
                                                </a>
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
                                                    <th class="py-2 px-4 text-right">Diskon Item</th>
                                                    <th class="py-2 px-4 text-right">Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200">
                                                @foreach ($pembayaran->incomeDetails ?? [] as $detail)
                                                    <tr>
                                                        <td class="py-3 px-4 text-black">
                                                            {{ $detail->item->name ?? '-' }}</td>
                                                        {{-- Harga saat transaksi (dari subtotal tersimpan), bukan harga menu sekarang --}}
                                                        <td class="py-3 px-4 text-right text-black">
                                                            Rp
                                                            {{ number_format($detail->item->price ?? 0, 0, ',', '.') }}
                                                        </td>
                                                        <td class="py-3 px-4 text-right text-black">
                                                            {{ $detail->quantity ?? 0 }}</td>
                                                        <td class="py-3 px-4 text-right text-sm">
                                                            @if ($detail->item_discount_type)
                                                                <span class="text-red-500">
                                                                    @if ($detail->item_discount_type === 'percent')
                                                                        {{ $detail->item_discount_value }}%
                                                                    @else
                                                                        Rp {{ number_format($detail->item_discount_value, 0, ',', '.') }}
                                                                    @endif
                                                                </span>
                                                            @else
                                                                <span class="text-gray-400">-</span>
                                                            @endif
                                                        </td>
                                                        <td class="py-3 px-4 text-right text-black">
                                                            Rp
                                                            {{ number_format($detail->subtotal, 0, ',', '.') }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot class="bg-gray-50">
                                                <tr>
                                                    <td class="py-3 px-4 font-semibold text-right text-black"
                                                        colspan="4">
                                                        @if ($pembayaran->gross_total && $pembayaran->gross_total != $pembayaran->net_total)
                                                            Gross Total:
                                                        @else
                                                            Total:
                                                        @endif
                                                    </td>
                                                    <td class="py-3 px-4 text-right text-black">
                                                        Rp
                                                        {{ number_format($pembayaran->gross_total ?? $pembayaran->amount, 0, ',', '.') }}
                                                    </td>
                                                </tr>
                                                @if ($pembayaran->net_total && $pembayaran->net_total != $pembayaran->gross_total)
                                                    <tr>
                                                        <td class="py-3 px-4 font-semibold text-right text-green-700"
                                                            colspan="4">Net Total (Tagihan):
                                                        </td>
                                                        <td class="py-3 px-4 text-right font-semibold text-green-700">
                                                            Rp
                                                            {{ number_format($pembayaran->net_total, 0, ',', '.') }}
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tfoot>
                                        </table>
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
