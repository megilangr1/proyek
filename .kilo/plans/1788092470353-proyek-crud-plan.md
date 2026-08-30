# Plan: CRUD Data Proyek

## Context

- Model `App\Models\Proyek` sudah ada (`app/Models/Proyek.php`) dengan `#[Fillable]`:
  `kode_proyek`, `nama_proyek`, `pemilik`, `lokasi` (text), `tanggal_mulai`,
  `tanggal_selesai`, `status`. Cast: `tanggal_*` → `date`, `status` → `App\Enum\StatusProyek`.
- Migration `create_proyeks_table` sudah ada: `kode_proyek` unique, `status` tinyInteger
  default 1, `softDeletes()` aktif.
- Enum `App\Enum\StatusProyek: int` → `AKTIF = 1`, `NONAKTIF = 2`, punya
  `label()` dan `toSelectArray()` (value/label).
- **Belum ada:** `ProyekFactory` (dibutuhkan test), Livewire component, view, route, menu, test.
- Pola kanonik ikuti `app/Livewire/MasterData/Pengguna/MainIndex.php` +
  `resources/views/livewire/master-data/pengguna/main-index.blade.php` (lihat `PROJECT.md` §10).

## Decisions (dari klarifikasi user)

1. **Akses**: Admin + Operator. Guard = `isAdmin() || isOperator()` (user ter-auth via
   `MainHelper->userData()`). User tanpa role → `403`. (Berbeda dari Pengguna yang admin-only.)
2. **Penempatan**: ikut grup `master-data` (konsisten). Route `/master-data/proyek`,
   name `proyek.index`, namespace `App\Livewire\MasterData\Proyek\MainIndex`, view
   `resources/views/livewire/master-data/proyek/main-index.blade.php`, menu "Data Proyek"
   di bawah `<details> Master Data` di sidebar.
3. **Status form**: `<select>` daisyUI biasa (bukan Tom Select) diisi dari
   `StatusProyek::toSelectArray()` — enum kecil & fixed.
4. **Validasi tanggal**: `tanggal_selesai` wajib `after_or_equal:tanggal_mulai`.
5. **Tidak ada self-delete guard** (Proyek bukan user). Guard hanya cek otorisasi.

## Task list (urutan eksekusi)

1. **Migrasi & factory**
   - Pastikan migration jalan di dev: `php artisan migrate` (di test otomatis via `RefreshDatabase`).
   - Buat factory: `php artisan make:factory ProyekFactory --model=Proyek`. `definition()`
     pakai `fake()`: `kode_proyek => strtoupper(fake()->unique()->bothify('PRJ-####'))`,
     `nama_proyek`, `pemilik`, `lokasi` (paragraph/word), `tanggal_mulai`/`tanggal_selesai`
     (`fake()->date()`), `status => 1`.

2. **Livewire component** — `app/Livewire/MasterData/Proyek/MainIndex.php`
   - `#[Layout('layouts.app')]`, `use WithPagination`, `use App\Helpers\MainHelper`,
     `use App\Models\Proyek`, `use App\Enum\StatusProyek`.
   - Properti: `#[Locked] bool $form`, `array $state`, `#[Locked] array $params`
     (default `kode_proyek/nama_proyek/pemilik/lokasi => null`, `tanggal_mulai/
     tanggal_selesai => null`, `status => StatusProyek::AKTIF->value`),
     `#[Locked] ?Proyek $editData`, `#[Url] $search/$order_by/$order_type`,
     `public array $statusOptions = []`.
   - `ALLOWED_SORT_COLUMNS = ['kode_proyek','nama_proyek','pemilik','tanggal_mulai',
     'tanggal_selesai','created_at','id']`.
   - `ensureCanManage()`: `abort_unless((new MainHelper)->userData()->isAdmin()
     || (new MainHelper)->userData()->isOperator(), 403);` — dipanggil di `mount()`,
     `doCreate()`, `doEdit()`, `doUpdate()`, `doDelete()`.
   - `mount()`: `ensureCanManage()`; `state = params`; `statusOptions =
     StatusProyek::toSelectArray()`.
   - `render()`: query `Proyek::query()` dengan `search` (LIKE di `kode_proyek`,
     `nama_proyek`, `pemilik`, `lokasi`), order by whitelist, `paginate(10)`.
   - `showForm()`/`actionForm()`/`doCreate()`/`doEdit()`/`doUpdate()`/`doDelete()`
     mengikuti pola Pengguna, tanpa `password`/`roles`/`syncRoles`. Validasi:
     - create: `kode_proyek => required|string|unique:proyeks,kode_proyek`,
       `nama_proyek => required|string`, `pemilik => required|string`,
       `lokasi => required|string`, `tanggal_mulai => required|date`,
       `tanggal_selesai => required|date|after_or_equal:tanggal_mulai`,
       `status => required|integer|in:1,2` (atau `Rule::enum(StatusProyek::class)`).
     - update: `unique:proyeks,kode_proyek,'.$editData->id`; `tanggal_*` & `status` sama.
   - `setOrderBy()`/`updatedSearch()` seperti Pengguna.
   - Semua properti di-type, method `: void` / `render(): View`, Pint-clean.

3. **View** — `resources/views/livewire/master-data/proyek/main-index.blade.php`
   - Tiru struktur pengguna: `<x-main.page-header title="Data Proyek">` + tombol
     "Tambah Data" (`wire:click="showForm(true)"`).
   - Form card: field `kode_proyek`, `nama_proyek`, `pemilik`, `lokasi` (textarea),
     `tanggal_mulai` (type=date), `tanggal_selesai` (type=date), `status` (`<select>`
     diisi `@foreach ($statusOptions as $value => $label)`). `wire:model="state.xxx"`,
     error via `@error('state.xxx')`, `@if ($form) hidden @endif` toggle.
   - Tabel: kolom No., Kode, Nama, Pemilik, Lokasi, Tgl Mulai, Tgl Selesai, Status
     (badge: `AKTIF` → `badge-success`, `NONAKTIF` → `badge-ghost`, label via
     `$item->status->label()`), Aksi. Header sortable pakai `<x-table.th ...>`.
   - Tombol edit `wire:click="doEdit({{ $item->id }})"`.
   - Tombol hapus: `class="... delete-btn"` `data-id="{{ $item->id }}"`
     `data-target="master-data.proyek.main-index"` (alias TEPAT, tanpa prefix `admin.`).
   - Pagination `{{ $data->onEachSide(1)->links() }}`.

4. **Routing** — `routes/web.php`
   - Import `use App\Livewire\MasterData\Proyek\MainIndex as ProyekMainIndex;`.
   - Dalam `Route::prefix('master-data')` group tambahkan
     `Route::livewire('/proyek', ProyekMainIndex::class)->name('proyek.index');`
     (sudah dalam `Route::middleware('auth')` group).

5. **Sidebar** — `resources/views/layouts/sidebar.blade.php`
   - Tambah `<li><a href="{{ route('proyek.index') }}" wire:current="menu-active"
     wire:navigate>Data Proyek</a></li>` di dalam `<ul>` details "Master Data".

6. **Feature test** — `tests/Feature/ProyekCrudTest.php` (mirip `UserCrudTest`)
   - `beforeEach`: `Role::firstOrCreate` administrator/meggi/operator.
   - `actingAs` admin (role administrator): bisa view `route('proyek.index')` (assertOk +
     assertSee 'Data Proyek'), create (cek DB + `has`/`status`), edit, soft-delete
     (`assertSoftDeleted`).
   - Operator (role operator): `Livewire::actingAs($operator)->test(MainIndex::class)
     ->assertOk()` dan boleh create/edit/delete (sesuai keputusan akses).
   - User tanpa role: `->test(MainIndex::class)->assertForbidden()`.
   - Pakai `Proyek::factory()`.

7. **Verifikasi**
   - `vendor/bin/pint --format agent`
   - `php artisan test --compact` (semua hijau)
   - `npm run build` (tidak ada error; UI pakai daisyUI/`<x-*`)

## Risks / known issues

- **BUG preexisting (di luar scope, tapi temuan)**: tombol hapus di
  `resources/views/livewire/master-data/pengguna/main-index.blade.php` pakai
  `data-target="admin.master-data.pengguna.main-index"`, padahal alias komponen asli
  `master-data.pengguna.main-index` (tanpa `admin.`) → delete Pengguna saat ini diam
  (tidak jalan). Proyek HARUS pakai alias benar (`master-data.proyek.main-index`).
  Opsional: perbaiki juga `data-target` Pengguna agar delete jalan.
- `config/livewire.php` `component_namespaces` masih menunjuk `views/admin`, `views/pages`,
  `views/auth` yang sudah dihapus — tidak error, tapi bisa dibersihkan.
- Pastikan `php artisan migrate` sudah dijalankan di environment lokal (tabel `proyeks`
  ada) sebelum buka menu di browser.

## Open questions

- Tidak ada yang menggantung; asumsi penempatan `master-data` & select status biasa
  bisa disesuaikan bila user mau top-level menu / Tom Select.
