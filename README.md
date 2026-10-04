# Tugas Rutin 11 — E-Commerce DB + Secure Auth

Aplikasi Laravel dengan skema database e-commerce (7 tabel + FK), data
seed 60+ produk realistis, sistem login/register (Breeze), multi-role
(admin/editor/user), custom middleware, dan Policy untuk otorisasi
edit/delete produk.

> Sama seperti TR9 & TR10: file di folder ini digabungkan ke project
> Laravel yang kamu buat sendiri lewat `composer create-project`, karena
> instalasi Laravel (dan Breeze) harus jalan di komputermu yang punya
> akses internet ke Packagist + NPM.

## Catatan soal nama "PostPolicy"

Di poin 7 rubrik ada tulisan **"PostPolicy untuk otorisasi edit/delete"**.
Karena aplikasi TR11 ini e-commerce (tidak ada Model `Post` sama sekali —
beda dengan TR10 yang memang blog), kemungkinan besar itu sisa teks dari
template rubrik tugas sebelumnya. Jadi yang saya buat adalah
**`ProductPolicy`** (pola otorisasinya 100% sama, cuma beda nama class
& model) karena itu yang masuk akal untuk aplikasi ini. Kalau pas
dikumpul ternyata dosennya memang mau nama class **harus** `PostPolicy`
literal, tinggal `rename` file `ProductPolicy.php` → `PostPolicy.php` dan
`class ProductPolicy` → `class PostPolicy` (isinya tidak perlu diubah
sama sekali). Kalau ragu, lebih aman tanya dulu ke dosen/asdos.

## Struktur folder (yang disiapkan di sini)

```
├── routes/web.php                         -> route produk + route protection by role
├── bootstrap/app.php                      -> daftar alias middleware "role"
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Controller.php             -> + trait AuthorizesRequests
│   │   │   └── ProductController.php      -> authorize() + eager loading demo
│   │   └── Middleware/
│   │       └── EnsureUserHasRole.php       -> custom middleware role
│   ├── Models/
│   │   ├── User.php                       -> + role, relationships, helper hasRole()
│   │   ├── Category.php, Product.php, Cart.php, CartItem.php,
│   │   │   Order.php, OrderItem.php, Review.php
│   └── Policies/
│       └── ProductPolicy.php              -> otorisasi create/update/delete
├── database/
│   ├── migrations/                        -> 7 tabel e-commerce + kolom role
│   ├── factories/                         -> Category, Product (66 nama realistis), Review, Order
│   └── seeders/                           -> Category, Product, User (akun demo), Review
└── resources/views/
    ├── layouts/app.blade.php
    └── products/ (index, show, create, edit, _form)
```

## Langkah instalasi

### 1. Buat project Laravel baru

```bash
composer create-project laravel/laravel TugasWeb-P11-EcommerceAuth
cd TugasWeb-P11-EcommerceAuth
```

### 2. Install Breeze (REQUIREMENT 5) — SEBELUM copy file custom

```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install
npm run build
```

Pilih opsi default (stack **Blade**) kalau ditanya pilihan. Ini otomatis
membuat halaman login, register, logout, dan profile, plus
`routes/auth.php`.

### 3. Timpa dengan file-file custom dari folder ini

Sekarang baru salin (timpa kalau sudah ada) semua file dari paket ini ke
project barumu — urutannya penting, makanya Breeze diinstall duluan di
Langkah 2:

- `routes/web.php`
- `bootstrap/app.php`
- `app/Http/Controllers/Controller.php`
- `app/Http/Controllers/ProductController.php`
- `app/Http/Middleware/EnsureUserHasRole.php`
- `app/Policies/ProductPolicy.php`
- `app/Models/User.php` (timpa yang sudah ada dari Breeze)
- `app/Models/Category.php`, `Product.php`, `Cart.php`, `CartItem.php`, `Order.php`, `OrderItem.php`, `Review.php`
- Semua isi `database/migrations/`, `database/factories/`, `database/seeders/`
- `resources/views/layouts/app.blade.php` (timpa punya Breeze)
- Semua isi `resources/views/products/`

> Command asli untuk menghasilkan file-file ini:
> ```bash
> php artisan make:migration create_categories_table
> php artisan make:model Product -m
> php artisan make:model Cart -m
> php artisan make:model CartItem -m
> php artisan make:model Order -m
> php artisan make:model OrderItem -m
> php artisan make:model Review -m
> php artisan make:middleware EnsureUserHasRole
> php artisan make:policy ProductPolicy --model=Product
> php artisan make:controller ProductController
> ```

### 4. Konfigurasi `.env` ke MySQL

Buat database baru di phpMyAdmin, misal `tugas11_ecommerce`, lalu edit
`.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tugas11_ecommerce
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Migrate + seed

```bash
php artisan migrate --seed
```

Ini akan membuat 7 tabel e-commerce + kolom `role` di `users`, lalu
mengisi:
- 10 kategori
- **66 produk** realistis (lihat `database/seeders/ProductSeeder.php`)
- 3 akun demo (1 per role) + 5 user tambahan
- Review contoh untuk 20 produk acak

**Akun demo (REQUIREMENT 8 — buat testing 2 role):**

| Role   | Email                   | Password   |
|--------|-------------------------|------------|
| admin  | admin@tugasweb.test     | `password` |
| editor | editor@tugasweb.test    | `password` |
| user   | user@tugasweb.test      | `password` |

### 6. Jalankan server

```bash
php artisan serve
```

Buka `http://127.0.0.1:8000` — otomatis redirect ke `/products` (katalog
produk publik, siapa saja bisa lihat tanpa login).

## Dokumentasi 5 Query Tinker (REQUIREMENT 4)

Jalankan `php artisan tinker`, lalu coba 5 query ini satu per satu.
**Screenshot hasil tiap query** (atau screenshot sekali kalau semua
dijalankan berurutan di satu sesi tinker) untuk dilampirkan sebagai bukti:

```php
// 1. Ambil 5 produk pertama yang masih ada stoknya (pakai scope inStock)
App\Models\Product::inStock()->take(5)->get(['id', 'name', 'stock', 'price']);

// 2. Cari produk lewat scope search()
App\Models\Product::search('mouse')->get(['id', 'name', 'price']);

// 3. Relasi: lihat semua produk dalam kategori "Elektronik" (belongsTo/hasMany)
App\Models\Category::where('name', 'Elektronik')->first()->products;

// 4. Hitung rata-rata rating salah satu produk (relasi hasMany + avg)
App\Models\Product::first()->reviews()->avg('rating');

// 5. Lihat user dengan role tertentu (filter kolom role)
App\Models\User::where('role', 'admin')->get(['id', 'name', 'email', 'role']);
```

## Foto Produk

Foto produk pakai **konvensi nama file**, bukan upload lewat form atau
kolom database — simpel karena jumlah produknya tetap (hasil seeder).

- Taruh file gambar di `public/images/products/`
- Nama file **harus sama dengan ID produk**: `1.webp`, `2.webp`, ... `66.webp`
  (ID mengikuti urutan insert di `ProductSeeder.php`, kategori per kategori,
  dari "Elektronik" sampai "Otomotif")
- Paket ini sudah menyertakan 66 file `.webp` di `public/images/products/`
  yang sudah dicocokkan ke ID masing-masing produk
- Kalau suatu produk belum ada fotonya, halaman otomatis nampilin teks
  "Tidak ada foto" (bukan gambar rusak/broken image)

Setelah project Laravel baru dibuat dan file-file custom ditimpa (Langkah 3),
pastikan folder `public/images/products/` ikut ketimpa juga.

## Eager Loading Demo (BONUS)

Untuk lihat bedanya N+1 query vs eager loading, jalankan di Tinker:

```php
// Tanpa eager loading -> perhatikan query log, ada 1 query per produk
// buat ambil kategorinya (N+1 problem)
DB::enableQueryLog();
$produk = App\Models\Product::take(10)->get();
foreach ($produk as $p) { $p->category->name; }
count(DB::getQueryLog()); // hasilnya ~11 query (1 + 10)

// Dengan eager loading -> cuma 2 query total, berapa pun jumlah produknya
DB::flushQueryLog();
$produk = App\Models\Product::with('category')->take(10)->get();
foreach ($produk as $p) { $p->category->name; }
count(DB::getQueryLog()); // hasilnya cuma 2 query
```

`ProductController@index` di aplikasi ini sudah pakai `with('category')`
dari awal — lihat komentar BONUS di file itu.

## Testing Route Protection dengan 2 Role (REQUIREMENT 8)

1. Buka browser biasa, login sebagai **admin** (`admin@tugasweb.test` / `password`).
   - Harus bisa lihat tombol **"+ Produk"**, bisa tambah/edit/hapus produk.
2. Buka **jendela Incognito/Private** (atau browser lain), login sebagai
   **user biasa** (`user@tugasweb.test` / `password`).
   - Tombol "+ Produk" tidak muncul (dicek lewat `@can` di layout).
   - Kalau coba akses langsung `http://127.0.0.1:8000/products/create` lewat
     URL, harus kena **403 Forbidden** (ditolak middleware `role:admin,editor`).
3. Coba juga login sebagai **editor** (`editor@tugasweb.test` / `password`)
   di jendela ketiga — editor bisa tambah & edit produk, tapi kalau coba
   hapus produk (lewat tombol atau request manual), tetap kena 403 karena
   route delete dibatasi `role:admin` saja.

## Ketentuan yang dipenuhi

### Bagian A — Database & Eloquent

| # | Ketentuan | Lokasi |
|---|---|---|
| 1 | Migrations 7 tabel e-commerce + FK | `database/migrations/` — categories, products, carts, cart_items, orders, order_items, reviews (semua pakai `foreignId()->constrained()`) |
| 2 | Seeders + factories (50+ produk realistis) | `ProductFactory.php` (66 nama) + `ProductSeeder.php` |
| 3 | Model + relationships + minimal 1 scope | Semua model di `app/Models/` + `Product::scopeInStock()`/`scopeSearch()`, `Order::scopeStatus()` |
| 4 | Dokumentasi 5 query Tinker | Bagian "Dokumentasi 5 Query Tinker" di README ini (screenshot dilakukan sendiri) |

### Bagian B — Auth & Security

| # | Ketentuan | Lokasi |
|---|---|---|
| 5 | Install Breeze (login/register/logout) | Langkah 2 di atas (dilakukan di komputermu) |
| 6 | Multi-role + custom middleware | Kolom `role` di migration `add_role_to_users_table`, `EnsureUserHasRole.php`, alias di `bootstrap/app.php` |
| 7 | Policy otorisasi edit/delete | `ProductPolicy.php` (lihat catatan nama di atas) |
| 8 | Route protection + testing 2 role | `routes/web.php` (`middleware(['auth','role:...'])`), panduan testing di atas |

**Bonus (keduanya ada petunjuknya):**
- **Eager loading demo** — lihat bagian "Eager Loading Demo" di atas + `ProductController@index`
- **Filament admin panel** — opsional, kalau mau coba:
  ```bash
  composer require filament/filament:"^3.3" -W
  php artisan filament:install --panels
  php artisan make:filament-user
  php artisan make:filament-resource Product --generate
  ```
  Setelah itu buka `http://127.0.0.1:8000/admin` untuk CRUD produk lewat
  panel admin otomatis (tanpa perlu bikin view manual).

## Cara push ke GitHub

```bash
cd TugasWeb-P11-EcommerceAuth
git init
git add .
git commit -m "Tugas Rutin 11: E-Commerce DB + Secure Auth"
git branch -M main
git remote add origin https://github.com/username/TugasWeb-P11-EcommerceAuth.git
git push -u origin main
```

**Ingat:** selalu pakai `git add`/`commit`/`push` dari command line, jangan
drag & drop manual di web GitHub — supaya `vendor/` dan `node_modules/`
otomatis diabaikan lewat `.gitignore` bawaan Laravel.
