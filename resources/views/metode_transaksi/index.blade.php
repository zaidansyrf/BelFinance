<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <!-- Tabel Metode Transaksi -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Sumber</h3>

                    <!-- Tombol Tambah Data -->
                    <div class="mb-4">
                        <a href="{{ route('metode-transaksi.create') }}" 
                           class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded shadow">
                            Tambah Metode Transaksi
                        </a>
                    </div>

                    <!-- Tabel Data Metode Transaksi -->
                    <table class="table-auto w-full border-collapse border border-gray-300">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border border-gray-300 px-4 py-2 text-left">#</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Sumber</th>
                                <th class="border border-gray-300 px-4 py-2 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($metodeTransaksi as $metode)
                                <tr>
                                    <td class="border border-gray-300 px-4 py-2">{{ $loop->iteration }}</td>
                                    <td class="border border-gray-300 px-4 py-2">{{ $metode->metode_transaksi }}</td>
                                    <td class="border border-gray-300 px-4 py-2 text-center">
                                        <!-- Tombol Edit -->
                                        <a href="{{ route('metode-transaksi.edit', $metode->id) }}" class="text-blue-500">Edit</a>

                                        <!-- Tombol Delete -->
                                        <form action="{{ route('metode-transaksi.destroy', $metode->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 ml-2">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
