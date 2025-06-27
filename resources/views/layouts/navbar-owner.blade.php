<x-slot name="header">
    <!-- Fixed header with white background and shadow -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-white shadow-md border-b border-gray-200">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex h-24 items-center justify-between">
                <!-- Logo section -->
                <div class="flex items-center">
                    <a href="/owner/beranda" class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="#468585" class="h-8 w-8 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <span class="text-xl font-bold text-[#468585]">BelFinance</span>
                    </a>
                </div>

                <!-- dropdown desktop -->
                <nav class="hidden md:flex items-center space-x-1 bg-white p-1 rounded-lg border border-gray-200">
                    <a href="{{ route('owner-beranda') }}"
                        class="px-4 py-2 text-sm font-medium rounded-md transition-colors duration-200
                        {{ request()->routeIs('owner-beranda') ? 'text-white bg-[#468585]' : 'text-gray-700 hover:text-[#468585] hover:bg-gray-50' }}">
                        Beranda
                    </a>
                    <a href="{{ route('laporan-owner') }}"
                        class="px-4 py-2 text-sm font-medium rounded-md transition-colors duration-200
                        {{ request()->routeIs('laporan-owner') ? 'text-white bg-[#468585]' : 'text-gray-700 hover:text-[#468585] hover:bg-gray-50' }}">
                        Laporan Keuangan
                    </a>
                </nav>

                <!-- Profile -->
                <div class="relative flex items-center">
                    <button type="button" id="profile-menu-button"
                        class="relative flex rounded-full bg-gray-100 p-1 text-gray-400 hover:text-[#468585] focus:outline-none focus:ring-2 focus:ring-[#468585] focus:ring-offset-2 transition-colors duration-200"
                        aria-expanded="false" aria-haspopup="true" onclick="toggleProfileDropdown()">
                        <span class="sr-only">View profile</span>
                        <img class="h-12 w-12 rounded-full"
                                src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=random&color=fff"
                                alt="Profile photo">
                    </button>
                    <div id="profile-dropdown"
                        class="hidden absolute right-0 top-full mt-6 w-48 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-50">
                        {{-- User info (optional) --}}
                        {{-- <div class="py-2 px-4 border-b border-gray-100">
                            <div class="font-semibold text-gray-800">{{ Auth::user()->name }}</div>
                            <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
                        </div> --}}

                        <div class="py-2 mt-4">
                            <a href="{{ url('keuangan/info-profile') }}"
                                class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-150">
                                <!-- <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-[#468585]"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5.121 17.804A13.937 13.937 0 0 1 12 15c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg> -->
                                Profile
                            </a>
                        </div>

                        <!-- Mobile Navigation inside Dropdown -->
                        <div class="mt-4 md:hidden  border-gray-100">
                            <a href="{{ route('owner-beranda') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-150
                                {{ request()->routeIs('owner-beranda') ? 'text-[#468585] font-semibold' : '' }}">
                                Beranda
                            </a>
                            <a href="{{ route('laporan-owner') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-150
                                {{ request()->routeIs('laporan-owner') ? 'text-[#468585] font-semibold' : '' }}">
                                Laporan Keuangan
                            </a>
                        </div>

                        <div class="border-t border-gray-100"></div>

                        <form method="POST" action="{{ route('logout') }}" class="py-2">
                            @csrf
                            <button type="submit"
                                class="flex items-center px-4 py-2 text-sm text-red-500 hover:text-red-700 hover:bg-gray-100 w-full text-left transition-colors duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>
</x-slot>

<!-- Script: Toggle Profile Dropdown -->
<script>
    function toggleProfileDropdown() {
        const dropdown = document.getElementById('profile-dropdown');
        dropdown.classList.toggle('hidden');
    }

    document.addEventListener('click', function (event) {
        const button = document.getElementById('profile-menu-button');
        const dropdown = document.getElementById('profile-dropdown');
        if (!button.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
    });
</script>
