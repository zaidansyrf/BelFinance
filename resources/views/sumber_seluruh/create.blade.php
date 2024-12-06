<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Data Sumber Seluruh') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('sumber-seluruh.store') }}" method="POST">
                        @csrf
                        <div>
                            <label for="id_metode_transaksis">Metode Transaksi</label>
                            <select name="id_metode_transaksis" required>
                                @foreach ($metodeTransaksis as $metode)
                                    <option value="{{ $metode->id }}">{{ $metode->metode_transaksi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="pemasukkan">Pemasukkan</label>
                            <input type="number" name="pemasukkan" step="0.01" required>
                        </div>
                        <div>
                            <label for="pengeluaran">Pengeluaran</label>
                            <input type="number" name="pengeluaran" step="0.01" required>
                        </div>
                        <div>
                            <label for="tanggal">Tanggal</label>
                            <input type="date" name="tanggal" required>
                        </div>
                        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
