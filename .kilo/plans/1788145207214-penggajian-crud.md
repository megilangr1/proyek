# Plan: CRUD ProyekPenggajian (Payroll) — route top-level

## Konteks
- `ProyekPenggajian` (`app/Models/ProyekPenggajian.php`) masih KOSONG (no fillable/cast/relasi/factory).
  User sudah buat model + migration `2026_08_31_090805_create_proyek_penggajians_table.php`.
- Migration `proyek_penggajians`: `proyek_id` (foreignId tanpa constraint), `nama_periode` (string),
  `periode_mulai` (date), `periode_selesai` (date), `jam_kerja` (tinyInteger), `keterangan` (text nullable),
  `status` (tinyInteger), `softDeletes` + timestamps.
- Keputusan user:
  1. **Route top-level sendiri**: `/penggajian` (name `penggajian.index`) — BUKAN child `/proyek/{proyek}/...`.
     Karena top-level, `proyek_id` dipilih di **form** via dropdown Proyek (tidak dari route param).
  2. **Enum baru `StatusPenggajian`** (AKTIF=1, NONAKTIF=2), `status` di-cast ke enum.
  3. **Kelola kolom existing dulu**. Tidak ada kolom uang ⇒ **tidak pakai pola input Rupiah**.
     `jam_kerja` = input angka (jam). Turunan per-pegawai (detail gaji per worker) **ditunda** (out of scope).
- Pola ikut kanonik PROJECT.md §10 (sama seperti `ProyekPekerja`, tapi tanpa route param & dengan
  dropdown `proyek_id`).

## Langkah implementasi

### 1. Enum baru — `app/Enum/StatusPenggajian.php`
Salin pola `app/Enum/StatusPekerja.php`: `case AKTIF = 1; case NONAKTIF = 2;` + `label()` + `toSelectArray()`.

### 2. Lengkapi Model — `app/Models/ProyekPenggajian.php`
- `#[Fillable(['proyek_id','nama_periode','periode_mulai','periode_selesai','jam_kerja','keterangan','status'])]`
- `use HasFactory, SoftDeletes;`
- `protected $casts = ['periode_mulai'=>'date','periode_selesai'=>'date','jam_kerja'=>'integer','status'=>StatusPenggajian::class];`
- Relasi: `public function proyek(): BelongsTo { return $this->belongsTo(Proyek::class); }`

### 3. Relasi balik — `app/Models/Proyek.php`
Tambah: `public function penggajians(): HasMany { return $this->hasMany(ProyekPenggajian::class); }`
(import `ProyekPenggajian` & `HasMany` bila belum).

### 4. Migration FK — `database/migrations/xxxx_add_proyek_foreign_key_to_proyek_penggajians_table.php`
`php artisan make:migration add_proyek_foreign_key_to_proyek_penggajians_table --table=proyek_penggajians`
lalu `Schema::table('proyek_penggajians', fn ($table) => $table->foreign('proyek_id')->references('id')->on('proyeks')->onDelete('cascade'));`
Drop FK di `down()`. Jalankan `php artisan migrate` (MySQL/XAMPP nyala).

### 5. Factory — `database/factories/ProyekPenggajianFactory.php`
`definition()`: `proyek_id => Proyek::factory()`, `nama_periode => fake()->word()`, `periode_mulai/selesai`
  (date, selesai >= mulai), `jam_kerja => fake()->numberBetween(1,255)`, `keterangan => nullable`,
  `status => StatusPenggajian::AKTIF`.

### 6. Component — `app/Livewire/MasterData/Penggajian/MainIndex.php`
Buat via `php artisan make:livewire MasterData/Penggajian/MainIndex`. Lalu isi ikut pola `ProyekPekerja`
(tanpa route param, +dropdown proyek):
- `#[Layout('layouts.app')]`, `use WithPagination`.
- `public array $state = [];` + `#[Locked] public array $params` berisi field di atas (`status => StatusPenggajian::AKTIF->value`).
- `#[Locked] public bool $form = false;`, `#[Locked] public ?ProyekPenggajian $editData = null;`
- `#[Locked] public array $statusOptions = [];` → `StatusPenggajian::toSelectArray()` di `mount()`.
- `mount()`: `ensureCanManage()` (admin||operator via MainHelper) — TIDAK ada param proyek.
- `ALLOWED_SORT_COLUMNS = ['nama_periode','periode_mulai','periode_selesai','jam_kerja','created_at','id']`;
  `#[Url] $search/$order_by/$order_type` (sama pola).
- `render()`: query `ProyekPenggajian::query()` + filter search (nama_periode/keterangan) + **filter proyek**
  (`#[Url] public ?int $filterProyek = null;` → `when($this->filterProyek, fn($q)=>$q->where('proyek_id',$this->filterProyek))`)
  + orderBy + `paginate(10)`. Kirim `'data'=>$data, 'proyeks'=>Proyek::orderBy('nama_proyek')->get()`.
- `showForm/actionForm/doCreate/doEdit/doUpdate/doDelete` = pola `ProyekPekerja`, beda:
  - Di `showForm` edit: isi `state.proyek_id`, `state.nama_periode`, `state.periode_mulai`
    (`->format('Y-m-d')`), `state.periode_selesai`, `state.jam_kerja`, `state.keterangan`,
    `state.status` (value). Reset `editData` bila bukan edit.
  - Validasi `doCreate`/`doUpdate`:
    `state.proyek_id => 'required|exists:proyeks,id'`,
    `state.nama_periode => 'required|string'`,
    `state.periode_mulai => 'required|date'`,
    `state.periode_selesai => 'required|date|after_or_equal:state.periode_mulai'`,
    `state.jam_kerja => 'required|integer|min:0|max:255'`,
    `state.keterangan => 'nullable|string'`,
    `state.status => ['required', Rule::enum(StatusPenggajian::class)]`.
  - `doDelete(#[On('doDelete')] int $id)` guard `ensureCanManage()`, soft delete.
- `setOrderBy(#[On('setOrderBy')] string $field)` + `updatedSearch()` = salin pola.

### 7. View — `resources/views/livewire/master-data/penggajian/main-index.blade.php`
Ikut struktur `proyek-pekerja/main-index.blade.php` (tanpa card rincian Proyek, karena top-level):
- `<x-main.page-header title="Penggajian Proyek">` + tombol "Tambah Data" (`wire:click="showForm(true)"`).
- **Form** (toggle `$form`): field `proyek_id` = `<select>` berisi `@foreach ($proyeks as $p)<option value="{{ $p->id }}">{{ $p->nama_proyek }}</option>@endforeach`
  (pakai pola error svg kanan spt status). Lalu `nama_periode`, `periode_mulai` (date), `periode_selesai` (date),
  `jam_kerja` (number, min 0 max 255), `keterangan` (textarea), `status` (select `statusOptions`).
  Semua `wire:model="state.xxx"`, error via `@error('state.xxx')`, submit `wire:submit="actionForm"`.
- **Filter proyek** (di atas tabel): `<select wire:model.live="filterProyek">` (Pilih Proyek + list proyeks) + search box (spt ProyekPekerja).
- **Tabel**: No, Proyek (`$item->proyek?->nama_proyek`), Periode (`nama_periode`), Mulai, Selesai,
  Jam Kerja, Status badge (`StatusPenggajian::AKTIF`→badge-success), Aksi (Edit / Hapus `delete-btn`
  `data-target="master-data.penggajian.main-index"`).
- Pagination `$data->onEachSide(1)->links()`.
- daisyUI + `data-motion`, ikon `<x-lucide-*>`, adaptif tema.

### 8. Route — `routes/web.php`
Dalam grup `auth` (bukan prefix `master-data` agar route top-level):
```php
Route::livewire('/penggajian', PenggajianMainIndex::class)->name('penggajian.index');
```
Import `use App\Livewire\MasterData\Penggajian\MainIndex as PenggajianMainIndex;`.
(Tidak ada tombol di list Proyek — beda dg ProyekPekerja yang child.)

### 9. Nav (opsional tapi disarankan) — `resources/views/layouts/sidebar.blade.php`
Tambah item menu menuju `route('penggajian.index')` (mirip item yg sudah ada) agar halaman terjangkau
selain lewat URL langsung. Cek pola item sidebar yg ada sebelum edit.

### 10. Test — `tests/Feature/PenggajianCrudTest.php`
Salin pola `ProyekPekerjaCrudTest.php` (TANPA argumen route param di `Livewire::test`):
- `beforeEach` role spatie (administrator/meggi/operator).
- admin & operator `->test(MainIndex::class)->assertOk()`; user tanpa role `->assertForbidden()`.
- admin create: `set('state.proyek_id', $proyek->id)`, `set('state.nama_periode', 'Minggu 1')`,
  `set('state.periode_mulai','2026-01-01')`, `set('state.periode_selesai','2026-01-07')`,
  `set('state.jam_kerja', 40)`, `set('state.status', 1)`, `call('actionForm')` → assert DB.
- validate: `periode_selesai` < `periode_mulai` ⇒ error; `jam_kerja` bukan angka ⇒ error.
- admin edit & soft delete (`doEdit`/`doDelete`) → assert.

## Risiko / catatan
- Top-level ⇒ `mount()` tanpa param ⇒ **tidak ada benturan nama properti** spt ProyekPekerja.
- `jam_kerja` tinyInteger (max 255) ⇒ validasi `max:255` agar tidak溢出.
- Tidak ada kolom uang ⇒ **tidak pakai pola Rupiah** (berbeda dr ProyekPekerja).
- Turunan per-pegawai (detail gaji per worker) **out of scope** — catat sbg TODO/next step.
- `exists:proyeks,id` butuh proyek valid dibuat di test (factory).

## Validasi
1. `vendor/bin/pint --format agent` (PHP).
2. `php artisan migrate` (MySQL/XAMPP nyala) → FK.
3. `php artisan route:list` → pastikan `penggajian.index` muncul (top-level `/penggajian`).
4. `npm run build` (perubahan Blade).
5. `php artisan test --compact` (utamakan `PenggajianCrudTest`).
6. Cek visual: buka `/penggajian` → tambah/edit/hapus; pilih Proyek di form; filter proyek; adaptif tema.
