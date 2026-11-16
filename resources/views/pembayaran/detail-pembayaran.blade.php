@section('title', 'Pembayaran | Detail Transaksi')
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
                    <div class="mt-8 px-6 pb-12">
                        <div class="mb-4">
                            <button type="button"
                                class="bg-[#2B7A78] hover:bg-[#205C5D] text-white font-semibold py-2 px-6 rounded-lg transition"
                                onclick="window.location.href='{{ url('/keuangan/pembayaran') }}'">
                                &larr; Kembali
                            </button>
                        </div>

                        <div class="container mx-auto">
                            <h1 class="text-2xl font-bold text-[#2B7A78] mb-4">Detail Transaksi #{{ $pembayaran->id }}</h1>

                            <div class="card text-primary-content bg-white w-full rounded-xl shadow-md overflow-hidden">
                                <div class="card-body p-6">
                                    <!-- Informasi Utama Transaksi -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-6 border-b border-gray-100">
                                        <div>
                                            <h2 class="text-base font-bold text-[#2B7A78] uppercase tracking-wide">Informasi Umum</h2>
                                            <div class="mt-3 space-y-2 text-sm text-gray-700">
                                                <div>
                                                    <span class="text-gray-500 block text-xs">Nomor Transaksi:</span>
                                                    <span class="font-bold text-gray-900">#{{ $pembayaran->id }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-gray-500 block text-xs">Tanggal Transaksi:</span>
                                                    <span class="font-medium text-gray-900">
                                                        {{ $pembayaran->date ? $pembayaran->date->format('d F Y') : $pembayaran->created_at->format('d F Y H:i') }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <span class="text-gray-500 block text-xs">Dicatat Oleh:</span>
                                                    <span class="font-medium text-gray-900">
                                                        {{ $pembayaran->creator->name ?? '-' }}
                                                        @if ($pembayaran->creator)
                                                            <span class="text-xs text-gray-500">({{ $pembayaran->creator->role }})</span>
                                                        @endif
                                                    </span>
                                                </div>
                                                @if ($pembayaran->description)
                                                    <div>
                                                        <span class="text-gray-500 block text-xs">Catatan Transaksi:</span>
                                                        <span class="text-gray-800">{{ $pembayaran->description }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div>
                                            <h2 class="text-base font-bold text-[#2B7A78] uppercase tracking-wide">Saluran & Pembayaran</h2>
                                            <div class="mt-3 space-y-2 text-sm text-gray-700">
                                                <div>
                                                    <span class="text-gray-500 block text-xs">Jenis Pesanan:</span>
                                                    <span class="font-medium text-gray-900">{{ $pembayaran->name }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-gray-500 block text-xs">Sumber Pembayaran / Saluran:</span>
                                                    <span class="font-semibold text-gray-900">{{ $pembayaran->source->name ?? 'Tidak Diketahui' }}</span>
                                                    @if ($pembayaran->source && $pembayaran->source->is_online)
                                                        <span class="ml-1 px-1.5 py-0.5 text-[10px] font-semibold rounded bg-blue-50 text-blue-700 border border-blue-200">Online</span>
                                                    @else
                                                        <span class="px-2.5 py-0.5 text-[10px] font-semibold rounded bg-gray-100 text-gray-700 border border-gray-300">Offline</span>
                                                    @endif
                                                </div>
                                                @if ($pembayaran->paid_amount !== null)
                                                    <div>
                                                        <span class="text-gray-500 block text-xs">Uang Diterima / Kembalian:</span>
                                                        <span class="font-medium text-gray-900">
                                                            Rp {{ number_format($pembayaran->paid_amount, 0, ',', '.') }}
                                                            @if ($pembayaran->change_amount)
                                                                / (Kembalian: Rp {{ number_format($pembayaran->change_amount, 0, ',', '.') }})
                                                            @endif
                                                        </span>
                                                    </div>
                                                @endif
                                                <div class="pt-2">
                                                    <a href="{{ route('kasir.struk', $pembayaran) }}" target="_blank"
                                                        class="inline-flex items-center gap-1.5 bg-[#468585] hover:bg-[#376e6e] text-white text-xs font-semibold py-1.5 px-3 rounded-lg transition shadow-sm">
                                                        <span>🖨️ Cetak Struk Penjualan</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Rekapitulasi Finansial & Snapshot Akuntansi -->
                                    <div class="mt-6 mb-8">
                                        <h2 class="text-base font-bold text-[#2B7A78] uppercase tracking-wide mb-3">
                                            Rincian Beban Promo, Komisi & Pencairan Bersih
                                        </h2>

                                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                            <div class="border-b md:border-b-0 md:border-r border-gray-200 pb-3 md:pb-0 pr-2">
                                                <span class="text-xs text-gray-500 font-medium block">1. Total Omset Kotor (Normal)</span>
                                                <span class="text-lg font-bold text-gray-900 block mt-0.5">
                                                    Rp {{ number_format($pembayaran->gross_amount ?: $pembayaran->amount, 0, ',', '.') }}
                                                </span>
                                                <span class="text-[11px] text-gray-400">Total harga normal menu</span>
                                            </div>

                                            <div class="border-b md:border-b-0 md:border-r border-gray-200 pb-3 md:pb-0 pr-2">
                                                <span class="text-xs text-amber-700 font-medium block">2. Diskon Menu Merchant</span>
                                                <span class="text-lg font-bold text-amber-700 block mt-0.5">
                                                    - Rp {{ number_format($pembayaran->item_discount_total, 0, ',', '.') }}
                                                </span>
                                                <span class="text-[11px] text-gray-500">
                                                    Subtotal: Rp {{ number_format($pembayaran->subtotalAfterItemPromo(), 0, ',', '.') }}
                                                </span>
                                            </div>

                                            <div class="border-b lg:border-b-0 lg:border-r border-gray-200 pb-3 lg:pb-0 pr-2">
                                                <span class="text-xs text-blue-700 font-medium block">3. Voucher Aplikasi Platform</span>
                                                @if ($pembayaran->platform_promo_total > 0)
                                                    <span class="text-lg font-bold text-blue-700 block mt-0.5">
                                                        Rp {{ number_format($pembayaran->platform_promo_total, 0, ',', '.') }}
                                                    </span>
                                                    <div class="text-[11px] space-y-0.5 mt-0.5">
                                                        <span class="text-orange-700 block">
                                                            • Beban Merchant: - Rp {{ number_format($pembayaran->platform_merchant_share, 0, ',', '.') }}
                                                        </span>
                                                        <span class="text-green-700 block">
                                                            • Subsidi Aplikasi: Rp {{ number_format($pembayaran->platform_app_subsidy, 0, ',', '.') }}
                                                        </span>
                                                    </div>
                                                @else
                                                    <span class="text-sm font-semibold text-gray-400 block mt-1">Tanpa Voucher</span>
                                                @endif
                                            </div>

                                            <div class="bg-teal-100/60 p-3 rounded-lg border border-teal-200">
                                                <span class="text-xs text-[#2B7A78] font-bold block uppercase tracking-wider">
                                                    Net Payout (Uang Bersih)
                                                </span>
                                                <span class="text-xl font-black text-[#2B7A78] block mt-0.5">
                                                    Rp {{ number_format($pembayaran->amount, 0, ',', '.') }}
                                                </span>
                                                @if ($pembayaran->commission_amount > 0)
                                                    <span class="text-[11px] text-red-600 block mt-1">
                                                        Potong Komisi {{ $pembayaran->commission_percent }}%: - Rp {{ number_format($pembayaran->commission_amount, 0, ',', '.') }}
                                                    </span>
                                                @endif
                                                @if ($pembayaran->customer_paid_amount !== null)
                                                    <span class="text-[11px] text-purple-700 block mt-0.5">
                                                        Dibayar Pembeli di App: Rp {{ number_format($pembayaran->customer_paid_amount, 0, ',', '.') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Daftar Baris Item Transaksi -->
                                    <h2 class="text-base font-bold text-[#2B7A78] uppercase tracking-wide mb-3">Rincian Menu Pesanan</h2>
                                    <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-sm">
                                        <table class="min-w-full bg-white divide-y divide-gray-200 text-sm">
                                            <thead class="bg-[#2B7A78] text-white text-xs uppercase">
                                                <tr>
                                                    <th class="py-3 px-4 text-left">Nama Menu</th>
                                                    <th class="py-3 px-4 text-right">Harga Normal</th>
                                                    <th class="py-3 px-4 text-center">Qty</th>
                                                    <th class="py-3 px-4 text-right">Potongan Menu / Porsi</th>
                                                    <th class="py-3 px-4 text-right">Harga Akhir Satuan</th>
                                                    <th class="py-3 px-4 text-right">Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-100 text-gray-700">
                                                @foreach ($pembayaran->incomeDetails ?? [] as $detail)
                                                    @php
                                                        $orig = $detail->original_price ?: ($detail->item->price ?? 0);
                                                        $disc = $detail->discount_amount ?: 0;
                                                        $final = $detail->final_price ?: max(0, $orig - $disc);
                                                    @endphp
                                                    <tr class="hover:bg-gray-50 transition">
                                                        <td class="py-3 px-4 font-semibold text-gray-900">
                                                            {{ $detail->item->name ?? '-' }}
                                                            @if ($detail->promo && $detail->promo->channel_scope === 'online_only')
                                                                <span class="text-xs bg-purple-100 text-purple-800 px-2 py-0.5 rounded block mt-0.5 w-fit font-normal">
                                                                    Promo: {{ $detail->promo->name }}
                                                                </span>
                                                            @elseif ($detail->promo && $detail->promo->channel_scope === 'offline_only')
                                                                <span class="text-xs bg-orange-100 text-orange-800 px-2 py-0.5 rounded block mt-0.5 w-fit font-normal">
                                                                    Promo: {{ $detail->promo->name }}
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td class="py-3 px-4 text-right text-gray-600">
                                                            Rp {{ number_format($orig, 0, ',', '.') }}
                                                        </td>
                                                        <td class="py-3 px-4 text-center font-bold text-gray-900">
                                                            {{ $detail->quantity }}
                                                        </td>
                                                        <td class="py-3 px-4 text-right">
                                                            @if ($disc > 0)
                                                                <span class="text-amber-700 font-semibold">
                                                                    - Rp {{ number_format($disc, 0, ',', '.') }}
                                                                </span>
                                                            @else
                                                                <span class="text-gray-400">-</span>
                                                            @endif
                                                        </td>
                                                        <td class="py-3 px-4 text-right font-medium text-gray-800">
                                                            Rp {{ number_format($final, 0, ',', '.') }}
                                                        </td>
                                                        <td class="py-3 px-4 text-right font-bold text-gray-900">
                                                            Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot class="bg-gray-50 border-t border-gray-200 font-semibold text-gray-800">
                                                <tr>
                                                    <td colspan="5" class="py-3 px-4 text-right text-gray-600">Subtotal Kotor Normal:</td>
                                                    <td class="py-3 px-4 text-right text-gray-900">
                                                        Rp {{ number_format($pembayaran->gross_amount ?: $pembayaran->amount, 0, ',', '.') }}
                                                    </td>
                                                </tr>
                                                @if ($pembayaran->item_discount_total > 0)
                                                    <tr>
                                                        <td colspan="5" class="py-2 px-4 text-right text-amber-700">Total Diskon Menu Merchant:</td>
                                                        <td class="py-2 px-4 text-right text-amber-700">
                                                            - Rp {{ number_format($pembayaran->item_discount_total, 0, ',', '.') }}
                                                        </td>
                                                    </tr>
                                                @endif
                                                @if ($pembayaran->platform_merchant_share > 0)
                                                    <tr>
                                                        <td colspan="5" class="py-2 px-4 text-right text-orange-700">Beban Patungan Voucher Aplikasi (Merchant):</td>
                                                        <td class="py-2 px-4 text-right text-orange-700">
                                                            - Rp {{ number_format($pembayaran->platform_merchant_share, 0, ',', '.') }}
                                                        </td>
                                                    </tr>
                                                @endif
                                                @if ($pembayaran->commission_amount > 0)
                                                    <tr>
                                                        <td colspan="5" class="py-2 px-4 text-right text-red-600">Potongan Komisi Platform ({{ $pembayaran->commission_percent }}%):</td>
                                                        <td class="py-2 px-4 text-right text-red-600">
                                                            - Rp {{ number_format($pembayaran->commission_amount, 0, ',', '.') }}
                                                        </td>
                                                    </tr>
                                                @endif
                                                <tr class="bg-teal-50 text-base">
                                                    <td colspan="5" class="py-3 px-4 text-right text-[#2B7A78] font-bold">
                                                        Uang Bersih Diterima (Net Payout):
                                                    </td>
                                                    <td class="py-3 px-4 text-right text-[#2B7A78] font-black text-lg">
                                                        Rp {{ number_format($pembayaran->amount, 0, ',', '.') }}
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('layouts.sidebar')
        </div>
    </div>
</x-app-layout>
