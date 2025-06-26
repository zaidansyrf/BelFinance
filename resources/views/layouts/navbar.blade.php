<div class="header-wrapper bg-[#D1DDD5]  pt-2 px-2 pb-0 lg:pt-3 lg:px-6 ">
    <!-- header -->
    <div
        class="header-box bg-white border border-gray-300 rounded-lg flex justify-between items-center py-3 px-6 lg:px-12">
        <!-- menu hamburger mobile view -->
        <div class="flex items-center lg:hidden">
            <label for="my-drawer-2" class="cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" x="0px" y="0px" width="100" height="100"
                    viewBox="0 0 50 50">
                    <path
                        d="M 5 8 A 2.0002 2.0002 0 1 0 5 12 L 45 12 A 2.0002 2.0002 0 1 0 45 8 L 5 8 z M 5 23 A 2.0002 2.0002 0 1 0 5 27 L 45 27 A 2.0002 2.0002 0 1 0 45 23 L 5 23 z M 5 38 A 2.0002 2.0002 0 1 0 5 42 L 45 42 A 2.0002 2.0002 0 1 0 45 38 L 5 38 z">
                    </path>
                </svg>
            </label>
            <span class="text-lg lg:text-xl font-semibold ml-4 text-[#2B7A78]">Hallo, {{ Auth::user()->name }}</span>
        </div>
        <div class="bg-transparent hidden lg:flex items-center w-full justify-between">
            <span class="text-lg lg:text-xl font-semibold text-[#2B7A78]">Hallo, {{ Auth::user()->name }}</span>
            <!-- dropdown menu profile -->
            <div class="dropdown dropdown-end mr-4">
                <div tabindex="0" role="button">
                    <div class="avatar">
                        <div class="w-12 h-12 rounded-full">
                            <a class="inline-flex items-center justify-center p-2 hover:text-[#000000] mt-2">
                                <img class="h-12 w-12 rounded-full"
                                src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=68585&color=fff"
                                alt="Profile photo">
                            </a>
                        </div>
                    </div>
                </div>
                <ul tabindex="0" class="menu dropdown-content bg-white rounded-box z-[1] mt-6 w-40 p-2 shadow-lg">
                    <li><a class="text-black" href="{{ url('keuangan/info-profile') }}">

                            Info Profile</a></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex text-red-500 hover:text-red-700 w-full text-left ">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Log Out</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        <!-- profile icon mobile view -->
        <div class="lg:hidden relative dropdown dropdown-end mr-4">
            <div tabindex="0" role="button">
                <div class="avatar">
                    <div class="w-12 h-12 rounded-full">
                        <a class="inline-flex items-center justify-center p-2 hover:text-[#000000] mt-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke-width="1.5"
                                stroke="currentColor" class="h-6 w-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            <ul tabindex="0" class="menu dropdown-content bg-white rounded-box z-[1] mt-6 w-40 p-2 shadow-lg">
                <li><a href="{{ url('keuangan/info-profile') }}" class="text-black">Info Profile</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="text-red-500 hover:text-red-700 flex items-center gap-2 w-full text-left">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Log Out
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
