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
  (glassmorphism + Motion + Lucide). Dashboard sudah ada (kerangka); area master-data mulai diisi.
  Detail Penggajian (`/penggajian/{id}/detail`) sudah ada 3 modal: pencatatan upah + summary read-only + ubah status bayar (per 2026-09-02 — Q5), auto-sync pekerja + prune hari, hitung `total_bersih` server-side.

---

## 2. Stack Teknologi

| Layer | Teknologi |
|-------|-----------|
| Framework | Laravel 13 (PHP 8.4) |
| Bahasa | PHP 8.4 |
| UI reaktif (server) | Livewire 4 — class-based components (`App\Livewire`, views di `resources/views/livewire`) |
| UI interaktif (client) | Alpine.js (`resources/js/alpine`) |
| Styling | Tailwind CSS v4 + daisyUI 5 (theme `corporate` default, `luxury` dark) |
| Font | **Outfit** via Bunny Fonts CDN |
| Animasi | Motion (`resources/js/motion`) — engine `data-motion` |
| Carousel | Swiper (`resources/js/components/swiper`) |
| Dialog/alert | SweetAlert2 (`sweetalert2`) — `window.Swal` / `window.deleteSwal` / `window.Toast` |
| Select | Native daisyUI `<select>` (kelas `select`) untuk opsi sedikit (status); **modal picker** (komponen Livewire) untuk pilih data referensi (mis. Proyek) — lihat §4.8 & §10.10 |
| Icon | Lucide (`mallardduck/blade-lucide-icons` → `<x-lucide-*>`) |
| Auth & otorisasi | Laravel built-in (`Auth::attempt`/`login`/`logout`) + spatie/laravel-permission v8 |
| Database | MySQL (`proyek`, 127.0.0.1:3306, user `root`, tanpa password) |
| Timezone | `Asia/Jakarta` (`config/app.php` `timezone`, diubah 2026-09-02 dari `UTC`) |
| Build frontend | Vite 8 (`@tailwindcss/vite`, `laravel-vite-plugin`) |
| Testing | Pest 5 |
| Formatting | Laravel Pint |
| AI tooling | Laravel Boost (`laravel/boost`) |

> Tidak ada starter kit (Breeze/Jetstream). Auth di-hand-roll via Livewire class component + facade `Auth`.

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
  Http/Controllers/MainController.php   # dipakai route logout (POST /logout)
  Livewire/                             # class components (namespace App\Livewire)
    Pages/Main.php                      # GET /            -> layouts.public  (landing)
    Auth/Login.php                      # GET /login       -> layouts.auth
    Dashboard/MainIndex.php             # GET /dashboard    -> layouts.app
    MasterData/Pengguna/MainIndex.php   # GET /pengguna     -> layouts.app
    MasterData/Proyek/MainIndex.php     # GET /proyek      -> layouts.app
    MasterData/Proyek/ProyekPickerModal.php # modal picker Proyek (child, bukan full-page)
    MasterData/ProyekPekerja/MainIndex.php # GET /proyek/{proyek}/pekerja -> layouts.app (main-detail + CRUD child)
    Penggajian/MainIndex.php            # GET /penggajian -> layouts.app (CRUD; pakai ProyekPickerModal)
    Penggajian/MainDetail.php           # GET /penggajian/{penggajian}/detail -> layouts.app (detail + 3 modal: pencatatan + summary + bayar)
  Helpers/MainHelper.php                # userData() + doAlert() (notif server -> client)
  Models/User.php                       # Authenticatable + HasRoles + SoftDeletes; isAdmin()/isOperator()/user_role
  Models/Proyek.php                     # #[Fillable]; casts StatusProyek (enum); HasFactory + SoftDeletes; pekerjas()/penggajians()
  Models/ProyekPekerja.php              # #[Fillable]; casts decimal:2 (DB decimal 15,2); StatusPekerja enum; HasFactory + SoftDeletes; proyek()
  Models/ProyekPenggajian.php           # #[Fillable]; casts date + StatusPenggajian (enum); HasFactory + SoftDeletes; proyek()/proyekPenggajianPekerja() HasMany
  Models/ProyekPenggajianPekerja.php    # #[Fillable]; casts decimal:2 (DB decimal 15,2); StatusBayar enum (BELUM/SUDAH) + tanggal_bayar date; SoftDeletes; proyekPenggajian()/proyekPekerja()/proyekPenggajianPekerjaHari() HasMany; tarif_overtime (rename 2026-09-02)
  Models/ProyekPenggajianPekerjaHari.php # #[Fillable]; casts date + decimal:2; HasFactory + SoftDeletes; proyekPenggajianPekerja()
  Enums/StatusProyek.php                # int enum: AKTIF=1, NONAKTIF=2 (label() + toSelectArray())
  Enums/StatusPekerja.php               # int enum: AKTIF=1, NONAKTIF=2 (label() + toSelectArray())
  Enums/StatusPenggajian.php            # int enum: AKTIF=1, NONAKTIF=2 (label() + toSelectArray())
  Enums/StatusBayar.php                 # int enum: BELUM=1, SUDAH=2 (label() + toSelectArray()) — rename dari AKTIF/NONAKTIF 2026-09-02
  View/Components/                      # reusable Blade components (class-backed)
    Main/PageHeader.php                 # <x-main.page-header title="...">
    Table/Th.php                        # <x-table.th ...> (header tabel sortable)
config/
  livewire.php                          # make_command.type => 'class'; emoji => false; class_namespace App\Livewire
  main_config.php                       # branding (name, short_name, tagline, description)
  routes/web.php                          # Route::livewire(FQCN) + logout POST
  database/factories/ProyekFactory.php     # factory Proyek (tanggal terurut, kode PRJnnn)
  database/factories/ProyekPekerjaFactory.php # factory ProyekPekerja (proyek_id => Proyek::factory())
  resources/views/
    livewire/                             # blade views untuk class components
      pages/main.blade.php
      auth/login.blade.php
      dashboard/main-index.blade.php
      master-data/pengguna/main-index.blade.php
      master-data/proyek/main-index.blade.php
      master-data/proyek/proyek-picker-modal.blade.php
      master-data/proyek-pekerja/main-index.blade.php
      penggajian/main-index.blade.php
      penggajian/main-detail.blade.php
  components/                           # blade views untuk App\View\Components
    main/page-header.blade.php
    table/th.blade.php
  layouts/
    public.blade.php                    # layout landing (navbar+footer+theme toggle)
    app.blade.php                       # DEFAULT admin layout (drawer+sidebar+navbar)
    auth.blade.php                      # layout login (card centered)
    navbar.blade.php / sidebar.blade.php
  js/
    app.js                              # entry: Alpine + Motion + Swiper
    alpine/themeSwitcher.js
    motion/index.js + animations.js     # engine + daftar animasi
    components/swiper.js
  css/app.css                           # @import tailwindcss + @plugin daisyui + @theme + utilitas + number input centered + hide spin (2026-09-02)
 public/img/logo.png                    # logo brand
  config/app.php                        # timezone Asia/Jakarta
```

### 4.1 Routing (Livewire full-page)

```php
use App\Http\Controllers\MainController;
use App\Livewire\Auth\Login;
use App\Livewire\Dashboard\MainIndex as DashboardMainIndex;
use App\Livewire\MasterData\Pengguna\MainIndex as PenggunaMainIndex;
use App\Livewire\MasterData\Proyek\MainIndex as ProyekMainIndex;
use App\Livewire\MasterData\ProyekPekerja\MainIndex as ProyekPekerjaMainIndex;
use App\Livewire\Penggajian\MainIndex as PenggajianMainIndex;
use App\Livewire\Penggajian\MainDetail as PenggajianMainDetail;
use App\Livewire\Pages\Main;
use Illuminate\Support\Facades\Route;

Route::livewire('/', Main::class)->name('main');

Route::middleware('guest')->group(function () {
    Route::livewire('/login', Login::class)->name('login');
});

Route::post('/logout', [MainController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::livewire('/dashboard', DashboardMainIndex::class)->name('dashboard');

    Route::prefix('master-data')->group(function () {
        Route::livewire('/pengguna', PenggunaMainIndex::class)->name('pengguna.index');
        Route::livewire('/proyek', ProyekMainIndex::class)->name('proyek.index');
        Route::livewire('/proyek/{proyek}/pekerja', ProyekPekerjaMainIndex::class)->name('proyek.pekerja.index');
    });

    Route::prefix('penggajian')->name('penggajian.')->group(function () {
        Route::livewire('/', PenggajianMainIndex::class)->name('index');
        Route::livewire('/{penggajian}/detail', PenggajianMainDetail::class)->name('detail');
    });
});
```

- Komponen Livewire berupa class di `App\Livewire` (view terpisah di `resources/views/livewire`).
  `Route::livewire()` menerima FQCN class, mis. `Login::class`.
- Auth: login via aksi Livewire (`Auth::attempt` + `RateLimiter` + `session()->regenerate()`),
  logout via route POST. `/dashboard`, `/pengguna`, `/proyek`, `/proyek/{proyek}/pekerja` &
  `/penggajian` wajib login; `/login` hanya untuk guest.
- **Main‑detail:** list `Proyek` (`/proyek`) punya tombol **Detail Pekerja** di tiap baris
  (popover Aksi) yang navigasi ke `/proyek/{proyek}/pekerja` (`proyek.pekerja.index`) — halaman
  rincian Proyek + **CRUD child `ProyekPekerja`**. Route param `{proyek}` diteruskan ke `mount()`
  dan di‑resolve jadi model (jangan namai properti public sama dengan nama route param, akan
  bentrok saat Livewire assign id mentah ke properti bertipe Model).
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
Tambahan 2026-09-02: `input[type="number"]` di-center & spin arrow di-hide (webkit + Firefox) + border konsisten `border-base-300` (ganti `border-slate-300`) di card/tabel view pengguna/proyek/pekerja/penggajian.

### 4.5 Font (Outfit via Bunny Fonts CDN)

Font tidak dibundle via plugin Vite (pernah orphan). Link CDN ada di `<head>` layout
(`public.blade.php` & `app.blade.php`); diikat di `@theme` `resources/css/app.css`.
**Jangan kembalikan** `bunny()` ke `vite.config.js` tanpa memastikan `@font-face` benar-benar
di-link.

### 4.6 Antarmuka Login (`auth`)

Halaman login (`App\Livewire\Auth\Login` + `layouts/auth.blade.php`) sudah dipercantik selaras dengan
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

### 4.7 Komponen Blade (View Components)

UI yang berulang diekstrak jadi Blade component **class-backed** (`App\View\Components`),
dipanggil via tag `<x-...>` (bukan inline di view):

- `<x-main.page-header title="...">` — judul halaman + slot aksi kanan
  (`app/View/Components/Main/PageHeader.php` + `resources/views/components/main/page-header.blade.php`).
- `<x-table.th label="..." field="..." :order-by="$order_by" :order-type="$order_type">`
  — header kolom sortable; men-dispatch event `setOrderBy` saat diklik
  (`app/View/Components/Table/Th.php` + `resources/views/components/table/th.blade.php`).

Bikin komponen serupa dengan `php artisan make:component <Nama> --view` (class di
`app/View/Components`, blade di `resources/views/components`).

### 4.8 Modal Select Referensi (Proyek Picker)

Pilih data referensi (mis. **Proyek**) di form/filter **harus** pakai modal picker Livewire,
bukan `<select>` native besar. Referensi kanonik:
`app/Livewire/MasterData/Proyek/ProyekPickerModal.php` +
`resources/views/livewire/master-data/proyek/proyek-picker-modal.blade.php`,
dipakai di CRUD Penggajian (`/penggajian`). Detail pola di §10.10.

---

## 5. Konvensi & Catatan Arsitektur

- **Livewire 4 class component** adalah cara utama render halaman. Class di `App\Livewire` (view
  terpisah di `resources/views/livewire`), layout diikat via atribut `#[Layout('layouts.x')]`.
- **Alpine.js** hanya interaksi client ringan (theme switcher). State penting di server.
- **daisyUI 5 + Tailwind v4** wajib untuk UI.
- **Komponen Blade berulang** diekstrak jadi `App\View\Components` (lihat §4.7), bukan
  di-copy-paste antar view.
- **Theme switcher** (`themeSwitcher` Alpine) di `<html>`; nilai `corporate`/`luxury` di
  `localStorage`. Desain harus adaptif kedua tema (pakai `--color-primary`/`--color-secondary`).
- **Model `User`**: `HasRoles` (spatie) + `SoftDeletes`; atribut `#[Fillable]`/`#[Hidden]`
  pakai PHP 8 attribute (bukan `$fillable`). Password auto-hashed via cast. Helper: `isAdmin()`
  (role `administrator`/`meggi`), `isOperator()` (role `operator`), `user_role` (akses role pertama).
- **Model `Proyek`**: `#[Fillable]`, `status` di-cast ke enum `App\Enum\StatusProyek`
  (`AKTIF`/`NONAKTIF`); `HasFactory` + `SoftDeletes`; relasi `pekerjas()` (hasMany). `kode_proyek`
  dibuat otomatis (`PRJ001`…`PRJ999`) di `doCreate`, bukan diinput user.
- **Model `ProyekPekerja`**: `#[Fillable]` (termasuk `proyek_id`); `tarif_harian`/`tarif_overtime`
  di-cast `decimal:2` (DB `decimal(15,2)` per 2026-09-02), `status` di-cast enum `App\Enum\StatusPekerja`; `HasFactory` + `SoftDeletes`;
  relasi `proyek()` (belongsTo). `proyek_id` punya FK constraint → `proyeks.id` (cascade).
- **Model `ProyekPenggajian`**: `#[Fillable]` (termasuk `proyek_id`); `periode_mulai`/`periode_selesai`
  di-cast `date`, `jam_kerja` `integer`, `status` di-cast enum `App\Enums\StatusPenggajian`;
  `HasFactory` + `SoftDeletes`; relasi `proyek()` (belongsTo) + `proyekPenggajianPekerja()` (HasMany, ditambah 2026-09-02). `proyek_id` punya FK constraint →
  `proyeks.id` (cascade). Pilih Proyek di form/filter pakai `ProyekPickerModal` (lihat §4.8/§10.10).
- **Model `ProyekPenggajianPekerja`**: `#[Fillable]` (termasuk `proyek_penggajian_id`, `proyek_pekerja_id`);
  `tarif_harian`/`tarif_overtime` (`tarif_lembur` → `tarif_overtime` rename 2026-09-02)/`total_hari`/`total_overtime`/`gaji_normal`/`upah_overtime`/
  `bonus`/`potongan`/`kasbon`/`total_bersih` di-cast `decimal:2` (DB `decimal(15,2)` per 2026-09-02), `status_bayar` di-cast enum
  `App\Enums\StatusBayar` (`BELUM`/`SUDAH`), `tanggal_bayar` di-cast `date`; `SoftDeletes` (tanpa HasFactory); relasi
  `proyekPenggajian()` + `proyekPekerja()` (belongsTo) + `proyekPenggajianPekerjaHari()` (HasMany). Keterangan diisi existing saat buka modal bayar.
- **Model `ProyekPenggajianPekerjaHari`**: `#[Fillable]` (termasuk `proyek_penggajian_pekerja_id`);
  `tanggal` di-cast `date`, `hari_normal`/`jam_overtime` di-cast `decimal:2`; `HasFactory` + `SoftDeletes`;
  relasi `proyekPenggajianPekerja()` (belongsTo).
- **Enum `StatusProyek`, `StatusPekerja`, `StatusPenggajian`**: int enum (`AKTIF=1`, `NONAKTIF=2`) dengan
  `label()` + `toSelectArray()`. **Enum `StatusBayar`**: `BELUM=1`, `SUDAH=2` (rename dari AKTIF/NONAKTIF 2026-09-02, label tetap "Belum di-Bayar"/"Sudah di-Bayar"). Lokasi: `App\Enums\`. Tambah status baru → extend enum, jangan simpan string.
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
- Dashboard (`dashboard`) masih kerangka; layout drawer+sidebar+navbar sudah ada.
- `app/Http/Controllers/MainController.php` masih dipakai untuk route `logout` (POST `/logout`);
  bukan orphan. File `resources/views/main.blade.php` & `welcome.blade.php` sudah dihapus.
- **Sudah ada:** main‑detail Proyek + CRUD child `ProyekPekerja` di `/proyek/{proyek}/pekerja`
  (route `proyek.pekerja.index`), enum `StatusPekerja`, model `ProyekPekerja` + FK
  `proyek_pekerjas.proyek_id`, factory & feature test terkait.
- **Sudah ada:** CRUD `ProyekPenggajian` di `/penggajian` (route `penggajian.index`), enum
  `StatusPenggajian`, model `ProyekPenggajian` + FK `proyek_penggajians.proyek_id`, serta
  **modal picker Proyek** (`ProyekPickerModal`) untuk pilih Proyek di form & filter.
- **Sudah ada:** Halaman detail Penggajian di `/penggajian/{penggajian}/detail` (route `penggajian.detail`),
  komponen `MainDetail` + view `main-detail.blade.php` — **update 2026-09-02**: bukan lagi read-only; ada 3 modal per pekerja — (1) pencatatan upah (`openModalPencatatan`, `saveData` validate `hari_normal in:0,0.5,1`, `jam_overtime 0..24`, `bonus/potongan/kasbon required|numeric`, `keterangan` textarea + prune hari di luar periode), (2) summary read-only (`openSummary`, tabel hari + totals `gaji_normal/upah_overtime/total_bersih`), (3) ubah status bayar (`openBayarModal`/`saveBayar` — `status_bayar` enum + `tanggal_bayar required_if SUDAH` + `keterangan` existing terisi). `prepareState()` `firstOrCreate` (preserve data) auto-sync pekerja dari `proyek.proyekPekerja`, `getTotalUpahProperty` pure, `border-base-300` konsisten, `type="number"` + lock pencatatan saat `SUDAH`.
- **Sudah ada:** Sub-modul penggajian per pekerja: `ProyekPenggajianPekerja` (`tarif_overtime` rename + `decimal(15,2)` 2026-09-02) + `ProyekPenggajianPekerjaHari`,
  tabel `proyek_penggajian_pekerjas` & `proyek_penggajian_pekerja_haris` (decimal 15,2), enum `StatusBayar` (`BELUM`/`SUDAH`, label "Belum/Sudah di-Bayar"). `ProyekPenggajian::proyekPenggajianPekerja()` HasMany ditambah 2026-09-02. Aksi tabel: `Lihat Summary` + `Pencatatan Upah` (disabled saat SUDAH) + `Ubah Status Bayar`.

---

## 8. Panduan AI Agent (Onboarding)

Bagian ini wajib dibaca agent di **setiap sesi** agar konsisten dengan pola project.

### 8.1 Pola yang berlaku (patterns)

1. **Halaman = Livewire class component** (`App\Livewire`), bukan controller. Tambah rute dengan `Route::livewire()`.
2. **UI = daisyUI 5** (kelas `btn`, `card`, `badge`, `table`, dll). Hindari CSS vanila.
3. **Animasi = atribut `data-motion`**; extend di `motion/animations.js`, bukan inline JS.
4. **Brand = `config('main_config.*')`**, bukan string hardcode.
5. **State & auth di server** (Livewire/`Auth`). Client hanya Alpine ringan.
6. **Theme adaptif**: semua warna pakai token daisyUI (`primary`, `secondary`, `base-content`).
7. **Ikons = `<x-lucide-*>`**, bukan emoji, untuk kesan tech/rapi.
8. **Komponen berulang = `App\View\Components`** (lihat §4.7), dipakai via tag `<x-...>`,
   bukan di-copy-paste antar view.
9. **Pilih data referensi = modal picker** (lihat §4.8 / §10.10), bukan `<select>` native besar
   (kecuali opsi sedikit/fixed seperti enum status).

### 8.2 Adaptasi yang harus dilakukan model

- Saat membuat komponen/UI baru: tiru struktur `app/Livewire/Pages/Main.php` + view
  `resources/views/livewire/pages/main.blade.php` & `layouts/*` (class + `#[Layout]`, daisyUI `border-base-300`, `data-motion`).
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
4. Inspect `app/Livewire/Pages/Main.php` + `resources/views/livewire/pages/main.blade.php` & `layouts/*` untuk pola UI.
5. `php artisan test --compact` — pastikan baseline hijau sebelum ubah kode.

---

## 9. Catatan untuk AI Agent

- Selalu baca `AGENTS.md` dan `.ai/rules` sebelum mengubah kode.
- Gunakan `php artisan make:` untuk file baru.
- Setelah ubah PHP: `vendor/bin/pint --format agent`.
- Build frontend setelah ubah Blade/JS/CSS.
- Tools Boost (`database-query`, `database-schema`, `search-docs`, `get-absolute-url`) bila relevan.

---

## 10. Pola CRUD Livewire (Referensi: Manajemen Pengguna)

Ini adalah **pola kanonik** untuk fitur CRUD di project ini. Segala CRUD baru
(user, role, post, dll) **wajib mengikuti konsep yang sama** agar konsisten.
Referensi utama: `app/Livewire/MasterData/Pengguna/MainIndex.php` +
`resources/views/livewire/master-data/pengguna/main-index.blade.php`.
Contoh kedua (akses admin + operator, tanpa password/role):
`app/Livewire/MasterData/Proyek/MainIndex.php` +
`resources/views/livewire/master-data/proyek/main-index.blade.php`.
`kode_proyek` dibuat otomatis (`PRJ001`…`PRJ999`, increment) via `generateKodeProyek()`
di `doCreate` — tidak diinput user & tidak berubah saat edit.
Contoh ketiga (**main‑detail + CRUD child** dengan route param & **input Rupiah
terformat**): `app/Livewire/MasterData/ProyekPekerja/MainIndex.php` +
`resources/views/livewire/master-data/proyek-pekerja/main-index.blade.php`.
Contoh keempat (CRUD + **modal select referensi**): `Penggajian/MainIndex.php` +
`resources/views/livewire/penggajian/main-index.blade.php` (+ `ProyekPickerModal`).
Pola modal referensi dijelaskan di §10.10.

### 10.1 Struktur class component

- Namespace `App\Livewire\MasterData\<Fitur>\...`, class `MainIndex` (atau
  `<Fitur>Index`). View terpisah di `resources/views/livewire/master-data/<fitur>/...`.
- Layout diikat via `#[Layout('layouts.app')]` (admin). Jangan pakai `#[Layout]`
  ganda (class + method) — method menang & bisa menunjuk view tak ada.
- Semua properti **wajib di-type** (`bool`/`array`/`?Model`/`string`/`?string`) dan
  method void pakai `: void`, `render(): View`.

### 10.2 State & form (pola `state` array)

- Satu properti `public array $state = []` menampung field form (bukan properti
  `$name`, `$email`, ... per-field). Bind di view dengan `wire:model="state.name"`.
- Template awal disimpan di `#[Locked] public array $params`; `showForm()` me-reset
  `state` ke `params`.
- Properti yang **tidak boleh diubah dari client** (form open/close flag, data statis,
  baris sedang diedit) ditandai `#[Locked]` agar `wire:model` client tak bisa memanipulasi.
- `editData` (model sedang diedit) juga `#[Locked]`; diisi lewat method server
  (`doEdit`), bukan dari client.

### 10.3 Otorisasi (defense-in-depth)

Livewire **tidak menjalankan `mount()` pada request AJAX** (hanya saat load halaman).
Maka guard akses HARUS ada di `mount()` **dan** di tiap method aksi mutasi.

**Aturan:** pengambilan informasi user saat ini **selalu** lewat `MainHelper`, bukan
`Auth::user()` langsung. Cek admin pakai `(new MainHelper)->userData()->isAdmin()`
(`userData()` mereturn `User` ter-auth; `isAdmin()` ada di `App\Models\User`).

```php
private function ensureCanManage(): void
{
    abort_unless((new MainHelper)->userData()->isAdmin(), 403);
}
// dipanggil di mount(), doCreate(), doEdit(), doUpdate(), doDelete()
// id user ter-auth: (new MainHelper)->userData()->id  (bukan Auth::id())
```
- Varian akses: bila fitur juga boleh diakses operator (mis. Proyek), ganti cek
  `isAdmin()` dengan `isAdmin() || isOperator()` (atau method helper serupa di `User`).

### 10.4 Siklus aksi (tambah/ubah/hapus)

- `showForm(bool $open, bool $edit = false)` — buka/tutup form, isi `state` dari `editData`
  bila edit.
- `actionForm()` — router: jika `editData` ter-set → `doUpdate()`, else `doCreate()`.
- `doCreate()` / `doUpdate()` — `validate()` pada `state.*`, lalu tulis DB di dalam
  `DB::transaction(fn () => ...)` (commit/rollback otomatis, hindari leak transaksi).
  Pakai `Hash::make()` untuk password. `syncRoles()` untuk spatie role.
- `doDelete(int $id)` — dengarkan event `#[On('doDelete')]`; guard self-delete
  (`if ($id === (new MainHelper)->userData()->id) return;`), hapus dalam `DB::transaction`.
- Notifikasi via `(new MainHelper)->doAlert($this, 'success'|'info'|'warning'|'error', $msg)`
  → memicu event `toast` yang dirender oleh `Toast.fire` (lihat `resources/js/app.js`).

### 10.5 Sorting & filter (URL-driven)

Gunakan `#[Url(except: '')]` agar state bisa di-share via URL:

```php
#[Url(except: '')] public ?string $search = '';
#[Url(except: '')] public string $order_by = 'created_at';
#[Url(except: '')] public string $order_type = 'DESC';
```

- **Whitelist** kolom sort (`private const ALLOWED_SORT_COLUMNS`) & normalisasi
  `order_type` ke `ASC`/`DESC` — `orderBy()` tidak escape nama kolom ⇒ rawan SQL
  injection bila `order_by` langsung dari user.
- `updatedSearch()` → `resetPage()` supaya filter reset ke halaman 1.
- `setOrderBy(string $field)` (listener `#[On('setOrderBy')]) validasi field ke whitelist.

### 10.6 View (konvensi Blade)

- Form: `<form wire:submit="actionForm">`, field `wire:model="state.xxx"`,
  error via `@error('state.xxx')`. Toggle form dengan `@if ($form) hidden @endif`.
- Tombol edit: `wire:click="doEdit({{ $item->id }})"` (pakai **id**, bukan uuid).
- Tombol hapus: `<button class="... delete-btn" data-id="{{ $item->id }}"
  data-target="{{ $componentAlias }}">`. Penghapusan tidak memanggil method
  langsung, tapi lewat konfirmasi `deleteSwal` (SweetAlert) → JS dispatch event.
- Tabel: `{{ $data->onEachSide(1)->links() }}` (paginator dari `paginate(10)`).

### 10.7 Wiring JS (delete)

- `resources/views/layouts/script.blade.php`: listener klik `.delete-btn` membaca
  `dataset.id` + `dataset.target`, lalu
  `deleteSwal(() => Livewire.dispatchTo(compTarget, 'doDelete', { id }))`.
- `resources/js/app.js`: `window.deleteSwal` (SweetAlert confirm → jalankan callback).
- `data-target` = alias komponen penuh, mis. `master-data.pengguna.main-index`
  (bukan suffix) agar `dispatchTo` sampai ke komponen.

### 10.8 Pola Input Rupiah / Angka Berformat (wajib untuk field uang)

Field uang (`decimal`) **wajib** pakai pola input berformat ribuan Indonesia, bukan
`<input type="number">` polos. Referensi: `tarif_harian`/`tarif_overtime` di
`master-data/proyek-pekerja/main-index.blade.php`.

- `<input type="text">` (bukan `number`) dengan `wire:model="state.field_text"`.
- `class` ditambah `ps-14 text-right` → ruang prefix `Rp.` di kiri & angka rata kanan.
- Prefix `Rp.` di `<div class="absolute inset-y-0 inset-s-0 flex items-center pointer-events-none z-20 ps-4">`
  (z-20 agar di atas input tanpa `z-index` konflik; lihat §4.6 untuk pola ikon kanan/error).
- Format & simpan via **Alpine** pada input yang sama:
  ```blade
  x-data
  x-on:input="const raw = $el.value.replace(/[^\d]/g, '');
              $wire.set('state.field', raw);
              $wire.set('state.field_text', new Intl.NumberFormat('id-ID').format(raw));"
  ```
- `state.field` menyimpan **raw digit** (string angka) → divalidasi `numeric`/`min:0`;
  `state.field_text` hanya tampilan (jangan divalidasi/disable‑mass‑assign).
- Di `params` sediakan `field_text => null` agar reset bersih; di `showForm()` saat edit,
  isi `state.field_text` dengan `number_format($editData->field, 0, ',', '.')`
  (null‑safe bila field nullable: `$item->field !== null ? number_format(...) : ''`).
- Tabel menampilkan nilai dengan `Rp {{ number_format((float) $item->field, 0, ',', '.') }}`.
- Field uang **nullable**: di view beri `required` hanya bila wajib; validasi `nullable|numeric`.

### 10.9 Testing

- Feature test mengikuti `tests/Feature/UserCrudTest.php` (Pengguna),
  `tests/Feature/ProyekCrudTest.php` (Proyek) &
  `tests/Feature/ProyekPekerjaCrudTest.php` (ProyekPekerja — child CRUD via route param) &
  `tests/Feature/PenggajianCrudTest.php` (Penggajian + listener picker):
  - `actingAs($admin)` + `Livewire::test(MainIndex::class)` (CRUD biasa) atau
    `Livewire::test(MainIndex::class, ['proyek' => $proyek->id])` untuk komponen yang
    menerima route param di `mount()` (child CRUD, mis. ProyekPekerja).
  - Isi via `->set('state.nama_periode', ...)` lalu `->call('actionForm')` / `->call('doEdit', $id)`
    / `->call('doDelete', $id)`.
  - Otorisasi: user tanpa role `->test(MainIndex::class)->assertForbidden()`. Untuk fitur
    admin+operator (Proyek, Penggajian), `operator` justru `->assertOk()` dan boleh CRUD.
  - Self-delete hanya relevan untuk resource bertipe user (Pengguna): `->call('doDelete', $admin->id)`
    lalu assert masih ada. Proyek tidak punya self-delete guard.
- `beforeEach` siapkan role spatie (`administrator`/`meggi`/`operator`).

> **Checklist CRUD baru:** class + view + route (`Route::livewire` FQCN) + JS delete
> wiring (bila ada hapus) + feature test. Jalankan `vendor/bin/pint` & `php artisan test`.

### 10.10 Pola Modal Select Referensi (wajib untuk pilih data FK/referensi)

Saat form/filter perlu memilih data referensi (Proyek, User, dll) yang jumlahnya bisa banyak,
**jangan pakai `<select>` native**. Buat **Livewire component modal picker** reusable.
Referensi kanonik: `ProyekPickerModal` + `proyek-picker-modal.blade.php`, dipakai di
`MasterData/Penggajian/MainIndex` + `main-index.blade.php`.

**Komunikasi parent → modal (buka):**
```php
// Parent (MainIndex):
public function openProyekPicker(string $context = 'form'): void
{
    $this->dispatch('openProyekPicker', context: $context); // context: 'form' | 'filter'
}
```
```php
// Child (ProyekPickerModal):
#[On('openProyekPicker')]
public function openModal(string $context = 'form'): void
{
    $this->context = $context;
    $this->search = '';
    $this->loadResults();
    $this->open = true;
}
```

**Komunikasi modal → parent (pilih):**
```php
// Child, saat user klik baris:
public function selectProyek(int $id): void
{
    $proyek = Proyek::findOrFail($id);
    $this->dispatch('proyekSelected',
        id: $proyek->id, kode: $proyek->kode_proyek, nama: $proyek->nama_proyek,
        context: $this->context);
    $this->closeModal();
}
```
```php
// Parent:
#[On('proyekSelected')]
public function handleProyekSelected(int $id, string $nama, string $context = 'form'): void
{
    if ($context === 'filter') {
        $this->filterProyekId = $id;
        $this->filterProyekName = $nama;
        return;
    }
    $this->state['proyek_id'] = $id;
    $this->selectedProyekId = $id;
    $this->selectedProyekName = $nama;
}
```

**Aturan & konvensi:**
- **Modal diedit via parent**: letakkan `<livewire:master-data.proyek.proyek-picker-modal />`
  di akhir view parent (bukan route sendiri).
- **View modal**: `<dialog class="modal modal-open">` (daisyUI), `wire:click.self="closeModal"`,
  tombol tutup ×, input `<input wire:model.live.debounce.300ms="search">`, tabel hasil dengan
  baris `wire:click="selectProyek($id)"`. Kolom modal tampilkan data yang membantu identifikasi
  (mis. kode + nama + status).
- **Filter pencarian**: `where('status', AKTIF)->whereNull('deleted_at')` + LIKE pada kolom
  relevan (kode/nama/pemilik/lokasi). Debounce via `wire:model.live.debounce.300ms`.
- **State parent**: pasangan `#[Locked] public ?int $selectedXId` + `#[Locked] public ?string $selectedXName`
  untuk form; untuk filter tambah `#[Url(except: '')] public ?int $filterXId` (biar shareable URL)
  + `#[Locked] public ?string $filterXName` (display). Name diisi server-side (jangan dari client).
- **Field hidden** `<input type="hidden" wire:model="state.proyek_id">` tetap disimpan untuk
  validasi; input display (`readonly`) hanya menampilkan `$selectedXName`.
- **Validasi** tetap `required|exists:proyeks,id` di `state.proyek_id`.
- **Clear**: method `clearProyekSelection()` reset `state.proyek_id` + `selectedXId/Name` +
  `filterXId/Name` (atau terpisah per-konteks bila perlu).
- **Saat edit** (`showForm(true, true)`): isi `state.proyek_id` dari `editData->proyek_id` dan
  `selectedXName` dari `editData->proyek?->nama_proyek`.
- **Gunakan hanya untuk data referensi yang banyak/variabel**. Status enum (2 nilai) tetap pakai
  `<select>` native (lihat pola §10.6).
- **Testing**: parent listener bisa diuji langsung, mis.
  `->call('handleProyekSelected', id: $proyek->id, nama: $proyek->nama_proyek)`
  lalu `->assertSet('state.proyek_id', $proyek->id)` (lihat `PenggajianCrudTest`).
