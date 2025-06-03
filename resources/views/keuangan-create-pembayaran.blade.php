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

            @include('layouts.sidebar')

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
