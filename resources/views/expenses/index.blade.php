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
            <h1 class="text-xl font-semibold text-[#2B7A78] mb-4">Halaman Pengeluaran</h1>
            <button class="bg-[#2B7A78] text-white font-semibold py-2 px-4 rounded-lg hover:bg-[#205C5D]" onclick="window.location.href='{{url('/keuangan/pengeluaran/create')}}'">
              + Tambah              
            </button>
          </div>
          <div class="flex justify-center w-full px-8">
              <div class="card text-primary-content bg-white mt-4 w-full">
                <div class="card-body">
                  <h2 class="card-title text-black">Tabel Pengeluaran</h2>
                  <div class="overflow-x-auto">
                  <table class="table w-full">
                    <thead>
                      <tr class="text-left">  
                        <th class="py-2 px-4 border-b text-left text-gray-800">No</th>
                        <th class="py-2 px-4 border-b text-left text-gray-800">Sumber</th>
                        <th class="py-2 px-4 border-b text-left text-gray-800">Tagihan</th>
                        <th class="py-2 px-4 border-b text-left text-gray-800">Nominal</th>
                        <th class="py-2 px-4 border-b text-left text-gray-800">Keterangan</th>
                        <th class="py-2 px-4 border-b text-left text-gray-800">Tanggal</th>
                        <th class="py-2 px-4 border-b text-left text-gray-800">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($expenses as $key => $expense)
                      <tr class="hover:bg-gray-100/50">
                        <td class="text-black px-4 py-2">{{ $key + 1 }}</td>
                        <td class="text-black px-4 py-2">{{ $expense->source->name }}</td>
                        <td class="text-black px-4 py-2">{{ $expense->bill->name }}</td>
                        <td class="text-black px-4 py-2">Rp. {{ number_format($expense->amount, 0, ',', '.') }}</td>
                        <td class="text-black px-4 py-2">{{ $expense->description }}</td>
                        <td class="text-black px-4 py-2">{{ $expense->date->format('d-m-Y') }}</td>
                        <td class="text-black px-4 py-2">
                          <a href="" class="btn btn-sm btn-primary">Edit</a>
                            <form action="{{ url('/keuangan/pengeluaran/' . $expense->id) }}" method="POST">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-sm btn-error">Hapus</button>
                            </form>

                        </td>
                      </tr>
                      @endforeach
                      @if($expenses->isEmpty())
                      <tr>
                          <td colspan="6" class="px-4 py-2 text-center text-black">Tidak ada data tersedia.</td>
                        </tr>
                      @endif
                    </tbody>
                  </table>
                  </div>
                </div>
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
            <a href="{{ url('/admin/keuangan/dashboard') }}" class=" text-black  hover:bg-[#2B7A78] hover:text-[#DEF2F1] mb-2 block w-full px-4 py-2">
            Dashboard
            </a>
          </li>
          <li class="relative">
            <button id="dropdownPemasukkanButton" onclick="dropdownPemasukkan()" class="text-black hover:bg-[#2B7A78] hover:text-[#DEF2F1] mt-2 mb-2 block w-full px-4 py-2 text-left">
              Pemasukkan
            </button>
            <ul id="dropdownPemasukkanMenu" class="hidden bg-[#116A71] rounded text-white shadow-lg left-0 m-0 pl-0">
              <li class="px-0 py-0 cursor-pointer"><a href="{{ url('/keuangan/pembayaran') }}" class="hover:bg-[#3A9B98] hover:rounded-none">Pembayaran</a></li>
              <li class="px-0 py-0 cursor-pointer"><a href="{{ url('/keuangan/uang-masuk') }}" class="hover:bg-[#3A9B98] hover:rounded-none">Uang Masuk</a></li>
            </ul>
          </li>
          <li>
            <a href="{{ url('/keuangan/pengeluaran') }}" class=" bg-[#116A71] text-white hover:bg-[#2B7A78] hover:text-[#DEF2F1] mb-4 mt-2 block w-full px-4 py-2">
              Pengeluaran
            </a>
          </li>
          <li>
            <a href="{{ url('/admin/keuangan/menu') }}" class=" text-black hover:bg-[#2B7A78] hover:text-[#DEF2F1] mb-4 block w-full px-4 py-2">
              Menu
            </a>
          </li>
          <li class="relative">
            <!-- Dropdown Kategori -->
            <button id="dropdownKategoriButton" onclick="dropdownKategori()" class="text-black hover:bg-[#2B7A78] hover:text-[#DEF2F1] mt-2 mb-2 block w-full px-4 py-2 text-left">
              Kategori
            </button>
            <!-- Dropdown Menu -->
            <ul id="dropdownKategoriMenu" class="hidden bg-[#116A71] rounded text-white shadow-lg left-0 m-0 pl-0">
              <li class="px-0 py-0 cursor-pointer"><a href="{{url('/admin/keuangan/kategori/sumber-masuk')}}" class="hover:bg-[#3A9B98] hover:rounded-none">Sumber Masuk</a></li>
              <li class="px-0 py-0 cursor-pointer"><a href="{{url('/admin/keuangan/kategori/sumber-keluar')}}" class="hover:bg-[#3A9B98] hover:rounded-none">Sumber Keluar</a></li>
            </ul>
          </li>
          <li class="relative">
            <!-- Dropdown Laporan -->
            <button id="dropdownLaporanButton" onclick="dropdownLaporan()" class="text-black hover:bg-[#2B7A78] hover:text-[#DEF2F1] mt-2 mb-2 block w-full px-4 py-2 text-left">
              Laporan Keuangan
            </button>
            <!-- Dropdown Menu -->
            <ul id="dropdownLaporanMenu" class="hidden bg-[#116A71] rounded text-white shadow-lg left-0 m-0 pl-0">
              <li class="px-0 py-0 cursor-pointer"><a href="{{url('/admin/keuangan/laporan-keuangan/pembayaran')}}" class="hover:bg-[#3A9B98] hover:rounded-none">Pembayaran</a></li>
              <li class="px-0 py-0 cursor-pointer"><a href="{{url('/admin/keuangan/laporan-keuangan/pemasukkan')}}" class="hover:bg-[#3A9B98] hover:rounded-none">Pemasukan</a></li>
              <li class="px-0 py-0 cursor-pointer"><a href="{{url('/admin/keuangan/laporan-keuangan/pengeluaran')}}" class="hover:bg-[#3A9B98] hover:rounded-none">Pengeluaran</a></li>
              <li class="px-0 py-0 cursor-pointer"><a href="{{url('/admin/keuangan/laporan-keuangan/sumber')}}" class="hover:bg-[#3A9B98] hover:rounded-none">Sumber</a></li>
            </ul>
          </li>    
          </ul>
      </div>
    </div>
  </div>
<script>
  function dropdownLaporan() {
  const dropdownLaporanMenu = document.getElementById('dropdownLaporanMenu');
  dropdownLaporanMenu.classList.toggle('hidden');
  }
  document.addEventListener("click", function (event) {
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
  document.addEventListener("click", function (event) {
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
  function toggleAdditionalFields() {
    const paymentSource = document.getElementById('paymentSource').value;

    // Hide all additional fields first
    document.getElementById('orderMenu').classList.add('hidden');

    // Show relevant fields based on the selected income source
    if (paymentSource === 'shopee') {
      document.getElementById('orderMenu').classList.remove('hidden');
    } 
  }
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
