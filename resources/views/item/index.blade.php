@section('title', 'Menu')
<x-app-layout>
        <div class="h-screen w-full bg-gray-100 flex overflow-hidden">
            <!-- sidebar -->
            <div class="drawer lg:drawer-open">
                <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />

                <!-- drawer content -->
                <div class="drawer-content flex flex-col h-screen">
                    <!-- wrapper for spacing -->
                    <div class="header-wrapper bg-[#D1DDD5] px-4 py-2 lg:px-4 lg:py-2">
                        <!-- header -->
                        <div class="header-box bg-white border border-gray-300 rounded-lg flex justify-between items-center py-4 px-6 lg:px-12">
                            <!-- menu hamburger mobile view -->
                            <div class="flex items-center lg:hidden">
                                <label for="my-drawer-2" class="cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" x="0px" y="0px" width="100" height="100" viewBox="0 0 50 50">
                                    <path d="M 5 8 A 2.0002 2.0002 0 1 0 5 12 L 45 12 A 2.0002 2.0002 0 1 0 45 8 L 5 8 z M 5 23 A 2.0002 2.0002 0 1 0 5 27 L 45 27 A 2.0002 2.0002 0 1 0 45 23 L 5 23 z M 5 38 A 2.0002 2.0002 0 1 0 5 42 L 45 42 A 2.0002 2.0002 0 1 0 45 38 L 5 38 z"></path>
                                </svg>
                                </label>
                                <!-- "Halo Pengguna" text mobile view -->
                                <span class="text-lg lg:text-xl font-semibold ml-4 text-[#2B7A78]">Hallo, </span>
                            </div>
                            <div class="bg-transparent hidden lg:flex items-center w-full justify-between">
                                <span class="text-lg lg:text-xl font-semibold text-[#2B7A78]">Hallo, </span>
                                <!-- dropdown menu profile -->
                                <div class="dropdown dropdown-end mr-4">
                                    <div tabindex="0" role="button">
                                        <div class="avatar">
                                            <div class="w-12 h-12 rounded-full">
                                                <a class="inline-flex items-center justify-center p-2 hover:text-[#000000] mt-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <ul tabindex="0" class="menu dropdown-content bg-white rounded-box z-[1] mt-6 w-40 p-2 shadow-lg">
                                        <li><a class="text-black" id="showInfo" onclick="showInfo()">Info Profile</a></li>
                                        <li><a class=" text-red-700">Log Out</a></li>
                                    </ul>
                                </div>
                            </div>
                            <!-- profile icon mobile view -->
                            <div class="lg:hidden relative dropdown dropdown-end mr-4">
                                <div tabindex="0" role="button">
                                    <div class="avatar">
                                        <div class="w-12 h-12 rounded-full">
                                            <a class="inline-flex items-center justify-center p-2 hover:text-[#000000] mt-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <ul tabindex="0" class="menu dropdown-content bg-white rounded-box z-[1] mt-6 w-40 p-2 shadow-lg">
                                    <li><a class="text-black" id="showInfo" onclick="showInfo()">Info Profile</a></li>
                                    <li><a class=" text-red-700">Log Out</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- main content -->
                    <div class="flex-1 bg-[#D1DDD5] overflow-auto">
                        <div class="sticky justify-between items-center mt-12 px-8">
                            <h1 class="text-xl font-semibold text-[#2B7A78] mb-4">Menu</h1>
                            <div class="flex items-center space-x-4">
                                <button onclick="openMenuForm()" class="bg-[#2B7A78] text-white font-semibold py-2 px-4 rounded-lg hover:bg-[#205C5D]">
                                    + <span class="hidden sm:inline">Tambah</span>
                                </button>
                                <form action="{{ route('menu.search') }}" method="GET" class="flex items-center max-w-md">
                                    <div class="relative w-full">
                                        <label for="search" class="hidden mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Cari Menu</label>
                                        <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/>
                                            </svg>
                                        </div>
                                        <input type="search" id="search" name="search" placeholder="Cari" 
                                            value="{{ request('search') }}"
                                            class="block p-3 pl-10 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    </div>
                                    
                                    @if(request('search'))
                                        <!-- Tombol Clear Jika Ada Input -->
                                        <a href="{{ url('/keuangan/menu') }}" class="py-3 px-5 text-sm font-medium text-white bg-red-600 border border-red-500 rounded-r-lg hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 dark:focus:ring-red-800">
                                            Clear
                                        </a>
                                    @endif
                                </form>

                            </div>
                            <div class="card text-primary-content bg-white mt-4 w-full">
                                <div class="card-body">
                                    <!-- <h2 class="card-title text-black">Tabel Menu</h2> -->
                                    <div class="overflow-x-auto">
                                    <table class="table w-full table-auto">
                                        <thead>
                                            <tr>
                                                <th class="py-2 px-4 border-b text-left text-gray-800">No</th>
                                                <th class="py-2 px-4 border-b text-left text-gray-800">Kode Menu</th>
                                                <th class="py-2 px-4 border-b text-left text-gray-800">Nama Menu</th>
                                                <th class="py-2 px-4 border-b text-left text-gray-800">Jumlah (Terjual)</th>
                                                <th class="py-2 px-4 border-b text-left text-gray-800">Harga</th>
                                                <th class="py-2 px-4 border-b text-left text-gray-800">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($items as $menu)
                                            <tr class="hover:bg-gray-100/50">
                                                <td class="py-2 px-4 border-b text-black">{{ $loop->iteration }}</td>
                                                <td class="py-2 px-4 border-b text-black">{{ $menu->code }}</td>
                                                <td class="py-2 px-4 border-b text-black">{{ $menu->name }}</td>
                                                <td class="py-2 px-4 border-b text-black">{{ $menu->quantity }}</td>
                                                <td class="py-2 px-4 border-b text-black">Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                                                <td class="py-2 px-4 border-b text-black">
                                                    <a href="#" class="text-blue-500 hover:underline">Edit</a>
                                                    <form action="{{ route('menu.destroy', $menu->id) }}" method="POST" class="inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-500 hover:underline ml-2" onclick="return confirm('Apakah Anda yakin ingin menghapus menu {{ $menu->name }}?')">Hapus</button>
                                                    </form>
                                                </td>
                                            </tr>
                                            @endforeach
                                            @if($items->isEmpty())
                                            <tr>
                                                <td colspan="6" class="px-4 py-2 text-center text-black">Tidak ada data tersedia.</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                    </div>
                                    {{ $items->links() }}
                                </div>
                            </div>
                        </div>

                        <!-- Modal untuk forms -->
                        <div id="menuOverlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-20 flex justify-center items-center">
                            <!-- Form Menu -->
                            <div id="menuModal" class="hidden bg-white w-[400px] h-auto max-w-[400px] rounded-lg shadow-lg p-6">
                                <h3 class="text-xl font-semibold mb-4">Tambah Menu</h3>
                                <form method="POST" action="{{ route('menu.store') }}">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="menuName" class="block text-sm font-medium text-gray-700">Nama Menu</label>
                                        <input type="text" name="name" id="menuName" class="w-full p-2 border border-gray-300 rounded-md" placeholder="cth. Ayam Goreng" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="menuCode" class="block text-sm font-medium text-gray-700">Kode Menu</label>
                                        <input type="text" name="code" id="menuCode" class="w-full p-2 border border-gray-300 rounded-md" placeholder="" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="menuJumlah" class="block text-sm font-medium text-gray-700">Jumlah</label>
                                        <input type="number" name="quantity" id="menuJumlah" class="w-full p-2 border border-gray-300 rounded-md" placeholder="cth. 10" required value="0">
                                    </div>
                                    <div class="mb-4">
                                        <label for="menuPrice" class="block text-sm font-medium text-gray-700">Harga</label>
                                        <input type="number" name="price" id="menuPrice" class="w-full p-2 border border-gray-300 rounded-md" placeholder="Rp " required step="1" min="0" inputmode="numeric">
                                    </div>
                                    <div class="flex justify-end mt-4">
                                        <button type="button" onclick="closeModal()" class="bg-[#db5461] text-white font-semibold py-2 px-6 rounded-lg hover:bg-gray-600">Batal</button>
                                        <button type="submit" class="bg-[#2B7A78] text-white font-semibold py-2 px-6 rounded-lg hover:bg-[#205C5D] ml-4">Simpan</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- sidebar content -->
                <div class="drawer-side">
                    <label for="my-drawer-2" aria-label="close sidebar" class="drawer-overlay"></label>
                    <ul class="menu text-black min-h-full w-80 p-4 bg-white">
                        <div class="lg:hidden flex justify-end mb-4">
                            <label for="my-drawer-2" class="cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500 hover:text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </label>
                        </div>
                        <!-- Sidebar Logo -->
                        <div class="text-[#2B7A78] text-center mb-4 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mt-10 mb-6 h-8 w-8 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            <h1 class="mt-10 mb-6 text-xl font-bold">BelFinance</h1>
                        </div>

                        <!-- Sidebar Menu Links -->
                        <li>
                            <a href="{{ url('/keuangan/dashboard') }}" class=" text-black  hover:bg-[#2B7A78] hover:text-[#DEF2F1] mb-2 block w-full px-4 py-2">
                                Dashboard
                            </a>
                        </li>
                        <li class="relative">
                            <button id="dropdownPemasukkanButton" onclick="dropdownPemasukkan()" class="group text-black hover:bg-[#2B7A78] mt-2 mb-2 block w-full px-4 py-2 text-left flex items-center gap-2 transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 122.88 96.2" class="w-4 h-4 fill-current text-black group-hover:text-[#DEF2F1] transition-colors duration-200">
                                <path d="M0,42.92h24.83V87.1H0V42.92L0,42.92z M85.36,20.49c9.01,0,16.32,7.3,16.32,16.32 
                                c0,9.01-7.3,16.32-16.32,16.32c-9.01,0-16.32-7.3-16.32-16.32C69.04,27.8,76.34,20.49,85.36,20.49z 
                                M56.72,0C64.49,0,70.8,6.3,70.8,14.08c0,7.77-6.3,14.08-14.08,14.08c-7.77,0-14.08-6.3-14.08-14.08
                                C42.64,6.31,48.94,0,56.72,0z 
                                M29.83,83.39V46.47h16.61c7.04,1.26,14.08,5.08,21.12,9.51h12.9c5.84,0.35,8.9,6.27,3.22,10.16
                                c-4.52,3.32-10.49,3.13-16.61,2.58c-4.22-0.21-4.4,5.46,0,5.48c1.53,0.12,3.19-0.24,4.64-0.24
                                c7.64-0.01,13.92-1.47,17.77-7.5l1.93-4.51l19.19-9.51c9.6-3.16,16.42,6.88,9.35,13.87
                                c-13.9,10.11-28.15,18.43-42.73,25.15c-10.59,6.44-21.18,6.22-31.76,0z"/>
                            </svg>
                            <span class="group-hover:text-[#DEF2F1] transition-colors duration-200">Pemasukkan</span>
                            </button>
                            <ul id="dropdownPemasukkanMenu" class="hidden bg-[#116A71] rounded text-white shadow-lg left-0 m-0 pl-0">
                            <li class="px-0 py-0 cursor-pointer">
                                <a href="{{ url('/keuangan/pembayaran') }}" class="hover:bg-[#3A9B98] hover:rounded-none">Pembayaran</a>
                            </li>
                            <li class="px-0 py-0 cursor-pointer">
                                <a href="{{ url('/keuangan/uang-masuk') }}" class="hover:bg-[#3A9B98] hover:rounded-none">Uang Masuk</a>
                            </li>
                            </ul>
                        </li>
                        <li>
                            <a href="{{ url('/keuangan/pengeluaran') }}" class="text-black hover:bg-[#2B7A78] hover:text-[#DEF2F1] mb-4 mt-2 block w-full px-4 py-2 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 452 512.11">
                                <path d="M336.47 255.21h64.36v-12.46c-3.68-13.63-9.54-22.87-17.13-28.49-7.59-5.61-17.43-8.01-28.98-7.93l-263.96.06c-6.5 0-11.76-5.27-11.76-11.76 0-6.5 5.26-11.76 11.76-11.76l263.65.03c16.59-.16 31.23 3.62 43.25 12.53 1.08.8 2.14 1.64 3.17 2.52v-7.07c0-10.98-4.53-21.02-11.82-28.31-7.23-7.29-17.25-11.8-28.29-11.8h-8.49l-1.09-.05-4.15 15.56h-28.52l16.92-63.47c-14.22-3.8-22.7-18.5-18.89-32.72l-94.11-25.21c-3.81 14.21-18.5 22.71-32.7 18.9l-27.63 102.5h-29.41L177.4 0l199.7 53.51-19.69 73.73h3.31c17.45 0 33.36 7.19 44.9 18.72 11.56 11.51 18.73 27.45 18.73 44.92v64.99c6.79 1.35 12.86 4.71 17.57 9.42 6.21 6.21 10.08 14.81 10.08 24.28v77.35c0 9.87-4.04 18.85-10.52 25.32-4.63 4.63-10.53 8.02-17.13 9.57v46.66c0 17.46-7.18 33.39-18.72 44.93l-.74.68c-11.5 11.13-27.11 18.03-44.17 18.03H63.63c-17.47 0-33.4-7.17-44.94-18.7C7.17 481.89 0 465.98 0 448.47V190.88c0-17.52 7.16-33.43 18.68-44.95 11.52-11.52 27.44-18.69 44.95-18.69h37.12l.16.01L130.46 17.5l28.19 7.55-38.73 141.23H90.4l4.18-15.51H63.63c-11.01 0-21.04 4.52-28.32 11.79-7.27 7.27-11.79 17.31-11.79 28.32v257.59c0 11.01 4.53 21.03 11.81 28.3 7.28 7.29 17.32 11.82 28.3 11.82h297.09c10.73 0 20.54-4.3 27.74-11.25l.54-.58c7.29-7.28 11.83-17.32 11.83-28.29v-45.71h-64.36c-19.88 0-37.96-8.14-51.02-21.2l-1.23-1.35c-12.36-13-19.98-30.52-19.98-49.68v-3.1c0-19.79 8.13-37.83 21.21-50.94l.13-.13c13.1-13.05 31.12-21.15 50.89-21.15zm-95.71-93.06c17.19 4.6 34.89-5.6 39.49-22.8 4.61-17.19-5.61-34.89-22.8-39.49-17.2-4.6-34.9 5.6-39.5 22.8-4.6 17.19 5.62 34.88 22.81 39.49zM362.3 309.07l.06.05c10.93 10.96 10.9 28.79-.02 39.74l-.05.06c-10.96 10.93-28.79 10.9-39.75-.02l-.05-.05c-10.93-10.96-10.9-28.79.02-39.75l.05-.05c10.96-10.93 28.79-10.91 39.74.02z"/>
                            </svg>
                            <span class="group-hover:text-[#DEF2F1] transition-colors duration-200">Pengeluaran</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/keuangan/menu') }}" class="bg-[#2B7A78] text-white hover:bg-[#2B7A78] hover:text-[#DEF2F1] mb-4 block w-full px-4 py-2 text-left flex items-center gap-2 transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 122.88 90.02" class="w-5 h-5 icon">
                                <path d="M0 8.62c17.28-10.66 34.96-12.3 53.26 0v79.64c-12.65-9.37-31.03-8.24-53.26 0V8.62zm59.09.2h5.28c1.08 0 1.96.88 1.96 1.95v77.29c0 1.08-.88 1.96-1.96 1.96h-5.28a1.97 1.97 0 01-1.96-1.96V10.77c.01-1.07.89-1.95 1.96-1.95zm63.79-.2c-17.28-10.66-34.97-12.3-53.27 0v79.64c12.65-9.37 31.03-8.24 53.27 0V8.62z" 
                                fill="white"/>
                            </svg>
                            <span class="group-hover:text-[#DEF2F1] transition-colors duration-200">Menu</span>
                            </a>
                        </li>
                        <li class="relative">
                        <!-- Dropdown Kategori -->
                        <button id="dropdownKategoriButton" onclick="dropdownKategori()" class="text-black hover:bg-[#2B7A78] hover:text-[#DEF2F1] mt-2 mb-2 w-full px-4 py-2 text-left flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg"  width="16" height="16" viewBox="0 0 122.879 122.891" fill="currentColor">
                            <path d="M89.767,18.578c3.848,0,7.332,1.561,9.854,4.082c2.521,2.522,4.082,6.007,4.082,9.855s-1.561,7.332-4.082,9.854 
                            c-2.522,2.522-6.007,4.082-9.854,4.082c-3.849,0-7.333-1.56-9.854-4.082c-2.522-2.522-4.082-6.006-4.082-9.854 
                            s1.56-7.333,4.082-9.855C82.434,20.138,85.918,18.578,89.767,18.578L89.767,18.578z M122.04,56.704l-65.337,65.337 
                            c-1.132,1.133-2.969,1.133-4.101,0L0.849,70.287c-1.132-1.131-1.132-2.967,0-4.1L66.186,0.85C66.752,0.284,67.494,0,68.236,0v0 
                            h50.051c1.602,0,2.9,1.298,2.9,2.9c0,0.048-0.002,0.097-0.004,0.145l1.694,51.517c0.026,0.83-0.301,1.589-0.845,2.134 
                            L122.04,56.704L122.04,56.704z M54.652,115.889l62.406-62.407L115.49,5.8H69.438L7.001,68.238L54.652,115.889L54.652,115.889z 
                            M96.244,26.037c-1.657-1.657-3.948-2.683-6.478-2.683c-2.53,0-4.82,1.025-6.478,2.683c-1.658,1.657-2.684,3.948-2.684,6.478 
                            s1.025,4.82,2.684,6.478c1.657,1.658,3.947,2.683,6.478,2.683c2.529,0,4.82-1.025,6.478-2.683s2.683-3.948,2.683-6.478 
                            S97.901,27.694,96.244,26.037L96.244,26.037z"/>
                        </svg>
                        <!-- Teks -->
                        <span class="text-sm">Kategori</span>
                        </button>
                        <!-- Dropdown Menu -->
                        <ul id="dropdownKategoriMenu" class="hidden bg-[#116A71] rounded text-white shadow-lg left-0 m-0 pl-0">
                        <li class="px-0 py-0 cursor-pointer"><a href="{{url('/keuangan/kategori/sumber-masuk')}}" class="hover:bg-[#3A9B98] hover:rounded-none">Sumber Masuk</a></li>
                        <li class="px-0 py-0 cursor-pointer"><a href="{{url('/keuangan/kategori/sumber-keluar')}}" class="hover:bg-[#3A9B98] hover:rounded-none">Sumber Keluar</a></li>
                        </ul>
                    </li> 
                    </ul>
                </div>

            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
                @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 2000
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('error') }}',
            showConfirmButton: true
        });
    @endif
            function contoh(event) {
                event.preventDefault(); // Mencegah pengiriman form

                // Menampilkan SweetAlert untuk konfirmasi sukses
                swal({
                    title: "Berhasil!",
                    text: "Data berhasil disimpan",
                    icon: "success",
                    button: "OK"
                }).then((willProceed) => {
                    if (willProceed) {
                        // Jika pengguna mengklik OK, kirim form
                        event.target.submit();
                    }
                }).catch((error) => {
                    // Menampilkan SweetAlert untuk kesalahan
                    swal({
                        title: "Gagal!",
                        text: "Terjadi kesalahan saat menyimpan data.",
                        icon: "error",
                        button: "Coba Lagi"
                    });
                });
            }

            // Open Menu Form
            function openMenuForm() {
                const modalOverlay = document.getElementById('menuOverlay');
                const menuModal = document.getElementById('menuModal');
                modalOverlay.classList.remove('hidden');
                menuModal.classList.remove('hidden');
                closeDropdown(); // Menutup dropdown saat membuka form
            }
            // Close Modal
            function closeModal() {
                document.getElementById('menuOverlay').classList.add('hidden');
            }
            // Menutup dropdown ketika klik luar area
            window.onclick = function(event) {
                const dropdown = document.getElementById('menuDropdown');
                const dropdownButton = document.getElementById('dropdownButton');
                if (!dropdown.contains(event.target) && !dropdownButton.contains(event.target)) {
                    closeDropdown();
                }
            };
            // Event handler saat pilihan dropdown diklik
            function selectDropdownOption() {
                closeDropdown();
            }
            // Close Dropdown When Click Outside
            window.onclick = function(event) {
                if (!event.target.matches('button') && !event.target.matches('.block')) {
                    closeDropdown();
                }
            };

            function dropdownLaporan() {
                const dropdownLaporanMenu = document.getElementById('dropdownLaporanMenu');
                dropdownLaporanMenu.classList.toggle('hidden');
            }
            document.addEventListener("click", function(event) {
                const dropdownLaporanMenu = document.getElementById("dropdownLaporanMenu");
                const dropdownLaporanButton = document.getElementById("dropdownLaporanButton");

                // Jika elemen yang diklik bukan bagian dari dropdown
                if (
                    !dropdownLaporanMenu.contains(event.target) &&
                    !dropdownLaporanButton.contains(event.target)
                ) {
                    dropdownLaporanMenu.classList.add("hidden");
                }
            });

            function dropdownKategori() {
                const dropdownKategoriMenu = document.getElementById('dropdownKategoriMenu');
                dropdownKategoriMenu.classList.toggle('hidden');
            }
            document.addEventListener("click", function(event) {
                const dropdownKategoriMenu = document.getElementById("dropdownKategoriMenu");
                const dropdownKategoriButton = document.getElementById("dropdownKategoriButton");

                // Jika elemen yang diklik bukan bagian dari dropdown
                if (
                    !dropdownKategoriMenu.contains(event.target) &&
                    !dropdownKategoriButton.contains(event.target)
                ) {
                    dropdownKategoriMenu.classList.add("hidden");
                }
            });
            function dropdownPemasukkan() {
            const dropdownPemasukkanMenu = document.getElementById('dropdownPemasukkanMenu');
            dropdownPemasukkanMenu.classList.toggle('hidden');
            }
            document.addEventListener("click", function (event) {
                const dropdownPemasukkanMenu = document.getElementById("dropdownPemasukkanMenu");
                const dropdownPemasukkanButton = document.getElementById("dropdownPemasukkanButton");

                // Jika elemen yang diklik bukan bagian dari dropdown
                if (
                !dropdownPemasukkanMenu.contains(event.target) &&
                !dropdownPemasukkanButton.contains(event.target)
                ) {
                dropdownPemasukkanMenu.classList.add("hidden");
                }
            });
        </script>
    </x-app-layout>