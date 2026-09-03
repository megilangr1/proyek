# Refactor: Konsolidasi Flow Proyek → Halaman Detail Tunggal

> Status: PLAN. Baca sebelum eksekusi. Tiap fase harus selesai + test hijau sebelum lanjut.

## Tujuan

Flow saat ini terlalu banyak lompat halaman: Proyek → `/proyek/{id}/pekerja` → `/proyek/{id}/pengeluaran` → `/penggajian` (global) → `/penggajian/{id}/detail`.

Refactor jadi **satu halaman detail proyek** dengan tab + CRUD inline, dan rincian per-pekerja penggajian tetap sub-halaman.

## Flow Baru

```
/proyek                                  (index — list + aksi "Detail")
  └─ /proyek/{proyek}/detail             (SATU halaman, tab)
        ├─ tab: Info Proyek              (ringkasan + edit)
        ├─ tab: Pekerja                  (CRUD inline, reusable panel)
        ├─ tab: Pengeluaran              (CRUD inline, reusable panel)
        └─ tab: Penggajian               (list periode per-proyek + CRUD, gated)
              └─ /penggajian/{id}/detail (SUB-halaman, existing, 3 modal: pencatatan/summary/bayar)
```

## Keputusan (konfirmasi user)

| # | Keputusan |
|---|-----------|
| 1 | Parent detail + child Livewire component per tab (bukan monolit raksasa) |
| 2 | Rincian penggajian per-pekerja tetap sub-halaman `/penggajian/{id}/detail` (existing) |
| 3 | **Hapus route lama**: `proyek.pekerja.index`, `proyek.pengeluaran.index`, `penggajian.index` (global) |
| 4 | Gating penggajian: butuh ≥1 pekerja AKTIF; enforce backend + UI disable |

## Arsitektur Livewire

- **Parent** `app/Livewire/MasterData/Proyek/MainDetail.php` — `#[Layout('layouts.app')]`, resolve `$proyek`, state tab `#[Url]`, render child panel per tab.
- **Child panels** — pakai class existing, `#[Layout]` DIHAPUS (jadi nested panel, bukan full-page):
  - `MasterData/ProyekPekerja/MainIndex.php` → panel Pekerja
  - `MasterData/ProyekPengeluaran/MainIndex.php` → panel Pengeluaran
  - `Penggajian/MainIndex.php` → panel Penggajian (refactor global → per-proyek, proyek implisit, buang picker)
- Child dipanggil via `<livewire:master-data.proyek-pekerja.main-index :key="...">` dalam parent view.
  Catatan: `#[Layout]` hanya aktif saat full-page route mount — aman bila dipakai sebagai nested child.

---

## FASE EXECUTION (urut, tiap fase test hijau)

### FASE 1 — Child panels Pekerja & Pengeluaran (tanpa layout)

File disentuh:
- `app/Livewire/MasterData/ProyekPekerja/MainIndex.php` — hapus `use ...Layout` + `#[Layout('layouts.app')]`
- `resources/views/livewire/master-data/proyek-pekerja/main-index.blade.php` — buang `<x-main.page-header>`, hero card, tabs; sisakan form card + search + table + pagination
- `app/Livewire/MasterData/ProyekPengeluaran/MainIndex.php` — hapus `#[Layout]`
- `resources/views/livewire/master-data/proyek-pengeluaran/main-index.blade.php` — buang page-header/hero/tabs; sisakan form + search + table

Verifikasi: `php artisan test --compact` — `ProyekPekerjaCrudTest` & `ProyekPengeluaranCrudTest` tetap lintas (`Livewire::test(MainIndex::class, ['proyek'=>$id])` mount langsung). Sesuaikan assert bila rusak.

### FASE 2 — Parent detail proyek + route + view parent

File dibuat:
- `app/Livewire/MasterData/Proyek/MainDetail.php` (parent)
- `resources/views/livewire/master-data/proyek/main-detail.blade.php` (hero + tabs + render child)

File diubah:
- `routes/web.php` — tambah `proyek.detail`; (route lama dihapus di FASE 4)
- `resources/views/livewire/master-data/proyek/main-index.blade.php` — popover Aksi: 3 link → 1 link "Detail Proyek" `proyek.detail`

### FASE 3 — Panel Penggajian per-proyek (+ gating)

File diubah:
- `app/Livewire/Penggajian/MainIndex.php` — refactor global → per-proyek:
  - `mount($proyek)` resolve; buang `#[Layout]`
  - buang `ProyekPickerModal` logic: `openProyekPicker`, `clearProyekSelection`, `handleProyekSelected`, `selectedProyek*`, `#[Url] filterProyekId`
  - `state.proyek_id` implicit/fixed; form tak tampilkan picker proyek
  - query `ProyekPenggajian::where('proyek_id', $this->proyekData->id)`
  - `doCreate/doUpdate`: guard gating pekerja AKTIF
- `resources/views/livewire/penggajian/main-index.blade.php` — buang page-header, field picker proyek, `<livewire:...proyek-picker-modal>`; jadi panel

### FASE 4 — Hapus route lama + sidebar + picker orphan

File diubah:
- `routes/web.php` — hapus `proyek.pekerja.index`, `proyek.pengeluaran.index`, `penggajian.index`. Pertahankan `penggajian.detail` + auth group.
- `resources/views/layouts/sidebar.blade.php` — hapus link "Penggajian Proyek" global.
- Hapus file orphan `ProyekPickerModal` (komponen + view) bila tak dipakai di flow baru. Prioritas: hapus route dulu, cek picker tak dirujuk, baru hapus.

Verifikasi: cek `route:list` bersih, `grep` tidak ada sisa `proyek.pekerja.index` / `penggajian.index` / `proyek-picker-modal`.

### FASE 5 — Tests

- Buat `tests/Feature/ProyekDetailTest.php`: parent admin/operator ok, user-norole forbidden, `setTab` switch, render child.
- Update `tests/Feature/PenggajianCrudTest.php`: hapus test picker/filter global; test create pakai `proyek` param; tambah test gating (tanpa pekerja → error).
- Update `ProyekPekerjaCrudTest` / `ProyekPengeluaranCrudTest` bila assert rusak akibat layout dihapus.

### FASE 6 — Docs & rule & final verify

- Update `.ai/rules/livewire.md`: catat penggajian per-proyek implisit (tanpa picker global); pola child-panel tanpa layout.
- Update `PROJECT.md` §4.1/§4.8/§7 sesuai flow baru.
- `vendor/bin/pint --format agent`
- `php artisan test --compact` hijau penuh
- `npm run build` (karena sentuh view)
- update file ini → Status: DONE

---

## Verifikasi akhir (checklist)

- [ ] `/proyek` → aksi "Detail Proyek" → `/proyek/{id}/detail`
- [ ] tab Pekerja CRUD inline jalan (old child)
- [ ] tab Pengeluaran CRUD inline jalan
- [ ] tab Penggajian: list per-proyek; tombol tambah disabled + pesan bila tak ada pekerja aktif
- [ ] `/penggajian/{id}/detail` (3 modal) tetap akses dari panel penggajian
- [ ] route lama 404 / hilang
- [ ] sidebar tanpa "Penggajian Proyek" global
- [ ] `php artisan test --compact` hijau
- [ ] `vendor/bin/pint --format agent` bersih
- [ ] `npm run build` sukses

## Resiko / catatan

- Child pakai `WithPagination` — pastikan `wire:key` unik tiap tab biar pagination tak bentrok.
- Hapus `#[Layout]` dari child: pastikan tak ada `Route::livewire(...)` yang masih menunjuk langsung ke child (dihapus di FASE 4) — kalau masih ada, child render tanpa layout (layout kosong → rusak). Urutan FASE 2/3 bikin child, FASE 4 hapus route. Aman karena di FASE 4 test cek.
- `ProyekPickerModal` orphan: hapus hanya setelah dipastikan tak dirujuk.
