<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Data: ') . ucfirst($metode_transaksi) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Detail Data untuk Metode Transaksi: {{ ucfirst($metode_transaksi) }}</h3>
                    <a href="{{ route('sumber-seluruh.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Kembali</a>

                    <table class="w-full mt-4 border-collapse border border-gray-200">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Metode Transaksi</th>
                                <th>Pemasukkan</th>
                                <th>Pengeluaran</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->metodeTransaksi->metode_transaksi }}</td>
                                    <td>{{ number_format($item->pemasukkan, 2, ',', '.') }}</td>
                                    <td>{{ number_format($item->pengeluaran, 2, ',', '.') }}</td>
                                    <td>{{ $item->tanggal }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
