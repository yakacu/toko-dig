# Toko Online — Laravel Edition

Migrasi dari aplikasi PHP native (`mysqli` + HTML manual) ke Laravel 11,
lengkap dengan fitur **live search (debounce)** dan beberapa **optimasi
query/cache**.

---

## Daftar Isi

1. [Requirement](#requirement)
2. [Instalasi Step-by-Step](#instalasi-step-by-step)
3. [Struktur File yang Disalin](#struktur-file-yang-disalin)
4. [Kredensial Default](#kredensial-default)
5. [Fitur](#fitur)
6. [Live Search / Debounce](#live-search--debounce)
7. [Optimasi](#optimasi)
8. [Daftar Route](#daftar-route)
9. [Perubahan dari Versi PHP Lama](#perubahan-dari-versi-php-lama)
10. [Troubleshooting](#troubleshooting)

---

## Requirement

- PHP >= 8.2
- Composer
- MySQL / MariaDB
- Node.js (opsional, hanya kalau mau compile asset sendiri — project ini
  pakai Bootstrap via CDN jadi sebenarnya tidak wajib)

## Instalasi Step-by-Step

### 1. Buat project Laravel baru

```bash
composer create-project laravel/laravel toko-online
cd toko-online
```

### 2. Salin file dari paket ini

Timpa/tambahkan file berikut ke project barumu (struktur foldernya sama
persis, tinggal copy-paste):

```
app/Models/Kategori.php
app/Models/Produk.php
app/Models/User.php                                    (timpa default)
app/Providers/AppServiceProvider.php                    (timpa default)
app/Http/Controllers/Controller.php                     (timpa default, isinya sama)
app/Http/Controllers/ProdukController.php
app/Http/Controllers/Admin/AuthController.php
app/Http/Controllers/Admin/DashboardController.php
app/Http/Controllers/Admin/KategoriController.php
app/Http/Controllers/Admin/ProdukController.php
app/Http/Controllers/Admin/UserController.php
database/migrations/0001_01_01_000000_create_users_table.php   (timpa default)
database/migrations/2025_01_01_000001_create_kategori_table.php
database/migrations/2025_01_01_000002_create_produk_table.php
database/seeders/DatabaseSeeder.php                      (timpa default)
resources/views/layouts/shop.blade.php
resources/views/layouts/admin.blade.php
resources/views/produk/**/*.blade.php
resources/views/admin/**/*.blade.php
routes/web.php                                           (timpa default)
```

> Hapus dulu `database/migrations/0001_01_01_000000_create_users_table.php`
> versi default Laravel sebelum menimpanya, supaya tidak ada dua file
> migration dengan nama class yang sama.

```bash
# dari folder hasil ekstrak paket ini
cp -r app/Models/* <path-project-laravel>/app/Models/
cp app/Providers/AppServiceProvider.php <path-project-laravel>/app/Providers/
cp -r app/Http/Controllers/* <path-project-laravel>/app/Http/Controllers/
rm <path-project-laravel>/database/migrations/*_create_users_table.php
cp database/migrations/* <path-project-laravel>/database/migrations/
cp database/seeders/DatabaseSeeder.php <path-project-laravel>/database/seeders/
cp -r resources/views/* <path-project-laravel>/resources/views/
cp routes/web.php <path-project-laravel>/routes/web.php
```

### 3. Atur redirect guest ke `bootstrap/app.php`

Route login admin di sini bernama `admin.login` (bukan `login` bawaan
Laravel), jadi middleware `auth` perlu tahu ke mana harus redirect kalau
belum login. Tambahkan di dalam `->withMiddleware()`:

```php
use Illuminate\Foundation\Configuration\Middleware;

->withMiddleware(function (Middleware $middleware) {
    $middleware->redirectGuestsTo(fn () => route('admin.login'));
})
```

### 4. Konfigurasi `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=toko_online
DB_USERNAME=root
DB_PASSWORD=

FILESYSTEM_DISK=public

# Cache dipakai untuk fitur optimasi (lihat bagian Optimasi).
# "database" sudah cukup untuk skala kecil-menengah dan aktif secara default.
CACHE_STORE=database
```

### 5. Migrate + seed

```bash
php artisan migrate --seed
php artisan storage:link
```

Seeder otomatis mengisi 2 kategori, 2 produk, dan 1 akun admin (lihat
[Kredensial Default](#kredensial-default)).

### 6. Pindahkan foto produk lama

Foto produk dari data lama ada di folder `storage-seed/produk/` (disertakan
di paket ini). Salin ke `storage/app/public/produk/` di project barumu:

```bash
cp storage-seed/produk/* <path-project-laravel>/storage/app/public/produk/
```

### 7. Jalankan

```bash
php artisan serve
```

- Halaman toko: `http://localhost:8000`
- Login admin: `http://localhost:8000/admin/login`

---

## Struktur File yang Disalin

```
app/
├── Models/
│   ├── Kategori.php        # + cachedList(), auto-invalidate cache
│   ├── Produk.php          # + cachedTerbaru(), auto-invalidate cache
│   └── User.php            # dipakai sebagai akun admin (guard 'web')
├── Providers/
│   └── AppServiceProvider.php   # set pagination ke gaya Bootstrap 5
└── Http/Controllers/
    ├── ProdukController.php     # halaman publik + endpoint AJAX search
    └── Admin/
        ├── AuthController.php
        ├── DashboardController.php   # + endpoint AJAX search
        ├── KategoriController.php
        ├── ProdukController.php
        └── UserController.php

database/
├── migrations/          # users, kategori, produk (+ index & FK)
└── seeders/              # data awal dari SQL dump lama

resources/views/
├── layouts/               # shop.blade.php (publik), admin.blade.php
├── produk/
│   ├── index.blade.php, search.blade.php, show.blade.php
│   └── partials/list.blade.php     # fragment grid produk (dipakai AJAX)
└── admin/
    ├── auth/login.blade.php
    ├── dashboard.blade.php
    ├── partials/lists.blade.php    # fragment 3 tabel (dipakai AJAX)
    ├── kategori/, produk/, users/  # form create & edit

routes/web.php
storage-seed/produk/     # foto produk lama, salin ke storage/app/public/produk
```

## Kredensial Default

| Username | Password   |
|----------|------------|
| `admin`  | `password` |

Ganti password ini setelah login pertama (menu Edit User) — seeder ini
untuk kebutuhan development, jangan dipakai apa adanya di production.

## Fitur

**Publik**
- Beranda — daftar kategori + 6 produk terbaru yang stoknya tersedia
- Pencarian produk — filter nama & kategori, dengan **live search
  (debounce)**, pagination
- Detail produk — info lengkap + tombol "Hubungi via WhatsApp" (link
  `wa.me` otomatis terisi pesan template)

**Admin** (`/admin`, butuh login)
- Login / logout pakai session Laravel bawaan (`Auth::attempt`)
- Dashboard — 3 tabel (users, kategori, produk) dengan pencarian **live
  (debounce)** dan pagination masing-masing
- CRUD kategori
- CRUD produk, termasuk upload foto (validasi tipe & ukuran, foto lama
  otomatis terhapus saat diganti/dihapus)
- CRUD user admin

## Live Search / Debounce

Dua halaman yang tadinya reload penuh setiap submit form sekarang pakai
**live search dengan debounce**, tanpa library tambahan (vanilla JS, tidak
ada Alpine/Vue supaya tetap ringan):

- **Pencarian produk** (`resources/views/produk/search.blade.php`)
- **Dashboard admin** (`resources/views/admin/dashboard.blade.php`)

Cara kerjanya:

1. User mengetik di kolom pencarian.
2. JS menunggu **400ms** sejak ketikan terakhir (debounce) sebelum
   mengirim request — jadi tidak ada request nyasar tiap ketik satu huruf.
3. Request dikirim via `fetch()` dengan header `X-Requested-With:
   XMLHttpRequest`.
4. Controller mendeteksi ini lewat `$request->ajax()`, dan membalas
   **fragment HTML saja** (partial view), bukan halaman penuh —
   `produk.partials.list` atau `admin.partials.lists`.
5. JS mengganti `innerHTML` container hasil, dan meng-update URL browser
   (`history.replaceState`) supaya tetap bisa di-bookmark/di-refresh.
6. Kalau user mengetik lagi sebelum request sebelumnya selesai, request
   lama otomatis **dibatalkan** (`AbortController`) supaya hasil yang
   datang belakangan tidak ketiban hasil yang lebih lama (race condition).
7. Klik link pagination di dalam hasil juga lewat `fetch()` yang sama
   (event delegation), jadi tidak reload halaman.
8. Kalau JavaScript nonaktif/gagal, form tetap berfungsi normal sebagai
   `GET` request biasa (progressive enhancement) — tidak ada fitur yang
   hilang total.

## Optimasi

- **Cache daftar kategori** (`Kategori::cachedList()`) — dipakai di
  beranda, halaman pencarian, dan form tambah/edit produk. Kategori jarang
  berubah, jadi di-cache 1 jam dan otomatis dibersihkan lewat Eloquent
  model event (`saved`/`deleted`) setiap kali ada perubahan — tidak perlu
  `Cache::forget()` manual di controller manapun.
- **Cache produk terbaru** (`Produk::cachedTerbaru()`) untuk beranda,
  di-cache 5 menit dengan mekanisme invalidasi yang sama.
- **Eager loading** (`with('kategori')`) di semua query produk untuk
  menghindari N+1 query saat menampilkan nama kategori.
- **Index database**: `produk.nama` (untuk `LIKE` search),
  `produk.kategori_id` (FK), dan index gabungan `(stok, kategori_id)`
  untuk mempercepat query filter di halaman pencarian & beranda.
- **Debounce** itu sendiri adalah optimasi — mengurangi jumlah request ke
  server saat user mengetik cepat, dan `AbortController` mencegah request
  lama yang sudah tidak relevan tetap diproses server.
- **Pagination** pakai `paginate()` bawaan Laravel (query `LIMIT`/`OFFSET`
  otomatis), termasuk 3 paginator independen di satu halaman dashboard.

## Daftar Route

| Method     | URL                    | Fungsi                                    |
|------------|-------------------------|--------------------------------------------|
| GET        | `/`                     | Beranda                                    |
| GET        | `/produk`               | Cari/filter produk (mendukung AJAX)        |
| GET        | `/produk/{produk}`      | Detail produk                              |
| GET        | `/admin/login`          | Form login admin                           |
| POST       | `/admin/login`          | Proses login                               |
| POST       | `/admin/logout`         | Logout                                     |
| GET        | `/admin`                | Dashboard (mendukung AJAX)                 |
| GET/POST   | `/admin/kategori/...`   | CRUD kategori                              |
| GET/POST   | `/admin/produk/...`     | CRUD produk (upload foto)                  |
| GET/POST   | `/admin/users/...`      | CRUD user admin                            |

## Perubahan dari Versi PHP Lama

- Raw query `mysqli` → **Eloquent ORM**, parameter binding otomatis (aman
  dari SQL injection tanpa perlu `real_escape_string` manual).
- `session.php` custom → middleware `auth` bawaan Laravel +
  `Auth::attempt()`, password di-hash otomatis lewat cast `'password' =>
  'hashed'`.
- `move_uploaded_file` manual → `Storage::disk('public')`, termasuk
  penghapusan foto lama otomatis saat produk diedit/dihapus.
- Alert `<script>alert(...)</script>` → session flash message + Bootstrap
  alert, validasi pakai `$request->validate()` (redirect otomatis dengan
  pesan error per-field).
- Pagination manual hitung `LIMIT`/`OFFSET` sendiri → `Model::paginate()`.
- Bug lama: kode PHP menulis `stok = 'Tersedia'` (kapital) padahal kolom
  `enum` di schema lowercase (`tersedia`/`habis`) — jadi filter di
  beranda versi lama sebenarnya selalu kosong. Sudah dirapikan jadi
  konsisten lowercase di migration, model, dan seluruh view.
- FK `kategori_id` di produk: kalau kategori dihapus, produk terkait
  sekarang otomatis di-null-kan (`nullOnDelete()`), bukan memblokir
  penghapusan seperti versi lama.
- **Baru:** live search dengan debounce (lihat bagian di atas) dan
  caching kategori/produk terbaru untuk mengurangi beban database.

## Troubleshooting

- **Redirect ke `/login` (bukan `/admin/login`) saat belum login** — pastikan
  langkah [3](#3-atur-redirect-guest-ke-bootstrapapp) sudah dilakukan.
- **Foto produk tidak muncul** — pastikan sudah `php artisan storage:link`
  dan foto sudah disalin ke `storage/app/public/produk/`.
- **Live search tidak jalan / halaman malah reload penuh** — cek console
  browser; kemungkinan `@stack('scripts')` di layout terhapus saat
  copy-paste, atau ada error JS lain yang menghentikan `fetch()`.
- **Cache tidak ke-update setelah edit kategori/produk** — pastikan
  `CACHE_STORE` di `.env` mengarah ke driver yang benar-benar aktif
  (bukan driver yang salah konfigurasi); jalankan `php artisan cache:clear`
  untuk reset manual kalau perlu.
