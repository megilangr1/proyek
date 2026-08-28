# PROJECT.md

Panduan dan ringkasan proyek ini untuk developer dan AI coding agent.
Untuk aturan perilaku agent yang wajib diikuti, baca `AGENTS.md` (berisi Laravel Boost guidelines). File ini melengkapi, bukan menggantikan, `AGENTS.md`.

## Identitas Proyek

- **Nama:** Proyek Latihan laravel 13 v4
- **Jenis:** Aplikasi web Laravel (latihan / starter)
- **URL lokal:** http://proyek.test (di-serve oleh Laravel Herd; lihat `AGENTS.md` section herd)
- **Status:** Fresh init, sudah migrasi & build.

## Stack Teknologi

| Layer | Teknologi |
|-------|-----------|
| Framework | Laravel 13.29 (PHP 8.4) |
| Bahasa | PHP 8.4 |
| UI reaktif (server) | Livewire 4 (full-page / single-file "Volt" components, `⚡` prefix) |
| UI interaktif (client) | Alpine.js (via `resources/js/alpine`) |
| Styling | Tailwind CSS v4 + daisyUI 5 |
| Animasi | Motion (`resources/js/motion`) |
| Carousel | Swiper (`resources/js/components/swiper`) |
| Auth & otorisasi | Laravel built-in + spatie/laravel-permission v8 |
| Database | MySQL (`proyek`, host 127.0.0.1:3306, user `root`, tanpa password) |
| Build frontend | Vite 8 (`@tailwindcss/vite`, `laravel-vite-plugin`) |
| Testing | Pest 5 (`pestphp/pest` + `pest-plugin-laravel`) |
| Formatting | Laravel Pint |
| AI tooling | Laravel Boost (`laravel/boost`) |

## Cara Menjalankan

```bash
# Dependensi (sudah terpasang, jalankan kalau ada perubahan composer/package)
composer install
npm install --ignore-scripts

# Setup awal (generate key, migrasi, build)
composer run setup

# Development:
composer run dev      # menjalankan `php artisan dev` (artisan + vite watch)
# atau jalankan terpisah:
php artisan serve     # tidak perlu jika pakai Herd
npm run dev           # vite hot-reload

# Build produksi
npm run build

# Testing
php artisan test --compact
vendor/bin/pest
```

> Frontend menggunakan Vite. Jika perubahan UI tidak muncul, jalankan `npm run dev` / `npm run build` (lihat `AGENTS.md` section Frontend Bundling).

## Struktur Direktori Penting

```
app/
  Http/Controllers/
    Controller.php          # base controller
    MainController.php       # (ORPHAN) tidak lagi dipakai route, lihat catatan
  Models/
    User.php                 # Authenticatable + HasRoles + SoftDeletes
routes/
  web.php                    # Route::livewire() full-page components (lihat bawah)
  console.php
resources/
  views/
    pages/
      ⚡main.blade.php        # GET /  -> component "pages::main" (layouts::public)
    admin/
      ⚡main.blade.php        # GET /admin -> component "admin::main" (layout default)
    main.blade.php           # (ORPHAN) sisa lama, tidak dipakai
    welcome.blade.php        # sisa default Laravel (tidak dipakai)
    layouts/
      public.blade.php       # layout untuk pages::main (x-data themeSwitcher)
      app.blade.php          # layout alternatif (x-data themeSwitcher)
      css.blade.php          # @livewireStyles + stack css
      script.blade.php       # @livewireScripts + stack script
  js/
    app.js                   # entry: init Alpine, Motion, Swiper
    alpine/                  # komponen Alpine (themeSwitcher.js)
    components/swiper/        # inisialisasi Swiper
    motion/                   # inisialisasi Motion
  css/app.css                # entry CSS (Tailwind + daisyUI)
config/database.php          # koneksi mysql (default)
```

### Routing (Livewire full-page components)

`routes/web.php` tidak lagi memakai controller, melainkan `Route::livewire()`:

```php
Route::livewire('/', 'pages::main')->name('main');
Route::prefix('admin')->name('admin.')->group(function () {
    Route::livewire('/', 'admin::main')->name('main');
});
```

- `pages::main` -> `resources/views/pages/⚡main.blade.php`
- `admin::main` -> `resources/views/admin/⚡main.blade.php`
- Komponen adalah **single-file Livewire 4** (Volt-style): diawali blok `<?php new class extends Component { ... } ?>` lalu markup Blade. File ditandai prefix `⚡`.
- `pages::main` memanggil `$this->view()->layout('layouts::public')`.

## Konvensi & Catatan Arsitektur

- **Livewire 4 full-page components** adalah cara utama merender halaman (via `Route::livewire()`). Komponen single-file disimpan di `resources/views/{namespace}/⚡{name}.blade.php` dan dideklarasikan dengan `new class extends Component`.
- **Alpine.js** hanya untuk interaksi client ringan (theme switcher). State penting tetap di server (Livewire).
- **Tailwind v4 + daisyUI 5** wajib dipakai untuk komponen UI (lihat skill `daisyui`). Jangan tulis CSS vanilla untuk komponen UI.
- **Theme switcher:** komponen `themeSwitcher` (Alpine) didefinisikan di `<html>` (`layouts/public.blade.php` & `layouts/app.blade.php`), dengan nilai `corporate` / `luxury` disimpan di `localStorage`.
- **Layout:** Volt component merender via `$this->view()->layout('layouts::public')`. `layouts::public` memuat `@livewireStyles`/`@livewireScripts` langsung; `layouts::app` memakai include `layouts.css`/`layouts.script`.
- **Model `User`** memakai trait `HasRoles` (spatie) dan `SoftDeletes`. Atribut `#[Fillable]` / `#[Hidden]` menggunakan attribute PHP 8 (bukan property `$fillable`).
- **Penamaan:** TitleCase untuk Enum key, descriptive names untuk method/variable, curly braces wajib (lihat `AGENTS.md` PHP rules).
- **Testing:** Pakai Pest. Buat test dengan `php artisan make:test --pest NamaFeatureTest`. Utamakan feature test.
- **Jangan** ubah dependency (`composer.json` / `package.json`) tanpa persetujuan.

## Database

- Engine: **MySQL**, database `proyek`.
- Driver terkait di `.env`: `SESSION_DRIVER=database`, `QUEUE_CONNECTION=database`, `CACHE_STORE=database` — tabel pendukung (`sessions`, `jobs`, `cache`, `failed_jobs`) sudah dimigrasi.
- Tabel spatie permission (`roles`, `permissions`, `model_has_roles`, dll.) sudah ada.
- Pastikan MySQL (XAMPP) berjalan di port 3306 sebelum `php artisan` yang menyentuh DB.

## TODO / Yang Belum Ada

- Belum ada auth scaffolding (login/register) — hanya model `User` + permission.
- File **orphan** hasil refactor ke Livewire full-page (bisa dihapus bila tidak dipakai):
  - `app/Http/Controllers/MainController.php` (route sudah pakai `Route::livewire`, bukan controller).
  - `resources/views/main.blade.php` (halaman utama sudah dipindah ke `pages/⚡main.blade.php`).
- `resources/views/welcome.blade.php` sisa default Laravel, tidak terpakai.
- `admin::main` baru berisi placeholder komentar Aristotle.

## Catatan untuk AI Agent

- Selalu baca `AGENTS.md` dan `.ai/rules` (jika ada) sebelum mengubah kode.
- Gunakan `php artisan make:` untuk membuat file baru.
- Setelah mengubah PHP, jalankan `vendor/bin/pint --format agent`.
- Gunakan tools Laravel Boost (database-query, database-schema, search-docs, get-absolute-url) bila relevan.
