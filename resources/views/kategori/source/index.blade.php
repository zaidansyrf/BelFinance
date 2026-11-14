@section('title', 'Kategori | Sumber masuk')
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
                        <h1 class="text-xl font-semibold text-[#2B7A78] mb-4">Sumber masuk</h1>
                        <button onclick="openSourceForm()"
                            class="bg-[#2B7A78] text-white font-semibold py-2 px-4 rounded-lg hover:bg-[#205C5D]">
                            + <span class="hidden sm:inline">Tambah</span>
                        </button>
                        <div class="card text-primary-content bg-white mt-4 w-full">
                            <div class="card-body">
                                <!-- <h2 class="card-title text-black">Tabel Sumber masuk</h2> -->
                                <div class="overflow-x-auto">
                                    <table class="table w-full table-auto">
                                        <thead>
                                            <tr>
                                                <th class="py-2 px-4 border-b text-black">No</th>
                                                <th class="py-2 px-4 border-b text-black">Nama Sumber</th>
                                                <th class="py-2 px-4 border-b text-black">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($sources as $source)
                                                <tr>
                                                    <th class="py-2 px-4 border-b text-black">{{ $loop->iteration }}
                                                    </th>
                                                    <td class="py-2 px-4 border-b text-black">
                                                        <div class="flex flex-wrap items-center gap-2">
                                                            <span class="font-medium">{{ $source->name }}</span>
                                                            @if ($source->is_online)
                                                                <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                                    Online
                                                                </span>
                                                                @if ($source->default_commission_percent > 0)
                                                                    <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-amber-100 text-amber-800 border border-amber-200">
                                                                        Komisi {{ rtrim(rtrim(number_format($source->default_commission_percent, 2), '0'), '.') }}%
                                                                    </span>
                                                                @endif
                                                            @else
                                                                <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-gray-100 text-gray-700 border border-gray-300">
                                                                    Offline
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="flex items-center space-x-2">
                                                        <a href="{{ route('sumber-masuk.edit', $source->id) }}"
                                                            class="bg-blue-600 hover:bg-blue-700 text-white text-xs px-3 py-1 rounded transition">Edit</a>
                                                        <form action="{{ route('sumber-masuk.destroy', $source->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Apa anda yakin menghapus sumber {{ $source->name }}?')"
                                                            style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 ml-2">Hapus</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @if ($sources->isEmpty())
                                                <tr>
                                                    <td colspan="6" class="px-4 py-2 text-center text-black">Tidak
                                                        ada data tersedia.</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                    {{ $sources->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="sourceOverlay"
                        class="px-4 py-2 hidden fixed inset-0 bg-black bg-opacity-50 z-20 flex justify-center items-center">
                        <!-- Form Sumber -->
                        <div id="sourceModal"
                            class="hidden bg-white w-[400px] h-auto max-w-[400px] rounded-lg shadow-lg p-6">
                            <h3 class="text-xl font-semibold mb-4">Tambah Sumber</h3>
                            <form action="{{ route('sumber-masuk.store') }}" method="POST">
                                @csrf
                                <!-- Nama Sumber -->
                                <div class="mb-4">
                                    <label for="sourceName" class="block text-sm font-medium text-gray-700">Nama
                                        Kategori</label>
                                    <input type="text" id="sourceName" name="name"
                                        class="mb-2 w-full p-2 border border-gray-300 rounded-md" placeholder="cth. GoFood"
                                        required>
                                    <!-- Tipe Sumber (Fixed Online / Offline) -->
                                <div class="mb-3">
                                    <label for="sourceIsOnline" class="block text-sm font-medium text-gray-700 mb-1">
                                        Tipe Pesanan
                                    </label>
                                    <select id="sourceIsOnline" name="is_online" onchange="toggleCommissionField()"
                                        class="w-full p-2 border border-gray-300 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-[#2B7A78]"
                                        required>
                                        <option value="" disabled selected>--Pilih tipe--</option>
                                        <option value="0">Offline</option>
                                        <option value="1">Online </option>
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Menentukan promo menu &amp; voucher platform yang aktif saat input penjualan.
                                    </p>
                                </div>

                                <!-- Komisi Default Platform (Muncul otomatis jika Online) -->
                                <div id="commissionWrapper" class="mb-4 hidden">
                                    <label for="sourceCommission" class="block text-sm font-medium text-gray-700 mb-1">
                                        Komisi Platform (%) <span class="text-xs text-gray-400">(Opsional)</span>
                                    </label>
                                    <input type="number" id="sourceCommission" name="default_commission_percent"
                                        step="0.01" min="0" max="100" value="0"
                                        class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#2B7A78]"
                                        placeholder="cth. 20">
                                </div>
                                </div>
                                <div class="flex justify-end mt-4">
                                    <button type="button" onclick="closeSourceModal()"
                                        class="bg-[#db5461] text-white font-semibold py-2 px-6 rounded-lg hover:bg-gray-600">Batal</button>
                                    <button type="submit"
                                        class="bg-[#2B7A78] text-white font-semibold py-2 px-6 rounded-lg hover:bg-[#205C5D] ml-4">Simpan</button>
                                </div>
                            </form>
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
            toggleCommissionField();
        }

        // Close Modal
        function closeSourceModal() {
            const sourceOverlay = document.getElementById('sourceOverlay');
            const sourceModal = document.getElementById('sourceModal');
            sourceOverlay.classList.add('hidden');
            sourceModal.classList.add('hidden');
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
