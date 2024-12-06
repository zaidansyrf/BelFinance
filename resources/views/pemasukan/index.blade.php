<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Pemasukan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Data Pemasukan</h3>

                    <a href="{{ route('pemasukan.create') }}" class="bg-green-500 text-white px-4 py-2 rounded">Tambah Pemasukan</a>
                    <table class="w-full mt-4">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Metode Transaksi</th>
                                <th>Nominal</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pemasukan as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                <a href="{{ route('pemasukan.metode', $item->metodeTransaksi->metode_transaksi) }}">
                                    {{ $item->metodeTransaksi->metode_transaksi }}
                                </a>
                                </td>
                                <td>{{ $item->nominal }}</td>
                                <td>{{ $item->tanggal }}</td>
                                <td>
                                    <a href="{{ route('pemasukan.edit', $item->id) }}" class="text-blue-500">Edit</a>
                                    <form action="{{ route('pemasukan.destroy', $item->id) }}" method="POST" class="inline">
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