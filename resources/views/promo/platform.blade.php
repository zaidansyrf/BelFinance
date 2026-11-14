@section('title', 'Promo Platform Online & Komisi')
<x-app-layout>
    <div class="h-screen w-full bg-gray-100 flex overflow-hidden">
        <div class="drawer lg:drawer-open">
            <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />
            <div class="drawer-content flex flex-col h-screen">
                @include('layouts.navbar')
                <div class="flex-1 bg-[#D1DDD5] overflow-auto">
                    <div class="mt-8 px-6 pb-12">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                            <div>
                                <h1 class="text-2xl font-bold text-[#2B7A78]">📱 Promo Platform & Komisi Saluran</h1>
                                <p class="text-sm text-gray-600 mt-1">
                                    Atur preset voucher aplikasi (GoFood, Grab, dll) dengan skema patungan beban (co-funding) serta tarif komisi standar.
                                </p>
                            </div>
                            <button type="button" onclick="openPresetModal()"
                                class="bg-[#2B7A78] hover:bg-[#205C5D] text-white font-semibold py-2 px-4 rounded-lg shadow flex items-center gap-2">
                                <span>+ Buat Preset Voucher</span>
                            </button>
                        </div>

                        {{-- Bagian 1: Konfigurasi Sumber / Platform --}}
                        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                            <h2 class="text-lg font-bold text-gray-800 mb-2">1. Konfigurasi Saluran & Tarif Komisi</h2>
                            <p class="text-xs text-gray-500 mb-4">
                                Tandai platform online dan tentukan tarif komisi platform standar (misal: GoFood 20%). Komisi otomatis dihitung dari subtotal menu.
                            </p>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach ($sources as $source)
                                    <form action="{{ route('promo.platform.source-update', $source) }}" method="POST"
                                        class="border border-gray-200 rounded-xl p-4 bg-gray-50 flex flex-col justify-between hover:shadow-sm transition">
                                        @csrf
                                        @method('PATCH')
                                        <div>
                                            <div class="flex justify-between items-center mb-2">
                                                <span class="font-bold text-gray-900 text-base">{{ $source->name }}</span>
                                                <label class="flex items-center gap-1.5 cursor-pointer">
                                                    <input type="checkbox" name="is_online" value="1" {{ $source->is_online ? 'checked' : '' }}
                                                        class="rounded border-gray-300 text-[#2B7A78] focus:ring-[#2B7A78]">
                                                    <span class="text-xs font-semibold {{ $source->is_online ? 'text-purple-700' : 'text-gray-500' }}">
                                                        {{ $source->is_online ? 'Online' : 'Offline' }}
                                                    </span>
                                                </label>
                                            </div>

                                            <div class="mt-3">
                                                <label class="block text-xs font-semibold text-gray-600 mb-1">
                                                    Komisi Platform Standar (%):
                                                </label>
                                                <div class="relative">
                                                    <input type="number" name="default_commission_percent" step="0.01" min="0" max="100"
                                                        value="{{ $source->default_commission_percent }}"
                                                        class="w-full border border-gray-300 rounded-lg p-2 text-sm pr-8 focus:ring-[#2B7A78] focus:border-[#2B7A78]">
                                                    <span class="absolute right-3 top-2 text-sm text-gray-400 font-semibold">%</span>
                                                </div>
                                            </div>
                                        </div>

                                        <button type="submit"
                                            class="mt-4 bg-[#2B7A78] hover:bg-[#205C5D] text-white text-xs font-semibold py-1.5 px-3 rounded-lg w-full transition">
                                            Simpan Perubahan
                                        </button>
                                    </form>
                                @endforeach
                            </div>
                        </div>

                        {{-- Bagian 2: Preset Voucher Platform --}}
                        <div class="bg-white rounded-xl shadow-md overflow-hidden">
                            <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                                <div>
                                    <h2 class="font-bold text-gray-800 text-lg">2. Daftar Preset Voucher Aplikasi</h2>
                                    <p class="text-xs text-gray-500">
                                        Preset voucher ini akan muncul di dropdown saat menginput transaksi penjualan online sesuai platform yang dipilih.
                                    </p>
                                </div>
                                <span class="text-xs bg-gray-200 text-gray-700 px-2 py-1 rounded-full font-medium">
                                    {{ $promos->count() }} preset
                                </span>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-sm text-gray-600">
                                    <thead class="bg-[#2B7A78] text-white uppercase text-xs">
                                        <tr>
                                            <th class="py-3 px-4">Platform</th>
                                            <th class="py-3 px-4">Nama Voucher</th>
                                            <th class="py-3 px-4">Nilai Potongan</th>
                                            <th class="py-3 px-4">Skema Pembagian Beban (Co-Funding)</th>
                                            <th class="py-3 px-4 text-center">Status</th>
                                            <th class="py-3 px-4 text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @forelse ($promos as $promo)
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="py-3 px-4 font-bold text-gray-900">
                                                    {{ $promo->source->name ?? '-' }}
                                                </td>
                                                <td class="py-3 px-4 font-semibold text-gray-800">
                                                    {{ $promo->name }}
                                                </td>
                                                <td class="py-3 px-4">
                                                    @if ($promo->discount_type === 'percent')
                                                        <span class="inline-block bg-blue-100 text-blue-800 font-bold px-2 py-0.5 rounded">
                                                            {{ $promo->discount_value }}%
                                                        </span>
                                                        @if ($promo->max_discount)
                                                            <span class="text-xs text-gray-500 block mt-0.5">
                                                                Maks. Rp {{ number_format($promo->max_discount, 0, ',', '.') }}
                                                            </span>
                                                        @endif
                                                    @else
                                                        <span class="inline-block bg-green-100 text-green-800 font-bold px-2 py-0.5 rounded">
                                                            Rp {{ number_format($promo->discount_value, 0, ',', '.') }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="py-3 px-4 text-xs">
                                                    @if ($promo->merchant_share_percent == 0)
                                                        <span class="bg-green-100 text-green-800 font-semibold px-2 py-1 rounded">
                                                            100% Subsidi Penuh Aplikasi (Aman/Bukan Beban)
                                                        </span>
                                                    @elseif ($promo->merchant_share_percent == 100)
                                                        <span class="bg-red-100 text-red-800 font-semibold px-2 py-1 rounded">
                                                            100% Beban Ditanggung Merchant
                                                        </span>
                                                    @else
                                                        <div class="flex items-center gap-2">
                                                            <span class="bg-orange-100 text-orange-800 font-semibold px-2 py-1 rounded">
                                                                Patungan: Merchant {{ $promo->merchant_share_percent }}%
                                                            </span>
                                                            <span class="text-gray-400">|</span>
                                                            <span class="text-gray-600">
                                                                Aplikasi {{ 100 - $promo->merchant_share_percent }}%
                                                            </span>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="py-3 px-4 text-center">
                                                    <form action="{{ route('promo.platform.toggle', $promo) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                            class="text-xs px-2.5 py-1 rounded-full font-semibold transition-colors {{ $promo->is_active ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-200 text-gray-600 hover:bg-gray-300' }}">
                                                            {{ $promo->is_active ? '● Aktif' : '○ Nonaktif' }}
                                                        </button>
                                                    </form>
                                                </td>
                                                <td class="py-3 px-4 text-center">
                                                    <div class="flex justify-center items-center gap-2">
                                                        <button type="button"
                                                            onclick='editPreset(@json($promo))'
                                                            class="bg-blue-600 hover:bg-blue-700 text-white text-xs px-3 py-1 rounded transition">
                                                            Edit
                                                        </button>
                                                        <form action="{{ route('promo.platform.destroy', $promo) }}" method="POST"
                                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus preset voucher {{ $promo->name }}?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="bg-red-500 hover:bg-red-600 text-white text-xs px-3 py-1 rounded transition">
                                                                Hapus
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="py-8 text-center text-gray-400">
                                                    Belum ada preset voucher platform. Klik tombol <strong>+ Buat Preset Voucher</strong> untuk mendaftarkan voucher baru.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            @include('layouts.sidebar')
        </div>
    </div>

    {{-- Modal Tambah / Edit Preset Voucher --}}
    <div id="presetModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-lg overflow-hidden my-8">
            <div class="bg-[#2B7A78] text-white px-6 py-4 flex justify-between items-center">
                <h3 id="presetModalTitle" class="text-lg font-bold">Buat Preset Voucher Platform</h3>
                <button type="button" onclick="closePresetModal()" class="text-white hover:text-gray-200 text-xl font-bold">&times;</button>
            </div>

            <form id="presetForm" action="{{ route('promo.platform.store') }}" method="POST" class="p-6">
                @csrf
                <div id="presetMethodContainer"></div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih Platform *</label>
                        <select name="source_id" id="presetSourceId" required
                            class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-[#2B7A78] focus:border-[#2B7A78]">
                            <option value="" disabled selected>-- Pilih platform --</option>
                            @foreach ($sources as $source)
                                <option value="{{ $source->id }}">{{ $source->name }} ({{ $source->is_online ? 'Online' : 'Offline' }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Voucher *</label>
                        <input type="text" name="name" id="presetName" required placeholder="Contoh: GoFood Hemat 15rb"
                            class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-[#2B7A78] focus:border-[#2B7A78]">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Tipe Diskon *</label>
                            <select name="discount_type" id="presetDiscountType" onchange="togglePresetMaxDiscount()" required
                                class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-[#2B7A78] focus:border-[#2B7A78]">
                                <option value="nominal">Nominal Rupiah (Rp)</option>
                                <option value="percent">Persentase (%)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1" id="presetDiscountValueLabel">Nilai Potongan (Rp) *</label>
                            <input type="number" name="discount_value" id="presetDiscountValue" required min="1" placeholder="15000"
                                class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-[#2B7A78] focus:border-[#2B7A78]">
                        </div>
                    </div>

                    <div id="presetMaxDiscountField" style="display: none;">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Batas Maksimal Potongan (Rp) <span class="text-xs font-normal text-gray-500">(Opsional)</span>
                        </label>
                        <input type="number" name="max_discount" id="presetMaxDiscount" min="1" placeholder="Misal: 25000"
                            class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-[#2B7A78] focus:border-[#2B7A78]">
                    </div>

                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-3">
                        <label class="block text-sm font-bold text-amber-900 mb-1">
                            Porsi Yang Ditanggung Merchant (%) *
                        </label>
                        <p class="text-xs text-amber-700 mb-2">
                            Masukkan <strong>0</strong> jika diskon 100% disubsidi oleh aplikasi.<br>
                            Masukkan <strong>40</strong> jika merchant menanggung 40% dan aplikasi 60%.
                        </p>
                        <div class="relative">
                            <input type="number" name="merchant_share_percent" id="presetMerchantShare" step="0.01" min="0" max="100" required value="0"
                                class="w-full border border-gray-300 rounded-lg p-2 text-sm pr-8 focus:ring-[#2B7A78] focus:border-[#2B7A78]">
                            <span class="absolute right-3 top-2 text-sm text-gray-400 font-semibold">%</span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 border-t pt-4">
                    <button type="button" onclick="closePresetModal()"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-5 rounded-lg text-sm transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="bg-[#2B7A78] hover:bg-[#205C5D] text-white font-semibold py-2 px-6 rounded-lg text-sm shadow transition">
                        Simpan Preset
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
                timer: 2000,
                showConfirmButton: false
            });
        @endif
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: @json(session('error'))
            });
        @endif

        function togglePresetMaxDiscount() {
            const type = document.getElementById('presetDiscountType').value;
            const maxField = document.getElementById('presetMaxDiscountField');
            const label = document.getElementById('presetDiscountValueLabel');

            if (type === 'percent') {
                maxField.style.display = 'block';
                label.innerText = 'Nilai Diskon (%) *';
            } else {
                maxField.style.display = 'none';
                label.innerText = 'Nilai Potongan (Rp) *';
            }
        }

        function openPresetModal() {
            document.getElementById('presetModalTitle').innerText = 'Buat Preset Voucher Platform';
            document.getElementById('presetForm').action = "{{ route('promo.platform.store') }}";
            document.getElementById('presetMethodContainer').innerHTML = '';
            document.getElementById('presetForm').reset();
            document.getElementById('presetMerchantShare').value = '0';
            togglePresetMaxDiscount();
            document.getElementById('presetModal').classList.remove('hidden');
        }

        function editPreset(promo) {
            document.getElementById('presetModalTitle').innerText = 'Edit Preset Voucher: ' + promo.name;
            document.getElementById('presetForm').action = "/keuangan/promo/platform/" + promo.id;
            document.getElementById('presetMethodContainer').innerHTML = '@method("PUT")';

            document.getElementById('presetSourceId').value = promo.source_id;
            document.getElementById('presetName').value = promo.name;
            document.getElementById('presetDiscountType').value = promo.discount_type;
            document.getElementById('presetDiscountValue').value = promo.discount_value;
            document.getElementById('presetMaxDiscount').value = promo.max_discount || '';
            document.getElementById('presetMerchantShare').value = promo.merchant_share_percent;

            togglePresetMaxDiscount();
            document.getElementById('presetModal').classList.remove('hidden');
        }

        function closePresetModal() {
            document.getElementById('presetModal').classList.add('hidden');
        }

        window.onclick = function(event) {
            const modal = document.getElementById('presetModal');
            if (event.target === modal) {
                closePresetModal();
            }
        };
    </script>
</x-app-layout>
