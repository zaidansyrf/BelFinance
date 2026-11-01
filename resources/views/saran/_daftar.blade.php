@php
    $gaya = [
        'peringatan' => ['border-red-300 bg-red-50', 'bg-red-500', 'Perlu diperhatikan'],
        'peluang' => ['border-amber-300 bg-amber-50', 'bg-amber-500', 'Peluang'],
        'info' => ['border-teal-200 bg-teal-50', 'bg-[#2B7A78]', 'Info'],
    ];
@endphp

@if (! $cukup_data)
    <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 text-sm text-gray-600">
        Data penjualan masih sedikit ({{ $transaksi }} transaksi dalam {{ \App\Services\SaranUsahaService::PERIODE_HARI }} hari terakhir).
        Saran akan muncul setelah ada minimal {{ \App\Services\SaranUsahaService::MIN_TRANSAKSI }} transaksi, supaya tidak menyesatkan.
    </div>
@endif

<div class="space-y-3">
    @foreach ($saran->take($batas ?? 100) as $s)
        <div class="rounded-lg border p-4 {{ $gaya[$s['tipe']][0] }}">
            <div class="flex items-start gap-3">
                <span class="mt-0.5 shrink-0 rounded px-2 py-0.5 text-xs font-semibold text-white {{ $gaya[$s['tipe']][1] }}">{{ $gaya[$s['tipe']][2] }}</span>
                <div>
                    <p class="font-semibold text-gray-800">{{ $s['judul'] }}</p>
                    <p class="mt-1 text-sm text-gray-600">{{ $s['detail'] }}</p>
                </div>
            </div>
        </div>
    @endforeach

    @if ($cukup_data && $saran->isEmpty())
        <p class="text-sm text-gray-600">Belum ada saran khusus. Penjualan terlihat stabil.</p>
    @endif
</div>
