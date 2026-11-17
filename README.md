<h1 align="center"> $BelFinance</h1>

<p align="center">
  Sistem manajemen keuangan berbasis web untuk bisnis kuliner & retail — mencatat pemasukan, pengeluaran, laporan keuangan, dan kasir dalam satu platform. Rekomendasi bisnis berbasis AI untuk mendukung pengambilan keputusan bisnis.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.x-red?logo=laravel" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?logo=php" alt="PHP">
  <img src="https://img.shields.io/badge/TailwindCSS-3.x-38BDF8?logo=tailwindcss" alt="Tailwind">
  <img src="https://img.shields.io/badge/Livewire-3.x-FB70A9?logo=livewire" alt="Livewire">
  <img src="https://img.shields.io/badge/MySQL-Database-4479A1?logo=mysql" alt="MySQL">
</p>

---

## Tentang Aplikasi

**BelFinance** membantu pemilik bisnis kuliner & retail mengelola operasional harian dan arus kas secara terpusat:

- **Kasir (POS) & Shift** — Pencatatan transaksi penjualan dengan manajemen shift kasir.
- **Pencatatan Arus Kas** — Kelola pemasukan (POS & non-kasir) serta pengeluaran berdasarkan kategori tagihan.
- **Laporan Keuangan & Ekspor PDF** — Ringkasan pemasukan, pengeluaran, dan laporan untung rugi dengan filter periode fleksibel.
- **Manajemen Operasional** — Kelola menu, stok, promo (item & platform online), serta akun pegawai.
- **Saran Usaha AI** — Rekomendasi bisnis berbasis AI untuk mendukung pengambilan keputusan.

---

## Tech Stack

| Layer | Teknologi |
|-------|-----------|
| **Backend & Auth** | Laravel 11.x (PHP 8.2+), Laravel Breeze, Spatie Permission v6 |
| **Frontend & UI** | Blade, Livewire v3, Alpine.js, Tailwind CSS v3, DaisyUI v4, SweetAlert2 |
| **Database & Search** | MySQL, Laravel Scout |

---

## Role & Hak Akses

| Role | Hak Akses |
|------|-----------|
| **Owner** | Akses penuh ke seluruh fitur, manajemen pegawai, Laporan Untung Rugi, dan Saran Usaha |
| **Keuangan** | Dashboard, Pemasukan, Pengeluaran, Laporan Keuangan, Menu & Stok, serta Promo |
| **Kasir** | Akses khusus sistem Kasir (POS) dan manajemen Shift Kasir |

---

## Instalasi Singkat

```bash
# 1. Clone & masuk ke direktori
git clone <url-repo> BelFinance && cd BelFinance

# 2. Install dependensi
composer install && npm install

# 3. Konfigurasi environment & database (.env)
cp .env.example .env
php artisan key:generate

# 4. Migrasi database & build aset
php artisan migrate --seed
npm run build

# 5. Jalankan aplikasi
php artisan serve