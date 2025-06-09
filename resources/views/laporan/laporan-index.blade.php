@section('title', 'Laporan Keuangan')
@php
use Carbon\Carbon;
    $awal = request('tanggal_awal');
    $akhir = request('tanggal_akhir');
    $hariIni = Carbon::today()->toDateString();
@endphp
<x-app-layout>
    <div class="h-screen w-full bg-gray-100 flex overflow-hidden">
        <!-- sidebar -->
        <div class="drawer lg:drawer-open">
            <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />

            <!-- drawer content -->
            <div class="drawer-content flex flex-col h-screen">
                <!-- wrapper for spacing -->
                @include('layouts.navbar')
                <!-- main content -->

                <div class="py-4 flex-1 bg-[#D1DDD5] overflow-auto">
                    <h2 class="text-xl font-semibold text-[#2B7A78] mb-4 px-8 mt-12">Filter Laporan</h2>

                    <div class="flex justify-center w-full px-8">
                        <div class="card text-primary-content bg-white mt-4 w-full">
                            <form method="GET" action="" class="p-6">
                                <div class="flex flex-col sm:flex-row items-end gap-4">
                                    <div class="w-full sm:w-auto flex-grow">
                                        <label for="tanggal_awal" class="block text-sm font-medium text-gray-700 mb-1">Mulai Tanggal</label>
                                        <div class="relative">
                                            <input type="date" name="tanggal_awal" id="tanggal_awal"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-gray-900 bg-white"
                                                value="{{ request('tanggal_awal') }}">
                                        </div>
                                    </div>

                                    <div class="w-full sm:w-auto flex-grow">
                                        <label for="tanggal_akhir" class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                                        <div class="relative">
                                            <input type="date" name="tanggal_akhir" id="tanggal_akhir"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-gray-900 bg-white"
                                                value="{{ request('tanggal_akhir') }}">
                                        </div>
                                    </div>

                                    <div class="w-full sm:w-auto">
                                        @if(request('tanggal_awal') || request('tanggal_akhir'))
                                            <a href="{{ url()->current() }}"
                                                class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-2 bg-red-500 hover:bg-red-700 text-white font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                                Reset
                                            </a>
                                        @else
                                            <button type="submit"
                                                class="w-full sm:w-auto px-6 py-2 bg-[#468585] hover:bg-[#234343] text-white font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                                Filter
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-center w-full px-8 mt-6">
                            <div class="card text-primary-content bg-white w-full">
                                <div class="card-body">
                                    <div class="flex justify-between items-center">
                                        <h2 class="card-title text-[#468585]">
                                            Menampilkan :    @if ($awal === $hariIni && $akhir === $hariIni)
                                                {{ Carbon::parse($awal)->translatedFormat('d F Y') }}
                                            @elseif ($awal && $akhir)
                                                {{ Carbon::parse($awal)->translatedFormat('d F Y') }} - {{ Carbon::parse($akhir)->translatedFormat('d F Y') }}
                                            @elseif ($awal)
                                                {{ Carbon::parse($awal)->translatedFormat('d F Y') }}
                                            @elseif ($akhir)
                                                {{ Carbon::parse($akhir)->translatedFormat('d F Y') }}
                                            @else
                                                Semua
                                            @endif
                                        </h2>
                                        <a href="{{ route('laporan.export-pdf', request()->query()) }}"
                                            class="px-4 py-2 bg-[#468585] hover:bg-[#234343] text-white rounded-md">
                                            Export PDF
                                        </a>
                                    </div>

                                    <div class="overflow-x-auto pt-5">
                                        <table class="table w-full">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama</th>
                                                    <th>Sumber</th>
                                                    <th>Pemasukan</th>
                                                    <th>Pengeluaran</th>
                                                    <th>Tanggal</th>
                                                    <th>Jumlah</th>
                                                    <th>Keterangan</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-black">
                                                @foreach ($laporan as $index => $item)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $item->nama ?? '-' }}</td>
                                                        <td>{{ $item->source->name ?? '-' }}</td>
                                                        <td>
                                                            @if ($item->jenis == 'pemasukan')
                                                                {{ number_format($item->jumlah, 0, ',', '.') }}
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($item->jenis == 'pengeluaran')
                                                                {{ number_format($item->jumlah, 0, ',', '.') }}
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                                        </td>
                                                        <td>{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                                        <td>{{ $item->keterangan }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>

                                        </table>
                                        <div class="mt-4">
                                            {{ $laporan->links() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- sidebar content -->
            @include('layouts.sidebar')
        </div>
    </div>
    <script>
        function dropdownLaporan() {
            const dropdownLaporanMenu = document.getElementById('dropdownLaporanMenu');
            dropdownLaporanMenu.classList.toggle('hidden');
        }
        document.addEventListener("click", function(event) {
            const dropdownLaporanMenu = document.getElementById("dropdownLaporanMenu");
            const dropdownLaporanButton = document.getElementById("dropdownLaporanButton");

            // Jika elemen yang diklik bukan bagian dari dropdown
            if (
                !dropdownLaporanMenu.contains(event.target) &&
                !dropdownLaporanButton.contains(event.target)
            ) {
                dropdownLaporanMenu.classList.add("hidden");
            }
        });

        function dropdownKategori() {
            const dropdownKategoriMenu = document.getElementById('dropdownKategoriMenu');
            dropdownKategoriMenu.classList.toggle('hidden');
        }
        document.addEventListener("click", function(event) {
            const dropdownKategoriMenu = document.getElementById("dropdownKategoriMenu");
            const dropdownKategoriButton = document.getElementById("dropdownKategoriButton");

            // Jika elemen yang diklik bukan bagian dari dropdown
            if (
                !dropdownKategoriMenu.contains(event.target) &&
                !dropdownKategoriButton.contains(event.target)
            ) {
                dropdownKategoriMenu.classList.add("hidden");
            }
        });
    </script>
</x-app-layout>
