# Panduan Kontribusi BelFinance

## Alur kerja

1. Buat branch dari `main` yang terbaru, satu branch untuk satu perubahan:
   - `feat/<nama>` untuk fitur baru
   - `fix/<nama>` untuk perbaikan bug
   - `refactor/<nama>` untuk merapikan kode tanpa mengubah perilaku
2. Commit kecil-kecil dengan pesan yang jelas (lihat format di bawah).
3. Buka Pull Request ke `main` dan isi template PR.
4. Tunggu CI hijau dan minimal **1 reviewer** menyetujui sebelum merge.
5. Jangan push langsung ke `main`.

## Format pesan commit

```
<jenis>: <ringkasan singkat dalam bahasa Indonesia>
```

Jenis yang dipakai: `feat`, `fix`, `refactor`, `test`, `docs`, `chore`.

Contoh:

```
fix: total pemasukan dashboard memakai tanggal transaksi
feat: filter laporan berdasarkan sumber
```

Hindari pesan seperti `update`, `update all`, atau `test` karena tidak bisa dipakai untuk melacak perubahan.

## Menjalankan proyek di lokal (Laragon)

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
```

Lalu jalankan dua perintah ini di terminal terpisah:

```bash
php artisan serve
npm run dev
```

## Test

Test memakai database MySQL terpisah bernama `belfinance_test` (lihat `phpunit.xml`):

```bash
mysql -uroot -e "CREATE DATABASE IF NOT EXISTS belfinance_test"
php artisan test
```

Setiap perubahan pada perhitungan keuangan, hak akses, atau alur pembayaran **wajib** disertai test.

## Gaya kode

Jalankan Pint untuk file yang kamu ubah sebelum commit:

```bash
vendor/bin/pint --dirty
```

## Aturan data keuangan

- Semua laporan dan dashboard memakai kolom `date` (tanggal transaksi), bukan `created_at`.
- Perhitungan dashboard ada di `app/Services/DashboardKeuanganService.php`, query laporan di
  `app/Services/LaporanKeuanganService.php`. Ubah di sana, jangan menyalin query ke controller.
- Pemasukan dan pengeluaran memakai soft delete dan mencatat `created_by`, `updated_by`,
  `deleted_by` (trait `App\Models\Concerns\Auditable`). Jangan memakai `forceDelete()`.
