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
              <span class="text-lg lg:text-xl font-semibold ml-4 text-[#2B7A78]">Hallo, </span>
            </div>

            <!-- Desktop Navigation Links -->
            <div class="hidden lg:flex items-center w-full justify-between">
              <span class="text-lg lg:text-xl font-semibold text-[#2B7A78]">Hallo, </span>
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
                  <li><a class="text-red-700">Log Out</a></li>
                </ul>
              </div>
            </div>

            <!-- Mobile Profile Icon -->
            <div class="flex items-center lg:hidden relative">
              <div class="avatar">
                <div class="w-10 h-10 rounded-full">
                  <button class="inline-flex items-center justify-center p-2 hover:text-[#000000]" id="profile-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                  </button>
                  <!-- Dropdown Menu -->
                  <ul id="mobile-profile-dropdown" class="menu dropdown-content bg-white rounded-box z-[1] mt-6 w-40 p-2 shadow-lg hidden absolute right-0">
                    <li><a class="text-black" id="showInfo" onclick="showInfo()">Info Profile</a></li>
                    <li><a class="text-red-700">Log Out</a></li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 bg-[#D1DDD5] overflow-hidden">
          <div class="card-body">
            <h2 class="card-title text-black">Tabel Laporan Keuangan</h2>
          </div>
        </div>
      </div>
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
          <a href="{{url('/owner/beranda')}}" class="text-black hover:bg-[#2B7A78] hover:text-[#DEF2F1] mb-4 mt-2 block w-full px-4 py-2" onclick="setActiveLink(event)">Beranda</a>
          </li>
          <li>
          <a href="#" class="text-black hover:bg-[#2B7A78] hover:text-[#DEF2F1] mb-4 mt-2 block w-full px-4 py-2" onclick="setActiveLink(event)">Button</a>
          </li>
          <li>
          <a href="{{url('/owner/laporan-keuangan')}}" class="bg-[#116A71] text-white hover:bg-[#2B7A78] hover:text-[#DEF2F1] mt-2 mb-2 block w-full px-4 py-2 text-left" onclick="setActiveLink(event)">Laporan Keuangan</a>
          </li>   
        </ul>
      </div>
    </div>
  </div>
</x-app-layout>

<script>
  function setActiveLink(event) {
    // hapus link aktif modal
    document.querySelectorAll('.nav-link').forEach(link => {
      link.classList.remove('bg-[#2B7A78]', 'text-white', 'rounded-md');
      link.classList.add('text-gray-600', 'rounded-md');
    });

    // efek aktif pada modal
    event.target.classList.add('bg-[#2B7A78]', 'text-white', 'rounded-md');
    event.target.classList.remove('text-gray-600');
  }

  // Show/Hide Profile Dropdown on Mobile
  document.getElementById('profile-icon').addEventListener('click', function(event) {
    event.stopPropagation(); // Prevent click event from bubbling up
    const dropdown = document.getElementById('mobile-profile-dropdown');
    dropdown.classList.toggle('hidden');
  });

  // Hide dropdown when clicking outside
  document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('mobile-profile-dropdown');
    const profileIcon = document.getElementById('profile-icon');

    if (!profileIcon.contains(event.target) && !dropdown.contains(event.target)) {
      dropdown.classList.add('hidden');
    }
  });
</script>
