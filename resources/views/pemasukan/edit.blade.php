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

                    <!-- Form untuk Create/Edit Pemasukan -->
                    <form action="{{ route('pemasukan.update', $pemasukan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Metode Transaksi -->
                        <div class="mb-4">
                            <label for="metode_transaksi" class="block text-sm font-medium text-gray-700">Metode Transaksi</label>
                            <select name="id_metode_transaksis" id="metode_transaksi" class="mt-1 block w-full">
                                @foreach ($metodeTransaksi as $metode)
                                    <option value="{{ $metode->id }}" {{ $pemasukan->id_metode_transaksis == $metode->id ? 'selected' : '' }}>
                                        {{ $metode->metode_transaksi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Nominal -->
                        <div class="mb-4">
                            <label for="nominal" class="block text-sm font-medium text-gray-700">Nominal</label>
                            <input type="number" name="nominal" id="nominal" class="mt-1 block w-full" value="{{ old('nominal', $pemasukan->nominal) }}" required>
                        </div>

                        <!-- Tanggal -->
                        <div class="mb-4">
                            <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal</label>
                            <input type="date" name="tanggal" id="tanggal" class="mt-1 block w-full" value="{{ old('tanggal', $pemasukan->tanggal) }}" required>
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
