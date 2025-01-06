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
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-current" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
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
            <h1 class="text-xl font-semibold text-[#2B7A78] mb-4">Halaman Uang Masuk</h1>
            <div class="mb-4">
            <button class="bg-[#2B7A78] text-white font-semibold py-2 px-4 rounded-lg hover:bg-[#205C5D]" onclick="openModal()">
              + Tambah              
            </button>
            </div>
          </div>
          <div class="flex justify-center w-full px-8">
              <div class="card text-primary-content bg-white mt-4 w-full">
                <div class="card-body">
                  <h2 class="card-title text-black">Tabel Uang Masuk</h2>
                  <div class="overflow-x-auto">
                    <table class="table w-full table-auto">
                        <thead>
                            <tr class="text-left">
                                <th class="py-2 px-4 border-b text-left text-gray-800">No</th>
                                <th class="py-2 px-4 border-b text-left text-gray-800">Nama</th>
                                <th class="py-2 px-4 border-b text-left text-gray-800">Sumber</th>
                                <th class="py-2 px-4 border-b text-left text-gray-800">Nominal</th>
                                <th class="py-2 px-4 border-b text-left text-gray-800">Tanggal</th>
                                <th class="py-2 px-4 border-b text-left text-gray-800">Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($income as $i)
                                <tr class="hover:bg-gray-100/50">
                                    <td class="text-black px-4 py-2">{{ $loop->iteration }}</td>
                                    <td class="text-black px-4 py-2">{{ $i->name }}</td>
                                    <td class="text-black px-4 py-2">{{ $i->source->name }}</td>
                                    <td class="text-black px-4 py-2">Rp {{ number_format($i->amount, 0, ',', '.') }}</td>
                                    <td class="text-black px-4 py-2">{{ $i->date->format('d-m-Y') }}</td>
                                    <td class="text-black px-4 py-2">{{ $i->description }}</td>
                                </tr>
                            @endforeach
                            @if ($income->isEmpty())
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
        <div id="menuOverlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-20 flex justify-center items-center">
            <!-- Form Menu -->
            <div id="uangMasukModal" class="hidden bg-white w-[400px] h-auto max-w-[400px] rounded-lg shadow-lg p-6">
              <h3 class="text-xl font-semibold mb-4">Uang Masuk</h3>
              <form method="POST" action="{{ route('uang-masuk.store') }}">
                @csrf
                <div class="mb-4">
                  <label for="tagihanName" class="block text-sm font-medium text-gray-700">Nama</label>
                  <input type="text" name="name" id="tagihanName" class="w-full p-2 border border-gray-300 rounded-md" placeholder="Masukkan Nama" required>
                </div>
                <div class="mb-4">
                  <label for="source_id" class="block text-sm font-medium text-gray-700">Sumber</label>
                  <select name="source_id" id="source" class="text-gray-500 w-full p-2 border border-gray-300 rounded-md form-select" required>
                    <option class="text-black" value="" selected disabled>Pilih Sumber</option>
                    @foreach ($sources as $source)
                      <option class="text-black" value="{{ $source->id }}">{{ $source->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="mb-4">
                  <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal</label>
                  <input type="date" name="date" id="tanggal" class="w-full p-2 border border-gray-300 rounded-md" required>
                </div>
                <div class="mb-4">
                  <label for="nominal" class="block text-sm font-medium text-gray-700">Nominal</label>
                  <input type="number" name="amount" id="nominal" class="w-full p-2 border border-gray-300 rounded-md" placeholder="Rp. " required>
                </div>
                <div class="mb-4">
                  <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                  <textarea name="description" id="deskripsi" rows="4" class="w-full p-2 border border-gray-300 rounded-md" placeholder="Masukkan Deskripsi"></textarea>
                </div>
                <div class="flex justify-end mt-4">
                  <button type="button" onclick="closeModal()" class="bg-[#db5461] text-white font-semibold py-2 px-6 rounded-lg hover:bg-gray-600">Batal</button>
                  <button type="submit" class="bg-[#2B7A78] text-white font-semibold py-2 px-6 rounded-lg hover:bg-[#205C5D] ml-4">Simpan</button>
                </div>
              </form>
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
            <button id="dropdownPemasukkanButton" onclick="dropdownPemasukkan()" class="bg-[#116A71] text-white hover:bg-[#2B7A78] hover:text-[#DEF2F1] mt-2 mb-2 block w-full px-4 py-2 text-left">
              Pemasukkan
            </button>
            <ul id="dropdownPemasukkanMenu" class=" hidden bg-[#116A71] rounded text-white shadow-lg left-0 m-0 pl-0">
              <li class="px-0 py-0 cursor-pointer"><a href="{{ url('/keuangan/pembayaran') }}" class="hover:bg-[#3A9B98] hover:rounded-none">Pembayaran</a></li>
              <li class="px-0 py-0 cursor-pointer"><a href="{{ url('/keuangan/uang-masuk') }}" class="hover:bg-[#3A9B98] hover:rounded-none">Uang Masuk</a></li>
            </ul>
          </li>
          <li>
            <a href="{{ url('/keuangan/pengeluaran') }}" class="hover:bg-[#2B7A78] text-black hover:text-[#DEF2F1] mb-4 mt-2 block w-full px-4 py-2">
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
  // Fungsi untuk menampilkan modal
function openModal() {
    const menuOverlay = document.getElementById('menuOverlay');
    const uangMasukModal = document.getElementById('uangMasukModal');

    menuOverlay.classList.remove('hidden');
    uangMasukModal.classList.remove('hidden');
}

// Fungsi untuk menutup modal
function closeModal() {
    const menuOverlay = document.getElementById('menuOverlay');
    const uangMasukModal = document.getElementById('uangMasukModal');

    menuOverlay.classList.add('hidden');
    uangMasukModal.classList.add('hidden');
}

// Menutup modal ketika area overlay di-klik
document.getElementById('menuOverlay').addEventListener('click', function (e) {
    if (e.target === this) {
        closeModal();
    }
});

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