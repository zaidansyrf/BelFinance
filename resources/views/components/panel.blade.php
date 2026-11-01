@props(['plain' => false])

{{-- Kerangka halaman dengan sidebar untuk semua role (owner, keuangan); isi sidebar menyesuaikan role --}}
<div class="h-screen w-full bg-gray-100 flex overflow-hidden">
    <div class="drawer lg:drawer-open">
        <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />
        <div class="drawer-content flex flex-col h-screen">
            @include('layouts.navbar')
            <div class="flex-1 bg-[#D1DDD5] overflow-auto {{ $plain ? '' : 'px-4 sm:px-8 pt-8 pb-8' }}">
                {{ $slot }}
            </div>
        </div>
        @include('layouts.sidebar')
    </div>
</div>
