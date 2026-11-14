@section('title', 'Promo Menu Merchant')
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
                                <h1 class="text-2xl font-bold text-[#2B7A78]">🏷️ Promo Menu Merchant</h1>
                                <p class="text-sm text-gray-600 mt-1">
                                    Atur diskon per porsi menu untuk seluruh saluran atau khusus online/offline.
                                </p>
                            </div>
                            <button type="button" onclick="openPromoModal()"
                                class="bg-[#2B7A78] hover:bg-[#205C5D] text-white font-semibold py-2 px-4 rounded-lg shadow flex items-center gap-2">
                                <span>+ Buat Kampanye Promo</span>
                            </button>
                        </div>

                        {{-- Alert info sistem hybrid --}}
                        <div class="bg-teal-50 border-l-4 border-[#2B7A78] p-4 rounded-r-lg mb-6 shadow-sm">
                            <p class="text-sm text-teal-900 font-medium">
                                <strong>💡 Sistem Promo Hybrid (Semi-Otomatis):</strong>
                                Diskon menu yang aktif otomatis terpasang saat menu dimasukkan ke keranjang penjualan, namun tetap dapat dilepas secara manual per baris item jika dibutuhkan. Setiap menu hanya dapat terikat pada maksimal 1 promo aktif.
                            </p>
                        </div>

                        {{-- Tabel Daftar Promo --}}
                        <div class="bg-white rounded-xl shadow-md overflow-hidden">
                            <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                                <h2 class="font-semibold text-gray-800">Daftar Kampanye Promo Menu</h2>
                                <span class="text-xs bg-gray-200 text-gray-700 px-2 py-1 rounded-full font-medium">
                                    Total: {{ $promos->count() }} promo
                                </span>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-sm text-gray-600">
                                    <thead class="bg-[#2B7A78] text-white uppercase text-xs">
                                        <tr>
                                            <th class="py-3 px-4">Nama Promo</th>
                                            <th class="py-3 px-4">Diskon / Porsi</th>
                                            <th class="py-3 px-4">Saluran Berlaku</th>
                                            <th class="py-3 px-4">Periode</th>
                                            <th class="py-3 px-4">Menu Terdaftar</th>
                                            <th class="py-3 px-4 text-center">Status</th>
                                            <th class="py-3 px-4 text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @forelse ($promos as $promo)
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="py-3 px-4 font-semibold text-gray-900">
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
                                                <td class="py-3 px-4">
                                                    @if ($promo->channel_scope === 'online_only')
                                                        <span class="text-xs bg-purple-100 text-purple-700 font-medium px-2 py-1 rounded">
                                                            📱 Khusus Online
                                                        </span>
                                                    @elseif ($promo->channel_scope === 'offline_only')
                                                        <span class="text-xs bg-orange-100 text-orange-700 font-medium px-2 py-1 rounded">
                                                            🏪 Khusus Offline / Kasir
                                                        </span>
                                                    @else
                                                        <span class="text-xs bg-gray-100 text-gray-700 font-medium px-2 py-1 rounded">
                                                            🌐 Semua Saluran
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="py-3 px-4 text-xs text-gray-600">
                                                    @if ($promo->start_date || $promo->end_date)
                                                        {{ $promo->start_date ? $promo->start_date->format('d/m/Y') : 'Sekarang' }}
                                                        s/d
                                                        {{ $promo->end_date ? $promo->end_date->format('d/m/Y') : 'Seterusnya' }}
                                                    @else
                                                        <span class="text-gray-400">Permanen (Tanpa batas)</span>
                                                    @endif
                                                </td>
                                                <td class="py-3 px-4">
                                                    <span class="font-medium text-gray-800">
                                                        {{ $promo->items->count() }} menu
                                                    </span>
                                                    @if ($promo->items->isNotEmpty())
                                                        <div class="text-xs text-gray-500 truncate max-w-xs mt-0.5"
                                                            title="{{ $promo->items->pluck('name')->implode(', ') }}">
                                                            {{ $promo->items->pluck('name')->implode(', ') }}
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="py-3 px-4 text-center">
                                                    <form action="{{ route('promo.item.toggle', $promo) }}" method="POST" class="inline">
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
                                                            onclick='editPromo(@json($promo), @json($promo->items->pluck("id")))'
                                                            class="bg-blue-600 hover:bg-blue-700 text-white text-xs px-3 py-1 rounded transition">
                                                            Edit
                                                        </button>
                                                        <form action="{{ route('promo.item.destroy', $promo) }}" method="POST"
                                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus promo {{ $promo->name }}? Menu terdaftar akan dilepas dari promo ini.')">
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
                                                <td colspan="7" class="py-8 text-center text-gray-400">
                                                    Belum ada kampanye promo menu. Klik tombol <strong>+ Buat Kampanye Promo</strong> untuk membuat promo baru.
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

    {{-- Modal Tambah / Edit Promo Menu --}}
    <div id="promoModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl overflow-hidden my-8">
            <div class="bg-[#2B7A78] text-white px-6 py-4 flex justify-between items-center">
                <h3 id="modalTitle" class="text-lg font-bold">Buat Kampanye Promo Menu</h3>
                <button type="button" onclick="closePromoModal()" class="text-white hover:text-gray-200 text-xl font-bold">&times;</button>
            </div>

            <form id="promoForm" action="{{ route('promo.item.store') }}" method="POST" class="p-6">
                @csrf
                <div id="methodContainer"></div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Kampanye Promo *</label>
                        <input type="text" name="name" id="promoName" required placeholder="Contoh: Promo Spesial Gajian"
                            class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-[#2B7A78] focus:border-[#2B7A78]">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Tipe Diskon *</label>
                        <select name="discount_type" id="discountType" onchange="toggleMaxDiscount()" required
                            class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-[#2B7A78] focus:border-[#2B7A78]">
                            <option value="percent">Persentase (%)</option>
                            <option value="nominal">Nominal Rupiah (Rp)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1" id="discountValueLabel">Nilai Diskon (%) *</label>
                        <input type="number" name="discount_value" id="discountValue" required min="1" placeholder="Misal: 20"
                            class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-[#2B7A78] focus:border-[#2B7A78]">
                    </div>

                    <div id="maxDiscountField">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Batas Maksimal Potongan (Rp) <span class="text-xs font-normal text-gray-500">(Opsional)</span>
                        </label>
                        <input type="number" name="max_discount" id="maxDiscount" min="1" placeholder="Contoh: 15000"
                            class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-[#2B7A78] focus:border-[#2B7A78]">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Batasan Saluran Transaksi *</label>
                        <select name="channel_scope" id="channelScope" required
                            class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-[#2B7A78] focus:border-[#2B7A78]">
                            <option value="all">🌐 Semua Saluran (Online & Offline)</option>
                            <option value="online_only">📱 Khusus Saluran Online (GoFood, Grab, dll)</option>
                            <option value="offline_only">🏪 Khusus Offline / POS Kasir</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Mulai Berlaku</label>
                        <input type="datetime-local" name="start_date" id="startDate"
                            class="w-full border border-gray-300 rounded-lg p-2 text-sm focus:ring-[#2B7A78] focus:border-[#2B7A78]">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Berakhir</label>
                        <input type="datetime-local" name="end_date" id="endDate"
                            class="w-full border border-gray-300 rounded-lg p-2 text-sm focus:ring-[#2B7A78] focus:border-[#2B7A78]">
                    </div>
                </div>

                {{-- Checklist Menu --}}
                <div class="mt-4 border-t pt-4">
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-sm font-bold text-gray-800">
                            Pilih Menu Yang Diikutsertakan:
                        </label>
                        <div class="text-xs text-gray-500">
                            1 menu hanya boleh 1 promo aktif
                        </div>
                    </div>

                    <div class="border border-gray-200 rounded-lg max-h-60 overflow-y-auto divide-y divide-gray-100 p-2 bg-gray-50">
                        @foreach ($items as $item)
                            <label class="flex items-center justify-between p-2 hover:bg-white rounded cursor-pointer transition">
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" name="item_ids[]" value="{{ $item->id }}"
                                        data-promo-id="{{ $item->item_promo_id ?? '' }}"
                                        class="item-checkbox rounded border-gray-300 text-[#2B7A78] focus:ring-[#2B7A78]">
                                    <div>
                                        <span class="font-medium text-gray-900 text-sm">{{ $item->name }}</span>
                                        <span class="text-xs text-gray-500 block">
                                            Rp {{ number_format($item->price, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    @if ($item->promo)
                                        <span id="promo-badge-{{ $item->id }}"
                                            class="text-xs bg-amber-100 text-amber-800 px-2 py-0.5 rounded font-medium">
                                            Terikat: {{ $item->promo->name }}
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400">Belum ada promo</span>
                                    @endif
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 border-t pt-4">
                    <button type="button" onclick="closePromoModal()"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-5 rounded-lg text-sm transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="bg-[#2B7A78] hover:bg-[#205C5D] text-white font-semibold py-2 px-6 rounded-lg text-sm shadow transition">
                        Simpan Promo Menu
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

        function toggleMaxDiscount() {
            const type = document.getElementById('discountType').value;
            const maxField = document.getElementById('maxDiscountField');
            const label = document.getElementById('discountValueLabel');

            if (type === 'percent') {
                maxField.style.display = 'block';
                label.innerText = 'Nilai Diskon (%) *';
            } else {
                maxField.style.display = 'none';
                label.innerText = 'Nilai Diskon (Rp) *';
            }
        }

        function openPromoModal() {
            document.getElementById('modalTitle').innerText = 'Buat Kampanye Promo Menu';
            document.getElementById('promoForm').action = "{{ route('promo.item.store') }}";
            document.getElementById('methodContainer').innerHTML = '';
            document.getElementById('promoForm').reset();
            toggleMaxDiscount();

            // Uncheck all items
            document.querySelectorAll('.item-checkbox').forEach(cb => {
                cb.checked = false;
                cb.disabled = false;
            });

            document.getElementById('promoModal').classList.remove('hidden');
        }

        function editPromo(promo, attachedItemIds) {
            document.getElementById('modalTitle').innerText = 'Edit Promo: ' + promo.name;
            document.getElementById('promoForm').action = "/keuangan/promo/item/" + promo.id;
            document.getElementById('methodContainer').innerHTML = '@method("PUT")';

            document.getElementById('promoName').value = promo.name;
            document.getElementById('discountType').value = promo.discount_type;
            document.getElementById('discountValue').value = promo.discount_value;
            document.getElementById('maxDiscount').value = promo.max_discount || '';
            document.getElementById('channelScope').value = promo.channel_scope;

            if (promo.start_date) {
                document.getElementById('startDate').value = promo.start_date.substring(0, 16);
            } else {
                document.getElementById('startDate').value = '';
            }

            if (promo.end_date) {
                document.getElementById('endDate').value = promo.end_date.substring(0, 16);
            } else {
                document.getElementById('endDate').value = '';
            }

            toggleMaxDiscount();

            // Check attached items
            document.querySelectorAll('.item-checkbox').forEach(cb => {
                const itemId = parseInt(cb.value);
                cb.checked = attachedItemIds.includes(itemId);
            });

            document.getElementById('promoModal').classList.remove('hidden');
        }

        function closePromoModal() {
            document.getElementById('promoModal').classList.add('hidden');
        }

        // Close on backdrop click
        window.onclick = function(event) {
            const modal = document.getElementById('promoModal');
            if (event.target === modal) {
                closePromoModal();
            }
        };
    </script>
</x-app-layout>
