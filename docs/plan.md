# Barbershop System — Implementation Plan

Laravel 13 + Blade + Tailwind. SQLite (dev). 3 roles: Owner, Kasir, Barber.

## 1. Konsep & Flow

```
Landing page (publik)
  -> Login / Register
       -> redirect by role:
            Owner  -> /dashboard (full access)
            Kasir  -> /kasir
            Barber -> /barber

Booking flow:
  Customer pilih paket + barber + jadwal
    -> Booking (status: pending)
    -> Barber lihat antrian booking-nya
    -> Selesai layanan -> tagihan masuk ke Kasir
    -> Kasir proses pembayaran -> status: paid/done
```

Booking dibuat oleh publik/kasir tanpa wajib akun customer. Owner mengawasi semua.

## 2. Roles & Akses

| Fitur                 | Owner | Kasir | Barber |
|-----------------------|:-----:|:-----:|:------:|
| Dashboard & laporan   |  V    |  -    |  -     |
| Kelola user/barber    |  V    |  -    |  -     |
| Kelola paket layanan  |  V    |  -    |  -     |
| Lihat semua booking   |  V    |  V    |  hanya miliknya |
| Buat booking          |  V    |  V    |  -     |
| Proses pembayaran     |  V    |  V    |  -     |
| Update status layanan |  V    |  -    |  V     |

Otorisasi: kolom `role` di tabel users + middleware `role:owner` dst.
(ponytail: enum role di kolom, bukan tabel roles/permissions. Pindah ke
spatie/permission kalau butuh permission granular.)

## 3. Skema Database

- **users**: + `role` enum(owner,kasir,barber), `phone`, `is_active`.
- **services** (paket): `name`, `description`, `price`, `duration_minutes`, `is_active`.
- **bookings**: `customer_name`, `customer_phone`, `barber_id` (->users),
  `service_id` (->services), `scheduled_at`, `status`
  enum(pending,in_progress,done,cancelled), `notes`.
- **payments**: `booking_id`, `cashier_id` (->users), `amount`,
  `method` enum(cash,qris,transfer), `paid_at`.

Harga disalin ke payment.amount saat bayar (snapshot), bukan join ke
services, supaya laporan tetap akurat kalau harga berubah.

## 4. Struktur Kode

```
app/Models/            User, Service, Booking, Payment
app/Http/Controllers/
  Auth/                (dari Breeze)
  DashboardController   (Owner: ringkasan + laporan)
  UserController        (Owner: CRUD barber/kasir)
  ServiceController     (Owner: CRUD paket)
  BookingController     (Owner+Kasir: CRUD, Barber: index+update status)
  PaymentController     (Owner+Kasir: proses bayar)
app/Http/Middleware/    RoleMiddleware
resources/views/
  layouts/app.blade.php          (sidebar dashboard)
  landing.blade.php
  auth/                          (Breeze)
  dashboard/, users/, services/, bookings/, payments/
```

Controller resourceful, validasi via FormRequest, query berat di model
scope. Tanpa service layer terpisah (app kecil) — tambah kalau controller
mulai gemuk.

## 5. UI/UX

- Tailwind (sudah via Breeze). Landing: hero, daftar paket+harga, CTA booking, footer.
- Dashboard: sidebar kiri (menu per-role), top bar (nama+role+logout),
  konten card-based. Palet netral + 1 warna aksen, tipografi rapi, spacing konsisten.
- Komponen Blade reusable: `<x-card>`, `<x-stat>`, `<x-badge status>`, `<x-table>`.
- Status booking pakai badge warna; tabel dengan filter + search sederhana.

## 6. Urutan Pengerjaan

1. Setup: install Breeze (Blade), migrate, tambah `role` ke users.
2. RoleMiddleware + redirect by-role setelah login.
3. Model + migration: services, bookings, payments + seeder (owner, kasir,
   barber, beberapa paket).
4. Landing page + layout dashboard (sidebar/topbar) + komponen Blade.
5. Owner: CRUD paket & user, halaman dashboard ringkasan.
6. Booking: form buat booking, list, halaman antrian Barber + update status.
7. Kasir: daftar tagihan (booking done belum bayar) + proses pembayaran.
8. Laporan Owner: omzet harian, per-barber, per-paket.

## 7. Command Setup (jalankan sendiri)

```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install && npm run build
php artisan migrate
php artisan db:seed
```

Akun seeder default dibuat di langkah 3 (owner@mail.com dst, password: password).
