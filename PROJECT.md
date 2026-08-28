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
| Font utama | **Outfit** (di-load via Bunny Fonts CDN, lihat bawah) |
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
      ⚡main.blade.php        # GET /  -> "pages::main" (layout layouts::public)
                                # Landing page "Pendataan dan Rekap Pintar": hero +
                                # fitur (stagger) + cara kerja (steps) + teknologi
                                # (Swiper) + testimoni + CTA
    admin/
      ⚡main.blade.php        # GET /admin -> "admin::main" (layout default layouts::app)
                                # Masih minimal; area admin memakai layout drawer +
                                # sidebar + navbar (lihat layouts di bawah)
    main.blade.php           # (ORPHAN) sisa lama, tidak dipakai
    welcome.blade.php        # sisa default Laravel (tidak dipakai)
    layouts/
      public.blade.php       # layout landing page (navbar + theme toggle + footer)
      app.blade.php          # DEFAULT component_layout Livewire (config/livewire.php
                                # -> 'component_layout' => 'layouts::app'); berisi drawer
                                # + sidebar + navbar (admin)
      navbar.blade.php       # navbar admin (theme toggle, dropdown user, logout)
      sidebar.blade.php      # sidebar admin (logo, menu, profil user)
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
- `admin::main` TIDAK memanggil `->layout()` secara eksplisit, sehingga memakai
  layout default yang di-set di `config/livewire.php` → `'component_layout' => 'layouts::app'`
  (drawer + sidebar + navbar). `layouts::app` memuat `@vite` dan meng-include `layouts.css`/`layouts.script`.

### Frontend & Font (Outfit via Bunny Fonts CDN)

Font utama **Outfit** tidak di-bundle lewat plugin Vite (`laravel-vite-plugin/fonts`),
melainkan di-load langsung dari CDN Bunny Fonts agar `@font-face` pasti tersedia:

```html
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800" rel="stylesheet">
```

Link tersebut ada di `<head>` kedua layout (`layouts/public.blade.php` & `layouts/app.blade.php`).
Nama font diikat sebagai default lewat `@theme` di `resources/css/app.css`:

```css
@theme {
    --font-sans: "Outfit", ui-sans-serif, system-ui, sans-serif, ...;
}
```

> Catatan: plugin `bunny()` di `vite.config.js` pernah dipakai tapi menghasilkan file
> `@font-face` yang tidak di-link ke halaman (orphan), sehingga font tidak muncul.
> Itu sebabnya sekarang pakai CDN. Jangan kembalikan `bunny()` ke `vite.config.js`
> tanpa memastikan css font-nya benar-benar di-link.

## Konvensi & Catatan Arsitektur

- **Livewire 4 full-page components** adalah cara utama merender halaman (via `Route::livewire()`). Komponen single-file disimpan di `resources/views/{namespace}/⚡{name}.blade.php` dan dideklarasikan dengan `new class extends Component`.
- **Alpine.js** hanya untuk interaksi client ringan (theme switcher). State penting tetap di server (Livewire).
- **Tailwind v4 + daisyUI 5** wajib dipakai untuk komponen UI (lihat skill `daisyui`). Jangan tulis CSS vanilla untuk komponen UI.
- **Theme switcher:** komponen `themeSwitcher` (Alpine) didefinisikan di `<html>` (`layouts/public.blade.php` & `layouts/app.blade.php`), dengan nilai `corporate` / `luxury` disimpan di `localStorage`.
- **Layout:** `pages::main` merender via `$this->view()->layout('layouts::public')` (navbar + footer + theme toggle). `admin::main` memakai layout default `layouts::app` (drawer + `layouts/sidebar.blade.php` + `layouts/navbar.blade.php`). Keduanya memuat `@vite` dan Livewire assets.
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
- Area admin baru memiliki kerangka layout (drawer + sidebar + navbar) di `layouts/app.blade.php`,
  tapi komponen `admin::main` (`resources/views/admin/⚡main.blade.php`) masih minimal/placeholder.
- File **orphan** hasil refactor ke Livewire full-page (bisa dihapus bila tidak dipakai):
  - `app/Http/Controllers/MainController.php` (route sudah pakai `Route::livewire`, bukan controller).
  - `resources/views/main.blade.php` (halaman utama sudah dipindah ke `pages/⚡main.blade.php`).
- `resources/views/welcome.blade.php` sisa default Laravel, tidak terpakai.

## Catatan untuk AI Agent

- Selalu baca `AGENTS.md` dan `.ai/rules` (jika ada) sebelum mengubah kode.
- Gunakan `php artisan make:` untuk membuat file baru.
- Setelah mengubah PHP, jalankan `vendor/bin/pint --format agent`.
- Gunakan tools Laravel Boost (database-query, database-schema, search-docs, get-absolute-url) bila relevan.
