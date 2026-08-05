# Checklist Deploy ke Production

Panduan ini mencakup semua yang perlu diubah saat memindahkan aplikasi dari
lingkungan lokal (sandbox Midtrans, Mailtrap, `si-masjid.test`) ke hosting
production, supaya semua fitur berjalan penuh tanpa error.

## 1. Variabel `.env`

| Variabel | Lokal (sekarang) | Production |
|---|---|---|
| `APP_ENV` | `local` | `production` |
| `APP_DEBUG` | `true` | `false` — **wajib**, kalau lupa stack trace error akan tampil ke publik |
| `APP_URL` | `http://si-masjid.test` | `https://domainmu.com` |
| `MIDTRANS_SERVER_KEY` | key sandbox | key **production** dari dashboard Midtrans |
| `MIDTRANS_CLIENT_KEY` | key sandbox | key **production** dari dashboard Midtrans |
| `MIDTRANS_IS_PRODUCTION` | `false` | `true` |
| `MAIL_MAILER` / `MAIL_HOST` / `MAIL_USERNAME` / `MAIL_PASSWORD` | Mailtrap | SMTP asli (Gmail App Password, SendGrid, Mailgun, dll) |
| `MAIL_FROM_ADDRESS` | — | alamat email pengirim resmi masjid |
| `DB_HOST` / `DB_DATABASE` / `DB_USERNAME` / `DB_PASSWORD` | MySQL Laragon lokal | kredensial database hosting |
| Google OAuth Client ID/Secret | redirect ke localhost | Client ID/Secret dengan **Authorized redirect URI** production terdaftar di Google Cloud Console: `https://domainmu.com/auth/google/callback` |

## 2. Akun/layanan eksternal yang perlu disiapkan

- **Midtrans production** — ajukan verifikasi bisnis di dashboard Midtrans
  (prosesnya tidak instan, mulai dari jauh-jauh hari). Setelah aktif:
  - Daftarkan **Payment Notification URL**: `https://domainmu.com/webhook/midtrans`
- **SMTP asli** — pilih provider (Gmail, SendGrid, Mailgun, SES, dll)
  menggantikan Mailtrap sandbox yang tidak pernah mengirim ke inbox asli.
- **Google Cloud Console** — buat/perbarui OAuth Client untuk domain production.
- **SSL/HTTPS aktif** — wajib untuk Midtrans production dan Google OAuth.

## 3. Perintah build & setup di server

```bash
composer install --optimize-autoloader --no-dev
npm run build
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

> Catatan: setelah `config:cache`, perubahan apa pun di `.env` butuh
> `php artisan config:clear` lalu `config:cache` ulang supaya terbaca.

## 4. Cron job (paling sering kelupaan)

Aplikasi punya 3 tugas terjadwal yang **tidak akan pernah berjalan** tanpa
cron asli di server:

- `qurban:release-expired-bookings` — lepas slot kurban yang tidak dibayar
- `donation:release-expired-bookings` — lepas reservasi infaq/zakat yang tidak dibayar
- `qurban:process-overdue-installments` — proses cicilan kurban yang telat lunas

Tambahkan satu baris ini di crontab server:

```
* * * * * cd /path-ke-project && php artisan schedule:run >> /dev/null 2>&1
```

## 5. Izin folder (file permissions)

Pastikan web server punya akses tulis ke:
- `storage/`
- `bootstrap/cache/`

## 6. Data awal (jangan bawa data dummy dari lokal)

- Isi ulang **Profil Masjid** (nama, alamat, rekening, link media sosial) lewat admin panel.
- Buat akun **admin asli** — jangan pakai akun/password hasil seeder lokal.
- Kosongkan/skip seeder yang berisi data contoh (hewan kurban dummy, campaign infaq contoh, dll) kecuali memang ingin dipakai sebagai data awal nyata.

## 7. Verifikasi akhir setelah deploy

- [ ] Buka halaman utama, pastikan tidak ada error 500.
- [ ] Coba login dengan Google — redirect harus kembali ke domain production, bukan localhost.
- [ ] Coba transaksi infaq/zakat/kurban kecil dengan Midtrans **production** (nominal nyata, bisa langsung refund jika perlu) untuk pastikan Snap.js dan webhook berjalan.
- [ ] Cek email verifikasi/notifikasi benar-benar masuk ke inbox asli (bukan Mailtrap).
- [ ] Cek foto/gambar (hero image, galeri, sertifikat) tampil dengan benar (`storage:link` sudah jalan).
- [ ] Cek `php artisan schedule:list` di server untuk pastikan cron terbaca.
