# Plan: Main‑Detail Proyek + CRUD ProyekPekerja (route terpisah)

## Konteks
- `Proyek` (`app/Livewire/MasterData/Proyek/MainIndex.php`) sudah jadi master list + CRUD Proyek.
- User minta **main‑detail**: dari list Proyek bisa membuka halaman rincian Proyek yang berisi
  info lengkap Proyek + **full CRUD ProyekPekerja** (list/tambah/edit/hapus) untuk proyek tersebut.
- Keputusan user:
  1. UI detail = **route/component terpisah** (bukan modal di MainIndex).
  2. ProyekPekerja = **Full CRUD** (List + Tambah + Edit + Hapus).
  3. `status` ProyekPekerja = **enum baru `StatusPekerja`** (mirip `StatusProyek`).
- `app/Models/ProyekPekerja.php` saat ini KOSONG (no fillable/cast/relasi/factory) → harus dilengkapi.
- Migration `proyek_pekerjas` sudah ada: `proyek_id` (foreignId tanpa constraint), `nama_pekerja`,
  `nomor_hp`, `status_jabatan`, `tarif_harian`, `tarif_overtime`, `catatan` nullable, `status` tinyInteger, softDeletes.

## Arsitektur hasil
```
/master-data/proyek            -> Proyek\MainIndex        (list + CRUD Proyek, ADA) 
/master-data/proyek/{proyek}/pekerja -> ProyekPekerja\MainIndex (detail Proyek + CRUD Pekerja, BARU)
```
Navigasi: tombol "Detail Pekerja" di popover Aksi tabel Proyek → `<a href="{{ route('proyek.pekerja.index', $item->id) }}" wire:navigate>`.

## Langkah implementasi

### 1. Enum baru — `app/Enum/StatusPekerja.php`
Salin pola `app/Enum/StatusProyek.php`: `case AKTIF = 1; case NONAKTIF = 2;` + `label()` + `toSelectArray()`.
(Value 1/2 selaras dengan `tinyInteger status default 1` di migration.)

### 2. Lengkapi Model — `app/Models/ProyekPekerja.php`
- `#[Fillable(['proyek_id','nama_pekerja','nomor_hp','status_jabatan','tarif_harian','tarif_overtime','catatan','status'])]`
- `use HasFactory, SoftDeletes;`
- `protected $casts = ['tarif_harian'=>'decimal:2','tarif_overtime'=>'decimal:2','status'=>StatusPekerja::class];`
- Relasi: `public function proyek(): BelongsTo { return $this->belongsTo(Proyek::class); }`

### 3. Relasi balik — `app/Models/Proyek.php`
Tambah: `public function pekerjas(): HasMany { return $this->hasMany(ProyekPekerja::class); }`
(import `App\Models\ProyekPekerja`, `Illuminate\Database\Eloquent\Relations\HasMany`).

### 4. Migration FK — `database/migrations/xxxx_add_proyek_foreign_key_to_proyek_pekerjas_table.php`
```php
Schema::table('proyek_pekerjas', function (Blueprint $table) {
    $table->foreign('proyek_id')->references('id')->on('proyeks')->onDelete('cascade');
});
```
(Drop FK di `down()`.) Jalankan `php artisan migrate` (butuh MySQL/XAMPP nyala).

### 5. Factory — `database/factories/ProyekPekerjaFactory.php`
`definition()` mengisi semua field + `status => StatusPekerja::AKTIF`, `proyek_id => Proyek::factory()`.

### 6. Component baru — `app/Livewire/MasterData/ProyekPekerja/MainIndex.php`
Pola kanonik (PROJECT.md §10), beda:
- `#[Layout('layouts.app')]`, `use WithPagination`.
- `#[Locked] public ?Proyek $proyek = null;` diisi di `mount($proyek)` via `Proyek::findOrFail($proyek)`.
- `#[Locked] public bool $form = false;`, `#[Locked] public ?ProyekPekerja $editData = null;`
- `public array $pekerjaState = [];` + `#[Locked] public array $params = [nama_pekerja=>null, nomor_hp=>null, status_jabatan=>null, tarif_harian=>null, tarif_overtime=>null, catatan=>null, status=>StatusPekerja::AKTIF->value];`
- `#[Locked] public array $statusOptions = [];` diisi `StatusPekerja::toSelectArray()` di mount.
- `mount($proyek)`: `ensureCanManage()` (admin||operator via MainHelper), set `$this->proyek`, `state=$params`.
- `ALLOWED_SORT_COLUMNS = ['nama_pekerja','status_jabatan','tarif_harian','created_at','id']`; `#[Url]` `$search/$order_by/$order_type`.
- `render()`: `$this->proyek->pekerjas()` + filter search + `orderBy` + `paginate(10)`, kirim `'proyek'=>$this->proyek,'data'=>$data`.
- `showForm/actionForm/doCreate/doEdit/doUpdate/doDelete` = salin pola Proyek, beda:
  - `doCreate`/`doUpdate` validate `pekerjaState.*`:
    `nama_pekerja` required|string, `nomor_hp` required|string, `status_jabatan` required|string,
    `tarif_harian` required|numeric|min:0, `tarif_overtime` nullable|numeric|min:0,
    `catatan` nullable|string, `status` required|Rule::enum(StatusPekerja::class).
  - Saat simpan: `ProyekPekerja::create([...$this->pekerjaState, 'proyek_id'=>$this->proyek->id])`
    (create) / `$pekerja->update($this->pekerjaState)` (update).
  - `doDelete(#[On('doDelete')] int $id)` guard `ensureCanManage()`, soft delete.
- `setOrderBy(#[On('setOrderBy')] string $field)` + `updatedSearch()` = salin pola Proyek.

### 7. View — `resources/views/livewire/master-data/proyek-pekerja/main-index.blade.php`
- `<x-main.page-header title="Pekerja Proyek">` + tombol "Kembali" (`<a href="{{ route('proyek.index') }}" wire:navigate>`).
- **Card rincian Proyek** (atas): kode, nama, pemilik, lokasi (full), tanggal mulai/selesai, badge status.
- Form pekerja (toggle `$form`, pola `wire:submit="actionForm"`, `wire:model="pekerjaState.xxx"`, `@error('pekerjaState.xxx')`), tombol submit "Buat/Simpan Data".
- Tabel pekerja: No, Nama, No HP, Jabatan, Tarif Harian, Tarif OT, Catatan, Status badge, Aksi (Edit / Hapus `delete-btn` `data-target="master-data.proyek-pekerja.main-index"`).
- Pagination `$data->onEachSide(1)->links()`.
- Pakai daisyUI + `data-motion`, ikon `<x-lucide-*>`, adaptif tema (token `primary`/`base-content`).

### 8. Route — `routes/web.php`
Tambah (dalam grup `auth` + prefix `master-data`):
```php
Route::livewire('/proyek/{proyek}/pekerja', ProyekPekerjaMainIndex::class)->name('proyek.pekerja.index');
```
Import `use App\Livewire\MasterData\ProyekPekerja\MainIndex as ProyekPekerjaMainIndex;`.
`mount($proyek)` menerima nilai `{proyek}` (id) → `Proyek::findOrFail`.

### 9. Link dari list Proyek — edit `resources/views/livewire/master-data/proyek/main-index.blade.php`
Di dalam popover Aksi tiap baris, tambah tombol:
```blade
<a href="{{ route('proyek.pekerja.index', $item->id) }}" wire:navigate
   class="btn btn-xs btn-outline w-full font-normal tracking-wider"
   popovertarget="popover-proyek-...">Detail Pekerja</a>
```

### 10. Test — `tests/Feature/ProyekPekerjaCrudTest.php`
Salin pola `tests/Feature/ProyekCrudTest.php`:
- `beforeEach` buat role spatie (administrator/meggi/operator).
- admin & operator `->test(ProyekPekerjaMainIndex::class, ['proyek' => $proyek->id])->assertOk()` (pastikan mount param di-pass; bila `test()` tak支持 param, pakai `Livewire::withQueryParams`/mount manual — lihat catatan).
- admin bisa create pekerja (set `pekerjaState.*`, `call('actionForm')`), assert DB.
- admin bisa edit & soft delete pekerja (`call('doEdit',...)`, `call('doDelete',...)`).
- user tanpa role `->assertForbidden()`.

## Risiko / hal perlu dikonfirmasi saat implementasi
- **Route param ke mount Livewire**: `Route::livewire('/proyek/{proyek}/pekerja', ...)` meneruskan `{proyek}` ke `mount($proyek)`. Jika `Livewire::test()` tidak inject param otomatis, test harus memanggil `->call('mount', $proyek->id)` atau pass via konstruktor; verifikasi saat implementasi.
- **FK cascade vs soft delete**: Proyek pakai SoftDeletes ⇒ cascade hanya saat forceDelete. Aman.
- `decimal:2` cast butuh Laravel 11+ (sudah Laravel 13) — ok.

## Validasi (setelah implementasi)
1. `vendor/bin/pint --format agent` (PHP).
2. `php artisan migrate` (MySQL/XAMPP nyala) → cek FK.
3. `php artisan route:list` → pastikan `proyek.pekerja.index` muncul.
4. `npm run build` (perubahan Blade).
5. `php artisan test --compact` (utamakan `ProyekPekerjaCrudTest`).
6. Cek visual di browser: buka `/master-data/proyek` → klik "Detail Pekerja" → tambah/edit/hapus pekerja, pastikan adaptif tema corporate & luxury.
