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
                                        <div class="mb-4">
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

                                            <!-- Tanggal Transaksi -->
                                            <div class="mt-3">
                                                <label for="date"
                                                    class="block text-sm font-medium text-black form-label">Tanggal
                                                    Transaksi</label>
                                                <input type="date" name="date" id="date"
                                                    value="{{ old('date', date('Y-m-d')) }}"
                                                    class="text-gray-500 w-full p-2 border border-gray-300 rounded-md form-control"
                                                    required>
                                            </div>

                                            <!-- Pilih Sumber -->
                                            <div class="mb-3 mt-3">
                                                <label for="source"
                                                    class="block text-sm font-medium text-black form-label">Dari
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

                                            <!-- Sumber Pesanan (GoFood, GrabFood, dll) -->
                                            <div class="mb-3 mt-3">
                                                <label for="order_source"
                                                    class="block text-sm font-medium text-black form-label">
                                                    Platform Pesanan
                                                    <span class="text-gray-400 text-xs">(opsional, mis: GoFood, GrabFood, Manual)</span>
                                                </label>
                                                <input type="text" name="order_source" id="order_source"
                                                    placeholder="GoFood / GrabFood / Manual / ..."
                                                    class="text-gray-500 w-full p-2 border border-gray-300 rounded-md form-control">
                                            </div>

                                            <!-- Promo Aplikasi -->
                                            <div class="mb-3 mt-3 bg-blue-50 border border-blue-200 rounded-lg p-4">
                                                <h3 class="text-sm font-semibold text-blue-800 mb-3">🏷️ Promo Aplikasi (Opsional)</h3>
                                                <div class="flex gap-3 items-end">
                                                    <div class="w-28">
                                                        <label class="block text-sm font-medium text-black mb-1">Jenis</label>
                                                        <select name="app_discount_type" id="app_discount_type"
                                                            class="text-gray-500 w-full p-2 border border-gray-300 rounded-md">
                                                            <option value="">Tanpa Promo</option>
                                                            <option value="percent">Persentase (%)</option>
                                                            <option value="nominal">Nominal (Rp)</option>
                                                        </select>
                                                    </div>
                                                    <div class="flex-1">
                                                        <label class="block text-sm font-medium text-black mb-1">Nilai Promo</label>
                                                        <input type="number" name="app_discount_value" id="app_discount_value"
                                                            min="0" placeholder="0"
                                                            class="text-gray-500 w-full p-2 border border-gray-300 rounded-md">
                                                    </div>
                                                </div>
                                                <div class="mt-3 flex items-center gap-2">
                                                    <input type="hidden" name="is_app_subsidy" value="0">
                                                    <input type="checkbox" name="is_app_subsidy" id="is_app_subsidy"
                                                        value="1"
                                                        class="w-4 h-4 text-blue-600 rounded">
                                                    <label for="is_app_subsidy" class="text-sm text-gray-700">
                                                        <strong>Subsidi Penuh Aplikasi</strong>
                                                        <span class="text-gray-400">(diskon ditanggung aplikasi, bukan merchant)</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-4" id="items-container">
                                            <!-- Pilih Item -->
                                            <div class="item-row mb-3 border border-gray-200 rounded-lg p-3" id="item-row-0">
                                                <label for="item_0"
                                                    class="block text-sm font-medium text-black form-label">Pilih
                                                    Item</label>
                                                <select name="items[0][item_id]" id="item_0"
                                                    class="item-select text-gray-500 w-full p-2 border border-gray-300 rounded-md form-select"
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

                                                <div class="flex gap-2 mt-2">
                                                    <div class="flex-1">
                                                        <label for="quantity_0"
                                                            class="block text-sm font-medium text-black form-label">Jumlah</label>
                                                        <input type="number" name="items[0][quantity]" min="1"
                                                            id="quantity_0" required
                                                            class="qty-input text-gray-500 w-full p-2 border border-gray-300 rounded-md form-control">
                                                    </div>
                                                    {{-- Diskon per item (opsional) --}}
                                                    <div class="w-28">
                                                        <label class="block text-sm font-medium text-black">Jenis Diskon</label>
                                                        <select name="items[0][item_discount_type]"
                                                            class="disc-type text-gray-500 w-full p-2 border border-gray-300 rounded-md">
                                                            <option value="">Tanpa</option>
                                                            <option value="percent">%</option>
                                                            <option value="nominal">Rp</option>
                                                        </select>
                                                    </div>
                                                    <div class="w-32">
                                                        <label class="block text-sm font-medium text-black">Nilai Diskon</label>
                                                        <input type="number" name="items[0][item_discount_value]" min="0"
                                                            class="disc-value text-gray-500 w-full p-2 border border-gray-300 rounded-md" placeholder="0">
                                                    </div>
                                                </div>
                                                <!-- Tombol Hapus (disembunyikan jika hanya 1 item) -->
                                            </div>
                                        </div>

                                        <!-- Menampilkan Total -->
                                        <div class="mt-4 bg-gray-50 rounded-lg p-4 border border-gray-200">
                                            <div class="flex justify-between text-sm text-gray-700 mb-1">
                                                <span>Subtotal (sebelum promo app):</span>
                                                <span id="gross-total-display">Rp 0</span>
                                            </div>
                                            <div id="app-discount-row" class="flex justify-between text-sm text-red-500 mb-1 hidden">
                                                <span>Promo Aplikasi:</span>
                                                <span id="app-discount-display">- Rp 0</span>
                                            </div>
                                            <div class="flex justify-between font-semibold text-black border-t pt-2 mt-1">
                                                <span>Total Tagihan (Net):</span>
                                                <span id="total">Rp 0</span>
                                            </div>
                                        </div>

                                        <!-- Buttons -->
                                        <div class="flex justify-end gap-4 mt-4">
                                            <button type="reset"
                                                class="bg-[#db5461] text-white font-semibold py-2 px-4 rounded-lg hover:bg-gray-600">Reset</button>
                                            <button type="button" id="add-item"
                                                class="bg-[#2B7A78] text-white font-semibold py-2 px-4 rounded-lg hover:bg-[#205C5D] flex items-center justify-center">
                                                <span class="sm:hidden ">+</span>

                                                <span class="hidden sm:inline">Tambah Item</span>
                                            </button>

                                            <button type="submit"
                                                class="bg-[#2B7A78] text-white font-semibold py-2 px-4 rounded-lg hover:bg-[#205C5D]">Simpan
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
            const grossDisplay = document.getElementById('gross-total-display');
            const appDiscountRow = document.getElementById('app-discount-row');
            const appDiscountDisplay = document.getElementById('app-discount-display');
            const appDiscountType = document.getElementById('app_discount_type');
            const appDiscountValue = document.getElementById('app_discount_value');
            let itemCount = 1;

            const formatRp = (val) => `Rp ${Math.round(val).toLocaleString('id-ID')}`;

            // Menghitung total harga (dengan diskon item + promo aplikasi)
            const calculateTotal = () => {
                const itemRows = document.querySelectorAll('.item-row');
                let grossTotal = 0;

                itemRows.forEach(itemRow => {
                    const selectElement = itemRow.querySelector('.item-select');
                    const quantityElement = itemRow.querySelector('.qty-input');
                    const discTypeEl = itemRow.querySelector('.disc-type');
                    const discValueEl = itemRow.querySelector('.disc-value');

                    if (selectElement && selectElement.value && quantityElement && quantityElement.value) {
                        const selectedOption = selectElement.selectedOptions[0];
                        const price = parseFloat(selectedOption.dataset.price || 0);
                        const quantity = parseInt(quantityElement.value || 0, 10);

                        if (!isNaN(price) && !isNaN(quantity)) {
                            const rawTotal = price * quantity;

                            // Hitung diskon item
                            let diskonNominal = 0;
                            const discType = discTypeEl ? discTypeEl.value : '';
                            const discVal = discValueEl ? parseFloat(discValueEl.value || 0) : 0;
                            if (discType === 'percent' && discVal > 0) {
                                diskonNominal = (discVal / 100) * price * quantity;
                            } else if (discType === 'nominal' && discVal > 0) {
                                diskonNominal = discVal * quantity;
                            }

                            grossTotal += Math.max(0, rawTotal - diskonNominal);
                        }
                    }
                });

                // Promo aplikasi
                const appType = appDiscountType ? appDiscountType.value : '';
                const appVal = appDiscountValue ? parseFloat(appDiscountValue.value || 0) : 0;
                let appDiscount = 0;
                if (appType === 'percent' && appVal > 0) {
                    appDiscount = (appVal / 100) * grossTotal;
                } else if (appType === 'nominal' && appVal > 0) {
                    appDiscount = appVal;
                }
                const netTotal = Math.max(0, grossTotal - appDiscount);

                // Tampilkan
                if (grossDisplay) grossDisplay.innerText = formatRp(grossTotal);
                if (appDiscountRow && appDiscountDisplay) {
                    if (appDiscount > 0) {
                        appDiscountRow.classList.remove('hidden');
                        appDiscountDisplay.innerText = `- ${formatRp(appDiscount)}`;
                    } else {
                        appDiscountRow.classList.add('hidden');
                    }
                }
                if (totalElement) totalElement.innerText = formatRp(netTotal);
            };

            // Menambah event listener pada elemen input dan tombol hapus
            const addEventListeners = (row) => {
                row.querySelectorAll('select, input[type="number"]').forEach(el => {
                    el.addEventListener('change', calculateTotal);
                    el.addEventListener('input', calculateTotal);
                });

                const removeButton = row.querySelector('.remove-item-btn');
                // Menambahkan event listener untuk tombol hapus
                if (removeButton) {
                    removeButton.addEventListener('click', function() {
                        row.remove(); // Menghapus elemen item
                        calculateTotal(); // Menghitung ulang total setelah penghapusan
                        toggleRemoveButtonVisibility(); // Menyembunyikan/memperlihatkan tombol hapus
                    });
                }
            };

            // Listener promo aplikasi
            if (appDiscountType) appDiscountType.addEventListener('change', calculateTotal);
            if (appDiscountValue) appDiscountValue.addEventListener('input', calculateTotal);

            // Menambah item baru ke form
            addItemButton.addEventListener('click', function() {
                const newItemRow = document.createElement('div');
                newItemRow.classList.add('item-row', 'mb-3', 'border', 'border-gray-200', 'rounded-lg', 'p-3');
                newItemRow.id = `item-row-${itemCount}`;

                newItemRow.innerHTML = `
            <label for="item_${itemCount}" class="block text-sm font-medium text-black form-label">Pilih Item</label>
            <select name="items[${itemCount}][item_id]" id="item_${itemCount}" class="item-select text-gray-500 w-full p-2 border border-gray-300 rounded-md form-select" required>
                <option class="text-black" value="" selected disabled>Pilih Item</option>
                @foreach ($allItems as $item)
                    @if ($item->price != 0)
                        <option class="text-black" value="{{ $item->id }}" data-price="{{ $item->price }}">{{ $item->name }} ({{ number_format($item->price, 0, ',', '.') }})</option>
                    @endif
                @endforeach
            </select>

            <div class="flex gap-2 mt-2">
                <div class="flex-1">
                    <label for="quantity_${itemCount}" class="block text-sm font-medium text-black form-label">Jumlah</label>
                    <input type="number" name="items[${itemCount}][quantity]" min="1" id="quantity_${itemCount}" required class="qty-input text-gray-500 w-full p-2 border border-gray-300 rounded-md form-control">
                </div>
                <div class="w-28">
                    <label class="block text-sm font-medium text-black">Jenis Diskon</label>
                    <select name="items[${itemCount}][item_discount_type]" class="disc-type text-gray-500 w-full p-2 border border-gray-300 rounded-md">
                        <option value="">Tanpa</option>
                        <option value="percent">%</option>
                        <option value="nominal">Rp</option>
                    </select>
                </div>
                <div class="w-32">
                    <label class="block text-sm font-medium text-black">Nilai Diskon</label>
                    <input type="number" name="items[${itemCount}][item_discount_value]" min="0" class="disc-value text-gray-500 w-full p-2 border border-gray-300 rounded-md" placeholder="0">
                </div>
            </div>

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
                    if (removeButton) {
                        if (itemRows.length === 1) {
                            removeButton.style.display =
                                'none'; // Sembunyikan tombol hapus jika hanya satu item
                        } else {
                            removeButton.style.display =
                                'inline-block'; // Tampilkan tombol hapus jika ada lebih dari satu item
                        }
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
