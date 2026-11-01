<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Struk #{{ $income->id }}</title>
    <style>
        /* Struk untuk printer thermal 58mm (area cetak ~48mm) */
        @page {
            size: 58mm auto;
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #e5e7eb;
            font-family: "Courier New", ui-monospace, monospace;
            font-size: 11px;
            color: #000;
        }

        .struk {
            width: 58mm;
            margin: 16px auto;
            padding: 4mm 5mm;
            background: #fff;
        }

        .center { text-align: center; }
        .right { text-align: right; }
        .bold { font-weight: bold; }
        .big { font-size: 14px; }
        .muted { color: #333; }

        hr {
            border: 0;
            border-top: 1px dashed #000;
            margin: 6px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            vertical-align: top;
            padding: 1px 0;
        }

        .actions {
            width: 58mm;
            margin: 0 auto 16px;
            display: flex;
            gap: 8px;
            font-family: system-ui, sans-serif;
        }

        .actions a,
        .actions button {
            flex: 1;
            padding: 10px;
            border-radius: 8px;
            border: 0;
            font-size: 14px;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-print { background: #2B7A78; color: #fff; }
        .btn-back { background: #fff; color: #2B7A78; border: 1px solid #2B7A78 !important; }

        .flash {
            width: 58mm;
            margin: 16px auto 0;
            padding: 8px;
            border-radius: 8px;
            background: #dcfce7;
            color: #166534;
            font-family: system-ui, sans-serif;
            font-size: 13px;
            text-align: center;
        }

        @media print {
            body { background: #fff; }
            .struk { margin: 0; }
            .actions, .flash { display: none; }
        }
    </style>
</head>

<body>
    @if (session('success'))
        <div class="flash">{{ session('success') }}</div>
    @endif

    <div class="struk">
        <div class="center">
            <div class="bold big">Belindo Kitchen</div>
            <div class="muted">Struk Pembayaran</div>
        </div>
        <hr>
        <table>
            <tr><td>No</td><td class="right">#{{ str_pad($income->id, 6, '0', STR_PAD_LEFT) }}</td></tr>
            <tr><td>Tanggal</td><td class="right">{{ $income->date->format('d/m/Y') }} {{ ($income->created_at ?? $income->date)->format('H:i') }}</td></tr>
            <tr><td>Kasir</td><td class="right">{{ $income->creator->name ?? '-' }}</td></tr>
            <tr><td>Jenis</td><td class="right">{{ $income->name }}</td></tr>
        </table>
        <hr>
        <table>
            @foreach ($income->incomeDetails as $detail)
                <tr>
                    <td colspan="2">{{ $detail->item->name ?? 'Menu dihapus' }}</td>
                </tr>
                <tr>
                    <td class="muted">{{ $detail->quantity }} x {{ number_format($detail->subtotal / max($detail->quantity, 1), 0, ',', '.') }}</td>
                    <td class="right">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </table>
        <hr>
        <table>
            <tr class="bold big">
                <td>TOTAL</td>
                <td class="right">Rp {{ number_format($income->amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Bayar ({{ $income->source->name ?? '-' }})</td>
                <td class="right">{{ number_format($income->paid_amount ?? $income->amount, 0, ',', '.') }}</td>
            </tr>
            @if ($income->change_amount !== null)
                <tr>
                    <td>Kembali</td>
                    <td class="right">{{ number_format($income->change_amount, 0, ',', '.') }}</td>
                </tr>
            @endif
        </table>
        <hr>
        <div class="center muted">Terima kasih atas kunjungan Anda</div>
    </div>

    <div class="actions">
        <button type="button" class="btn-print" onclick="window.print()">Cetak</button>
        @if (auth()->user()->isKasir())
            <a href="{{ route('kasir.pos') }}" class="btn-back">Transaksi baru</a>
        @else
            <a href="{{ route('pembayaran.show', $income) }}" class="btn-back">Kembali</a>
        @endif
    </div>
</body>

</html>
