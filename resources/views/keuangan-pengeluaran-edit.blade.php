@section('title', 'Pengeluaran | Edit')
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
                            <a href="{{ route('expenses.search') }}"
                                class="bg-[#2B7A78] text-white font-semibold py-2 px-6 rounded-lg hover:bg-[#205C5D]">
                                &larr; Kembali
                            </a>
                        </div>
                        <div class="container">
                            <h1 class="text-xl font-semibold text-[#2B7A78] mb-4">Edit Pengeluaran</h1>
                            <div class="card text-primary-content bg-white mt-4 w-full">
                                <div class="card-body">
                                    <form action="{{ route('expenses.update', $expense->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <!-- Sumber -->
                                        <div>
                                            <label for="source"
                                                class="block text-sm font-medium text-black">Sumber</label>
                                            <select name="source_id" id="source"
                                                class="text-gray-500 w-full p-2 border border-gray-300 rounded-md"
                                                required>
                                                <option value="" disabled
                                                    {{ !$expense->source_id ? 'selected' : '' }}>Pilih Sumber</option>
                                                @foreach ($sources as $source)
                                                    <option value="{{ $source->id }}"
                                                        {{ $expense->source_id == $source->id ? 'selected' : '' }}>
                                                        {{ $source->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Tagihan -->
                                        <div class="mt-4">
                                            <label for="bill" class="block text-sm font-medium text-black">Nama
                                                Tagihan</label>
                                            <select name="bill_id" id="bill"
                                                class="text-gray-500 w-full p-2 border border-gray-300 rounded-md"
                                                required>
                                                <option value="" disabled
                                                    {{ !$expense->bill_id ? 'selected' : '' }}>Pilih Tagihan</option>
                                                @foreach ($bills as $bill)
                                                    <option value="{{ $bill->id }}"
                                                        {{ $expense->bill_id == $bill->id ? 'selected' : '' }}>
                                                        {{ $bill->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Nominal -->
                                        <div class="mt-4">
                                            <label for="amount"
                                                class="block text-sm font-medium text-black">Nominal</label>
                                            <input type="number" name="amount" id="amount" min="1"
                                                value="{{ old('amount', $expense->amount) }}"
                                                class="text-gray-500 w-full p-2 border border-gray-300 rounded-md"
                                                required>
                                        </div>

                                        <!-- Tanggal -->
                                        <div class="mt-4">
                                            <label for="date"
                                                class="block text-sm font-medium text-black">Tanggal</label>
                                            <input type="date" name="date" id="date"
                                                value="{{ old('date', \Carbon\Carbon::parse($expense->date)->format('Y-m-d')) }}"
                                                class="text-gray-500 w-full p-2 border border-gray-300 rounded-md"
                                                required>
                                        </div>

                                        <!-- Keterangan -->
                                        <div class="mt-4">
                                            <label for="description"
                                                class="block text-sm font-medium text-black">Keterangan</label>
                                            <textarea name="description" id="description" rows="3"
                                                class="text-gray-500 w-full p-2 border border-gray-300 rounded-md" placeholder="Masukkan keterangan tambahan">{{ old('description', $expense->description) }}</textarea>
                                        </div>

                                        <!-- Tombol -->
                                        <div class="flex justify-end gap-4 mt-4">

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
