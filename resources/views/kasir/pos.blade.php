@section('title', 'Kasir | POS')
<x-app-layout>
    @include('layouts.navbar-kasir')

    @php
        $menuData = $items->mapWithKeys(function ($item) {
            $promo = $item->activePromo();
            return [$item->id => [
                'id' => $item->id,
                'name' => $item->name,
                'price' => (int) $item->price,
                'stock' => $item->stock,
                'code' => $item->code,
                'has_promo' => (bool) $promo,
                'promo' => $promo ? [
                    'id' => $promo->id,
                    'name' => $promo->name,
                    'type' => $promo->discount_type,
                    'value' => (float) $promo->discount_value,
                    'scope' => $promo->channel_scope ?: 'all', // 'all', 'online_only', 'offline_only'
                    'discount_amount' => $promo->calculateUnitDiscount((int) $item->price),
                    'label' => $promo->discount_type === 'percent'
                        ? 'Diskon ' . rtrim(rtrim(number_format($promo->discount_value, 2), '0'), '.') . '%'
                        : 'Hemat Rp ' . number_format($promo->discount_value, 0, ',', '.'),
                    'scope_label' => $promo->channel_scope === 'online_only'
                        ? 'Khusus Online'
                        : ($promo->channel_scope === 'offline_only' ? 'Khusus Offline' : 'Semua Saluran'),
                ] : null,
            ]];
        });

        $oldCart = collect(old('items', []))
            ->filter(fn ($row) => isset($row['item_id'], $menuData[$row['item_id']]))
            ->map(fn ($row) => ['id' => (int) $row['item_id'], 'qty' => (int) $row['quantity']])
            ->values();

        $activePromosCount = $activeItemPromos->count();
    @endphp

    <div class="pt-16 min-h-screen bg-[#D1DDD5]"
        x-data="pos({
            menu: @js($menuData),
            cart: @js($oldCart),
            jenis: @js(old('name', $jenisPesanan[0])),
            sourceId: @js(old('source_id') ? (int) old('source_id') : ($sources->where('is_online', false)->first()?->id ?? $cashSourceIds->first())),
            platformPromoId: @js(old('platform_promo_id') ? (int) old('platform_promo_id') : null),
            paid: @js(old('paid_amount')),
            cashSourceIds: @js($cashSourceIds),
            sources: @js($sources->map(fn($s) => [
                'id' => $s->id,
                'name' => $s->name,
                'is_online' => (bool) $s->is_online,
                'commission_percent' => (float) $s->default_commission_percent,
                'platform_promos' => $s->platformPromos->map(fn($p) => [
                    'id' => $p->id,
                    'name' => $p->name,
                    'discount_type' => $p->discount_type,
                    'discount_value' => (int) $p->discount_value,
                    'max_discount' => (int) $p->max_discount,
                    'merchant_share_percent' => (int) $p->merchant_share_percent,
                ]),
            ])),
        })">

        <div class="lg:grid lg:grid-cols-[1fr_400px] lg:h-[calc(100vh-4rem)]">
            {{-- ===== Daftar menu ===== --}}
            <section class="p-4 sm:p-6 lg:overflow-y-auto">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
                    <div>
                        <h1 class="text-xl font-semibold text-[#2B7A78]">Pilih Menu</h1>
                        <p class="text-xs text-gray-600 mt-0.5">
                            Harga otomatis menyesuaikan promo aktif untuk <span class="font-bold text-[#2B7A78]" x-text="jenis"></span>.
                        </p>
                    </div>
                    <div class="relative w-full sm:w-72">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                        </svg>
                        <input type="search" x-model="search" placeholder="Cari menu..."
                            class="w-full pl-10 pr-3 py-2.5 text-sm rounded-lg border border-gray-300 bg-white focus:ring-2 focus:ring-[#468585] focus:border-[#468585]">
                    </div>
                </div>

                {{-- Banner Info Promo Aktif --}}
                @if ($activePromosCount > 0)
                    <div class="mb-4 bg-white border-l-4 border-[#2B7A78] rounded-xl p-3 sm:p-4 shadow-sm" x-data="{ expanded: false }">
                        <div class="flex items-center justify-between cursor-pointer" @click="expanded = !expanded">
                            <div class="flex items-center gap-2">
                                <span class="text-lg">🔥</span>
                                <div>
                                    <h2 class="text-sm font-bold text-[#2B7A78]">
                                        {{ $activePromosCount }} Promo Menu Sedang Aktif
                                    </h2>
                                    <p class="text-xs text-gray-500">
                                        Diskon otomatis terpasang pada menu bertanda promo. Klik untuk melihat daftar promo.
                                    </p>
                                </div>
                            </div>
                            <button type="button" class="text-xs font-semibold text-[#2B7A78] hover:underline flex items-center gap-1">
                                <span x-text="expanded ? 'Tutup Detail' : 'Lihat Promo'"></span>
                                <svg class="w-4 h-4 transition-transform" :class="expanded ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </div>
                        
                        <div x-show="expanded" x-collapse class="mt-3 pt-3 border-t border-gray-100 grid grid-cols-1 md:grid-cols-2 gap-2 text-xs">
                            @foreach ($activeItemPromos as $promo)
                                <div class="p-2.5 rounded-lg bg-teal-50 border border-teal-100 flex items-start justify-between">
                                    <div>
                                        <span class="font-bold text-teal-900">{{ $promo->name }}</span>
                                        <div class="text-teal-700 mt-0.5">
                                            @if ($promo->discount_type === 'percent')
                                                Diskon {{ $promo->discount_value }}%
                                                @if ($promo->max_discount) (Maks. Rp {{ number_format($promo->max_discount, 0, ',', '.') }}) @endif
                                            @else
                                                Potongan Rp {{ number_format($promo->discount_value, 0, ',', '.') }} / porsi
                                            @endif
                                            • 
                                            <span class="font-semibold">
                                                {{ $promo->channel_scope === 'online_only' ? 'Khusus Online' : ($promo->channel_scope === 'offline_only' ? 'Khusus Offline' : 'Semua Saluran') }}
                                            </span>
                                        </div>
                                        <div class="text-[11px] text-gray-500 mt-0.5">
                                            {{ $promo->items_count }} menu terdaftar
                                        </div>
                                    </div>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#2B7A78] text-white uppercase">
                                        Aktif
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($items->isEmpty())
                    <div class="bg-white rounded-xl p-8 text-center text-gray-500">
                        Belum ada menu. Minta admin keuangan menambahkan menu terlebih dahulu.
                    </div>
                @endif

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-3">
                    @foreach ($items as $item)
                        @php $promo = $item->activePromo(); @endphp
                        <button type="button" @click="add({{ $item->id }})"
                            @disabled($item->lacakStok() && $item->stock < 1)
                            x-show="matches(@js($item->name . ' ' . $item->code))"
                            class="group relative text-left bg-white rounded-xl shadow-sm hover:shadow-md border-2 transition overflow-hidden focus:outline-none focus:ring-2 focus:ring-[#468585] disabled:opacity-50 disabled:cursor-not-allowed flex flex-col justify-between"
                            :class="qtyOf({{ $item->id }}) ? 'border-[#468585]' : 'border-transparent'">
                            
                            <div>
                                <div class="relative">
                                    <x-menu-image :item="$item" size="w-full h-24 sm:h-28" text="text-2xl" class="rounded-none" />
                                    
                                    {{-- Badge Promo di sudut gambar --}}
                                    @if ($promo)
                                        <div class="absolute top-2 left-2 flex flex-col gap-1 items-start">
                                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold text-white shadow bg-[#DB5461]">
                                                🏷️ {{ $promo->discount_type === 'percent' ? $promo->discount_value.'%' : 'Hemat Rp '.number_format($promo->discount_value, 0, ',', '.') }}
                                            </span>
                                            @if ($promo->channel_scope === 'online_only')
                                                <span class="px-1.5 py-0.5 rounded text-[9px] font-semibold text-blue-800 bg-blue-100/90 shadow border border-blue-200">
                                                    Online Saja
                                                </span>
                                            @elseif ($promo->channel_scope === 'offline_only')
                                                <span class="px-1.5 py-0.5 rounded text-[9px] font-semibold text-amber-800 bg-amber-100/90 shadow border border-amber-200">
                                                    Offline Saja
                                                </span>
                                            @endif
                                        </div>
                                    @endif

                                    {{-- Badge Qty di pojok kanan atas --}}
                                    <span x-show="qtyOf({{ $item->id }})" x-text="qtyOf({{ $item->id }})"
                                        class="absolute top-2 right-2 min-w-7 h-7 px-2 rounded-full bg-[#2B7A78] text-white text-sm font-bold flex items-center justify-center shadow"></span>
                                </div>

                                <div class="p-3">
                                    <p class="text-sm font-medium text-gray-800 leading-tight line-clamp-2">{{ $item->name }}</p>
                                    @if ($item->description)
                                        <p class="mt-0.5 text-xs text-gray-500 leading-snug line-clamp-2">{{ $item->description }}</p>
                                    @endif

                                    {{-- Tampilan Harga & Status Promo Adaptif --}}
                                    <div class="mt-1.5">
                                        @if ($promo)
                                            <template x-if="isPromoApplicable({{ $item->id }})">
                                                <div class="flex flex-wrap items-baseline gap-1.5">
                                                    <span class="text-sm font-bold text-[#2B7A78]" x-text="rupiah(finalPrice({{ $item->id }}))"></span>
                                                    <span class="text-xs text-gray-400 line-through">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                                </div>
                                            </template>
                                            <template x-if="!isPromoApplicable({{ $item->id }})">
                                                <div>
                                                    <span class="text-sm font-semibold text-gray-800">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                                    <span class="block text-[10px] text-amber-600 mt-0.5 font-medium">
                                                        {{ $promo->channel_scope === 'online_only' ? '*Promo khusus online' : '*Promo khusus offline' }}
                                                    </span>
                                                </div>
                                            </template>
                                        @else
                                            <p class="text-sm font-semibold text-[#2B7A78]">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                        @endif
                                    </div>

                                    @if ($item->lacakStok())
                                        <p class="mt-1 text-xs font-medium {{ $item->stock < 1 ? 'text-red-600' : ($item->stokMenipis() ? 'text-amber-600' : 'text-gray-500') }}">
                                            {{ $item->stock < 1 ? 'Stok habis' : 'Sisa '.$item->stock }}
                                        </p>
                                    @endif
                                </div>
                            </div>
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

                    {{-- Daftar item keranjang --}}
                    <div class="flex-1 overflow-y-auto px-5 py-3 min-h-[120px]">
                        <p x-show="!cart.length" class="text-sm text-gray-400 text-center py-8">
                            Ketuk menu di sebelah kiri untuk menambahkan.
                        </p>
                        <template x-for="(row, index) in cart" :key="row.id">
                            <div class="py-2.5 border-b border-dashed last:border-0">
                                <div class="flex items-center gap-3">
                                    <input type="hidden" :name="`items[${index}][item_id]`" :value="row.id">
                                    <input type="hidden" :name="`items[${index}][quantity]`" :value="row.qty">
                                    
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-800 truncate" x-text="menu[row.id].name"></p>
                                        <div class="flex items-center gap-1.5 text-xs">
                                            <template x-if="isPromoApplicable(row.id)">
                                                <div class="flex items-center gap-1">
                                                    <span class="font-semibold text-emerald-700" x-text="rupiah(finalPrice(row.id))"></span>
                                                    <span class="text-gray-400 line-through text-[11px]" x-text="rupiah(menu[row.id].price)"></span>
                                                </div>
                                            </template>
                                            <template x-if="!isPromoApplicable(row.id)">
                                                <span class="text-gray-500" x-text="rupiah(menu[row.id].price)"></span>
                                            </template>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-1">
                                        <button type="button" @click="dec(row.id)" aria-label="Kurangi"
                                            class="h-8 w-8 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100 flex items-center justify-center font-bold">−</button>
                                        <input type="number" min="1" max="999" x-model.number="row.qty" @change="normalize(row)"
                                            class="h-8 w-12 text-center text-sm border border-gray-300 rounded-md p-0 [appearance:textfield]">
                                        <button type="button" @click="add(row.id)" aria-label="Tambah"
                                            class="h-8 w-8 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100 flex items-center justify-center font-bold">+</button>
                                    </div>

                                    <div class="w-24 text-right">
                                        <p class="text-sm font-semibold text-gray-800"
                                            x-text="rupiah(finalPrice(row.id) * row.qty)"></p>
                                        <template x-if="isPromoApplicable(row.id)">
                                            <p class="text-[10px] text-emerald-600 font-medium">
                                                Hemat <span x-text="rupiah(unitDiscount(row.id) * row.qty)"></span>
                                            </p>
                                        </template>
                                    </div>
                                </div>

                                {{-- Info Badge Promo di bawah nama menu --}}
                                <template x-if="isPromoApplicable(row.id)">
                                    <div class="mt-1 flex items-center gap-1">
                                        <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">
                                            🏷️ <span x-text="menu[row.id].promo.name"></span> 
                                            (<span x-text="menu[row.id].promo.label"></span>)
                                        </span>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>

                    {{-- Form Pembayaran --}}
                    <div class="border-t px-5 py-4 space-y-4 bg-gray-50">
                        <div>
                            <p class="text-xs font-medium text-gray-500 mb-1.5">Jenis pesanan</p>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach ($jenisPesanan as $jenisOption)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="name" value="{{ $jenisOption }}" x-model="jenis" @change="setJenis($el.value)" class="peer sr-only">
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
                                    <label class="cursor-pointer" x-show="{{ $source->is_online ? 'isOnline' : '!isOnline' }}">
                                        <input type="radio" name="source_id" value="{{ $source->id }}" x-model.number="sourceId" @change="platformPromoId = null" class="peer sr-only">
                                        <span class="block text-sm px-3 py-1.5 rounded-lg border border-gray-300 bg-white peer-checked:bg-[#468585] peer-checked:text-white peer-checked:border-[#468585] peer-focus-visible:ring-2 peer-focus-visible:ring-[#468585]">
                                            {{ $source->name }}
                                            @if ($source->default_commission_percent > 0)
                                                <span class="text-[10px] opacity-80">({{ rtrim(rtrim(number_format($source->default_commission_percent, 2), '0'), '.') }}%)</span>
                                            @endif
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Dropdown Voucher Promo Platform (Saat Pesanan Online) --}}
                        <div x-show="isOnline && availablePlatformPromos.length > 0" x-cloak class="pt-1">
                            <label for="platform_promo_id" class="block text-xs font-medium text-gray-600 mb-1">
                                Voucher Platform Aplikasi
                            </label>
                            <select name="platform_promo_id" id="platform_promo_id" x-model.number="platformPromoId"
                                class="w-full text-xs rounded-lg border border-gray-300 bg-white p-2 focus:ring-[#468585] focus:border-[#468585]">
                                <option :value="null">-- Tanpa Voucher Platform --</option>
                                <template x-for="p in availablePlatformPromos" :key="p.id">
                                    <option :value="p.id" x-text="`${p.name} (${p.discount_type === 'percent' ? p.discount_value + '%' : rupiah(p.discount_value)})`"></option>
                                </template>
                            </select>
                            <template x-if="selectedPlatformPromo">
                                <p class="text-[11px] text-blue-700 mt-1 font-medium">
                                    💡 Potongan voucher: <span x-text="rupiah(platformDiscountAmount)"></span> 
                                    (Tanggungan Merchant: <span x-text="selectedPlatformPromo.merchant_share_percent"></span>%)
                                </p>
                            </template>
                        </div>

                        <div x-show="isCash" x-cloak>
                            <label for="paid" class="text-xs font-medium text-gray-500">Uang diterima</label>
                            <div class="relative mt-1">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                                <input id="paid" type="number" min="0" name="paid_amount" x-model.number="paid" :disabled="!isCash"
                                    inputmode="numeric" placeholder="0" :aria-invalid="kurang" aria-describedby="paid-kurang"
                                    class="w-full pl-10 pr-3 py-2.5 text-lg font-semibold rounded-lg border"
                                    :class="kurang
                                        ? 'border-red-500 bg-red-50 text-red-700 focus:ring-2 focus:ring-red-400 focus:border-red-500'
                                        : 'border-gray-300 focus:ring-2 focus:ring-[#468585] focus:border-[#468585]'">
                            </div>
                            <div id="paid-kurang" x-show="kurang" x-cloak role="alert"
                                class="mt-2 flex items-start gap-2 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">
                                <svg class="h-5 w-5 shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 6a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 6zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                </svg>
                                <div>
                                    <p class="font-semibold">Uang kurang <span x-text="rupiah(-change)"></span></p>
                                    <p class="text-red-600">Total belanja <span x-text="rupiah(total)"></span>, uang diterima baru <span x-text="rupiah(paid)"></span>.</p>
                                </div>
                            </div>
                            <div class="mt-2 flex flex-wrap gap-2">
                                <button type="button" @click="paid = total" class="text-xs px-3 py-1.5 rounded-md bg-white border border-gray-300 hover:bg-gray-100 font-medium">Uang pas</button>
                                <template x-for="nominal in quickCash" :key="nominal">
                                    <button type="button" @click="paid = nominal" x-text="rupiah(nominal)"
                                        class="text-xs px-3 py-1.5 rounded-md bg-white border border-gray-300 hover:bg-gray-100 font-medium"></button>
                                </template>
                            </div>
                        </div>

                        {{-- Rincian Tagihan & Total --}}
                        <div class="space-y-1.5 pt-2 border-t border-gray-200">
                            <div class="flex justify-between text-xs text-gray-500">
                                <span x-text="`${totalQty} porsi (${cart.length} menu)`"></span>
                                <span x-text="rupiah(grossTotal)"></span>
                            </div>

                            {{-- Diskon Menu Promo --}}
                            <div x-show="itemDiscountTotal > 0" x-cloak class="flex justify-between text-xs font-semibold text-emerald-700">
                                <span class="flex items-center gap-1">🏷️ Hemat Promo Menu</span>
                                <span>- <span x-text="rupiah(itemDiscountTotal)"></span></span>
                            </div>

                            {{-- Diskon Voucher Platform --}}
                            <div x-show="platformDiscountAmount > 0" x-cloak class="flex justify-between text-xs font-semibold text-blue-700">
                                <span class="flex items-center gap-1">📱 Voucher Platform</span>
                                <span>- <span x-text="rupiah(platformDiscountAmount)"></span></span>
                            </div>

                            <div class="flex justify-between items-baseline pt-1">
                                <div>
                                    <span class="text-gray-800 font-bold">Total Tagihan</span>
                                    <span x-show="itemDiscountTotal > 0 || platformDiscountAmount > 0" class="block text-[10px] text-emerald-600 font-medium">
                                        (Hemat <span x-text="rupiah(itemDiscountTotal + platformDiscountAmount)"></span>)
                                    </span>
                                </div>
                                <span class="text-2xl font-extrabold text-[#2B7A78]" x-text="rupiah(total)"></span>
                            </div>

                            <div x-show="isCash && paidFilled && !kurang" x-cloak class="flex justify-between text-sm pt-1">
                                <span class="text-gray-600">Kembalian</span>
                                <span class="font-bold text-gray-800" x-text="rupiah(change)"></span>
                            </div>
                        </div>

                        <button type="submit" :disabled="!canPay || submitting"
                            class="w-full py-3 rounded-xl text-white text-lg font-semibold bg-[#2B7A78] hover:bg-[#205C5D] disabled:bg-gray-300 disabled:text-gray-600 disabled:cursor-not-allowed transition shadow">
                            <span x-show="submitting" x-cloak>Menyimpan...</span>
                            <span x-show="!submitting" x-text="blocker ?? 'Bayar'">Bayar</span>
                        </button>
                    </div>
                </form>
            </aside>
        </div>

        {{-- Ringkasan bawah untuk layar kecil (Mobile) --}}
        <a href="#keranjang" x-show="cart.length" x-cloak
            class="lg:hidden fixed bottom-4 left-4 right-4 z-40 flex items-center justify-between px-5 py-3 rounded-xl bg-[#2B7A78] text-white shadow-lg">
            <div>
                <span class="text-xs opacity-90" x-text="`${totalQty} item`"></span>
                <span x-show="itemDiscountTotal > 0" class="text-[10px] bg-white/20 px-1.5 py-0.5 rounded ml-1">
                    Hemat <span x-text="rupiah(itemDiscountTotal)"></span>
                </span>
            </div>
            <span class="font-bold text-lg" x-text="rupiah(total)"></span>
        </a>
    </div>

    <script>
        function pos(initial) {
            return {
                menu: initial.menu,
                cart: initial.cart,
                jenis: initial.jenis,
                sourceId: initial.sourceId,
                platformPromoId: initial.platformPromoId,
                paid: initial.paid === null ? null : Number(initial.paid),
                cashSourceIds: initial.cashSourceIds,
                sources: initial.sources,
                search: '',
                submitting: false,

                get isOnline() {
                    return this.jenis === 'Pesanan Online';
                },
                get filteredSources() {
                    return this.sources.filter(s => Boolean(s.is_online) === this.isOnline);
                },
                get currentSource() {
                    return this.sources.find(s => s.id === Number(this.sourceId)) || null;
                },
                get availablePlatformPromos() {
                    if (!this.isOnline || !this.currentSource) return [];
                    return this.currentSource.platform_promos || [];
                },
                get selectedPlatformPromo() {
                    if (!this.platformPromoId) return null;
                    return this.availablePlatformPromos.find(p => p.id === Number(this.platformPromoId)) || null;
                },

                isPromoApplicable(id) {
                    const item = this.menu[id];
                    if (!item || !item.has_promo || !item.promo) return false;
                    const scope = item.promo.scope;
                    if (scope === 'all') return true;
                    if (this.isOnline && scope === 'online_only') return true;
                    if (!this.isOnline && scope === 'offline_only') return true;
                    return false;
                },
                unitDiscount(id) {
                    if (!this.isPromoApplicable(id)) return 0;
                    return this.menu[id].promo.discount_amount || 0;
                },
                finalPrice(id) {
                    const original = this.menu[id].price;
                    const discount = this.unitDiscount(id);
                    return Math.max(0, original - discount);
                },

                get grossTotal() {
                    return this.cart.reduce((sum, row) => sum + (this.menu[row.id].price * row.qty), 0);
                },
                get itemDiscountTotal() {
                    return this.cart.reduce((sum, row) => sum + (this.unitDiscount(row.id) * row.qty), 0);
                },
                get subtotalAfterItemPromo() {
                    return Math.max(0, this.grossTotal - this.itemDiscountTotal);
                },
                get platformDiscountAmount() {
                    const promo = this.selectedPlatformPromo;
                    if (!promo) return 0;
                    let disc = 0;
                    if (promo.discount_type === 'percent') {
                        disc = Math.round(this.subtotalAfterItemPromo * (promo.discount_value / 100));
                        if (promo.max_discount && disc > promo.max_discount) {
                            disc = promo.max_discount;
                        }
                    } else {
                        disc = promo.discount_value;
                    }
                    return Math.min(disc, this.subtotalAfterItemPromo);
                },
                get total() {
                    return Math.max(0, this.subtotalAfterItemPromo - this.platformDiscountAmount);
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
                get paidFilled() {
                    return this.paid !== null && this.paid !== '';
                },
                // Uang tunai sudah diisi tapi belum cukup untuk total
                get kurang() {
                    return this.isCash && this.cart.length > 0 && this.paidFilled && this.change < 0;
                },
                // Alasan tombol Bayar belum bisa ditekan (null = siap bayar)
                get blocker() {
                    if (!this.cart.length) return 'Pilih menu dulu';
                    if (!this.jenis) return 'Pilih jenis pesanan';
                    if (!this.sourceId) return 'Pilih metode pembayaran';
                    if (this.isCash && !this.paidFilled) return 'Isi uang diterima';
                    if (this.kurang) return 'Uang kurang ' + this.rupiah(-this.change);
                    return null;
                },
                get canPay() {
                    return this.blocker === null;
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
                    const max = this.menu[id]?.stock ?? 999;
                    if (row) row.qty = Math.min(row.qty + 1, 999, max);
                    else if (max > 0) this.cart.push({ id, qty: 1 });
                },
                dec(id) {
                    const row = this.cart.find((r) => r.id === id);
                    if (!row) return;
                    row.qty -= 1;
                    if (row.qty <= 0) this.cart = this.cart.filter((r) => r.id !== id);
                },
                normalize(row) {
                    row.qty = Math.max(0, Math.min(999, this.menu[row.id]?.stock ?? 999, parseInt(row.qty) || 0));
                    if (row.qty === 0) this.cart = this.cart.filter((r) => r.id !== row.id);
                },
                reset() {
                    this.cart = [];
                    this.paid = null;
                },
                rupiah(value) {
                    return 'Rp ' + Number(value || 0).toLocaleString('id-ID');
                },
                setJenis(val) {
                    this.jenis = val;
                    const valid = this.filteredSources;
                    if (!valid.some(s => s.id === Number(this.sourceId))) {
                        this.sourceId = valid[0]?.id || null;
                    }
                    this.platformPromoId = null;
                },
            };
        }
    </script>
</x-app-layout>
