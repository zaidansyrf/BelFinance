<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Sumber Seluruh') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <a href="{{ route('sumber-seluruh.create') }}" class="bg-green-500 text-white px-4 py-2 rounded">Tambah Data</a>
                    <table class="w-full mt-4 border-collapse border border-gray-200">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Metode Transaksi</th>
                                <th>Pemasukkan</th>
                                <th>Pengeluaran</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                <a href="{{ route('sumber-seluruh.detail', strtolower($item->metodeTransaksi->metode_transaksi)) }}">
    {{ $item->metodeTransaksi->metode_transaksi }}
</a>
                                <td>{{ number_format($item->pemasukkan, 2, ',', '.') }}</td>
                                <td>{{ number_format($item->pengeluaran, 2, ',', '.') }}</td>
                                <td>{{ $item->tanggal }}</td>
                                <td>
                                    <a href="{{ route('sumber-seluruh.edit', $item->id) }}" class="text-yellow-500">Edit</a>
                                    <form action="{{ route('sumber-seluruh.destroy', $item->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500">Hapus</button>
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