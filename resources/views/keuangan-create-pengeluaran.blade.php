@section('title', 'Tambah Pengeluaran')
<x-app-layout>
    <x-panel plain>
        <div class="min-h-full bg-[#D1DDD5] px-4 sm:px-8 pt-6 pb-12 space-y-6">

            {{-- Header & Tombol Kembali --}}
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl font-bold text-[#2B7A78]">Tambah Pengeluaran</h1>
                    <p class="text-xs sm:text-sm text-gray-600 mt-0.5">
                        Catat transaksi pembayaran beban operasional atau tagihan usaha baru
                    </p>
                </div>

                <a href="{{ route('expenses.search') }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-white hover:bg-gray-50 text-gray-700 text-xs sm:text-sm font-semibold rounded-xl border border-gray-300 shadow-sm transition">
                    <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Kembali</span>
                </a>
            </div>

            {{-- Card Form Tambah Pengeluaran --}}
            <div class="w-full bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 sm:p-8">
                <form action="{{ route('pengeluaran.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="source_id" class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Sumber Dana<span class="text-rose-500">*</span>
                        </label>
                        <select name="source_id" id="source_id" required
                            class="w-full px-3.5 py-2.5 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2B7A78] focus:border-[#2B7A78] bg-white text-gray-800 transition">
                            <option value="" disabled selected>-- Pilih Sumber Dana --</option>
                            @foreach ($sources as $source)
                                <option value="{{ $source->id }}" @selected(old('source_id') == $source->id)>
                                    {{ $source->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('source_id')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="bill_id" class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Kategori <span class="text-rose-500">*</span>
                        </label>
                        <select name="bill_id" id="bill_id" required
                            class="w-full px-3.5 py-2.5 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2B7A78] focus:border-[#2B7A78] bg-white text-gray-800 transition">
                            <option value="" disabled selected>-- Pilih Kategori --</option>
                            @foreach ($bills as $bill)
                                <option value="{{ $bill->id }}" @selected(old('bill_id') == $bill->id)>
                                    {{ $bill->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('bill_id')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="amount" class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Nominal Pengeluaran <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-xs font-bold text-gray-400">
                                Rp
                            </span>
                            <input type="number" name="amount" id="amount" min="1" required
                                value="{{ old('amount') }}"
                                placeholder="0"
                                class="w-full pl-10 pr-3.5 py-2.5 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2B7A78] focus:border-[#2B7A78] text-gray-800 transition">
                        </div>
                        @error('amount')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="date" class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Tanggal Transaksi <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required
                            class="w-full px-3.5 py-2.5 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2B7A78] focus:border-[#2B7A78] text-gray-800 bg-white transition">
                        @error('date')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Keterangan (Opsional)
                        </label>
                        <textarea name="description" id="description" rows="3"
                            placeholder="Contoh: Belanja ayam 10kg pasar pagi, bayar token listrik, perlengkapan dapur, dll."
                            class="w-full px-3.5 py-2.5 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2B7A78] focus:border-[#2B7A78] text-gray-800 transition">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                        <button type="reset"
                            class="px-4 py-2.5 rounded-xl border border-gray-300 text-xs sm:text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                            Reset
                        </button>
                        <button type="submit"
                            class="px-5 py-2.5 rounded-xl bg-[#2B7A78] hover:bg-[#205C5D] text-xs sm:text-sm font-semibold text-white shadow-sm transition">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </x-panel>
</x-app-layout>
