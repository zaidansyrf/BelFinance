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
                                                    <td class="py-2 px-4 border-b text-black">{{ $source->name }}</td>
                                                    <td class="flex items-center space-x-2">
                                                        <a href="{{ route('sumber-masuk.edit', $source->id) }}"
                                                            class=" btn-primary px-3 py-1 rounded bg-[#2B7A78] hover:bg-[#205C5D]">Edit</a>
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
                        class="hidden fixed inset-0 bg-black bg-opacity-50 z-20 flex justify-center items-center">
                        <!-- Form Sumber -->
                        <div id="sourceModal"
                            class="hidden bg-white w-[400px] h-auto max-w-[400px] rounded-lg shadow-lg p-6">
                            <h3 class="text-xl font-semibold mb-4">Tambah Sumber</h3>
                            <form action="{{ route('sumber-masuk.store') }}" method="POST">
                                @csrf
                                <!-- Nama Sumber -->
                                <div class="mb-4">
                                    <label for="sourceName" class="block text-sm font-medium text-gray-700">Nama
                                        Sumber</label>
                                    <input type="text" id="sourceName" name="name"
                                        class="w-full p-2 border border-gray-300 rounded-md" placeholder="cth. GoFood"
                                        required>
                                </div>
                                <div class="flex justify-end mt-4">
                                    <button type="button" onclick="closeSourceModal()"
                                        class="bg text-white font-semibold py-2 px-6 rounded-lg hover:bg-gray-600">Batal</button>
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
        // Open Sumber Form
        function openSourceForm() {
            const sourceOverlay = document.getElementById('sourceOverlay');
            const sourceModal = document.getElementById('sourceModal');
            sourceOverlay.classList.remove('hidden');
            sourceModal.classList.remove('hidden');
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
