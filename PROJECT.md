# PROJECT.md

Panduan & ringkasan proyek untuk developer **dan AI coding agent**. Ini adalah "kurikulum
pertama" yang harus dibaca agent saat mengenal project ini, baik di sesi pertama maupun sesi
berikutnya. Untuk aturan perilaku wajib, baca `AGENTS.md` (Laravel Boost guidelines). File ini
melengkapi, bukan menggantikan, `AGENTS.md`.

---

## 1. Identitas Proyek

- **Nama:** Proyek Latihan Laravel 13 (starter / latihan)
- **Jenis:** Aplikasi web Laravel monolitik dengan UI reaktif server-side (Livewire)
- **URL lokal:** http://proyek.test (di-serve Laravel Herd; lihat `AGENTS.md` → herd)
- **Status:** Sudah migrasi, build, auth (login/logout) jalan, landing page sudah direfactor
  (glassmorphism + Motion + Lucide). Area admin masih kerangka.

---

## 2. Stack Teknologi

| Layer | Teknologi |
|-------|-----------|
| Framework | Laravel 13 (PHP 8.4) |
| Bahasa | PHP 8.4 |
| UI reaktif (server) | Livewire 4 — full-page / single-file "Volt" components (prefix `⚡`) |
| UI interaktif (client) | Alpine.js (`resources/js/alpine`) |
| Styling | Tailwind CSS v4 + daisyUI 5 (theme `corporate` default, `luxury` dark) |
| Font | **Outfit** via Bunny Fonts CDN |
| Animasi | Motion (`resources/js/motion`) — engine `data-motion` |
| Carousel | Swiper (`resources/js/components/swiper`) |
| Icon | Lucide (`mallardduck/blade-lucide-icons` → `<x-lucide-*>`) |
| Auth & otorisasi | Laravel built-in (`Auth::attempt`/`login`/`logout`) + spatie/laravel-permission v8 |
| Database | MySQL (`proyek`, 127.0.0.1:3306, user `root`, tanpa password) |
| Build frontend | Vite 8 (`@tailwindcss/vite`, `laravel-vite-plugin`) |
| Testing | Pest 5 |
| Formatting | Laravel Pint |
| AI tooling | Laravel Boost (`laravel/boost`) |

> Tidak ada starter kit (Breeze/Jetstream). Auth di-hand-roll via Livewire Volt + facade `Auth`.

---

## 3. Cara Menjalankan

```bash
composer install
npm install --ignore-scripts

composer run setup        # key:generate + migrate + npm run build
composer run dev          # php artisan dev (artisan + vite watch)
npm run dev               # vite hot-reload (alternatif)
npm run build             # build produksi
php artisan test --compact
```

> Frontend Vite. Jika perubahan UI tak muncul → jalankan `npm run dev` / `npm run build`.

---

## 4. Struktur Direktori Penting

```
app/
  Http/Controllers/MainController.php   # ORPHAN (import sudah dihapus dari routes)
  Models/User.php                       # Authenticatable + HasRoles + SoftDeletes
config/
  livewire.php                          # component_namespaces: layouts, pages, admin, auth
  main_config.php                       # branding (name, short_name, tagline, description)
routes/web.php                          # Route::livewire() + logout POST
resources/views/
  pages/⚡main.blade.php                 # GET /  -> pages::main (layouts::public) — landing
  auth/⚡login.blade.php                 # GET /login -> auth::login (layouts::auth)
  admin/⚡main.blade.php                # GET /admin -> admin::main (layouts::app)
  main.blade.php                        # ORPHAN (pakai pages::main)
  welcome.blade.php                     # ORPHAN (default Laravel)
  layouts/
    public.blade.php                    # layout landing (navbar+footer+theme toggle)
    app.blade.php                       # DEFAULT Livewire layout (drawer+sidebar+navbar)
    auth.blade.php                      # layout login (card centered)
    navbar.blade.php / sidebar.blade.php
  js/
    app.js                              # entry: Alpine + Motion + Swiper
    alpine/themeSwitcher.js
    motion/index.js + animations.js     # engine + daftar animasi
    components/swiper.js
  css/app.css                           # @import tailwindcss + @plugin daisyui + @theme + utilitas
public/img/logo.png                    # logo brand
```

### 4.1 Routing (Livewire full-page)

```php
Route::livewire('/', 'pages::main')->name('main');

Route::middleware('guest')->group(function () {
    Route::livewire('/login', 'auth::login')->name('login');
});

Route::post('/logout', fn (Request $r) => /* Auth::logout + invalidate + regenerateToken */)
    ->name('logout')->middleware('auth');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::livewire('/', 'admin::main')->name('main');
});
```

- Namespace `auth` → `resources/views/auth`. Komponen SFC diakses sebagai `auth::login`.
- Auth: login via aksi Livewire (`Auth::attempt` + `RateLimiter` + `session()->regenerate()`),
  logout via route POST. `/admin` wajib login; `/login` hanya untuk guest.
- Perlu rute/guard baru? Pakai middleware `auth`/`guest` dan `Route::livewire()`.

### 4.2 Branding (config-driven)

Teks brand sentral di `config/main_config.php` (fallback + env `MAIN_CONFIG_*`):

```blade
{{ config('main_config.short_name') }}   {{-- "RekapPintar" (navbar/footer) --}}
{{ config('main_config.name') }}         {{-- APP_NAME "Rekap Pintar" --}}
{{ config('main_config.tagline') }}      {{-- judul halaman --}}
{{ config('main_config.description') }}
```

**Jangan hardcode** "RekapPintar"/brand di view — pakai `config('main_config.*')`.

### 4.3 Sistem Animasi (Motion)

Engine berbasis atribut: `data-motion="<nama>"` dipindai saat load & Livewire navigate.
Daftar tersedia di `resources/js/motion/index.js` (map `animations`):

`fade-up`, `fade-down`, `fade-left`, `fade-right`, `blur-fade`, `blur-scale`, `scale-in`,
`slide-scale`, `spring-in`, `pop-in`, `rotate-in`, `bounce-in`, `float-in`, `reveal`,
`reveal-left`, `scroll-parallax`, `scroll-progress`, `scroll-fade`, `stagger`, `text-split`,
`tilt-in`, `flip-in`, `wiggle`, `heartbeat`, `shake`, `shine`, `marquee`, `hover-lift`,
`press-scale`, `splash`, **`gradient-pan`**, **`glow-pulse`**, **`count-up`**, **`tilt-3d`**.

Opsi via `data-motion-*`: `data-motion-delay`, `data-motion-duration`, `data-motion-distance`,
`data-motion-scale`, dll. Khusus:
- `glow-pulse`: `data-motion-glow="primary|secondary|..."` (warna tema), `data-motion-duration`.
- `count-up`: `data-motion-to="10000"`, `data-motion-suffix="rb+"`, `data-motion-decimals="1"`.
- `gradient-pan`: butuh elemen bergradien `background-size:200%` (lihat `.bg-aurora`).

**Menambah animasi baru:** tulis fungsi di `animations.js` (ikut pola `prefersReducedMotion()`
guard + `inView()`/`animate()` dari `motion`), lalu daftarkan di `index.js` (import + map).

### 4.4 Styling & Utilitas

`resources/css/app.css` mengekspor utilitas kustom (bukan CSS vanila di view):
`.glass-card`, `.glass-card--primary`, `.text-gradient`, `.bg-grid`, `.bg-aurora`.
Komponen UI wajib pakai **daisyUI 5** + Tailwind v4 — jangan tulis CSS vanila untuk komponen.

### 4.5 Font (Outfit via Bunny Fonts CDN)

Font tidak dibundle via plugin Vite (pernah orphan). Link CDN ada di `<head>` layout
(`public.blade.php` & `app.blade.php`); diikat di `@theme` `resources/css/app.css`.
**Jangan kembalikan** `bunny()` ke `vite.config.js` tanpa memastikan `@font-face` benar-benar
di-link.

### 4.6 Antarmuka Login (`auth`)

Halaman login (`auth::login` + `layouts/auth.blade.php`) sudah dipercantik selaras dengan
landing page (estetika glass / mewah / tech):
- **Layout `auth.blade.php`**: background `bg-grid` + `bg-aurora` (`data-motion="gradient-pan"`
  & `scroll-parallax`), card di-center (`max-w-md`).
- **Komponen login**: card `glass-card--primary` + `data-motion="float-in"` +
  `data-motion="glow-pulse"` (`data-motion-glow="primary"` `data-motion-duration="4.5"`);
  header logo `img/logo.png` + judul `text-gradient`; input email/password memakai ikon
  `<x-lucide-mail>` / `<x-lucide-lock>` di dalam field; tombol `btn-primary` + `hover-lift`.
- **Pola ikon dalam input** (penting): wrapper `relative`, tulis `<input>` **dulu** lalu
  `<span class="absolute ...">` agar ikon (elemen absolut) tergambar di atas input statis —
  tanpa perlu `z-index`. Ikon pakai warna `text-base-content/50` agar cukup terlihat.
- Tiru pola ini bila membuat form/auth serupa.

---

## 5. Konvensi & Catatan Arsitektur

- **Livewire 4 Volt SFC** adalah cara utama render halaman. Deklarasi:
  `<?php new class extends Component { public function render() { return $this->view()->layout('layouts::x'); } } ?>` lalu markup Blade. File prefix `⚡`.
- **Alpine.js** hanya interaksi client ringan (theme switcher). State penting di server.
- **daisyUI 5 + Tailwind v4** wajib untuk UI.
- **Theme switcher** (`themeSwitcher` Alpine) di `<html>`; nilai `corporate`/`luxury` di
  `localStorage`. Desain harus adaptif kedua tema (pakai `--color-primary`/`--color-secondary`).
- **Model `User`**: `HasRoles` (spatie) + `SoftDeletes`; atribut `#[Fillable]`/`#[Hidden]`
  pakai PHP 8 attribute (bukan `$fillable`). Password auto-hashed via cast.
- **Penamaan**: TitleCase Enum key, descriptive method/variable, curly braces wajib.
- **Testing**: Pest; utamakan feature test. `tests/Pest.php` aktifkan `RefreshDatabase`.
- **Jangan ubah dependency** (`composer.json`/`package.json`) tanpa persetujuan.

---

## 6. Database

- MySQL `proyek`. `SESSION_DRIVER=database`, `QUEUE_CONNECTION=database`,
  `CACHE_STORE=database` (tabel `sessions`, `jobs`, `cache`, `failed_jobs` sudah ada).
- Tabel spatie permission sudah ada.
- Pastikan MySQL (XAMPP) jalan di 3306 sebelum `php artisan` yang menyentuh DB.

---

## 7. TODO / Yang Belum Ada

- **Register** belum ada — user dibuat via tinker/seeder. (Login/logout sudah jalan.)
- Area admin (`admin::main`) masih placeholder; layout drawer+sidebar+navbar sudah ada.
- File **orphan** (bisa dihapus bila tak dipakai): `app/Http/Controllers/MainController.php`,
  `resources/views/main.blade.php`, `resources/views/welcome.blade.php`.

---

## 8. Panduan AI Agent (Onboarding)

Bagian ini wajib dibaca agent di **setiap sesi** agar konsisten dengan pola project.

### 8.1 Pola yang berlaku (patterns)

1. **Halaman = Livewire Volt SFC**, bukan controller. Tambah rute dengan `Route::livewire()`.
2. **UI = daisyUI 5** (kelas `btn`, `card`, `badge`, `table`, dll). Hindari CSS vanila.
3. **Animasi = atribut `data-motion`**; extend di `motion/animations.js`, bukan inline JS.
4. **Brand = `config('main_config.*')`**, bukan string hardcode.
5. **State & auth di server** (Livewire/`Auth`). Client hanya Alpine ringan.
6. **Theme adaptif**: semua warna pakai token daisyUI (`primary`, `secondary`, `base-content`).
7. **Ikons = `<x-lucide-*>`**, bukan emoji, untuk kesan tech/rapi.

### 8.2 Adaptasi yang harus dilakukan model

- Saat membuat komponen/UI baru: tiru struktur `pages/⚡main.blade.php` & `layouts/*`
  (Volt + `->layout()`, daisyUI, `data-motion`).
- Setelah ubah PHP: `vendor/bin/pint --format agent`.
- Setelah ubah Blade/JS/CSS: `npm run build` (atau `npm run dev`) lalu cek visual.
- Gunakan tools Boost bila relevan: `database-query`, `database-schema`, `search-docs`,
  `get-absolute-url`.
- Hormati `prefers-reduced-motion` (sudah ada helper di `animations.js`).
- Jangan commit/ubah dependency tanpa konfirmasi user.

### 8.3 Pertanyaan konfirmasi (tanya dulu, jangan asumsi)

Sebelum implementasi non-trivial, tanyakan (satu per satu, beri opsi + rekomendasi):
- **Scope**: fitur mana yang dikerjakan? (mis. auth → login/logout saja atau + register?)
- **Proteksi & akses**: route perlu middleware `auth`/`guest`? Siapa boleh akses?
- **Layout**: pakai layout existing (`public`/`app`/`auth`) atau buat baru?
- **Dependency/paket**: perlu tambah npm/composer package? (wajib persetujuan)
- **Tone/visual**: kalau menyentut UI — terang/gelap/adaptif? brand/warna apa?
- **Data**: butuh migrasi/seed baru? butuh factory untuk test?

### 8.4 Konsep web project ini

- **Monolit Laravel + Livewire SPA-like**: navigasi terasa SPA via `wire:navigate`, tapi
  server-side rendering penuh (tidak ada API terpisah, kecuali diputuskan lain).
- **Guard `web` session-based**; auth manual (`Auth::attempt`/`login`/`logout`), bukan starter kit.
- **Dua tema** (`corporate`/`luxury`) dengan toggle; desain harus enak di keduanya.
- **Asset Vite**: `@vite` di layout; JS entry `resources/js/app.js` init Alpine+Motion+Swiper.
- **Testing in-memory sqlite** (phpunit.xml) dengan `RefreshDatabase`; factory untuk model.

### 8.5 Checklist sesi pertama

1. Baca `AGENTS.md` + file ini + `.ai/rules` (jika ada).
2. `php artisan route:list` — pahami rute & middleware.
3. `php artisan config:show` / cek `config/main_config.php`, `config/livewire.php`.
4. Inspect `layouts/*` & `pages/⚡main.blade.php` untuk pola UI.
5. `php artisan test --compact` — pastikan baseline hijau sebelum ubah kode.

---

## 9. Catatan untuk AI Agent

- Selalu baca `AGENTS.md` dan `.ai/rules` sebelum mengubah kode.
- Gunakan `php artisan make:` untuk file baru.
- Setelah ubah PHP: `vendor/bin/pint --format agent`.
- Build frontend setelah ubah Blade/JS/CSS.
- Tools Boost (`database-query`, `database-schema`, `search-docs`, `get-absolute-url`) bila relevan.
