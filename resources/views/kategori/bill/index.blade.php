@section('title', 'Kategori | Sumber keluar')
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
                    <div class="justify-between items-center mt-8 px-4 sm:px-8 pb-12">
                        <h1 class="text-xl font-semibold text-[#2B7A78] mb-4">Sumber keluar</h1>
                        <button onclick="openSourceForm()"
                            class="bg-[#2B7A78] text-white font-semibold py-2 px-4 rounded-lg hover:bg-[#205C5D]">
                            + <span class="hidden sm:inline">Tambah</span>
                        </button>
                        <div class="card text-primary-content bg-white mt-4 mb-8 w-full shadow-sm rounded-2xl">
                            <div class="card-body">
                                <div class="overflow-x-auto">
                                    <!-- <h2 class="card-title text-black">Tabel Sumber keluar</h2> -->
                                    <table class="table w-full table-auto">
                                        <thead>
                                            <tr>
                                                <th class="py-2 px-4 border-b text-black">No</th>
                                                <th class="py-2 px-4 border-b text-black">Nama Sumber</th>
                                                <th class="py-2 px-4 border-b text-black">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($Bills as $bill)
                                                <tr>
                                                    <th class="py-2 px-4 border-b text-black">{{ $loop->iteration }}
                                                    </th>
                                                    <td class="py-2 px-4 border-b text-black">{{ $bill->name }}</td>
                                                    <td class="flex items-center">
                                                        <a href="{{ route('sumber-keluar.edit', $bill->id) }}"
                                                            class="text-white px-3 py-1 rounded bg-[#2B7A78] hover:bg-[#205C5D] ml-2">Edit</a>
                                                        <form action="{{ route('sumber-keluar.destroy', $bill->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus data {{ $bill->name }}?');"
                                                            class="ml-2 inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Hapus</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @if ($Bills->isEmpty())
                                                <tr>
                                                    <td colspan="6" class="px-4 py-2 text-center text-black">Tidak
                                                        ada data tersedia.</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                    {{ $Bills->links() }}
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

    <!-- Modal Form Sumber Keluar (Blur Backdrop & Fullscreen) -->
    <div id="sourceOverlay"
        class="hidden fixed inset-0 top-0 left-0 right-0 bottom-0 w-full h-full bg-slate-900/60 backdrop-blur-sm z-[9999] flex justify-center items-center p-4 transition-opacity">
        <div id="sourceModal"
            class="hidden bg-white w-full max-w-md rounded-2xl shadow-xl overflow-hidden transform transition-all">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-slate-50/50">
                <h3 class="text-base font-bold text-gray-800">Tambah Sumber Keluar</h3>
                <button type="button" onclick="closeSourceModal()"
                    class="text-gray-400 hover:text-gray-600 focus:outline-none">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form action="{{ route('sumber-keluar.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label for="sourceName" class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Nama Kategori <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="sourceName" name="name"
                        class="w-full px-3.5 py-2.5 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2B7A78] text-gray-800"
                        placeholder="cth. Belanja" required>
                </div>
                <div class="pt-3 border-t border-gray-100 flex items-center justify-end gap-2.5">
                    <button type="button" onclick="closeSourceModal()"
                        class="px-4 py-2.5 rounded-xl border border-gray-300 text-xs sm:text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-[#2B7A78] hover:bg-[#205C5D] text-xs sm:text-sm font-semibold text-white shadow-sm transition">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: @json(session('success')),
                showConfirmButton: false,
                timer: 2000
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: @json(session('error')),
                confirmButtonText: 'OK',
                confirmButtonColor: '#2B7A78'
            });
        @endif

        // Open Sumber Form
        function openSourceForm() {
            const sourceOverlay = document.getElementById('sourceOverlay');
            const sourceModal = document.getElementById('sourceModal');
            sourceOverlay.classList.remove('hidden');
            sourceModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        // Close Modal
        function closeSourceModal() {
            const sourceOverlay = document.getElementById('sourceOverlay');
            const sourceModal = document.getElementById('sourceModal');
            sourceOverlay.classList.add('hidden');
            sourceModal.classList.add('hidden');
            document.body.style.overflow = '';
        }

        // Tutup saat klik overlay di luar modal
        document.addEventListener('click', function(event) {
            const overlay = document.getElementById('sourceOverlay');
            const modal = document.getElementById('sourceModal');
            if (overlay && modal && !overlay.classList.contains('hidden')) {
                if (event.target === overlay) {
                    closeSourceModal();
                }
            }
        });

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
