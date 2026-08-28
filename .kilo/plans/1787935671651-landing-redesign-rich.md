# Rencana: Redesain Landing Page Publik (`/main`) — Rich / Mewah / Tech / Modern

## Konteks
Halaman `/main` (`resources/views/pages/⚡main.blade.php`, layout `layouts::public`) saat ini
"rata": kartu flat `bg-base-200`, ikon emoji, tanpa depth. Stack: Tailwind v4 + daisyUI 5
(theme `corporate` default & `luxury` dark, toggle via Alpine `themeSwitcher`), Motion
(`resources/js/motion/*`), Swiper (`resources/js/components/swiper.js`), Alpine, Lucide
(`mallardduck/blade-lucide-icons` → `<x-lucide-*>`, sudah dipakai di admin). `logo.png` ada di
`public/img/logo.png`.

Mesin animasi sudah data-attribute driven: tambah fungsi di `animations.js`, export, daftarkan
di `index.js` (`animations` map) dengan key `data-motion`. Semua animasi wajib menghormati
`prefersReducedMotion()` (sudah ada helper `visible()`).

## Keputusan (dari user)
- **Tone**: Adaptive — gradient + glass terlihat mewah di BOTH tema (corporate & luxury);
  toggle tema tetap ada. Tidak memaksa dark.
- **Logo**: navbar = `<img logo.png>` + teks `config('main_config.short_name')`. Hero pakai
  logo sebagai badge.
- **Dependency**: boleh tambah paket, tapi efek dicapai via Motion + Tailwind/daisyUI (tanpa
  paket berat) agar build stabil. Jika nanti ingin particle field, baru tambah `tsparticles`.

## Task (urutan)

### 1. Animasi Motion baru — `resources/js/motion/animations.js`
Tambah fungsi (impor `animate`, `inView`, `hover` dari `motion` sudah ada di atas):

- **`gradientPan(element, options)`** → `data-motion="gradient-pan"`: animasikan
  `backgroundPosition` `["0% 50%","100% 50%"]` loop linear (elemen punya gradient + `bg-[length:200%_200%]`).
- **`glowPulse(element, options)`** → `data-motion="glow-pulse"`: pulse `boxShadow` /
  `opacity` (contoh `boxShadow: ['0 0 0px ...','0 0 40px ...','0 0 0px ...']`) loop.
- **`countUp(element, options)`** → `data-motion="count-up"`: baca `data-motion-to` (number),
  `data-motion-decimals` (default 0), `data-motion-suffix` (mis. `"rb+"`, `"%"`). Pakai
  `animate(0, to, { onUpdate: v => el.textContent = format(v) })` di dalam `inView`. Format:
  `Math.round(v).toLocaleString('id-ID')` + suffix; jika decimals>0 `v.toFixed(decimals)`.
- **`tilt3d(element, options)`** → `data-motion="tilt-3d"`: pointer-follow 3D tilt. Tambah
  listener `pointermove` di elemen: hitung `rotateX/rotateY` dari posisi kursor (max ±8°),
  `animate` transform; `pointerleave` reset ke 0. Set `transform-style: preserve-3d` &
  `perspective` di parent. Hormati reduced-motion (skip).

Lalu daftarkan di `resources/js/motion/index.js` (bagian `animations` map + import):
```js
import { gradientPan, glowPulse, countUp, tilt3d } from "./animations";
// ...
"gradient-pan": gradientPan,
"glow-pulse": glowPulse,
"count-up": countUp,
"tilt-3d": tilt3d,
```

### 2. CSS utilitas — `resources/css/app.css`
Tambah (setelah `@theme`, plain CSS / `@layer components`):
- `.glass` / `.glass-card`: `bg-base-100/60 backdrop-blur-xl border border-base-300/60
  shadow-xl` (+ variant `.glass-card--primary` dengan `border-primary/30`).
- `.text-gradient`: `bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent`.
- `.bg-grid`: overlay grid halus `background-image: linear-gradient(...)` + radial mask
  (transparan di tepi), `pointer-events-none`.
- `.bg-aurora`: container blob gradient (dipakai dengan `gradient-pan`).
Pastikan tak bentrok dengan daisyUI. Gunakan warna `primary`/`secondary` (adaptif tema).

### 3. Navbar publik — `resources/views/layouts/public.blade.php`
Ganti teks wordmark (baris ~44) menjadi:
```blade
<a href="#home" class="btn btn-ghost text-xl font-bold normal-case gap-2">
    <img src="{{ asset('img/logo.png') }}" alt="{{ config('main_config.short_name') }}"
         class="h-8 w-8 rounded-lg" />
    <span>{{ config('main_config.short_name') }}</span>
</a>
```
Navbar sudah `bg-base-100/80 backdrop-blur` — biarkan (sudah glassy).

### 4. Redesain `resources/views/pages/⚡main.blade.php`
Pertahankan section & urutan; tingkatkan visual. Contoh perubahan konkret:

- **Background hero**: tambah layer dekoratif di dalam `<section class="relative">` (sebelum
  `.hero`): `.bg-grid` fixed, 2 blob `.bg-aurora` dengan `data-motion="scroll-parallax"`
  (sudah ada) + 1 blob `data-motion="gradient-pan"`, semua `-z-10 opacity-...`.
- **Hero badge**: ganti emoji badge jadi `<x-lucide-sparkles class="size-4">` + teks.
- **Heading**: pertahankan `data-motion="text-split"`; bungkus kata "Pintar" dengan
  `<span class="text-gradient">Pintar</span>`.
- **Visual hero (mockup)**: ubah `.mockup-window bg-base-300` → `.glass-card` (atau
  `.glass-card--primary`), tambah header logo kecil + `data-motion="glow-pulse"` tipis,
  pertahankan `data-motion="float-in"`. Tabel & chip stats dibiarkan (atau perhalus border).
- **Tombol CTA**: pertahankan `hover-lift`; tambah `press-scale` pada tombol primer.
- **Stats row**: angka dapatkan `data-motion="count-up"`:
  - `10rb+` → `data-motion-to="10000" data-motion-suffix="rb+"`
  - `500+` → `data-motion-to="500" data-motion-suffix="+"`
  - `99,9%` → `data-motion-to="99.9" data-motion-decimals="1" data-motion-suffix="%"`
  (simpan teks label statis di bawahnya).
- **Fitur**: kartu `bg-base-200` → `.glass-card` + `data-motion="tilt-3d"` + `hover-lift`.
  Ganti kotak emoji `📝⚡📊🔒👥📱` dengan ikon Lucide di container gradient:
  `x-lucide-clipboard-pencil`, `x-lucide-zap`, `x-lucide-file-bar-chart`,
  `x-lucide-shield-check`, `x-lucide-users`, `x-lucide-smartphone`. Pertahankan `stagger`.
- **Cara kerja**: langkah `step` dibiarkan; bungkus tiap item `.glass-card` tipis +
  `data-motion="reveal"`.
- **Tech stack (Swiper)**: kartu slide → `.glass-card`; ganti emoji dengan Lucide generik
  (`x-lucide-server`, `x-lucide-bolt`, `x-lucide-palette`, `x-lucide-feather`, `x-lucide-bot`,
  `x-lucide-database` dll) — atau pertahankan emoji jika ingin simpel. Tambah `glow-pulse`
  lembut opsional. Swiper init tetap di `swiper.js` (tidak diubah).
- **Testimoni**: kartu → `.glass-card` + `data-motion="reveal"` (sudah `reveal`), avatar
  pakai `ui-avatars` (sudah) — opsi ganti ke `img/logo.png` kecil, tapi default initials aman.
- **CTA**: pertahankan `blur-scale` + `bg-primary`; tambah border gradient/`glow-pulse` &
  logo kecil di atas judul.

### 5. Build & verifikasi
- `npm run build` (atau `npm run dev`) — pastikan Vite compile (CSS kustom & JS Motion baru).
- `vendor/bin/pint --format agent` (PHP blade tidak perlu, tapi jalankan untuk aman).
- Cek `php artisan test --compact` (test `ExampleTest` GET `/` harus tetap 200; AuthTest tetap lulus).

## Validasi (manual)
- Buka `http://proyek.test` (Herd). Pastikan: navbar menampilkan logo+teks; hero gradient/glass
  mewah di tema corporate & luxury (toggle); animasi masuk (text-split, float-in, stagger,
  count-up pada angka stats, glow-pulse, tilt-3d kartu saat hover) berjalan; reduced-motion
  (`prefers-reduced-motion`) mematikan animasi tanpa layout rusak.
- Jika perubahan tidak muncul: jalankan `npm run dev`/`npm run build` (Vite).

## Risiko / Catatan
- `count-up` memformat angka; pastikan `data-motion-to` numerik & suffix cocok (termasuk koma
  desimal id-ID).
- Semua animasi baru wajib `prefersReducedMotion()` guard (copy pola `fadeUp`).
- Jangan ubah `composer.json`/`package.json` (tidak ada paket baru yang diperlukan).
- Pastikan `x-lucide-*` nama valid (cek di `vendor/mallardduck/blade-lucide-icons` jika ragu);
  fallback: inline SVG.
- `bg-grid`/`bg-aurora` pakai `primary`/`secondary` → otomatis adaptif tema.

## Open question (opsional)
Mau ditambahkan **particle/grid animasi background** via paket (`tsparticles`) untuk kesan
tech lebih kuat, atau cukup CSS gradient + Motion yang ada?
