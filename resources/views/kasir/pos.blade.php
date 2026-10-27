@section('title', 'Kasir | POS')
<x-app-layout>
    @include('layouts.navbar-kasir')

    @php
        $menuData = $items->mapWithKeys(fn ($item) => [$item->id => ['id' => $item->id, 'name' => $item->name, 'price' => $item->price]]);
        $oldCart = collect(old('items', []))
            ->filter(fn ($row) => isset($row['item_id'], $menuData[$row['item_id']]))
            ->map(fn ($row) => ['id' => (int) $row['item_id'], 'qty' => (int) $row['quantity']])
            ->values();
    @endphp

    <div class="pt-16 min-h-screen bg-[#D1DDD5]"
        x-data="pos({
            menu: @js($menuData),
            cart: @js($oldCart),
            jenis: @js(old('name', $jenisPesanan[0])),
            sourceId: @js(old('source_id') ? (int) old('source_id') : $cashSourceIds->first()),
            paid: @js(old('paid_amount')),
            cashSourceIds: @js($cashSourceIds),
        })">

        <div class="lg:grid lg:grid-cols-[1fr_400px] lg:h-[calc(100vh-4rem)]">
            {{-- ===== Daftar menu ===== --}}
            <section class="p-4 sm:p-6 lg:overflow-y-auto">
                <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-4">
                    <h1 class="text-xl font-semibold text-[#2B7A78]">Pilih Menu</h1>
                    <div class="relative sm:ml-auto sm:w-72">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                        </svg>
                        <input type="search" x-model="search" placeholder="Cari menu..."
                            class="w-full pl-10 pr-3 py-2.5 text-sm rounded-lg border border-gray-300 bg-white focus:ring-2 focus:ring-[#468585] focus:border-[#468585]">
                    </div>
                </div>

                @if ($items->isEmpty())
                    <div class="bg-white rounded-xl p-8 text-center text-gray-500">
                        Belum ada menu. Minta admin keuangan menambahkan menu terlebih dahulu.
                    </div>
                @endif

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-3">
                    @foreach ($items as $item)
                        <button type="button" @click="add({{ $item->id }})"
                            x-show="matches(@js($item->name . ' ' . $item->code))"
                            class="group relative text-left bg-white rounded-xl shadow-sm hover:shadow-md border-2 transition overflow-hidden focus:outline-none focus:ring-2 focus:ring-[#468585]"
                            :class="qtyOf({{ $item->id }}) ? 'border-[#468585]' : 'border-transparent'">
                            <x-menu-image :item="$item" size="w-full h-24 sm:h-28" text="text-2xl" class="rounded-none" />
                            <div class="p-3">
                                <p class="text-sm font-medium text-gray-800 leading-tight line-clamp-2">{{ $item->name }}</p>
                                <p class="mt-1 text-sm font-semibold text-[#2B7A78]">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                            </div>
                            <span x-show="qtyOf({{ $item->id }})" x-text="qtyOf({{ $item->id }})"
                                class="absolute top-2 right-2 min-w-7 h-7 px-2 rounded-full bg-[#DB5461] text-white text-sm font-bold flex items-center justify-center shadow"></span>
                        </button>
                    @endforeach
                </div>
                <p x-show="search && !anyMatch" class="text-center text-gray-500 py-8">Menu tidak ditemukan.</p>
            </section>

            {{-- ===== Keranjang & pembayaran ===== --}}
            <aside id="keranjang" class="bg-white lg:border-l border-gray-200 flex flex-col lg:h-[calc(100vh-4rem)] pb-24 lg:pb-0">
                <form method="POST" action="{{ route('kasir.checkout') }}" class="flex flex-col h-full" @submit="submitting = true">
                    @csrf
                    <div class="px-5 pt-5 pb-3 flex items-center justify-between border-b">
                        <h2 class="text-lg font-semibold text-gray-800">Pesanan</h2>
                        <button type="button" x-show="cart.length" @click="reset()"
                            class="text-sm text-red-500 hover:text-red-700">Kosongkan</button>
                    </div>

                    @if ($errors->any())
                        <div class="mx-5 mt-3 rounded-md bg-red-50 p-3 text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    {{-- Daftar item --}}
                    <div class="flex-1 overflow-y-auto px-5 py-3 min-h-[120px]">
                        <p x-show="!cart.length" class="text-sm text-gray-400 text-center py-8">
                            Ketuk menu di sebelah kiri untuk menambahkan.
                        </p>
                        <template x-for="(row, index) in cart" :key="row.id">
                            <div class="flex items-center gap-3 py-2 border-b border-dashed last:border-0">
                                <input type="hidden" :name="`items[${index}][item_id]`" :value="row.id">
                                <input type="hidden" :name="`items[${index}][quantity]`" :value="row.qty">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-800 truncate" x-text="menu[row.id].name"></p>
                                    <p class="text-xs text-gray-500" x-text="rupiah(menu[row.id].price)"></p>
                                </div>
                                <div class="flex items-center gap-1">
                                    <button type="button" @click="dec(row.id)" aria-label="Kurangi"
                                        class="h-8 w-8 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100">−</button>
                                    <input type="number" min="1" max="999" x-model.number="row.qty" @change="normalize(row)"
                                        class="h-8 w-12 text-center text-sm border border-gray-300 rounded-md p-0 [appearance:textfield]">
                                    <button type="button" @click="add(row.id)" aria-label="Tambah"
                                        class="h-8 w-8 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100">+</button>
                                </div>
                                <p class="w-24 text-right text-sm font-semibold text-gray-800"
                                    x-text="rupiah(menu[row.id].price * row.qty)"></p>
                            </div>
                        </template>
                    </div>

                    {{-- Pembayaran --}}
                    <div class="border-t px-5 py-4 space-y-4 bg-gray-50">
                        <div>
                            <p class="text-xs font-medium text-gray-500 mb-1.5">Jenis pesanan</p>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach ($jenisPesanan as $jenisOption)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="name" value="{{ $jenisOption }}" x-model="jenis" class="peer sr-only">
                                        <span class="block text-center text-sm py-2 rounded-lg border border-gray-300 bg-white peer-checked:bg-[#468585] peer-checked:text-white peer-checked:border-[#468585] peer-focus-visible:ring-2 peer-focus-visible:ring-[#468585]">
                                            {{ str_replace('Pesanan ', '', $jenisOption) }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <p class="text-xs font-medium text-gray-500 mb-1.5">Metode pembayaran</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($sources as $source)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="source_id" value="{{ $source->id }}" x-model.number="sourceId" class="peer sr-only">
                                        <span class="block text-sm px-3 py-1.5 rounded-lg border border-gray-300 bg-white peer-checked:bg-[#468585] peer-checked:text-white peer-checked:border-[#468585] peer-focus-visible:ring-2 peer-focus-visible:ring-[#468585]">
                                            {{ $source->name }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div x-show="isCash" x-cloak>
                            <label for="paid" class="text-xs font-medium text-gray-500">Uang diterima</label>
                            <div class="relative mt-1">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                                <input id="paid" type="number" min="0" name="paid_amount" x-model.number="paid" :disabled="!isCash"
                                    inputmode="numeric" placeholder="0"
                                    class="w-full pl-10 pr-3 py-2.5 text-lg font-semibold rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#468585] focus:border-[#468585]">
                            </div>
                            <div class="mt-2 flex flex-wrap gap-2">
                                <button type="button" @click="paid = total" class="text-xs px-3 py-1.5 rounded-md bg-white border border-gray-300 hover:bg-gray-100">Uang pas</button>
                                <template x-for="nominal in quickCash" :key="nominal">
                                    <button type="button" @click="paid = nominal" x-text="rupiah(nominal)"
                                        class="text-xs px-3 py-1.5 rounded-md bg-white border border-gray-300 hover:bg-gray-100"></button>
                                </template>
                            </div>
                        </div>

                        <div class="space-y-1 pt-1">
                            <div class="flex justify-between text-sm text-gray-600">
                                <span x-text="`${totalQty} item`"></span>
                            </div>
                            <div class="flex justify-between items-baseline">
                                <span class="text-gray-700 font-medium">Total</span>
                                <span class="text-2xl font-bold text-[#2B7A78]" x-text="rupiah(total)"></span>
                            </div>
                            <div x-show="isCash && paid !== null && paid !== ''" x-cloak class="flex justify-between text-sm">
                                <span class="text-gray-600">Kembalian</span>
                                <span class="font-semibold" :class="change < 0 ? 'text-red-600' : 'text-gray-800'"
                                    x-text="change < 0 ? 'Kurang ' + rupiah(-change) : rupiah(change)"></span>
                            </div>
                        </div>

                        <button type="submit" :disabled="!canPay || submitting"
                            class="w-full py-3 rounded-xl text-white text-lg font-semibold bg-[#2B7A78] hover:bg-[#205C5D] disabled:bg-gray-300 disabled:cursor-not-allowed transition">
                            <span x-show="!submitting">Bayar</span>
                            <span x-show="submitting" x-cloak>Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </aside>
        </div>

        {{-- Ringkasan bawah untuk layar kecil --}}
        <a href="#keranjang" x-show="cart.length" x-cloak
            class="lg:hidden fixed bottom-4 left-4 right-4 z-40 flex items-center justify-between px-5 py-3 rounded-xl bg-[#2B7A78] text-white shadow-lg">
            <span x-text="`${totalQty} item`"></span>
            <span class="font-bold" x-text="rupiah(total)"></span>
        </a>
    </div>

    <script>
        function pos(initial) {
            return {
                menu: initial.menu,
                cart: initial.cart,
                jenis: initial.jenis,
                sourceId: initial.sourceId,
                paid: initial.paid === null ? null : Number(initial.paid),
                cashSourceIds: initial.cashSourceIds,
                search: '',
                submitting: false,

                get total() {
                    return this.cart.reduce((sum, row) => sum + this.menu[row.id].price * row.qty, 0);
                },
                get totalQty() {
                    return this.cart.reduce((sum, row) => sum + row.qty, 0);
                },
                get isCash() {
                    return this.cashSourceIds.includes(Number(this.sourceId));
                },
                get change() {
                    return Number(this.paid || 0) - this.total;
                },
                get canPay() {
                    if (!this.cart.length || !this.sourceId || !this.jenis) return false;
                    return !this.isCash || (this.paid !== null && this.paid !== '' && this.change >= 0);
                },
                // Nominal uang cepat: pecahan di atas total
                get quickCash() {
                    const notes = [10000, 20000, 50000, 100000];
                    const options = new Set();
                    for (const note of notes) {
                        const rounded = Math.ceil(this.total / note) * note;
                        if (rounded > this.total) options.add(rounded);
                    }
                    return [...options].sort((a, b) => a - b).slice(0, 4);
                },
                get anyMatch() {
                    return Object.values(this.menu).some((m) => this.matches(m.name));
                },

                matches(text) {
                    return text.toLowerCase().includes(this.search.trim().toLowerCase());
                },
                qtyOf(id) {
                    return this.cart.find((row) => row.id === id)?.qty || 0;
                },
                add(id) {
                    const row = this.cart.find((r) => r.id === id);
                    if (row) row.qty = Math.min(row.qty + 1, 999);
                    else this.cart.push({ id, qty: 1 });
                },
                dec(id) {
                    const row = this.cart.find((r) => r.id === id);
                    if (!row) return;
                    row.qty -= 1;
                    if (row.qty <= 0) this.cart = this.cart.filter((r) => r.id !== id);
                },
                normalize(row) {
                    row.qty = Math.max(0, Math.min(999, parseInt(row.qty) || 0));
                    if (row.qty === 0) this.cart = this.cart.filter((r) => r.id !== row.id);
                },
                reset() {
                    this.cart = [];
                    this.paid = null;
                },
                rupiah(value) {
                    return 'Rp ' + Number(value || 0).toLocaleString('id-ID');
                },
            };
        }
    </script>
</x-app-layout>
