<div class="max-w-6xl mx-auto">
    <h1 class="text-xl font-semibold text-[#2B7A78] mb-4">Riwayat Shift Kasir</h1>

    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-left text-gray-700">
                <tr>
                    <th class="py-2 px-4">Kasir</th>
                    <th class="py-2 px-4">Buka</th>
                    <th class="py-2 px-4">Tutup</th>
                    <th class="py-2 px-4 text-right">Modal</th>
                    <th class="py-2 px-4 text-right">Penjualan</th>
                    <th class="py-2 px-4 text-right">Kas diharapkan</th>
                    <th class="py-2 px-4 text-right">Kas dihitung</th>
                    <th class="py-2 px-4 text-right">Selisih</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($shifts as $s)
                    <tr class="border-t">
                        <td class="py-2 px-4">
                            {{ $s->user->name }}
                            @if ($s->note)
                                <p class="text-xs text-gray-500">{{ $s->note }}</p>
                            @endif
                        </td>
                        <td class="py-2 px-4 whitespace-nowrap">{{ $s->opened_at->format('d/m/Y H:i') }}</td>
                        <td class="py-2 px-4 whitespace-nowrap">
                            {{ $s->closed_at?->format('d/m/Y H:i') }}
                            @unless ($s->closed_at)
                                <span class="text-xs font-semibold text-white bg-[#468585] px-2 py-0.5 rounded">Berjalan</span>
                            @endunless
                        </td>
                        <td class="py-2 px-4 text-right">{{ $rp($s->opening_cash) }}</td>
                        <td class="py-2 px-4 text-right">{{ $rp($s->total_penjualan ?? 0) }}</td>
                        <td class="py-2 px-4 text-right">{{ $s->closed_at ? $rp($s->expected_cash) : '-' }}</td>
                        <td class="py-2 px-4 text-right">{{ $s->closed_at ? $rp($s->closing_cash) : '-' }}</td>
                        <td class="py-2 px-4 text-right font-semibold {{ $s->difference === null ? '' : ($s->difference === 0 ? 'text-green-700' : 'text-red-600') }}">
                            @if ($s->difference === null)
                                -
                            @elseif ($s->difference === 0)
                                Pas
                            @else
                                {{ $s->difference > 0 ? '+' : '-' }}{{ $rp(abs($s->difference)) }}
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="py-8 text-center text-gray-500">Belum ada shift.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $shifts->links() }}</div>
</div>
