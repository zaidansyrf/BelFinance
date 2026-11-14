@section('title', 'Pembayaran | Tambah Transaksi')
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
                    <div class="mt-8 px-6 pb-12">
                        <div class="mb-4">
                            <button type="button"
                                class="bg-[#2B7A78] hover:bg-[#205C5D] text-white font-semibold py-2 px-6 rounded-lg transition"
                                onclick="window.location.href='{{ route('pembayaran.index') }}'">
                                &larr; Kembali
                            </button>
                        </div>

                        <div class="container mx-auto">
                            <h1 class="text-2xl font-bold text-[#2B7A78] mb-4">Input Transaksi Penjualan</h1>

                            <form action="{{ route('pembayaran.store') }}" method="POST" id="transactionForm">
                                @csrf

                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                    {{-- Kolom Kiri: Detail Pesanan & Keranjang Item (2 Col) --}}
                                    <div class="lg:col-span-2 space-y-6">
                                        {{-- Card 1: Informasi Header Transaksi --}}
                                        <div class="bg-white rounded-xl shadow-md p-6">
                                            <h2 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">1. Data Transaksi</h2>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">
                                                        Jenis Pesanan *
                                                    </label>
                                                    <select name="name" id="name" required onchange="onChannelChange()"
                                                        class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-[#2B7A78] focus:border-[#2B7A78]">
                                                        <option value="Pesanan Offline" selected>Pesanan Offline</option>
                                                        <option value="Pesanan Online">Pesanan Online</option>
                                                    </select>
                                                </div>

                                                <div>
                                                    <label for="date" class="block text-sm font-semibold text-gray-700 mb-1">
                                                        Tanggal Transaksi *
                                                    </label>
                                                    <input type="date" name="date" id="date"
                                                        value="{{ old('date', date('Y-m-d')) }}" required
                                                        class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-[#2B7A78] focus:border-[#2B7A78]">
                                                </div>

                                                <div>
                                                    <label for="source_id" class="block text-sm font-semibold text-gray-700 mb-1">
                                                        Sumber / Platform Pembayaran *
                                                    </label>
                                                    <select name="source_id" id="source_id" required onchange="onSourceChange()"
                                                        class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-[#2B7A78] focus:border-[#2B7A78]">
                                                        <option value="" disabled selected>-- Pilih Sumber Pembayaran --</option>
                                                    </select>
                                                </div>

                                                {{-- Dropdown Preset Promo Platform (Otomatis muncul saat Pesanan Online) --}}
                                                <div id="platformPromoContainer" class="hidden">
                                                    <label for="platform_promo_id" class="block text-sm font-semibold text-gray-700 mb-1">
                                                        Voucher Aplikasi Platform
                                                    </label>
                                                    <select name="platform_promo_id" id="platform_promo_id" onchange="calculateFinancials()"
                                                        class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-[#2B7A78] focus:border-[#2B7A78]">
                                                        <option value="">Tanpa Promo Platform</option>
                                                    </select>
                                                    <div id="platformPromoInfo" class="mt-1 text-xs text-blue-700 hidden"></div>
                                                </div>

                                                <div class="md:col-span-2">
                                                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-1">
                                                        Catatan Pesanan (Opsional)
                                                    </label>
                                                    <input type="text" name="description" id="description" placeholder="contoh : No. Pesanan GF-10294"
                                                        class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-[#2B7A78] focus:border-[#2B7A78]">
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Card 2: Keranjang Item Pesanan --}}
                                        <div class="bg-white rounded-xl shadow-md p-6">
                                            <div class="flex justify-between items-center mb-4 border-b pb-2">
                                                <div>
                                                    <h2 class="text-lg font-bold text-gray-800">2. Keranjang Item Pesanan</h2>
                                                    <p class="text-xs text-gray-500">
                                                        Diskon menu otomatis diterapkan jika menu memiliki promo aktif yang sesuai.
                                                    </p>
                                                </div>
                                                <button type="button" onclick="addItemRow()"
                                                    class="bg-[#2B7A78] hover:bg-[#205C5D] text-white text-xs font-semibold py-2 px-3 rounded-lg flex items-center gap-1 transition">
                                                    + Tambah Menu
                                                </button>
                                            </div>

                                            <div id="itemsContainer" class="space-y-3">
                                                {{-- Baris item akan dimasukkan via JavaScript --}}
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Kolom Kanan: Ringkasan Finansial & Pembayaran (1 Col) --}}
                                    <div class="space-y-6">
                                        <div class="bg-white rounded-xl shadow-md p-6 sticky top-6">
                                            <h2 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">3. Rekap Finansial Transaksi</h2>

                                            <div class="space-y-2.5 text-sm">
                                                <div class="flex justify-between text-gray-600">
                                                    <span>Total Kotor Normal:</span>
                                                    <span id="dispGross" class="font-semibold text-gray-900">Rp 0</span>
                                                </div>

                                                <div class="flex justify-between text-amber-700">
                                                    <span>Potongan Diskon Menu:</span>
                                                    <span id="dispItemDiscount" class="font-semibold">Rp 0</span>
                                                </div>

                                                <div class="flex justify-between text-gray-800 font-medium border-t pt-2">
                                                    <span>Subtotal Menu:</span>
                                                    <span id="dispSubtotalAfterItemPromo">Rp 0</span>
                                                </div>

                                                <div id="rowPlatformVoucher" class="space-y-1.5 border-t pt-2 hidden">
                                                    <div class="flex justify-between text-blue-700 font-semibold">
                                                        <span>Voucher Aplikasi:</span>
                                                        <span id="dispPlatformVoucher">Rp 0</span>
                                                    </div>
                                                    <div class="flex justify-between text-xs text-orange-700 pl-3">
                                                        <span>Beban Merchant:</span>
                                                        <span id="dispMerchantShare">Rp 0</span>
                                                    </div>
                                                    <div class="flex justify-between text-xs text-green-700 pl-3">
                                                        <span>Subsidi Aplikasi:</span>
                                                        <span id="dispAppSubsidy">Rp 0</span>
                                                    </div>
                                                </div>

                                                <div id="rowCustomerPaid" class="flex justify-between text-purple-800 font-medium border-t pt-2 hidden">
                                                    <span>Dibayar Pembeli di App:</span>
                                                    <span id="dispCustomerPaid" class="font-bold">Rp 0</span>
                                                </div>

                                                <div id="rowCommission" class="flex justify-between text-red-700 border-t pt-2 hidden">
                                                    <span id="labelCommission">Komisi Platform (0%):</span>
                                                    <span id="dispCommission" class="font-semibold">Rp 0</span>
                                                </div>

                                                <div class="bg-teal-50 border border-teal-200 rounded-xl p-4 mt-4">
                                                    <div class="text-xs text-teal-800 font-medium uppercase tracking-wider">
                                                        Uang Bersih Diterima (Net Payout)
                                                    </div>
                                                    <div id="dispNetPayout" class="text-2xl font-black text-[#2B7A78] mt-1">
                                                        Rp 0
                                                    </div>
                                                    <p class="text-[11px] text-teal-700 mt-1 leading-relaxed">
                                                        Dana riil yang masuk ke kas/rekening setelah dipotong beban promo &amp; komisi.
                                                    </p>
                                                </div>
                                            </div>

                                            {{-- Field Uang Diterima & Kembalian (Opsional) --}}
                                            <div class="mt-6 border-t pt-4 space-y-3">
                                                <div>
                                                    <label for="paid_amount" class="block text-xs font-semibold text-gray-700 mb-1">
                                                        Uang Diterima dari Pelanggan (Rp):
                                                    </label>
                                                    <input type="number" name="paid_amount" id="paid_amount" min="0" oninput="calculateChange()"
                                                        placeholder="Opsional (kosongkan jika pas / online)"
                                                        class="w-full border border-gray-300 rounded-lg p-2 text-sm focus:ring-[#2B7A78] focus:border-[#2B7A78]">
                                                </div>
                                                <div id="changeContainer" class="hidden flex justify-between items-center text-sm bg-gray-50 p-2.5 rounded-lg border">
                                                    <span class="text-gray-600 font-medium">Uang Kembalian:</span>
                                                    <span id="dispChange" class="font-bold text-gray-900">Rp 0</span>
                                                    <input type="hidden" name="change_amount" id="change_amount" value="0">
                                                </div>
                                            </div>

                                            <div class="mt-6 flex flex-col gap-2">
                                                <button type="submit"
                                                    class="w-full bg-[#2B7A78] hover:bg-[#205C5D] text-white font-bold py-3 px-4 rounded-xl shadow-lg transition">
                                                    Simpan Transaksi Penjualan
                                                </button>
                                                <button type="reset" onclick="resetFormState()"
                                                    class="w-full bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold py-2 px-4 rounded-lg text-sm transition text-center">
                                                    Reset Input
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @include('layouts.sidebar')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (session('success'))
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}', timer: 2000, showConfirmButton: false });
        @endif
        @if (session('error'))
            Swal.fire({ icon: 'error', title: 'Gagal!', text: '{{ session('error') }}' });
        @endif

        // Master item & sumber masuk dari backend
        const allItems = @json($allItems);
        const allSources = @json($allSources);
        let rowCounter = 0;

        document.addEventListener('DOMContentLoaded', function () {
            addItemRow();
            onChannelChange();
        });

        function formatRupiah(number) {
            return 'Rp ' + Math.round(number || 0).toLocaleString('id-ID');
        }

        function onChannelChange() {
            const jenisPesanan = document.getElementById('name').value.trim();
            const isOnlineChannel = (jenisPesanan === 'Pesanan Online');

            const sourceSelect = document.getElementById('source_id');
            const platformPromoContainer = document.getElementById('platformPromoContainer');

            // Tampilkan/sembunyikan dropdown voucher platform sesuai jenis pesanan
            if (isOnlineChannel) {
                platformPromoContainer.classList.remove('hidden');
            } else {
                platformPromoContainer.classList.add('hidden');
            }

            // Filter sumber pembayaran berdasarkan kolom is_online dari master sources
            const filteredSources = allSources.filter(src => Boolean(src.is_online) === isOnlineChannel);

            sourceSelect.innerHTML = '<option value="" disabled selected>-- Pilih Sumber Pembayaran --</option>';
            filteredSources.forEach(src => {
                const opt = document.createElement('option');
                opt.value = src.id;
                opt.dataset.isOnline = src.is_online ? '1' : '0';
                opt.dataset.commission = src.default_commission_percent || 0;
                opt.dataset.promos = JSON.stringify(src.platform_promos || src.platformPromos || []);

                const comm = parseFloat(src.default_commission_percent || 0);
                const commLabel = comm > 0 ? ` - Komisi ${comm}%` : '';
                opt.text = `${src.name}${commLabel}`;
                sourceSelect.appendChild(opt);
            });

            // Perbarui visibilitas banner promo menu pada tiap baris item
            document.querySelectorAll('.item-row').forEach(row => {
                const rowIndex = row.id.replace('item-row-', '');
                updateItemPromoBanner(rowIndex);
            });

            onSourceChange();
        }

        function onSourceChange() {
            const sourceSelect = document.getElementById('source_id');
            const selectedOpt = sourceSelect.selectedOptions[0];
            const promoSelect = document.getElementById('platform_promo_id');
            const promoInfo = document.getElementById('platformPromoInfo');

            promoSelect.innerHTML = '<option value="">Tanpa Promo Platform</option>';
            promoInfo.classList.add('hidden');
            promoInfo.innerText = '';

            if (!selectedOpt || !selectedOpt.value) {
                calculateFinancials();
                return;
            }

            const isOnline = selectedOpt.dataset.isOnline === '1';
            const promosData = JSON.parse(selectedOpt.dataset.promos || '[]');

            if (isOnline && promosData.length > 0) {
                promosData.forEach(p => {
                    const opt = document.createElement('option');
                    opt.value = p.id;
                    opt.dataset.type = p.discount_type;
                    opt.dataset.val = p.discount_value;
                    opt.dataset.max = p.max_discount || '';
                    opt.dataset.share = p.merchant_share_percent;
                    opt.dataset.name = p.name;

                    let labelDiskon = p.discount_type === 'percent' ? p.discount_value + '%' : formatRupiah(p.discount_value);
                    opt.text = `${p.name} (Diskon ${labelDiskon} | Tanggungan Merchant ${p.merchant_share_percent}%)`;
                    promoSelect.appendChild(opt);
                });
            }

            calculateFinancials();
        }

        function addItemRow() {
            const container = document.getElementById('itemsContainer');
            const rowIndex = rowCounter++;

            const rowDiv = document.createElement('div');
            rowDiv.className = 'item-row bg-gray-50 border border-gray-200 rounded-xl p-4 transition hover:border-[#2B7A78]';
            rowDiv.id = `item-row-${rowIndex}`;

            let menuOptionsHtml = '<option value="" disabled selected>-- Pilih Menu --</option>';
            allItems.forEach(item => {
                let promoBadge = '';
                if (item.promo && item.promo.is_active) {
                    promoBadge = ` [Promo: ${item.promo.name}]`;
                }
                menuOptionsHtml += `<option value="${item.id}" data-price="${item.price}" data-promo='${JSON.stringify(item.promo || null)}'>${item.name} (${formatRupiah(item.price)})${promoBadge}</option>`;
            });

            rowDiv.innerHTML = `
                <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center justify-between">
                    <div class="flex-1 w-full">
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Pilih Menu *</label>
                        <select name="items[${rowIndex}][item_id]" required onchange="onItemSelect(${rowIndex})"
                            class="item-select w-full border border-gray-300 rounded-lg p-2 text-sm focus:ring-[#2B7A78] focus:border-[#2B7A78]">
                            ${menuOptionsHtml}
                        </select>
                    </div>

                    <div class="w-24">
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Qty *</label>
                        <input type="number" name="items[${rowIndex}][quantity]" value="1" min="1" required oninput="calculateFinancials()"
                            class="item-qty w-full border border-gray-300 rounded-lg p-2 text-sm text-center focus:ring-[#2B7A78] focus:border-[#2B7A78]">
                    </div>

                    <div class="w-32 text-right">
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Subtotal</label>
                        <span class="row-subtotal font-bold text-gray-900 text-sm block py-2">Rp 0</span>
                    </div>

                    <div class="pt-4 sm:pt-0">
                        <button type="button" onclick="removeItemRow(${rowIndex})"
                            class="text-red-500 hover:text-red-700 font-bold text-lg p-1.5 rounded-lg hover:bg-red-50 transition"
                            title="Hapus baris">&times;</button>
                    </div>
                </div>

                {{-- Banner Promo Menu Otomatis & Checkbox Override --}}
                <div id="promo-banner-${rowIndex}" class="mt-2.5 pt-2.5 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center text-xs hidden">
                    <div class="flex items-center gap-1.5 text-green-700 font-medium">
                        <span>🏷️</span>
                        <span id="promo-name-${rowIndex}">Promo Otomatis: -</span>
                    </div>
                    <label class="flex items-center gap-2 cursor-pointer mt-1 sm:mt-0">
                        <input type="hidden" name="items[${rowIndex}][apply_item_promo]" value="0">
                        <input type="checkbox" name="items[${rowIndex}][apply_item_promo]" id="apply-promo-${rowIndex}" value="1" checked onchange="calculateFinancials()"
                            class="rounded border-gray-300 text-[#2B7A78] focus:ring-[#2B7A78]">
                        <span class="text-gray-600 font-medium">Terapkan Diskon Menu</span>
                    </label>
                </div>
            `;

            container.appendChild(rowDiv);
            calculateFinancials();
        }

        function removeItemRow(index) {
            const rows = document.querySelectorAll('.item-row');
            if (rows.length <= 1) {
                Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Minimal harus ada 1 menu dalam pesanan.' });
                return;
            }
            const targetRow = document.getElementById(`item-row-${index}`);
            if (targetRow) {
                targetRow.remove();
                calculateFinancials();
            }
        }

        function updateItemPromoBanner(rowIndex) {
            const rowDiv = document.getElementById(`item-row-${rowIndex}`);
            if (!rowDiv) return;

            const selectEl = rowDiv.querySelector('.item-select');
            const opt = selectEl.selectedOptions[0];
            const banner = document.getElementById(`promo-banner-${rowIndex}`);
            const promoNameSpan = document.getElementById(`promo-name-${rowIndex}`);

            if (!opt || !opt.value) {
                banner.classList.add('hidden');
                return;
            }

            const promoData = JSON.parse(opt.dataset.promo || 'null');
            const isOnlineChannel = (document.getElementById('name').value.trim() === 'Pesanan Online');

            if (promoData && promoData.is_active) {
                let eligible = true;
                if (promoData.channel_scope === 'online_only' && !isOnlineChannel) eligible = false;
                if (promoData.channel_scope === 'offline_only' && isOnlineChannel) eligible = false;

                if (eligible) {
                    let discText = promoData.discount_type === 'percent'
                        ? `${promoData.discount_value}%`
                        : formatRupiah(promoData.discount_value);
                    promoNameSpan.innerText = `${promoData.name} (Diskon ${discText} / porsi)`;
                    banner.classList.remove('hidden');
                    return;
                }
            }

            banner.classList.add('hidden');
        }

        function onItemSelect(rowIndex) {
            updateItemPromoBanner(rowIndex);
            calculateFinancials();
        }

        function calculateFinancials() {
            const jenisPesanan = document.getElementById('name').value.trim();
            const isOnlineChannel = (jenisPesanan === 'Pesanan Online');

            const sourceSelect = document.getElementById('source_id');
            const sourceOpt = sourceSelect.selectedOptions[0];
            const commissionPercent = (sourceOpt && sourceOpt.value) ? parseFloat(sourceOpt.dataset.commission || '0') : 0;

            let grossAmount = 0;
            let itemDiscountTotal = 0;

            const rows = document.querySelectorAll('.item-row');
            rows.forEach(row => {
                const selectEl = row.querySelector('.item-select');
                const qtyInput = row.querySelector('.item-qty');
                const subtotalSpan = row.querySelector('.row-subtotal');
                const applyPromoCheckbox = row.querySelector('input[type="checkbox"]');

                if (!selectEl || !selectEl.value) {
                    subtotalSpan.innerText = 'Rp 0';
                    return;
                }

                const opt = selectEl.selectedOptions[0];
                const originalPrice = parseInt(opt.dataset.price || '0');
                const quantity = Math.max(1, parseInt(qtyInput.value || '1'));
                const promo = JSON.parse(opt.dataset.promo || 'null');

                let unitDiscount = 0;

                if (promo && promo.is_active && (!applyPromoCheckbox || applyPromoCheckbox.checked)) {
                    let eligible = true;
                    if (promo.channel_scope === 'online_only' && !isOnlineChannel) eligible = false;
                    if (promo.channel_scope === 'offline_only' && isOnlineChannel) eligible = false;

                    if (eligible) {
                        if (promo.discount_type === 'nominal') {
                            unitDiscount = Math.min(parseInt(promo.discount_value), originalPrice);
                        } else {
                            let raw = Math.round(originalPrice * (parseFloat(promo.discount_value) / 100));
                            if (promo.max_discount) {
                                raw = Math.min(raw, parseInt(promo.max_discount));
                            }
                            unitDiscount = Math.min(raw, originalPrice);
                        }
                    }
                }

                const finalPrice = Math.max(0, originalPrice - unitDiscount);
                const subtotal = finalPrice * quantity;

                grossAmount += (originalPrice * quantity);
                itemDiscountTotal += (unitDiscount * quantity);

                subtotalSpan.innerText = formatRupiah(subtotal);
            });

            const subtotalAfterItemPromo = Math.max(0, grossAmount - itemDiscountTotal);

            // Hitung Voucher Platform
            const promoSelect = document.getElementById('platform_promo_id');
            const promoOpt = promoSelect.selectedOptions[0];
            let platformVoucherTotal = 0;
            let platformMerchantShare = 0;
            let platformAppSubsidy = 0;

            if (promoOpt && promoOpt.value) {
                const type = promoOpt.dataset.type;
                const val = parseFloat(promoOpt.dataset.val || '0');
                const max = promoOpt.dataset.max ? parseFloat(promoOpt.dataset.max) : null;
                const sharePercent = parseFloat(promoOpt.dataset.share || '0');

                if (type === 'nominal') {
                    platformVoucherTotal = Math.min(val, subtotalAfterItemPromo);
                } else {
                    let raw = Math.round(subtotalAfterItemPromo * (val / 100));
                    if (max) raw = Math.min(raw, max);
                    platformVoucherTotal = Math.min(raw, subtotalAfterItemPromo);
                }

                platformMerchantShare = Math.round(platformVoucherTotal * (sharePercent / 100));
                platformAppSubsidy = Math.max(0, platformVoucherTotal - platformMerchantShare);
            }

            // Komisi Platform & Net Payout
            const commissionAmount = Math.round(subtotalAfterItemPromo * (commissionPercent / 100));
            const customerPaidAmount = Math.max(0, subtotalAfterItemPromo - platformVoucherTotal);
            const netPayout = Math.max(0, subtotalAfterItemPromo - platformMerchantShare - commissionAmount);

            // Render ke tampilan
            document.getElementById('dispGross').innerText = formatRupiah(grossAmount);
            document.getElementById('dispItemDiscount').innerText = '- ' + formatRupiah(itemDiscountTotal);
            document.getElementById('dispSubtotalAfterItemPromo').innerText = formatRupiah(subtotalAfterItemPromo);

            const rowPlatformVoucher = document.getElementById('rowPlatformVoucher');
            if (platformVoucherTotal > 0) {
                rowPlatformVoucher.classList.remove('hidden');
                document.getElementById('dispPlatformVoucher').innerText = '- ' + formatRupiah(platformVoucherTotal);
                document.getElementById('dispMerchantShare').innerText = '- ' + formatRupiah(platformMerchantShare);
                document.getElementById('dispAppSubsidy').innerText = formatRupiah(platformAppSubsidy);
            } else {
                rowPlatformVoucher.classList.add('hidden');
            }

            const rowCustomerPaid = document.getElementById('rowCustomerPaid');
            if (isOnlineChannel) {
                rowCustomerPaid.classList.remove('hidden');
                document.getElementById('dispCustomerPaid').innerText = formatRupiah(customerPaidAmount);
            } else {
                rowCustomerPaid.classList.add('hidden');
            }

            const rowCommission = document.getElementById('rowCommission');
            if (commissionPercent > 0) {
                rowCommission.classList.remove('hidden');
                document.getElementById('labelCommission').innerText = `Komisi Platform (${commissionPercent}%):`;
                document.getElementById('dispCommission').innerText = '- ' + formatRupiah(commissionAmount);
            } else {
                rowCommission.classList.add('hidden');
            }

            document.getElementById('dispNetPayout').innerText = formatRupiah(netPayout);

            calculateChange();
        }

        function calculateChange() {
            const paidInput = document.getElementById('paid_amount');
            const changeDiv = document.getElementById('changeContainer');
            const dispChange = document.getElementById('dispChange');
            const hiddenChange = document.getElementById('change_amount');

            const jenisPesanan = document.getElementById('name').value.trim();
            const targetAmount = (jenisPesanan === 'Pesanan Offline')
                ? parseInt(document.getElementById('dispNetPayout').innerText.replace(/[^0-9]/g, '') || '0')
                : parseInt(document.getElementById('dispCustomerPaid').innerText.replace(/[^0-9]/g, '') || '0');

            const paidVal = parseInt(paidInput.value || '0');

            if (paidInput.value && paidVal >= targetAmount) {
                const change = paidVal - targetAmount;
                dispChange.innerText = formatRupiah(change);
                hiddenChange.value = change;
                changeDiv.classList.remove('hidden');
            } else {
                dispChange.innerText = 'Rp 0';
                hiddenChange.value = 0;
                changeDiv.classList.add('hidden');
            }
        }

        function resetFormState() {
            setTimeout(() => {
                document.getElementById('itemsContainer').innerHTML = '';
                rowCounter = 0;
                addItemRow();
                onChannelChange();
            }, 50);
        }
    </script>
</x-app-layout>