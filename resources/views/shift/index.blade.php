@section('title', 'Riwayat Shift')
<x-app-layout>
    @php $rp = fn ($n) => 'Rp '.number_format($n, 0, ',', '.'); @endphp

    @if (auth()->user()->isOwner())
        @include('layouts.navbar-owner')
        <div class="bg-[#D1DDD5] min-h-screen pt-28 px-4 sm:px-8 pb-8">
            @include('shift._tabel')
        </div>
    @else
        <div class="h-screen w-full bg-gray-100 flex overflow-hidden">
            <div class="drawer lg:drawer-open">
                <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />
                <div class="drawer-content flex flex-col h-screen">
                    @include('layouts.navbar')
                    <div class="flex-1 bg-[#D1DDD5] overflow-auto px-4 sm:px-8 pt-12 pb-8">
                        @include('shift._tabel')
                    </div>
                </div>
                @include('layouts.sidebar')
            </div>
        </div>
    @endif
</x-app-layout>
