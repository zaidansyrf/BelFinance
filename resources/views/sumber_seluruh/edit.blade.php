<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Data Sumber Seluruh') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Form untuk Edit Data Sumber Seluruh -->
                    <form action="{{ route('sumber-seluruh.update', $sumberSeluruh->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Metode Transaksi -->
                        <div class="mb-4">
                            <label for="id_metode_transaksis" class="block text-sm font-medium text-gray-700">Metode Transaksi</label>
                            <select name="id_metode_transaksis" required class="mt-1 block w-full">
                                @foreach ($metodeTransaksis as $metode)
                                    <option value="{{ $metode->id }}" {{ $sumberSeluruh->id_metode_transaksis == $metode->id ? 'selected' : '' }}>
                                        {{ $metode->metode_transaksi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Pemasukkan -->
                        <div class="mb-4">
                            <label for="pemasukkan" class="block text-sm font-medium text-gray-700">Pemasukkan</label>
                            <input type="number" name="pemasukkan" step="0.01" required value="{{ old('pemasukkan', $sumberSeluruh->pemasukkan) }}" class="mt-1 block w-full">
                        </div>

                        <!-- Pengeluaran -->
                        <div class="mb-4">
                            <label for="pengeluaran" class="block text-sm font-medium text-gray-700">Pengeluaran</label>
                            <input type="number" name="pengeluaran" step="0.01" required value="{{ old('pengeluaran', $sumberSeluruh->pengeluaran) }}" class="mt-1 block w-full">
                        </div>

                        <!-- Tanggal -->
                        <div class="mb-4">
                            <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal</label>
                            <input type="date" name="tanggal" required value="{{ old('tanggal', $sumberSeluruh->tanggal) }}" class="mt-1 block w-full">
                        </div>

                        <!-- Tombol Submit -->
                        <div class="mt-6">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
