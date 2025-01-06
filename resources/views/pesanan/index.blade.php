<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pesanan</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f4f4f4;
        }
        .add-button {
            margin-bottom: 20px;
            display: inline-block;
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h1>Daftar Pesanan</h1>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <a href="{{ route('pesanan.create') }}" class="add-button">Tambah Pesanan</a>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Sumber</th>
                <th>Pembeli</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pesanans as $pesanan)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $pesanan->tanggal }}</td>
                    <td>{{ $pesanan->sumber }}</td>
                    <td>{{ $pesanan->pembeli }}</td>
                    <td>
                        <a href="{{ route('pesanan.show', $pesanan->id) }}">Detail</a> |
                        <a href="{{ route('pesanan.edit', $pesanan->id) }}">Edit</a> |
                        <form action="{{ route('pesanan.destroy', $pesanan->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Yakin ingin menghapus pesanan ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Belum ada pesanan</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
