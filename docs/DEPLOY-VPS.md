# Deploy BelFinance ke VPS dengan Docker

Isi stack: `app` (Nginx + PHP 8.4), `db` (MySQL 8.0), dan `caddy` (HTTPS otomatis).
Database dan foto menu disimpan di volume Docker, jadi aman saat image diperbarui.

Kebutuhan VPS: Linux dengan Docker dan Docker Compose, RAM minimal 2 GB, serta port 80 dan 443 terbuka.
Bila memakai domain, arahkan A record domain ke IP VPS sebelum menyalakan stack.

## 1. Pemasangan pertama

```bash
git clone <url-repo> belfinance
cd belfinance
cp .env.docker.example .env
```

Buat nilai rahasia:

```bash
echo "base64:$(openssl rand -base64 32)"
openssl rand -hex 24
openssl rand -hex 24
```

Isi `.env`:

- `APP_KEY`: hasil perintah pertama. Simpan baik-baik; jangan diganti setelah aplikasi dipakai.
- `DB_PASSWORD` dan `DB_ROOT_PASSWORD`: dua hasil berikutnya.
- `APP_URL` dan `SITE_ADDRESS`: domainmu.
- `VIKEY_API_KEY`: bila fitur AI dipakai.

Nyalakan (migrasi database berjalan otomatis):

```bash
docker compose up -d --build
```

## 2. Data awal

Isi sumber dana, kategori pengeluaran, dan menu:

```bash
docker compose exec app php artisan db:seed --force --class=source
docker compose exec app php artisan db:seed --force --class=bill
docker compose exec app php artisan db:seed --force --class=item
```

Lalu buat akun dengan password sendiri, satu untuk tiap peran. Jangan menjalankan `db:seed` tanpa
`--class` di server: perintah itu ikut menjalankan `KeuanganSeeder`, yang membuat akun dengan
password bawaan yang tersimpan di repo.

```bash
docker compose exec app php artisan belfinance:buat-user --role=owner
docker compose exec app php artisan belfinance:buat-user --role=keuangan
docker compose exec app php artisan belfinance:buat-user --role=kasir
```

Bila ingin memindahkan data dari laptop, lewati bagian ini dan ikuti langkah 3.

## 3. Memindahkan data dari laptop (opsional)

Di laptop, ekspor database (buat dulu file-nya, lalu salin ke VPS bersama folder foto menu
`storage/app/public/menu`):

```bash
mysqldump -uroot belfinance > belfinance.sql
```

Di VPS, dari folder proyek:

```bash
docker compose exec -T db sh -c 'mysql -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" "$MYSQL_DATABASE"' < belfinance.sql
docker compose cp menu/. app:/var/www/html/storage/app/public/menu
docker compose exec app chown -R www-data:www-data storage/app/public
docker compose restart app
```

Akun dari laptop ikut terbawa, termasuk password bawaannya. Ganti lewat halaman Profil setelah login.

## 4. Memperbarui aplikasi

```bash
git pull
docker compose up -d --build
```

## 5. Perintah harian

Lihat log:

```bash
docker compose logs -f app
```

Cadangkan database (jadwalkan rutin dan simpan salinannya di luar VPS):

```bash
docker compose exec -T db sh -c 'mysqldump -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" "$MYSQL_DATABASE"' > backup-$(date +%F).sql
```

## Catatan

- `docker compose down -v` menghapus database dan foto menu. Pakai `docker compose down` tanpa `-v`.
- Mengubah `.env` cukup dengan `docker compose up -d`, tanpa `--build`.
- Bila belum punya domain, isi `SITE_ADDRESS=:80`, `APP_URL=http://IP-VPS`, dan
  `SESSION_SECURE_COOKIE=false`. Password akan terkirim tanpa enkripsi, jadi pakai hanya sementara.
