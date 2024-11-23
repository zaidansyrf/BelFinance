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
                <ul tabindex="0" class="menu dropdown-content bg-white rounded-box z-[1] mt-10 w-40 p-2 shadow-lg">
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
            <button onclick="toggleDropdown()" class="bg-[#2B7A78] text-white font-semibold py-2 px-4 rounded-lg hover:bg-[#205C5D]">
              + Tambah transaksi
            </button>
            <ul id="transactionDropdown" class="absolute hidden bg-white border rounded-lg mt-6 p-2 shadow-lg w-48 z-10">
              <li>
                <a href="#" onclick="openIncomeForm()" class="block px-4 py-2 hover:bg-gray-100 text-black">Pemasukan</a>
              </li>
              <li>
                <a href="#" onclick="openExpenseForm()" class="block px-4 py-2 hover:bg-gray-100 text-black">Pengeluaran</a>
              </li>
            </ul>
          </div>

          <!-- Modal untuk forms -->
          <div id="modalOverlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-20 flex justify-center items-center z-index: 20 inset: 0">
            <!-- Form Pemasukan -->
            <div id="incomeModal" class="hidden bg-white rounded-lg w-[400px] h-auto max-w-[400px] overflow-y-auto shadow-lg p-6 ">
              <h3 class="text-xl font-semibold mb-4">Tambah Pemasukan</h3>
              <form>
                <!-- input -->
                <div class="mb-4">
                  <label for="incomeDate" class="block text-sm font-medium text-gray-700">Tanggal Transaksi</label>
                  <input type="date" id="incomeDate" class="w-full p-2 border border-gray-300 rounded-md">
                </div>
                <div class="mb-4">
                  <label for="incomeSource" class="block text-sm font-medium text-gray-700">Sumber</label>
                  <select id="incomeSource" class="w-full p-2 border border-gray-300 rounded-md" onchange="toggleAdditionalFields()">
                    <option value="" disabled selected>Pilih Sumber</option>
                    <option value="gojek">GoFood</option>
                    <option value="grab">GrabFood</option>
                    <option value="shopee">ShopeeFood</option>
                    <option value="cash">Cash</option>
                    <option value="btn">BTN</option>
                    <option value="bni">BNI</option>
                  </select>
                </div>
                <!-- fields tambahan untuk shopeefoods -->
                  <div id="shopeefoodFields" class="hidden">
                    <div class="mb-4">
                      <label for="buyerName" class="block text-sm font-medium text-gray-700">Nama pembeli</label>
                      <input type="text" id="buyerName" class="w-full p-2 border border-gray-300 rounded-md" placeholder="Masukkan nama pembeli">
                    </div>
                    <div class="mb-4">
                      <label for="menuName" class="block text-sm font-medium text-gray-700">Nama menu</label>
                      <input type="text" id="menuName" class="w-full p-2 border border-gray-300 rounded-md" placeholder="Masukkan nama Menu">
                    </div>
                    <div class="mb-4">
                      <label for="itemQuantity" class="block text-sm font-medium text-gray-700">Jumlah item</label>
                      <input type="number" id="itemQuantity" class="w-full p-2 border border-gray-300 rounded-md" placeholder="Isi jumlah item">
                    </div>
                    <div class="mb-4">
                      <label for="incomeAmount" class="block text-sm font-medium text-gray-700">Jumlah</label>
                      <input type="number" id="incomeAmount" class="w-full p-2 border border-gray-300 rounded-md" placeholder="Isi nominal">
                    </div>
                  </div>
                  <!-- fields tambahan untuk gofoods -->
                  <div id="gofoodFields" class="hidden">
                    <div class="mb-4">
                      <label for="buyerName" class="block text-sm font-medium text-gray-700">Nama pembeli</label>
                      <input type="text" id="buyerName" class="w-full p-2 border border-gray-300 rounded-md" placeholder="Masukkan nama pembeli">
                    </div>
                    <div class="mb-4">
                      <label for="menuName" class="block text-sm font-medium text-gray-700">Nama menu</label>
                      <input type="text" id="menuName" class="w-full p-2 border border-gray-300 rounded-md" placeholder="Masukkan nama menu">
                    </div>
                    <div class="mb-4">
                      <label for="itemQuantity" class="block text-sm font-medium text-gray-700">Jumlah item</label>
                      <input type="number" id="itemQuantity" class="w-full p-2 border border-gray-300 rounded-md" placeholder="Isi jumlah item">
                    </div>
                    <div class="mb-4">
                      <label for="incomeAmount" class="block text-sm font-medium text-gray-700">Jumlah</label>
                      <input type="number" id="incomeAmount" class="w-full p-2 border border-gray-300 rounded-md" placeholder="Isi nominal">
                    </div>
                  </div>
                  <!-- grabfood fields tambahan -->
                  <div id="grabfoodFields" class="hidden">
                    <div class="mb-4">
                      <label for="buyerName" class="block text-sm font-medium text-gray-700">Nama pembeli</label>
                      <input type="text" id="buyerName" class="w-full p-2 border border-gray-300 rounded-md" placeholder="Masukkan nama pembeli">
                    </div>
                    <div class="mb-4">
                      <label for="menuName" class="block text-sm font-medium text-gray-700">Nama menu</label>
                      <input type="text" id="menuName" class="w-full p-2 border border-gray-300 rounded-md" placeholder="Masukkan nama menu">
                    </div>
                    <div class="mb-4">
                      <label for="itemQuantity" class="block text-sm font-medium text-gray-700">Jumlah item</label>
                      <input type="number" id="itemQuantity" class="w-full p-2 border border-gray-300 rounded-md" placeholder="Isi jumlah item">
                    </div>
                    <div class="mb-4">
                      <label for="incomeAmount" class="block text-sm font-medium text-gray-700">Jumlah</label>
                      <input type="number" id="incomeAmount" class="w-full p-2 border border-gray-300 rounded-md" placeholder="Isi nominal">
                    </div>
                  </div>
                  <!-- fields tambahan untuk langsung -->
                  <div id="cashFields" class="hidden overflow-auto">
                    <div class="mb-4">
                      <label for="buyerName" class="block text-sm font-medium text-gray-700">Nama pembeli</label>
                      <input type="text" id="buyerName" class="w-full p-2 border border-gray-300 rounded-md" placeholder="Masukkan nama pembeli">
                    </div>
                    <div class="mb-4">
                      <label for="menuName" class="block text-sm font-medium text-gray-700">Nama menu</label>
                      <input type="text" id="menuName" class="w-full p-2 border border-gray-300 rounded-md" placeholder="Masukkan nama menu">
                    </div>
                    <div class="mb-4">
                      <label for="itemQuantity" class="block text-sm font-medium text-gray-700">Jumlah item</label>
                      <input type="number" id="itemQuantity" class="w-full p-2 border border-gray-300 rounded-md" placeholder="Isi jumlah item">
                    </div>
                    <div class="mb-4">
                      <label for="incomeAmount" class="block text-sm font-medium text-gray-700">Jumlah</label>
                      <input type="number" id="incomeAmount" class="w-full p-2 border border-gray-300 rounded-md" placeholder="Isi nominal">
                    </div>
                  </div>
                  <!-- additional btn fields -->
                  <div id="btnFields" class="hidden">
                    <div class="mb-4">
                      <label for="buyerName" class="block text-sm font-medium text-gray-700">Nama pembeli</label>
                      <input type="text" id="buyerName" class="w-full p-2 border border-gray-300 rounded-md" placeholder="Masukkan nama pembeli">
                    </div>
                    <div class="mb-4">
                      <label for="menuName" class="block text-sm font-medium text-gray-700">Nama menu</label>
                      <input type="text" id="menuName" class="w-full p-2 border border-gray-300 rounded-md" placeholder="Masukkan nama menu">
                    </div>
                    <div class="mb-4">
                      <label for="itemQuantity" class="block text-sm font-medium text-gray-700">Jumlah item</label>
                      <input type="number" id="itemQuantity" class="w-full p-2 border border-gray-300 rounded-md" placeholder="Isi jumlah item">
                    </div>
                    <div class="mb-4">
                      <label for="incomeAmount" class="block text-sm font-medium text-gray-700">Jumlah</label>
                      <input type="number" id="incomeAmount" class="w-full p-2 border border-gray-300 rounded-md" placeholder="Isi nominal">
                    </div>
                  </div>
                  <!-- additional bni fields -->
                  <div id="bniFields" class="hidden">
                    <div class="mb-4">
                      <label for="buyerName" class="block text-sm font-medium text-gray-700">Nama pembeli</label>
                      <input type="text" id="buyerName" class="w-full p-2 border border-gray-300 rounded-md" placeholder="Masukkan nama pembeli">
                    </div>
                    <div class="mb-4">
                      <label for="menuName" class="block text-sm font-medium text-gray-700">Nama menu</label>
                      <input type="text" id="menuName" class="w-full p-2 border border-gray-300 rounded-md" placeholder="Masukkan nama menu">
                    </div>
                    <div class="mb-4">
                      <label for="itemQuantity" class="block text-sm font-medium text-gray-700">Jumlah item</label>
                      <input type="number" id="itemQuantity" class="w-full p-2 border border-gray-300 rounded-md" placeholder="Isi jumlah item">
                    </div>
                    <div class="mb-4">
                      <label for="incomeAmount" class="block text-sm font-medium text-gray-700">Jumlah</label>
                      <input type="number" id="incomeAmount" class="w-full p-2 border border-gray-300 rounded-md" placeholder="Isi nominal">
                    </div>
                  </div>
                <div class="flex justify-end mt-4">
                  <button type="button" onclick="closeModal()" class="bg-gray-500 text-white font-semibold py-2 px-6 rounded-lg hover:bg-gray-600">Batal</button>
                  <button type="submit" class="bg-[#2B7A78] text-white font-semibold py-2 px-6 rounded-lg hover:bg-[#205C5D] ml-4">Simpan</button>
                </div>
              </form>
            </div>

            <!-- Form Pengeluaran -->
            <div id="expenseModal" class="hidden bg-white w-[400px] h-auto max-w-[400px] rounded-lg shadow-lg p-6">
              <h3 class="text-xl font-semibold mb-4">Tambah Pengeluaran</h3>
              <form>
                <!-- input -->
                <div class="mb-4">
                  <label for="expenseDate" class="block text-sm font-medium text-gray-700">Tanggal Transaksi</label>
                  <input type="date" id="expenseDate" class="w-full p-2 border border-gray-300 rounded-md">
                </div>
                <div class="mb-4">
                  <label for="expenseCategory" class="block text-sm font-medium text-gray-700">Kategori Pengeluaran</label>
                  <select id="expenseCategory" class="w-full p-2 border border-gray-300 rounded-md">
                    <option value="" disabled selected>Pilih Kategori</option>
                    <option value="utilities">Tagihan Listrik & Air</option>
                    <option value="rent">Sewa</option>
                    <option value="food">Makanan & Minuman</option>
                  </select>
                </div>
                <div class="mb-4">
                  <label for="expenseAmount" class="block text-sm font-medium text-gray-700">Jumlah</label>
                  <input type="number" id="expenseAmount" class="w-full p-2 border border-gray-300 rounded-md" placeholder="Isi nominal">
                </div>
                <div class="flex justify-end mt-4">
                  <button type="button" onclick="closeModal()" class="bg-gray-500 text-white font-semibold py-2 px-6 rounded-lg hover:bg-gray-600">Batal</button>
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
            <a href="{{ url('/admin/keuangan/dashboard') }}" 
              class="{{ Request::is('admin/keuangan/dashboard') ? 'bg-[#2B7A78] text-white' : 'hover:bg-[#2B7A78] hover:text-[#DEF2F1]' }} mb-4 block w-full px-4 py-2">
              Dashboard
            </a>
          </li>
          <li>
            <a href="{{ url('/admin/keuangan/transaksi') }}" 
              class="{{ Request::is('admin/keuangan/transaksi') ? 'bg-[#2B7A78] text-white' : 'hover:bg-[#2B7A78] hover:text-[#DEF2F1]' }} mb-4 block w-full px-4 py-2">
              Transaksi
            </a>
          </li>
          <li>
            <a href="{{ url('/admin/keuangan/laporan-keuangan') }}" 
              class="{{ Request::is('admin/keuangan/laporan-keuangan') ? 'bg-[#2B7A78] text-white' : 'hover:bg-[#2B7A78] hover:text-[#DEF2F1]' }} mb-4 block w-full px-4 py-2">
              Laporan Keuangan
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <script>
  // dropdown pilihan
  function toggleDropdown() {
    const dropdown = document.getElementById('transactionDropdown');
    dropdown.classList.toggle('hidden');
  }

  // memunculkan modal dari form pemasukkan
  function openIncomeForm() {
    closeModal();
    document.getElementById('modalOverlay').classList.remove('hidden');
    document.getElementById('incomeModal').classList.remove('hidden');
    closeDropdown(); // Automatically close dropdown
  }

  // memunculkan modal dari form pengeluaran
  function openExpenseForm() {
    closeModal(); // Close other modals if open
    document.getElementById('modalOverlay').classList.remove('hidden');
    document.getElementById('expenseModal').classList.remove('hidden');
    closeDropdown(); // Automatically close dropdown
  }

  // Close All Modals
  function closeModal() {
    document.getElementById('modalOverlay').classList.add('hidden');
    document.getElementById('incomeModal').classList.add('hidden');
    document.getElementById('expenseModal').classList.add('hidden');
  }

  // Close Dropdown
  function closeDropdown() {
    const dropdown = document.getElementById('transactionDropdown');
    dropdown.classList.add('hidden');
  }

  // Toggle Additional Fields in Income Form
  function toggleAdditionalFields() {
    const incomeSource = document.getElementById('incomeSource').value;

    // Hide all additional fields first
    document.getElementById('shopeefoodFields').classList.add('hidden');
    document.getElementById('grabfoodFields').classList.add('hidden');
    document.getElementById('gofoodFields').classList.add('hidden');
    document.getElementById('cashFields').classList.add('hidden');
    document.getElementById('btnFields').classList.add('hidden');
    document.getElementById('bniFields').classList.add('hidden');

    // Show relevant fields based on the selected income source
    if (incomeSource === 'shopee') {
      document.getElementById('shopeefoodFields').classList.remove('hidden');
    } else if (incomeSource === 'gojek') {
      document.getElementById('gofoodFields').classList.remove('hidden');
    }else if (incomeSource === 'grab'){
      document.getElementById('grabfoodFields').classList.remove('hidden');
    }else if (incomeSource === 'cash') {
      document.getElementById('cashFields').classList.remove('hidden');
    }else if (incomSource === 'btn'){
      document.getElementById('btnFields').classList.remove('hidden');
    }else if (incomeSource === 'bni'){
      document.getElementById('bniFields').classList.remove('hidden');
    }
  }

  // Close Dropdown When Click Outside
  window.onclick = function (event) {
    if (!event.target.matches('button') && !event.target.matches('.block')) {
      closeDropdown();
    }
  };

</script>
</x-app-layout>
