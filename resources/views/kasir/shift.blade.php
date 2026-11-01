@section('title', 'Kasir | Shift')
<x-app-layout>
    @include('layouts.navbar-kasir')

    @php $rp = fn ($n) => 'Rp '.number_format($n, 0, ',', '.'); @endphp

    <div class="pt-16 min-h-screen bg-[#D1DDD5]">
        <div class="max-w-xl mx-auto p-4 sm:p-6 space-y-4">
            <h1 class="text-xl font-semibold text-[#2B7A78]">Shift Kasir</h1>

            @if (session('success'))
                <div class="rounded-lg bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="rounded-lg bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm">{{ session('error') }}</div>
            @endif

            @if (! $shift)
                {{-- Belum ada shift: buka shift --}}
                <form method="POST" action="{{ route('kasir.shift.buka') }}" class="bg-white rounded-xl p-5 space-y-4">
                    @csrf
                    <p class="text-sm text-gray-600">Hitung uang di laci kas, lalu isi sebagai modal awal untuk mulai berjualan.</p>
                    <div>
                        <label for="opening_cash" class="block text-sm font-medium text-gray-700">Modal awal di laci kas</label>
                        <div class="relative mt-1">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                            <input id="opening_cash" type="number" min="0" name="opening_cash" value="{{ old('opening_cash') }}" inputmode="numeric"
                                class="w-full pl-10 pr-3 py-2.5 text-lg font-semibold rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#468585] focus:border-[#468585]">
                        </div>
                        @error('opening_cash')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="w-full py-3 rounded-xl text-white text-lg font-semibold bg-[#2B7A78] hover:bg-[#205C5D]">Buka Shift</button>
                </form>

                @if ($terakhir)
                    <div class="bg-white rounded-xl p-5 text-sm text-gray-700 space-y-1">
                        <p class="font-semibold text-gray-800">Shift terakhir ({{ $terakhir->closed_at->locale('id')->translatedFormat('d F Y H:i') }})</p>
                        <p>Kas diharapkan {{ $rp($terakhir->expected_cash) }}, kas dihitung {{ $rp($terakhir->closing_cash) }}</p>
                        <p class="{{ $terakhir->difference === 0 ? 'text-green-700' : 'text-red-600' }} font-medium">
                            @if ($terakhir->difference === 0)
                                Kas pas
                            @else
                                {{ $terakhir->difference > 0 ? 'Lebih' : 'Kurang' }} {{ $rp(abs($terakhir->difference)) }}
                            @endif
                        </p>
                    </div>
                @endif
            @else
                {{-- Shift berjalan: ringkasan dan tutup shift --}}
                <div class="bg-white rounded-xl p-5 space-y-2 text-sm">
                    <p class="text-gray-500">Dibuka {{ $shift->opened_at->locale('id')->translatedFormat('l, d F Y H:i') }}</p>
                    <div class="flex justify-between"><span class="text-gray-600">Modal awal</span><span class="font-semibold">{{ $rp($shift->opening_cash) }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-600">Total penjualan</span><span class="font-semibold">{{ $rp($penjualan) }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-600">Penjualan tunai</span><span class="font-semibold">{{ $rp($tunai) }}</span></div>
                    <div class="flex justify-between border-t pt-2"><span class="text-gray-800 font-medium">Kas seharusnya di laci</span><span class="font-bold text-[#2B7A78]">{{ $rp($kasDiharapkan) }}</span></div>
                </div>

                <form method="POST" action="{{ route('kasir.shift.tutup') }}" class="bg-white rounded-xl p-5 space-y-4"
                    onsubmit="return confirm('Tutup shift sekarang? Setelah ini kamu perlu membuka shift baru untuk berjualan.')">
                    @csrf
                    <div>
                        <label for="closing_cash" class="block text-sm font-medium text-gray-700">Uang di laci kas (hasil hitung)</label>
                        <div class="relative mt-1">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                            <input id="closing_cash" type="number" min="0" name="closing_cash" value="{{ old('closing_cash') }}" inputmode="numeric"
                                class="w-full pl-10 pr-3 py-2.5 text-lg font-semibold rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#468585] focus:border-[#468585]">
                        </div>
                        @error('closing_cash')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="note" class="block text-sm font-medium text-gray-700">Catatan <span class="font-normal text-gray-500">(opsional)</span></label>
                        <input id="note" type="text" name="note" value="{{ old('note') }}" maxlength="255"
                            class="mt-1 w-full px-3 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#468585] focus:border-[#468585]">
                    </div>
                    <button type="submit" class="w-full py-3 rounded-xl text-white text-lg font-semibold bg-[#DB5461] hover:bg-[#c1424f]">Tutup Shift</button>
                </form>

                <a href="{{ route('kasir.pos') }}" class="block text-center text-sm text-[#2B7A78] hover:underline">Kembali ke kasir</a>
            @endif
        </div>
    </div>
</x-app-layout>
