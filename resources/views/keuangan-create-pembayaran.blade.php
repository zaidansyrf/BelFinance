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
        <!-- main content -->
        <div class="flex-1 bg-[#D1DDD5] overflow-auto">
          <div class="sticky justify-between items-center mt-12 px-8">
            <div class="mb-4">
              <!-- Tombol Kembali -->
              <button type="button" class="bg-[#2B7A78] text-white font-semibold py-2 px-6 rounded-lg hover:bg-[#205C5D]" onclick="window.location.href='{{url('/keuangan/pembayaran')}}'">
                &larr; Kembali
              </button>

            </div>
            <div class="container">
              <h1 class="text-xl font-semibold text-[#2B7A78] mb-4">Pesanan Baru</h1>
              <div class="card text-primary-content bg-white mt-4 w-full">
                <div class="card-body">
                  <form action="{{ route('pembayaran.store') }}" method="POST">
                    @csrf
                    <div class="mb-4" id="items-container">
                      <!-- Nama Pesanan -->
                      <div>
                        <label for="name" class="block text-sm font-medium text-black form-label">Nama Pesanan</label>
                        <input type="text" name="name" id="name" placeholder="cth. online/offline" class="text-gray-500 w-full p-2 border border-gray-300 rounded-md form-control" required>
                      </div>

                      <!-- Pilih Sumber -->
                      <div class="item-row mb-3">
                        <label for="source" class="block text-sm font-medium text-black form-label mt-2">Dari Sumber</label>
                        <select name="source_id" id="source" class="text-gray-500 w-full p-2 border border-gray-300 rounded-md form-select" required>
                          <option class="text-black" value="" selected disabled>Pilih Sumber</option>
                          @if ($allSources->count())
                          @foreach ($allSources as $source)
                          <option value="{{ $source->id }}">{{ $source->name }}</option>
                          @endforeach
                          @else
                          <option disabled>Data sumber tidak tersedia</option>
                          @endif
                        </select>
                      </div>

                      <!-- Pilih Item -->
                      <div class="item-row mb-3" id="item-row-0">
                        <label for="item_0" class="block text-sm font-medium text-black form-label">Pilih Item</label>
                        <select name="items[0][item_id]" id="item_0" class="text-gray-500 w-full p-2 border border-gray-300 rounded-md form-select" required>
                          <option class="text-black" value="" selected disabled>Pilih Item</option>
                          @foreach ($allItems as $item)
                          @if ($item->price != 0)
                          <option class="text-black" value="{{ $item->id }}" data-price="{{ $item->price }}">{{ $item->name }} ({{ number_format($item->price, 0, ',', '.') }})</option>
                          @endif
                          @endforeach
                        </select>

                        <label for="quantity_0" class="block text-sm font-medium text-black form-label mt-2">Jumlah</label>
                        <input type="number" name="items[0][quantity]" min="1" id="quantity_0" required class="text-gray-500 w-full p-2 border border-gray-300 rounded-md form-control">

                        <!-- Tombol Hapus -->
                        <!-- <button type="button" class="remove-item-btn bg-red-500 text-white font-semibold py-1 px-4 rounded-lg hover:bg-red-700 mt-2" data-index="0">Hapus</button> -->
                      </div>
                    </div>

                    <!-- Menampilkan Total -->
                    <div class="mt-4">
                      <p class="text-black font-medium">Total Harga: <span id="total">Rp 0</span></p>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end gap-4 mt-4">
                      <button type="button" id="add-item" class="bg-[#2B7A78] text-white font-semibold py-2 px-6 rounded-lg hover:bg-[#205C5D]">Tambah Item</button>
                      <button type="submit" class="bg-[#2B7A78] text-white font-semibold py-2 px-6 rounded-lg hover:bg-[#205C5D]">Simpan Pesanan</button>
                    </div>
                  </form>
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
            <button id="dropdownPemasukkanButton" onclick="dropdownPemasukkan()" class="bg-[#116A71] text-white hover:bg-[#2B7A78] hover:text-[#DEF2F1] mt-2 mb-2 block w-full px-4 py-2 text-left">
              Pemasukkan
            </button>
            <ul id="dropdownPemasukkanMenu" class="hidden bg-[#116A71] rounded text-white shadow-lg left-0 m-0 pl-0">
              <li class="px-0 py-0 cursor-pointer"><a href="{{ url('/keuangan/pembayaran') }}" class="hover:bg-[#3A9B98] hover:rounded-none">Pembayaran</a></li>
              <li class="px-0 py-0 cursor-pointer"><a href="{{ url('/keuangan/uang-masuk') }}" class="hover:bg-[#3A9B98] hover:rounded-none">Uang Masuk</a></li>
            </ul>
          </li>
          <li>
            <a href="{{ url('/keuangan/pengeluaran') }}" class=" text-black hover:bg-[#2B7A78] hover:text-[#DEF2F1] mb-4 mt-2 block w-full px-4 py-2">
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
            <button id="dropdownKategoriButton" onclick="dropdownKategori()" class=" text-black hover:bg-[#2B7A78] hover:text-[#DEF2F1] mt-2 mb-2 block w-full px-4 py-2 text-left">
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
    document.addEventListener('DOMContentLoaded', function() {
      const addItemButton = document.getElementById('add-item');
      const itemsContainer = document.getElementById('items-container');
      const totalElement = document.getElementById('total');
      let itemCount = 1;

      // Menghitung total harga
      const calculateTotal = () => {
        const itemRows = document.querySelectorAll('.item-row');
        let total = 0;

        itemRows.forEach(itemRow => {
          const selectElement = itemRow.querySelector('select');
          const quantityElement = itemRow.querySelector('input[type="number"]');

          if (selectElement && selectElement.value && quantityElement && quantityElement.value) {
            const selectedOption = selectElement.selectedOptions[0];
            const price = parseFloat(selectedOption.dataset.price || 0);
            const quantity = parseInt(quantityElement.value || 0, 10);

            if (!isNaN(price) && !isNaN(quantity)) {
              total += price * quantity;
            } else {
              console.error('Invalid price or quantity:', {
                price,
                quantity
              });
            }
          } else {
            console.warn('Incomplete row data:', {
              selectElement,
              quantityElement
            });
          }
        });

        totalElement.innerText = `Rp ${total.toLocaleString('id-ID')}`;
      };

      // Menambah event listener pada elemen input dan tombol hapus
      const addEventListeners = (row) => {
        const selectElement = row.querySelector('select');
        const quantityElement = row.querySelector('input[type="number"]');
        const removeButton = row.querySelector('.remove-item-btn');

        if (selectElement) {
          selectElement.addEventListener('change', calculateTotal);
        }
        if (quantityElement) {
          quantityElement.addEventListener('input', calculateTotal);
        }

        // Menambahkan event listener untuk tombol hapus
        if (removeButton) {
          removeButton.addEventListener('click', function() {
            row.remove(); // Menghapus elemen item
            calculateTotal(); // Menghitung ulang total setelah penghapusan
            toggleRemoveButtonVisibility(); // Menyembunyikan/memperlihatkan tombol hapus
          });
        }
      };

      // Menambah item baru ke form
      addItemButton.addEventListener('click', function() {
        const newItemRow = document.createElement('div');
        newItemRow.classList.add('item-row', 'mb-3');
        newItemRow.id = `item-row-${itemCount}`;

        newItemRow.innerHTML = `
            <label for="item_${itemCount}" class="block text-sm font-medium text-black form-label">Pilih Item</label>
            <select name="items[${itemCount}][item_id]" id="item_${itemCount}" class="text-gray-500 w-full p-2 border border-gray-300 rounded-md form-select" required>
                <option class="text-black" value="" selected disabled>Pilih Item</option>
                @foreach ($allItems as $item)
                    @if ($item->price != 0)
                        <option class="text-black" value="{{ $item->id }}" data-price="{{ $item->price }}">{{ $item->name }} ({{ number_format($item->price, 0, ',', '.') }})</option>
                    @endif
                @endforeach
            </select>

            <label for="quantity_${itemCount}" class="block text-sm font-medium text-black form-label mt-2">Jumlah</label>
            <input type="number" name="items[${itemCount}][quantity]" min="1" id="quantity_${itemCount}" required class="text-gray-500 w-full p-2 border border-gray-300 rounded-md form-control">

            <button type="button" class="remove-item-btn bg-red-500 text-white font-semibold py-1 px-4 rounded-lg hover:bg-red-700 mt-2" data-index="${itemCount}">Hapus</button>
        `;

        itemsContainer.appendChild(newItemRow);
        addEventListeners(newItemRow);
        itemCount++;

        // Periksa dan sesuaikan tombol hapus setelah menambahkan item baru
        toggleRemoveButtonVisibility();
      });

      // Fungsi untuk menyembunyikan atau menampilkan tombol hapus
      const toggleRemoveButtonVisibility = () => {
        const itemRows = document.querySelectorAll('.item-row');
        itemRows.forEach((row, index) => {
          const removeButton = row.querySelector('.remove-item-btn');
          if (itemRows.length === 1) {
            removeButton.style.display = 'none'; // Sembunyikan tombol hapus jika hanya satu item
          } else {
            removeButton.style.display = 'inline-block'; // Tampilkan tombol hapus jika ada lebih dari satu item
          }
        });
      };

      // Menambahkan event listeners untuk item yang sudah ada
      document.querySelectorAll('.item-row').forEach(addEventListeners);

      // Periksa tombol hapus ketika pertama kali dimuat
      toggleRemoveButtonVisibility();

      calculateTotal(); // Perhitungan total awal
    });


    // Fungsi untuk toggle dropdown kategori
    function dropdownKategori() {
      const dropdownKategoriMenu = document.getElementById('dropdownKategoriMenu');
      dropdownKategoriMenu.classList.toggle('hidden');
    }

    // Menutup dropdown ketika klik di luar
    document.addEventListener("click", function(event) {
      const dropdownKategoriMenu = document.getElementById("dropdownKategoriMenu");
      const dropdownKategoriButton = document.getElementById("dropdownKategoriButton");

      if (!dropdownKategoriMenu.contains(event.target) && !dropdownKategoriButton.contains(event.target)) {
        dropdownKategoriMenu.classList.add("hidden");
      }
    });

    // Fungsi untuk toggle dropdown pemasukkan
    function dropdownPemasukkan() {
      const dropdownPemasukkanMenu = document.getElementById('dropdownPemasukkanMenu');
      dropdownPemasukkanMenu.classList.toggle('hidden');
    }

    // Menutup dropdown ketika klik di luar
    document.addEventListener("click", function(event) {
      const dropdownPemasukkanMenu = document.getElementById("dropdownPemasukkanMenu");
      const dropdownPemasukkanButton = document.getElementById("dropdownPemasukkanButton");

      if (!dropdownPemasukkanMenu.contains(event.target) && !dropdownPemasukkanButton.contains(event.target)) {
        dropdownPemasukkanMenu.classList.add("hidden");
      }
    });

    // Fungsi untuk toggle dropdown laporan
    function dropdownLaporan() {
      const dropdownLaporanMenu = document.getElementById('dropdownLaporanMenu');
      dropdownLaporanMenu.classList.toggle('hidden');
    }

    // Menutup dropdown ketika klik di luar
    document.addEventListener("click", function(event) {
      const dropdownLaporanMenu = document.getElementById("dropdownLaporanMenu");
      const dropdownLaporanButton = document.getElementById("dropdownLaporanButton");

      if (!dropdownLaporanMenu.contains(event.target) && !dropdownLaporanButton.contains(event.target)) {
        dropdownLaporanMenu.classList.add("hidden");
      }
    });
  </script>
</x-app-layout>