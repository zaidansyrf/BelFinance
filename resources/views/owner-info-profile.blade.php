<x-app-layout>
  <div class="h-screen w-full bg-gray-100 flex overflow-hidden">
    <!-- Sidebar -->
    <div class="drawer lg:drawer-open">
      <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />
      
      <!-- Drawer Content -->
      <div class="drawer-content flex flex-col h-screen">
        <!-- Header Wrapper -->
        <div class="header-wrapper bg-[#D1DDD5] px-4 py-2 lg:px-4 lg:py-2">
          <!-- Header -->
          <div class="header-box bg-white border border-gray-300 rounded-lg shadow-lg flex justify-between items-center py-4 px-6 lg:px-12">
            <!-- Hamburger Menu for Mobile View -->
            <div class="flex items-center lg:hidden">
              <label for="my-drawer-2" class="cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-current" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
              </label>
              <!-- Greeting Text for Mobile View -->
              <span class="text-lg lg:text-xl font-semibold ml-4 text-[#2B7A78]">Hallo, </span>
            </div>
            <div class="hidden lg:flex items-center w-full justify-between">
              <span class="text-lg lg:text-xl font-semibold text-[#2B7A78]">Hallo, </span>
              <!-- Navigation Links -->
              <div class="flex space-x-6">
                <a href="{{url('/owner/beranda')}}" class="nav-link text-white bg-[#2B7A78] rounded-md px-4 py-2 font-semibold" onclick="setActiveLink(event)">Beranda</a>
                <a href="#" class="nav-link hover:bg-[#2B7A78] hover:text-[#DEF2F1] text-gray-600 rounded-md px-4 py-2 font-semibold" onclick="setActiveLink(event)">Button</a>
                <a href="{{url('/owner/laporan-keuangan')}}" class="nav-link hover:bg-[#2B7A78] hover:text-[#DEF2F1] text-gray-600 rounded-md px-4 py-2 font-semibold" onclick="setActiveLink(event)">Laporan Keuangan</a>
              </div>
              <!-- Profile Dropdown Menu -->
              <div class="dropdown dropdown-end">
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
        </div>

        <!-- Main Content -->
        <div class="flex-1 bg-[#D1DDD5] overflow-hidden">
          
         
        </div>

        <!-- Footer -->
        <footer class="footer footer-center bg-[#D1DDD5] p-4">
          <div class="flex items-center justify-center">
            <p class="opacity-40 text-lg mr-1" style="font-size: 15px;">Copyright <span class="opacity-30 text-current" style="font-size: 18px;">&copy;</span> 
            BelindoKitchen 2024</p>
          </div>
        </footer>
      </div>
    </div>
  </div>
</x-app-layout>

<script>
  function setActiveLink(event) {
    // hapus link aktif modal
    document.querySelectorAll('.nav-link').forEach(link => {
      link.classList.remove('bg-[#2B7A78]', 'text-white', 'rounded-lg');
      link.classList.add('text-gray-600', 'rounded-md');
    });

     // efek aktif pada modal
    event.target.classList.add('bg-[#2B7A78]', 'text-white', 'rounded-md');
    event.target.classList.remove('text-gray-600');
  }
</script>
