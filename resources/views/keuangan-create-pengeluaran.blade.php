@section('title', 'Pengeluaran | Tambah')
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
                        <div class="mb-4">
                            <!-- Tombol Kembali -->
                            <button type="button"
                                class="bg-[#2B7A78] text-white font-semibold py-2 px-6 rounded-lg hover:bg-[#205C5D]"
                                onclick="window.location.href='{{ url('/keuangan/pengeluaran') }}'">
                                &larr; Kembali
                            </button>
                        </div>
                        <div class="container">
                            <h1 class="text-xl font-semibold text-[#2B7A78] mb-4">Tambah pengeluaran</h1>
                            <div class="card text-primary-content bg-white mt-4 w-full">
                                <div class="card-body">
                                    <form action="{{ route('pengeluaran.store') }}" method="POST">
                                        @csrf
                                        <div class="mb-4" id="items-container">
                                            <!-- Sumber -->
                                            <div>
                                                <label for="source_id"
                                                    class="block text-sm font-medium text-black form-label">Sumber</label>
                                                <select name="source_id" id="source_id"
                                                    class="text-gray-500 w-full p-2 border border-gray-300 rounded-md form-select"
                                                    required>
                                                    <option class="text-black" value="" selected disabled>Pilih
                                                        Sumber</option>
                                                    @foreach ($sources as $source)
                                                        <option class="text-black" value="{{ $source->id }}">
                                                            {{ $source->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Nama Tagihan -->
                                            <div class="mt-4">
                                                <label for="bill_id"
                                                    class="block text-sm font-medium text-black form-label">Nama
                                                    Tagihan</label>
                                                <select name="bill_id" id="bill_id"
                                                    class="text-gray-500 w-full p-2 border border-gray-300 rounded-md form-select"
                                                    required>
                                                    <option class="text-black" value="" selected disabled>Pilih
                                                        Tagihan</option>
                                                    @foreach ($bills as $bill)
                                                        <option class="text-black" value="{{ $bill->id }}">
                                                            {{ $bill->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Nominal -->
                                            <div class="mt-4">
                                                <label for="nominal"
                                                    class="block text-sm font-medium text-black form-label">Nominal</label>
                                                <input type="number" name="amount" id="nominal" min="1"
                                                    placeholder="Rp. "
                                                    class="text-gray-500 w-full p-2 border border-gray-300 rounded-md form-control"
                                                    required>
                                            </div>

                                            <!-- Tanggal -->
                                            <div class="mt-4">
                                                <label for="date"
                                                    class="block text-sm font-medium text-black form-label">Tanggal</label>
                                                <input type="date" name="date" id="date"
                                                    class="text-gray-500 w-full p-2 border border-gray-300 rounded-md form-control"
                                                    required>
                                            </div>

                                            <!-- Keterangan -->
                                            <div class="mt-4">
                                                <label for="description"
                                                    class="block text-sm font-medium text-black form-label">Keterangan</label>
                                                <textarea name="description" id="description" rows="3" placeholder="Masukkan keterangan tambahan"
                                                    class="text-gray-500 w-full p-2 border border-gray-300 rounded-md form-control"></textarea>
                                            </div>
                                        </div>

                                        <!-- Buttons -->
                                        <div class="flex justify-end gap-4 mt-4">
                                            <button type="reset"
                                                class="bg-[#db5461] text-white font-semibold py-2 px-6 rounded-lg hover:bg-gray-600">Reset</button>
                                            <button type="submit"
                                                class="bg-[#2B7A78] text-white font-semibold py-2 px-6 rounded-lg hover:bg-[#205C5D]">Simpan</button>
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
