<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Metode Transaksi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Form Tambah Metode Transaksi</h3>

                    <!-- Form Tambah Metode Transaksi -->
                    <form action="{{ route('metode-transaksi.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="metode_transaksi" class="block text-gray-700 font-medium">Metode Transaksi</label>
                            <input type="text" name="metode_transaksi" id="metode_transaksi"
                                class="w-full border border-gray-300 rounded-md shadow-sm px-4 py-2"
                                placeholder="Masukkan metode transaksi" value="{{ old('metode_transaksi') }}" required>
                                
                            <!-- Menampilkan error jika ada -->
                            @error('metode_transaksi')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Simpan</button>
                            <a href="{{ route('metode-transaksi.index') }}"
                                class="ml-4 bg-red-500 text-white px-4 py-2 rounded">Batal</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
