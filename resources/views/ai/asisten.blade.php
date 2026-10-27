@section('title', 'Tanya AI')
<x-app-layout>
    @if (auth()->user()->isOwner())
        @include('layouts.navbar-owner')

        <div class="bg-[#D1DDD5] min-h-screen pt-28 px-4 sm:px-8 pb-8">
            <div class="max-w-4xl mx-auto">
                @include('ai._chat')
            </div>
        </div>
    @else
        <div class="h-screen w-full bg-gray-100 flex overflow-hidden">
            <div class="drawer lg:drawer-open">
                <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />
                <div class="drawer-content flex flex-col h-screen">
                    @include('layouts.navbar')
                    <div class="flex-1 bg-[#D1DDD5] overflow-auto px-4 sm:px-8 pt-12 pb-8">
                        <div class="max-w-4xl mx-auto">
                            @include('ai._chat')
                        </div>
                    </div>
                </div>
                @include('layouts.sidebar')
            </div>
        </div>
    @endif
</x-app-layout>
