# Tutorial Menjalankan Project Secara Lokal

Panduan ini untuk menjalankan **si-masjid** di komputer lokal (development),
bukan production. Untuk deploy ke hosting, lihat [DEPLOYMENT.md](DEPLOYMENT.md).

## 1. Prasyarat

- **PHP 8.3 atau lebih baru** (`composer.json` mensyaratkan `^8.3`)
- **Composer**
- **Node.js + npm**
- **MySQL** (atau server database lain yang didukung Laravel)
- Direkomendasikan pakai **Laragon** (Windows) — sudah termasuk PHP, MySQL, dan Node dalam satu paket, dan otomatis membuatkan domain lokal seperti `nama-folder.test`.

## 2. Clone / salin project

Kalau belum punya foldernya, clone dulu ke folder `www` Laragon (atau folder project kamu):

```bash
git clone https://github.com/salsa029/si-masjid.git
cd si-masjid
```

## 3. Install dependency

```bash
composer install
npm install
```

## 4. Siapkan file `.env`

```bash
cp .env.example .env
php artisan key:generate
```

> **Catatan penting:** `.env.example` di repo ini masih bawaan Laravel dan
> **belum mencantumkan** variabel khusus project ini (Google OAuth, Midtrans,
> kode kota jadwal sholat). Tambahkan manual ke `.env`:
>
> ```env
> # Database (contoh untuk MySQL Laragon)
> DB_CONNECTION=mysql
> DB_HOST=127.0.0.1
> DB_PORT=3306
> DB_DATABASE=si_masjid_db
> DB_USERNAME=root
> DB_PASSWORD=
>
> # Login Google (buat OAuth Client di Google Cloud Console, redirect URI:
> # http://nama-project.test/auth/google/callback)
> GOOGLE_CLIENT_ID=
> GOOGLE_CLIENT_SECRET=
> GOOGLE_REDIRECT_URI=http://nama-project.test/auth/google/callback
>
> # Midtrans Sandbox (buat akun sandbox gratis di https://dashboard.sandbox.midtrans.com)
> MIDTRANS_SERVER_KEY=
> MIDTRANS_CLIENT_KEY=
> MIDTRANS_IS_PRODUCTION=false
>
> # Kode kota untuk jadwal sholat (API MyQuran), default 1301 = Jakarta
> PRAYER_CITY_CODE=1301
>
> # Email testing — pakai Mailtrap Sandbox (https://mailtrap.io) supaya tidak
> # terkirim ke email asli saat development
> MAIL_MAILER=smtp
> MAIL_HOST=sandbox.smtp.mailtrap.io
> MAIL_PORT=2525
> MAIL_USERNAME=
> MAIL_PASSWORD=
> MAIL_FROM_ADDRESS="noreply@si-masjid.test"
> ```

Sesuaikan `APP_URL` di `.env` dengan domain lokal yang dipakai (misal
`http://si-masjid.test` kalau pakai Laragon dengan nama folder `si-masjid`).

## 5. Buat database

Buat database kosong sesuai nama di `DB_DATABASE` (lewat HeidiSQL/phpMyAdmin
bawaan Laragon, atau command line):

```sql
CREATE DATABASE si_masjid_db;
```

## 6. Migrasi & data awal

```bash
php artisan migrate --seed
```

`--seed` akan mengisi data awal: role admin/jamaah, 1 akun admin, profil
masjid contoh, beberapa pengurus/artikel/kegiatan/hewan kurban dummy, dan 10
user contoh. Cek `database/seeders/AdminUserSeeder.php` untuk tahu
email/password akun admin default yang dibuat.

## 7. Link storage

Supaya foto yang diupload (hero image, galeri, bukti transfer, dll) bisa
tampil di browser:

```bash
php artisan storage:link
```

## 8. Build asset frontend

Untuk development (auto-reload saat file CSS/JS diubah), jalankan di
terminal terpisah dan biarkan tetap berjalan:

```bash
npm run dev
```

Atau kalau cuma butuh build sekali (tanpa hot-reload):

```bash
npm run build
```

## 9. Jalankan aplikasi

**Kalau pakai Laragon:** cukup aktifkan Apache/Nginx dari Laragon, lalu buka
`http://nama-folder.test` di browser (otomatis sudah jalan, tidak perlu
`php artisan serve`).

**Kalau tidak pakai Laragon** (XAMPP manual, atau langsung dari terminal):

```bash
php artisan serve
```

lalu buka `http://localhost:8000`.

## 10. Jadwal terjadwal (scheduler) — opsional saat development

Ada 3 tugas terjadwal (pelepasan slot infaq/kurban yang tidak dibayar,
pemrosesan cicilan kurban yang telat). Di lokal tidak wajib disiapkan cron,
tapi kalau mau menguji fiturnya, jalankan manual:

```bash
php artisan qurban:release-expired-bookings
php artisan donation:release-expired-bookings
php artisan qurban:process-overdue-installments
```

## 11. Login sebagai admin

Setelah seeding, masuk ke `/login` dengan akun admin dari
`AdminUserSeeder.php`, lalu akses panel admin di `/admin/dashboard`.

## Troubleshooting singkat

| Masalah | Kemungkinan penyebab |
|---|---|
| Halaman blank/error 500 | Cek `storage/logs/laravel.log`, pastikan `APP_KEY` sudah ter-generate |
| Foto tidak muncul | Belum jalankan `php artisan storage:link` |
| Tampilan berantakan (CSS tidak termuat) | Belum `npm run dev`/`npm run build`, atau file public/build belum ada |
| Login Google gagal | `GOOGLE_REDIRECT_URI` tidak cocok dengan yang didaftarkan di Google Cloud Console |
| Pembayaran Midtrans tidak muncul | `MIDTRANS_SERVER_KEY`/`MIDTRANS_CLIENT_KEY` kosong atau salah |
| Email verifikasi tidak terkirim | Cek kredensial Mailtrap di `.env`, lihat inbox sandbox-nya di dashboard Mailtrap (bukan email asli) |
