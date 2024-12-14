<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pesanan</title>
    <!-- Tambahkan library CSS untuk styling, jika diperlukan -->
    <style>
        .menu-item {
            margin-bottom: 10px;
        }
        .menu-item label {
            margin-right: 5px;
        }
        .remove-menu {
            background-color: red;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>Form Pesanan</h1>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    @if ($errors->any())
        <ul style="color: red;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('pesanan.store') }}" method="POST">
        @csrf

        <label for="tanggal">Tanggal:</label>
        <input type="date" id="tanggal" name="tanggal" required><br><br>

        <label for="sumber">Sumber:</label>
        <input type="text" id="sumber" name="sumber" required><br><br>

        <label for="pembeli">Pembeli:</label>
        <input type="text" id="pembeli" name="pembeli" required><br><br>

        <div id="menu-list">
            <div class="menu-item">
                <label for="menu">Menu:</label>
                <select name="menu_id[]" required>
                    <option value="">-- Pilih Menu --</option>
                    @foreach ($menus as $menu)
                        <option value="{{ $menu->id }}">{{ $menu->nama }} - Rp {{ number_format($menu->harga) }}</option>
                    @endforeach
                </select>

                <label for="jumlah">Jumlah:</label>
                <input type="number" name="jumlah[]" min="1" required>

                <button type="button" class="remove-menu">Hapus</button>
            </div>
        </div>

        <button type="button" id="add-menu">Tambah Menu</button><br><br>
        <button type="submit">Simpan</button>
    </form>

    <!-- Tambahkan JavaScript untuk manipulasi form dinamis -->
    <script>
        document.getElementById('add-menu').addEventListener('click', function () {
            const menuList = document.getElementById('menu-list');
            const newMenuItem = document.createElement('div');
            newMenuItem.classList.add('menu-item');
            newMenuItem.innerHTML = `
                <label for="menu">Menu:</label>
                <select name="menu_id[]" required>
                    <option value="">-- Pilih Menu --</option>
                    @foreach ($menus as $menu)
                        <option value="{{ $menu->id }}">{{ $menu->nama }} - Rp {{ number_format($menu->harga) }}</option>
                    @endforeach
                </select>

                <label for="jumlah">Jumlah:</label>
                <input type="number" name="jumlah[]" min="1" required>

                <button type="button" class="remove-menu">Hapus</button>
            `;
            menuList.appendChild(newMenuItem);

            // Tambahkan event listener untuk tombol Hapus
            newMenuItem.querySelector('.remove-menu').addEventListener('click', function () {
                newMenuItem.remove();
            });
        });

        // Event listener untuk tombol Hapus pada item awal
        document.querySelectorAll('.remove-menu').forEach(button => {
            button.addEventListener('click', function () {
                button.parentElement.remove();
            });
        });
    </script>
</body>
</html>
