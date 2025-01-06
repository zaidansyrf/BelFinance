<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Pemasukan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Form Tambah Pemasukan</h3>

                    <form action="{{ route('pemasukan.store') }}" method="POST">
                        @csrf


                        <div class="mb-4">
                            <label for="id_metode_transaksis" class="block text-gray-700 font-medium">Metode Transaksi</label>
                            <select name="id_metode_transaksis" id="id_metode_transaksis" class="w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="">Pilih Metode Transaksi</option>
                                @foreach($metodeTransaksi as $metode)
                                    <option value="{{ $metode->id }}">{{ $metode->metode_transaksi }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="nominal" class="block text-gray-700 font-medium">Nominal</label>
                            <input type="number" name="nominal" id="nominal" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Masukkan nominal" required>
                        </div>

                        <div class="mb-4">
                            <label for="keterangan" class="block text-gray-700 font-medium">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Masukkan keterangan"></textarea>
                        </div>

                        <div class="mb-4">
                            <label for="tanggal" class="block text-gray-700 font-medium">Tanggal</label>
                            <input type="date" name="tanggal" id="tanggal" class="w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
                            <a href="{{ route('pemasukan.index') }}" class="ml-4 bg-gray-300 text-gray-700 px-4 py-2 rounded">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
