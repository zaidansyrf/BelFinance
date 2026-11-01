@section('title', 'Riwayat Shift')
<x-app-layout>
    @php $rp = fn ($n) => 'Rp '.number_format($n, 0, ',', '.'); @endphp

    <x-panel>
        @include('shift._tabel')
    </x-panel>
</x-app-layout>
