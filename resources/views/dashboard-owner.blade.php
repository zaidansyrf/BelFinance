<x-app-layout>
  <div class="h-screen w-full bg-gray-100 flex overflow-hidden">
    <!-- sidebar -->
    <div class="drawer lg:drawer-open">
      <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />
      
      <!-- drawer content -->
      <div class="drawer-content flex flex-col h-screen">
        <!-- Header -->
        <div class="flex justify-between items-center py-4 px-6 lg:px-12">
          <!-- menu hamburger mobile view -->
          <div class="flex items-center lg:hidden">
            <label for="my-drawer-2" class="cursor-pointer">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-current" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              </svg>
            </label>
            <!-- "Halo Pengguna" text mobile view -->
            <span class="text-lg lg:text-xl font-semibold ml-4">Hallo, {{$nama}}</span>
          </div>
          <div class="bg-transparent hidden lg:flex items-center w-full justify-between">
            <span class="text-lg lg:text-xl font-semibold">Hallo, {{$nama}}</span>
            <!-- dropdown menu profile -->
            <details class="relative">
              <summary class="inline-flex items-center justify-center p-2 hover:text-[#000000] cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
              </summary>
              <ul class="absolute right-0 mt-2 w-36 bg-white border border-gray-300 rounded-lg shadow-lg z-10">
                <li><a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Info Profile</a></li>
                <li><a href="#" class="block px-4 py-2 text-red-700 hover:bg-gray-100">Log Out</a></li>
              </ul>
            </details>
          </div>
          <!-- profile icon mobile view -->
          <div class="lg:hidden relative">
            <details>
              <summary class="inline-flex items-center justify-center p-2 hover:text-[#000000] cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
              </summary>
              <ul class="dropdown-content absolute right-0 mt-2 w-36 bg-white border border-gray-300 rounded-lg shadow-lg z-10">
                <li><a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Info Profile</a></li>
                <li><a href="#" class="block px-4 py-2 text-red-700 hover:bg-gray-100">Log Out</a></li>
              </ul>
            </details>
          </div>
        </div>

        <!-- main content -->
        <div class="flex-1 h-full bg-[#D1DDD5] overflow-hidden">
          <div class="h-[calc(100vh-8rem)] w-full">
          </div>
        </div>

        <!-- footer -->
        <footer class="footer footer-center bg-[#D1DDD5] p-4">
          <div class="flex items-center justify-center">
            <p class="opacity-40">made by arayanoun with love</p>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="opacity-30 h-6 w-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
            </svg>
          </div>
        </footer>
      </div>

      <!-- sidebar content -->
      <div class="drawer-side">
        <label for="my-drawer-2" aria-label="close sidebar" class="drawer-overlay"></label>
        <ul class="menu text-black h-full w-80 p-4 bg-[#ffffff]">
          <!-- sidebar content  -->
          <div class="text-[#2B7A78] text-center mb-4 flex items-center justify-center">
            <!-- icon and title -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mt-10 mb-6 h-8 w-8 mr-2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <h1 class="mt-10 mb-6 text-xl font-bold">BelFinance</h1>
          </div>
          <li><a class="mb-4 block w-full px-4 py-2 hover:bg-[#2B7A78] hover:text-[#DEF2F1]">Beranda</a></li>
          <li><a class="mb-4 block w-full px-4 py-2 hover:bg-[#2B7A78] hover:text-[#DEF2F1]">Transaksi</a></li>
          <li><a class="mb-4 block w-full px-4 py-2 hover:bg-[#2B7A78] hover:text-[#DEF2F1]">Hutang</a></li>
          <li><a class="mb-4 block w-full px-4 py-2 hover:bg-[#2B7A78] hover:text-[#DEF2F1]">Laporan Keuangan</a></li>
        </ul>
      </div>
    </div>
  </div>
</x-app-layout>
