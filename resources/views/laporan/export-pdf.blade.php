<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan</title>
    <style>
        @page {
            margin: 1cm;
            size: A4 landscape;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            width: 100%;
            text-align: center;
            border-bottom: 2px solid #2B7A78;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        h1 {
            font-size: 22px;
            font-weight: bold;
            color: #2B7A78;
            margin: 0;
        }

        .info-period {
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            page-break-inside: auto;
            margin-bottom: 20px;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
            word-wrap: break-word;
        }

        th {
            background-color: #2B7A78;
            color: white;
            font-weight: bold;
            text-align: center;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .total-row {
            background-color: #D1DDD5;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .signature-section {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }

        .signature-box {
            text-align: center;
            margin-left: 60px;
        }

        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #333;
            width: 200px;
            margin-bottom: 5px;
        }

        .footer-note {
            margin-top: 30px;
            text-align: right;
            font-style: italic;
            font-size: 11px;
        }

        @media print {
            body {
                padding: 0;
                margin: 0;
            }

            .no-print {
                display: none;
            }

            table {
                font-size: 10pt;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>LAPORAN KEUANGAN</h1>
    </div>

    <div class="info-period">
        <p>Periode: {{ $tanggalAwal }} s/d {{ $tanggalAkhir }}</p>
        <p>Tanggal Cetak: {{ date('d F Y') }}</p>
        <p>Dicetak oleh: {{ $user->name }}</p>
    </div>

    <table>
        <colgroup>
            <col style="width:5%">
            <col style="width:10%">
            <col style="width:20%">
            <col style="width:15%">
            <col style="width:15%">
            <col style="width:15%">
            <col style="width:20%">
        </colgroup>
        <thead>
            <tr>
                <th>No</th>
                <th>Jenis</th>
                <th>Nama</th>
                <th>Sumber</th>
                <th>Jumlah</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalPemasukan = 0;
                $totalPengeluaran = 0;
            @endphp

            @foreach ($laporan as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ ucfirst($item->jenis) }}</td>
                    <td>{{ $item->nama ?? '-' }}</td>
                    <td>{{ $item->source->name ?? '-' }}</td>
                    <td class="text-right">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                    <td>{{ $item->keterangan }}</td>
                </tr>
                @if ($item->jenis == 'pemasukan')
                    @php $totalPemasukan += $item->jumlah @endphp
                @else
                    @php $totalPengeluaran += $item->jumlah @endphp
                @endif
            @endforeach

            <tr class="total-row">
                <td colspan="4"><strong>TOTAL</strong></td>
                <td colspan="3" class="text-right">
                    <span style="margin-right: 20px;">Pemasukan: Rp
                        {{ number_format($totalPemasukan, 0, ',', '.') }}</span> |
                    <span style="margin: 0 20px;">Pengeluaran: Rp
                        {{ number_format($totalPengeluaran, 0, ',', '.') }}</span> |
                    <span style="margin-left: 20px;">Saldo: Rp
                        {{ number_format($totalPemasukan - $totalPengeluaran, 0, ',', '.') }}</span>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="footer-note">
        <p>Laporan ini dibuat secara otomatis oleh sistem</p>
    </div>


</body>

</html>
