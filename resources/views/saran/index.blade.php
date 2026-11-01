@section('title', 'Saran Usaha')
<x-app-layout>
    <x-panel>
        <div class="max-w-3xl mx-auto">
            <h1 class="text-xl font-semibold text-[#2B7A78]">Saran Usaha</h1>
            <p class="text-sm text-gray-600 mb-4">
                Dihitung dari data penjualan {{ $periode }} ({{ $transaksi }} transaksi) dan dibandingkan dengan periode sebelumnya.
            </p>
            @include('saran._ai')
            <h2 class="font-semibold text-gray-800 mt-6 mb-2">Temuan dari data</h2>
            @include('saran._daftar')
        </div>
    </x-panel>
</x-app-layout>
