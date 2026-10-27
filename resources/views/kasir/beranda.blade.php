@section('title', 'Kasir | Hari Ini')
<x-app-layout>
    @include('layouts.navbar-kasir')

    <div class="pt-16 min-h-screen bg-[#D1DDD5]">
        <div class="max-w-6xl mx-auto p-4 sm:p-6 space-y-6">
            <div>
                <h1 class="text-xl font-semibold text-[#2B7A78]">Penjualan Hari Ini</h1>
                <p class="text-sm text-gray-600">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</p>
            </div>

            {{-- Ringkasan seluruh kasir --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-[#2B7A78] text-white rounded-xl p-5">
                    <p class="text-sm opacity-90">Total penjualan</p>
                    <p class="text-2xl font-bold mt-1">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</p>
                </div>
                <div class="bg-[#3AAFA9] text-white rounded-xl p-5">
                    <p class="text-sm opacity-90">Jumlah transaksi</p>
                    <p class="text-2xl font-bold mt-1">{{ $jumlahTransaksi }}</p>
                </div>
                <div class="bg-white rounded-xl p-5">
                    <p class="text-sm text-gray-600">Rata-rata per transaksi</p>
                    <p class="text-2xl font-bold mt-1 text-gray-800">
                        Rp {{ number_format($jumlahTransaksi ? intdiv($totalPenjualan, $jumlahTransaksi) : 0, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl p-5">
                    <h2 class="font-semibold text-gray-800 mb-3">Per metode pembayaran</h2>
                    <table class="w-full text-sm">
                        <thead class="text-gray-500 text-left">
                            <tr><th class="py-1">Metode</th><th class="py-1 text-right">Transaksi</th><th class="py-1 text-right">Total</th></tr>
                        </thead>
                        <tbody>
                            @forelse ($perSumber as $nama => $row)
                                <tr class="border-t">
                                    <td class="py-2">{{ $nama }}</td>
                                    <td class="py-2 text-right">{{ $row['jumlah'] }}</td>
                                    <td class="py-2 text-right font-medium">Rp {{ number_format($row['total'], 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="py-4 text-center text-gray-400">Belum ada penjualan hari ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="bg-white rounded-xl p-5">
                    <h2 class="font-semibold text-gray-800 mb-3">Menu terlaris hari ini</h2>
                    <table class="w-full text-sm">
                        <thead class="text-gray-500 text-left">
                            <tr><th class="py-1">Menu</th><th class="py-1 text-right">Porsi</th><th class="py-1 text-right">Total</th></tr>
                        </thead>
                        <tbody>
                            @forelse ($menuTerlaris as $menu)
                                <tr class="border-t">
                                    <td class="py-2">{{ $menu->name }}</td>
                                    <td class="py-2 text-right">{{ $menu->jumlah }}</td>
                                    <td class="py-2 text-right font-medium">Rp {{ number_format($menu->total, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="py-4 text-center text-gray-400">Belum ada menu terjual.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Transaksi milik kasir yang sedang login (untuk setor kas) --}}
            <div class="bg-white rounded-xl p-5">
                <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-2 mb-3">
                    <h2 class="font-semibold text-gray-800">Transaksi saya hari ini</h2>
                    <div class="text-sm text-gray-600 flex gap-4">
                        <span>Total: <b class="text-gray-800">Rp {{ number_format($totalSaya, 0, ',', '.') }}</b></span>
                        <span>Tunai (setor kas): <b class="text-gray-800">Rp {{ number_format($tunaiSaya, 0, ',', '.') }}</b></span>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="text-gray-500 text-left">
                            <tr>
                                <th class="py-1">Jam</th>
                                <th class="py-1">No</th>
                                <th class="py-1">Jenis</th>
                                <th class="py-1">Metode</th>
                                <th class="py-1 text-right">Total</th>
                                <th class="py-1"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transaksiSaya as $trx)
                                <tr class="border-t">
                                    <td class="py-2">{{ ($trx->created_at ?? $trx->date)->format('H:i') }}</td>
                                    <td class="py-2">#{{ str_pad($trx->id, 6, '0', STR_PAD_LEFT) }}</td>
                                    <td class="py-2">{{ $trx->name }}</td>
                                    <td class="py-2">{{ $trx->source->name ?? '-' }}</td>
                                    <td class="py-2 text-right font-medium">Rp {{ number_format($trx->amount, 0, ',', '.') }}</td>
                                    <td class="py-2 text-right">
                                        <a href="{{ route('kasir.struk', $trx) }}" class="text-[#2B7A78] hover:underline">Struk</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="py-4 text-center text-gray-400">Anda belum membuat transaksi hari ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <p class="mt-3 text-xs text-gray-500">Transaksi yang salah hanya bisa dibatalkan oleh admin keuangan.</p>
            </div>
        </div>
    </div>
</x-app-layout>
