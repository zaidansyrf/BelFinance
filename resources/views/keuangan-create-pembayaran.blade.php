@section('title', 'Pembayaran | Tambah')
<x-app-layout>
    <div class="h-screen w-full bg-gray-100 flex overflow-hidden">
        <!-- sidebar -->
        <div class="drawer lg:drawer-open">
            <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />

            <!-- drawer content -->
            <div class="drawer-content flex flex-col h-screen">
                <!-- navbar -->
                @include('layouts.navbar')
                <!-- main content -->
                <div class="flex-1 bg-[#D1DDD5] overflow-auto">
                    <div class="sticky justify-between items-center mt-12 px-8">
                        <div class="mb-4">
                            <!-- Tombol Kembali -->
                            <button type="button"
                                class="bg-[#2B7A78] text-white font-semibold py-2 px-6 rounded-lg hover:bg-[#205C5D]"
                                onclick="window.location.href='{{ url('/keuangan/pembayaran') }}'">
                                &larr; Kembali
                            </button>

                        </div>
                        <div class="container">
                            <h1 class="text-xl font-semibold text-[#2B7A78] mb-4">Transaksi Baru</h1>
                            <div class="card text-primary-content bg-white mt-4 w-full">
                                <div class="card-body">
                                    <form action="{{ route('pembayaran.store') }}" method="POST">
                                        @csrf
                                        <div class="mb-4" id="items-container">
                                            <!-- Nama Pesanan -->
                                            <div>
                                                <label for="name"
                                                    class="block text-sm font-medium text-black form-label">Jenis
                                                    Pesanan</label>
                                                <select name="name" id="name"
                                                    class="text-gray-500 w-full p-2 border border-gray-300 rounded-md form-select"
                                                    required>
                                                    <option class="text-black" value="" selected disabled>Pilih
                                                        Jenis Pesanan</option>
                                                    <option value="Pesanan Offline">Pesanan Offline</option>
                                                    <option value="Pesanan Online">Pesanan Online</option>
                                                </select>
                                            </div>

                                            <!-- Pilih Sumber -->
                                            <div class="item-row mb-3">
                                                <label for="source"
                                                    class="block text-sm font-medium text-black form-label mt-2">Dari
                                                    Sumber</label>
                                                <select name="source_id" id="source"
                                                    class="text-gray-500 w-full p-2 border border-gray-300 rounded-md form-select"
                                                    required>
                                                    <option class="text-black" value="" selected disabled>Pilih
                                                        Sumber</option>
                                                    @if ($allSources->count())
                                                        @foreach ($allSources as $source)
                                                            <option value="{{ $source->id }}">{{ $source->name }}
                                                            </option>
                                                        @endforeach
                                                    @else
                                                        <option disabled>Data sumber tidak tersedia</option>
                                                    @endif
                                                </select>
                                            </div>

                                            <!-- Pilih Item -->
                                            <div class="item-row mb-3" id="item-row-0">
                                                <label for="item_0"
                                                    class="block text-sm font-medium text-black form-label">Pilih
                                                    Item</label>
                                                <select name="items[0][item_id]" id="item_0"
                                                    class="text-gray-500 w-full p-2 border border-gray-300 rounded-md form-select"
                                                    required>
                                                    <option class="text-black" value="" selected disabled>Pilih
                                                        Item</option>
                                                    @foreach ($allItems as $item)
                                                        @if ($item->price != 0)
                                                            <option class="text-black" value="{{ $item->id }}"
                                                                data-price="{{ $item->price }}">{{ $item->name }}
                                                                ({{ number_format($item->price, 0, ',', '.') }})
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>

                                                <label for="quantity_0"
                                                    class="block text-sm font-medium text-black form-label mt-2">Jumlah</label>
                                                <input type="number" name="items[0][quantity]" min="1"
                                                    id="quantity_0" required
                                                    class="text-gray-500 w-full p-2 border border-gray-300 rounded-md form-control">

                                                <!-- Tombol Hapus -->
                                                <!-- <button type="button" class="remove-item-btn bg-red-500 text-white font-semibold py-1 px-4 rounded-lg hover:bg-red-700 mt-2" data-index="0">Hapus</button> -->
                                            </div>
                                        </div>

                                        <!-- Menampilkan Total -->
                                        <div class="mt-4">
                                            <p class="text-black font-medium">Total Harga: <span id="total">Rp
                                                    0</span></p>
                                        </div>

                                        <!-- Buttons -->
                                        <div class="flex justify-end gap-4 mt-4">
                                            <button type="reset"
                                                class="bg-[#db5461] text-white font-semibold py-2 px-6 rounded-lg hover:bg-gray-600">Reset</button>
                                            <button type="button" id="add-item"
                                                class="bg-[#2B7A78] text-white font-semibold py-2 px-6 rounded-lg hover:bg-[#205C5D]">Tambah
                                                <span class="hidden sm:inline">Item</span></button>
                                            <button type="submit"
                                                class="bg-[#2B7A78] text-white font-semibold py-2 px-6 rounded-lg hover:bg-[#205C5D]">Simpan
                                                <span class="hidden sm:inline">Pesanan</span></button>
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
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500 hover:text-gray-700"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </label>
                    </div>
                    <!-- Sidebar Logo -->
                    <div class="text-[#2B7A78] text-center mb-4 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="mt-10 mb-6 h-8 w-8 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <h1 class="mt-10 mb-6 text-xl font-bold">BelFinance</h1>
                    </div>
                    <!-- Sidebar Menu Links -->
                    <li>
                        <a href="{{ url('/keuangan/dashboard') }}"
                            class=" text-black  hover:bg-[#2B7A78] hover:text-[#DEF2F1] mb-2 block w-full px-4 py-2">
                            Dashboard
                        </a>
                    </li>
                    <li class="relative">
                        <button id="dropdownPemasukkanButton" onclick="dropdownPemasukkan()"
                            class="bg-[#116A71] text-white hover:bg-[#2B7A78] hover:text-[#DEF2F1] mt-2 mb-2 block w-full px-4 py-2 text-left flex items-center gap-2 transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 122.88 96.2"
                                class="w-4 h-4 fill-current text-white group-hover:text-[#DEF2F1] transition-colors duration-200">
                                <path d="M0,42.92h24.83V87.1H0V42.92L0,42.92z M85.36,20.49c9.01,0,16.32,7.3,16.32,16.32
                  c0,9.01-7.3,16.32-16.32,16.32c-9.01,0-16.32-7.3-16.32-16.32C69.04,27.8,76.34,20.49,85.36,20.49z
                  M56.72,0C64.49,0,70.8,6.3,70.8,14.08c0,7.77-6.3,14.08-14.08,14.08c-7.77,0-14.08-6.3-14.08-14.08
                  C42.64,6.31,48.94,0,56.72,0z
                  M29.83,83.39V46.47h16.61c7.04,1.26,14.08,5.08,21.12,9.51h12.9c5.84,0.35,8.9,6.27,3.22,10.16
                  c-4.52,3.32-10.49,3.13-16.61,2.58c-4.22-0.21-4.4,5.46,0,5.48c1.53,0.12,3.19-0.24,4.64-0.24
                  c7.64-0.01,13.92-1.47,17.77-7.5l1.93-4.51l19.19-9.51c9.6-3.16,16.42,6.88,9.35,13.87
                  c-13.9,10.11-28.15,18.43-42.73,25.15c-10.59,6.44-21.18,6.22-31.76,0z" />
                            </svg>
                            <span class="group-hover:text-[#DEF2F1] transition-colors duration-200">Pemasukkan</span>
                        </button>
                        <ul id="dropdownPemasukkanMenu"
                            class="hidden bg-[#116A71] rounded text-white shadow-lg left-0 m-0 pl-0">
                            <li class="px-0 py-0 cursor-pointer"><a href="{{ url('/keuangan/pembayaran') }}"
                                    class="hover:bg-[#3A9B98] hover:rounded-none">Pembayaran</a></li>
                            <li class="px-0 py-0 cursor-pointer"><a href="{{ url('/keuangan/uang-masuk') }}"
                                    class="hover:bg-[#3A9B98] hover:rounded-none">Uang Masuk</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="{{ url('/keuangan/pengeluaran') }}"
                            class="text-black hover:bg-[#2B7A78] hover:text-[#DEF2F1] mb-4 mt-2 block w-full px-4 py-2 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor"
                                viewBox="0 0 452 512.11">
                                <path
                                    d="M336.47 255.21h64.36v-12.46c-3.68-13.63-9.54-22.87-17.13-28.49-7.59-5.61-17.43-8.01-28.98-7.93l-263.96.06c-6.5 0-11.76-5.27-11.76-11.76 0-6.5 5.26-11.76 11.76-11.76l263.65.03c16.59-.16 31.23 3.62 43.25 12.53 1.08.8 2.14 1.64 3.17 2.52v-7.07c0-10.98-4.53-21.02-11.82-28.31-7.23-7.29-17.25-11.8-28.29-11.8h-8.49l-1.09-.05-4.15 15.56h-28.52l16.92-63.47c-14.22-3.8-22.7-18.5-18.89-32.72l-94.11-25.21c-3.81 14.21-18.5 22.71-32.7 18.9l-27.63 102.5h-29.41L177.4 0l199.7 53.51-19.69 73.73h3.31c17.45 0 33.36 7.19 44.9 18.72 11.56 11.51 18.73 27.45 18.73 44.92v64.99c6.79 1.35 12.86 4.71 17.57 9.42 6.21 6.21 10.08 14.81 10.08 24.28v77.35c0 9.87-4.04 18.85-10.52 25.32-4.63 4.63-10.53 8.02-17.13 9.57v46.66c0 17.46-7.18 33.39-18.72 44.93l-.74.68c-11.5 11.13-27.11 18.03-44.17 18.03H63.63c-17.47 0-33.4-7.17-44.94-18.7C7.17 481.89 0 465.98 0 448.47V190.88c0-17.52 7.16-33.43 18.68-44.95 11.52-11.52 27.44-18.69 44.95-18.69h37.12l.16.01L130.46 17.5l28.19 7.55-38.73 141.23H90.4l4.18-15.51H63.63c-11.01 0-21.04 4.52-28.32 11.79-7.27 7.27-11.79 17.31-11.79 28.32v257.59c0 11.01 4.53 21.03 11.81 28.3 7.28 7.29 17.32 11.82 28.3 11.82h297.09c10.73 0 20.54-4.3 27.74-11.25l.54-.58c7.29-7.28 11.83-17.32 11.83-28.29v-45.71h-64.36c-19.88 0-37.96-8.14-51.02-21.2l-1.23-1.35c-12.36-13-19.98-30.52-19.98-49.68v-3.1c0-19.79 8.13-37.83 21.21-50.94l.13-.13c13.1-13.05 31.12-21.15 50.89-21.15zm-95.71-93.06c17.19 4.6 34.89-5.6 39.49-22.8 4.61-17.19-5.61-34.89-22.8-39.49-17.2-4.6-34.9 5.6-39.5 22.8-4.6 17.19 5.62 34.88 22.81 39.49zM362.3 309.07l.06.05c10.93 10.96 10.9 28.79-.02 39.74l-.05.06c-10.96 10.93-28.79 10.9-39.75-.02l-.05-.05c-10.93-10.96-10.9-28.79.02-39.75l.05-.05c10.96-10.93 28.79-10.91 39.74.02z"
                                    fill="" />
                            </svg>
                            <span class="group-hover:text-[#DEF2F1] transition-colors duration-200">Pengeluaran</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/keuangan/menu') }}"
                            class="menu-link text-black hover:bg-[#2B7A78] hover:text-[#DEF2F1] mb-4 block w-full px-4 py-2 flex items-center gap-2 group">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 122.88 90.02" fill="currentColor"
                                class="w-5 h-5 icon group-hover:text-white">
                                <path
                                    d="M0 8.62c17.28-10.66 34.96-12.3 53.26 0v79.64c-12.65-9.37-31.03-8.24-53.26 0V8.62zm59.09.2h5.28c1.08 0 1.96.88 1.96 1.95v77.29c0 1.08-.88 1.96-1.96 1.96h-5.28a1.97 1.97 0 01-1.96-1.96V10.77c.01-1.07.89-1.95 1.96-1.95zm63.79-.2c-17.28-10.66-34.97-12.3-53.27 0v79.64c12.65-9.37 31.03-8.24 53.27 0V8.62z"
                                    stroke="black" stroke-width="5" fill="white" />
                            </svg>
                            <span class="group-hover:text-[#DEF2F1] transition-colors duration-200">Menu</span>
                        </a>
                    </li>
                    <li class="relative">
                        <!-- Dropdown Kategori -->
                        <button id="dropdownKategoriButton" onclick="dropdownKategori()"
                            class="text-black hover:bg-[#2B7A78] hover:text-[#DEF2F1] mt-2 mb-2 w-full px-4 py-2 text-left flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                viewBox="0 0 122.879 122.891" fill="currentColor">
                                <path d="M89.767,18.578c3.848,0,7.332,1.561,9.854,4.082c2.521,2.522,4.082,6.007,4.082,9.855s-1.561,7.332-4.082,9.854
                  c-2.522,2.522-6.007,4.082-9.854,4.082c-3.849,0-7.333-1.56-9.854-4.082c-2.522-2.522-4.082-6.006-4.082-9.854
                  s1.56-7.333,4.082-9.855C82.434,20.138,85.918,18.578,89.767,18.578L89.767,18.578z M122.04,56.704l-65.337,65.337
                  c-1.132,1.133-2.969,1.133-4.101,0L0.849,70.287c-1.132-1.131-1.132-2.967,0-4.1L66.186,0.85C66.752,0.284,67.494,0,68.236,0v0
                  h50.051c1.602,0,2.9,1.298,2.9,2.9c0,0.048-0.002,0.097-0.004,0.145l1.694,51.517c0.026,0.83-0.301,1.589-0.845,2.134
                  L122.04,56.704L122.04,56.704z M54.652,115.889l62.406-62.407L115.49,5.8H69.438L7.001,68.238L54.652,115.889L54.652,115.889z
                  M96.244,26.037c-1.657-1.657-3.948-2.683-6.478-2.683c-2.53,0-4.82,1.025-6.478,2.683c-1.658,1.657-2.684,3.948-2.684,6.478
                  s1.025,4.82,2.684,6.478c1.657,1.658,3.947,2.683,6.478,2.683c2.529,0,4.82-1.025,6.478-2.683s2.683-3.948,2.683-6.478
                  S97.901,27.694,96.244,26.037L96.244,26.037z" />
                            </svg>
                            <!-- Teks -->
                            <span class="text-sm">Kategori</span>
                        </button>
                        <!-- Dropdown Menu -->
                        <ul id="dropdownKategoriMenu"
                            class="hidden bg-[#116A71] rounded text-white shadow-lg left-0 m-0 pl-0">
                            <li class="px-0 py-0 cursor-pointer"><a
                                    href="{{ url('/keuangan/kategori/sumber-masuk') }}"
                                    class="hover:bg-[#3A9B98] hover:rounded-none">Sumber Masuk</a></li>
                            <li class="px-0 py-0 cursor-pointer"><a
                                    href="{{ url('/keuangan/kategori/sumber-keluar') }}"
                                    class="hover:bg-[#3A9B98] hover:rounded-none">Sumber Keluar</a></li>
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

                    if (selectElement && selectElement.value && quantityElement && quantityElement
                        .value) {
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
                        removeButton.style.display =
                            'none'; // Sembunyikan tombol hapus jika hanya satu item
                    } else {
                        removeButton.style.display =
                            'inline-block'; // Tampilkan tombol hapus jika ada lebih dari satu item
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

            if (!dropdownPemasukkanMenu.contains(event.target) && !dropdownPemasukkanButton.contains(event
                    .target)) {
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
