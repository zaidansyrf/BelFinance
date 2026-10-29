{{-- Navbar tetap di atas; halaman kasir memberi padding atas pt-16 --}}
<header class="fixed top-0 left-0 right-0 z-50 bg-white shadow-md border-b border-gray-200">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between gap-2 sm:gap-4">
                <!-- Logo -->
                <a href="{{ route('kasir.pos') }}" class="flex items-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="#468585" class="h-8 w-8 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span class="hidden sm:inline text-xl font-bold text-[#468585]">BelFinance</span>
                    <span class="ml-1 sm:ml-2 text-xs font-semibold uppercase tracking-wide text-white bg-[#DB5461] px-2 py-0.5 rounded">Kasir</span>
                </a>

                <!-- Navigasi -->
                <nav class="flex items-center space-x-1 bg-white p-1 rounded-lg border border-gray-200">
                    <a href="{{ route('kasir.pos') }}"
                        class="whitespace-nowrap px-3 sm:px-4 py-2 text-sm font-medium rounded-md transition-colors duration-200
                        {{ request()->routeIs('kasir.pos') ? 'text-white bg-[#468585]' : 'text-gray-700 hover:text-[#468585] hover:bg-gray-50' }}">
                        Kasir
                    </a>
                    <a href="{{ route('kasir.beranda') }}"
                        class="whitespace-nowrap px-3 sm:px-4 py-2 text-sm font-medium rounded-md transition-colors duration-200
                        {{ request()->routeIs('kasir.beranda') ? 'text-white bg-[#468585]' : 'text-gray-700 hover:text-[#468585] hover:bg-gray-50' }}">
                        Hari Ini
                    </a>
                    <a href="{{ route('kasir.shift') }}"
                        class="whitespace-nowrap px-3 sm:px-4 py-2 text-sm font-medium rounded-md transition-colors duration-200
                        {{ request()->routeIs('kasir.shift') ? 'text-white bg-[#468585]' : 'text-gray-700 hover:text-[#468585] hover:bg-gray-50' }}">
                        Shift
                    </a>
                </nav>

                <!-- Profil -->
                <div class="relative flex items-center" x-data="{ open: false }" @click.outside="open = false">
                    <span class="hidden sm:block mr-3 text-sm text-gray-600">{{ auth()->user()->name }}</span>
                    <button type="button" @click="open = !open"
                        class="relative flex rounded-full bg-gray-100 p-1 text-gray-400 hover:text-[#468585] focus:outline-none focus:ring-2 focus:ring-[#468585] focus:ring-offset-2"
                        aria-haspopup="true" :aria-expanded="open">
                        <span class="sr-only">Menu akun</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                    </button>
                    <div x-show="open" x-cloak
                        class="absolute right-0 top-full mt-3 w-48 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-50 py-2">
                        <a href="{{ route('info-profile') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil</a>
                        <div class="border-t border-gray-100 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left px-4 py-2 text-sm text-red-500 hover:text-red-700 hover:bg-gray-100">
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>
