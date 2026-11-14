@section('title', 'Sumber masuk | Edit')
<x-app-layout>
    <div class="h-screen w-full bg-gray-100 flex overflow-hidden">
        <!-- sidebar -->
        <div class="drawer lg:drawer-open">
            <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />

            <!-- drawer content -->
            <div class="drawer-content flex flex-col h-screen">
                <!-- wrapper for spacing -->
                @include('layouts.navbar')

                <!-- main content -->
                <div class="flex-1 bg-[#D1DDD5] overflow-auto">
                    <div class="sticky justify-between items-center mt-12 px-8">
                        <h1 class="text-xl font-semibold text-[#2B7A78] mb-4">Edit</h1>
                        <div class="card text-primary-content bg-white mt-4 w-full">
                            <div class="card-body">
                                <div class="overflow-x-auto">
                                    <form action="{{ route('sumber-masuk.update', $source->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <!-- Nama Sumber -->
                                        <div class="mb-4">
                                            <label for="sourceName" class="block text-sm font-medium text-gray-700">Nama
                                                Sumber</label>
                                            <input type="text" id="sourceName"
                                                class="text-black w-full p-2 border border-gray-300 rounded-md"
                                                name="name" value="{{ $source->name }}" required>
                                        </div>

                                        <!-- Tipe Sumber (Online / Offline) -->
                                        <div class="mb-4">
                                            <label for="sourceIsOnline" class="block text-sm font-medium text-gray-700 mb-1">
                                                Tipe Pesanan
                                            </label>
                                            <select id="sourceIsOnline" name="is_online" onchange="toggleCommissionField()"
                                                class="w-full p-2 border border-gray-300 rounded-md bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#2B7A78]"
                                                required>
                                                <option value="0" {{ old('is_online', $source->is_online ? '1' : '0') == '0' ? 'selected' : '' }}>Offline</option>
                                                <option value="1" {{ old('is_online', $source->is_online ? '1' : '0') == '1' ? 'selected' : '' }}>Online</option>
                                            </select>
                                            <p class="text-xs text-gray-500 mt-1">
                                                Menentukan promo menu &amp; voucher platform yang aktif saat input penjualan.
                                            </p>
                                        </div>

                                        <!-- Komisi Platform (%) -->
                                        <div id="commissionWrapper" class="mb-4 {{ old('is_online', $source->is_online ? '1' : '0') == '1' ? '' : 'hidden' }}">
                                            <label for="sourceCommission" class="block text-sm font-medium text-gray-700 mb-1">
                                                Komisi Platform (%) <span class="text-xs text-gray-400">(Opsional)</span>
                                            </label>
                                            <input type="number" id="sourceCommission" name="default_commission_percent"
                                                step="0.01" min="0" max="100"
                                                value="{{ old('default_commission_percent', $source->default_commission_percent ?? 0) }}"
                                                class="w-full p-2 border border-gray-300 rounded-md text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#2B7A78]"
                                                placeholder="cth. 20">
                                        </div>
                                        <div class="flex justify-end mt-4">
                                            <a href="{{ route('sumber-masuk.index') }}"
                                                class="bg-[#db5461] text-white font-semibold py-2 px-6 rounded-lg hover:bg-gray-600">Batal</a>
                                            <button type="submit"
                                                class="bg-[#2B7A78] text-white font-semibold py-2 px-6 rounded-lg hover:bg-[#205C5D] ml-4">Update</button>
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
        function toggleCommissionField() {
            const select = document.getElementById('sourceIsOnline');
            const wrapper = document.getElementById('commissionWrapper');
            const commInput = document.getElementById('sourceCommission');
            if (!select || !wrapper) return;

            if (select.value === '1') {
                wrapper.classList.remove('hidden');
            } else {
                wrapper.classList.add('hidden');
                if (commInput) commInput.value = '0';
            }
        }

        // Open Sumber Form
        function openSourceForm() {
            const sourceOverlay = document.getElementById('sourceOverlay');
            const sourceModal = document.getElementById('sourceModal');
            sourceOverlay.classList.remove('hidden');
            sourceModal.classList.remove('hidden');
        }

        function dropdownLaporan() {
            const dropdownLaporanMenu = document.getElementById('dropdownLaporanMenu');
            dropdownLaporanMenu.classList.toggle('hidden');
        }
        document.addEventListener("click", function(event) {
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
        document.addEventListener("click", function(event) {
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

        function dropdownPemasukkan() {
            const dropdownPemasukkanMenu = document.getElementById('dropdownPemasukkanMenu');
            dropdownPemasukkanMenu.classList.toggle('hidden');
        }
        document.addEventListener("click", function(event) {
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
