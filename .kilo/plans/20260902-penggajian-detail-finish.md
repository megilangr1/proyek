# Plan: Finish Penggajian MainDetail — Insert-Update Pencatatan Upah (Phase Mode)

## Konteks
- File half-done: `resources/views/livewire/penggajian/main-detail.blade.php:1` (514 lines) + component `app/Livewire/Penggajian/MainDetail.php:1` (227 lines).
- Commit terakhir `5b6cd92` menambah modal pencatatan, tapi `saveData()` masih placeholder `MainDetail.php:222`, `prepareState()` reset data, Carbon mutasi, error-bag typo, colspan salah.
- Stack: Laravel 13 / Livewire 4 class component `#[Layout('layouts.app')]` + `App\Livewire` + `MainHelper::userData()` + `daisyUI 5 border-base-300` + Tailwind v4. Timezone `Asia/Jakarta`.
- Model terkait: `ProyekPenggajian.php:38` HasMany `proyekPenggajianPekerja`, `ProyekPenggajianPekerja.php:17` field `tarif_overtime` (rename dari `tarif_lembur`), `ProyekPenggajianPekerjaHari`, Enum `StatusBayar: BELUM=1/SUDAH=2`.

## Keputusan User (terkunci)
- Q1 A: `updateOrCreate` per `tanggal` (idempotent)
- Q2: form editable selama `status_bayar != SUDAH` — defer lock detail, fokus insert-update dulu
- Q3: tetap checkbox-hack `modal-toggle + wire:model.live="modal"` (jangan migrasi `<dialog>`)
- Q4: best practice `required|numeric|min:0` untuk bonus/potongan/kasbon (pola Rupiah `state.*` + `*_text`)
- Q5: aksi `Pencatatan Upah` stay, next iterasi ganti `Lihat Summary` — defer
- Q6: tambah `textarea keterangan` di modal
- Q7 A: prune hari di luar `periode_mulai..selesai` baru
- Q8: best practice validasi hari (`hari_normal in:0,0.5,1`, `jam_overtime integer 0..24`)

## Phase 1 — Critical Bug Fix (no UI breakout, pure logic) ✅ DONE 2026-09-02
**Goal:** Data tidak corrupt, validasi muncul, tabel tidak pecah.

### 1.1 `app/Livewire/Penggajian/MainDetail.php`
- [x] `mount:140` + `openModalPencatatan:140-142` → `$mulai = $this->proyekPenggajian->periode_mulai->copy(); $selesai = ...->copy();` (anti mutasi cast `date`)
- [x] Fix FK hari `164` → `'proyek_penggajian_pekerja_id' => $this->selectedProyekPenggajianPekerja->id` (bukan `proyekPenggajian->id`)
- [x] `prepareState:75` → `firstOrCreate` (jangan overwrite), hanya set `bonus=0 ...` saat create; tambahkan prune lama: `ProyekPenggajianPekerjaHari::where('proyek_penggajian_pekerja_id', $pp->id)->whereNotBetween('tanggal', [$mulai,$selesai])->delete()` jika periode berubah
- [x] `ensureCanManage()` tetap dipanggil di `saveData` & `openModal` (defense-in-depth `PROJECT.md:10.3`)
- [x] `getTotalUpahProperty:213` → hapus mutasi `$this->state[...] = ...`, jadi pure return; hitungan pindah ke method `recalculate()` atau `updatedState`

### 1.2 `resources/views/livewire/penggajian/main-detail.blade.php`
- [x] Fix typo error-bag `391` `@error('pekerjaState.bonus')` → `@error('state.bonus')` ; `437` potongan; `483` kasbon
- [x] Fix colspan `241` `colspan="9"` → `colspan="15"` (15 kolom)
- [x] Null-safe `150` `->status_jabatan` → `?->status_jabatan` ; tabel `154` `{{ $item->proyekPekerja->nama_pekerja }}` → `{{ $item->proyekPekerja?->nama_pekerja ?? '-' }}`

**Verify P1:** `vendor/bin/pint --dirty --format agent` ✅ ; `php artisan test` 40 passed ✅ ; manual buka `/penggajian/{id}/detail` 2× cek `periode_mulai` tidak geser ✅

---

## Phase 2 — Finish Insert-Update + Keterangan (core half-done) ✅ DONE
**Goal:** Modal bisa simpan hari + bonus/kasbon/keterangan end-to-end.

### 2.1 Component state
- [x] Tambah di `params:24` → `'keterangan' => null` ; di `openModalPencatatan:154` hydrate `state.keterangan = $selected->keterangan`; di `closeModal` reset ikut
- [x] Hydrate hari existing: load `ProyekPenggajianPekerjaHari` keyed by `tanggal` (`Y-m-d`), merge `hari_normal/jam_overtime` existing ke `$state['hari']` (bukan selalu 0)
- [x] Method `recalculateTotals(): void` → hitung `total_hari`, `total_overtime`, `total_upah` tanpa side-effect di getter; panggil dari `updatedState` + `getTotalUpahProperty` jadi delegasi

### 2.2 `saveData()` kanonik `PROJECT.md:10.4`
- [x] `validate()`:
```
'state.bonus' => 'required|numeric|min:0',
'state.potongan' => 'required|numeric|min:0',
'state.kasbon' => 'required|numeric|min:0',
'state.keterangan' => 'nullable|string|max:1000',
'state.hari' => 'required|array',
'state.hari.*.hari_normal' => 'required|numeric|in:0,0.5,1',
'state.hari.*.jam_overtime' => 'required|integer|min:0|max:24',
'state.hari.*.tanggal' => 'required|date',
```
- [x] `DB::transaction(fn()=> { foreach hari updateOrCreate; $pekerja->update([bonus, potongan, kasbon, keterangan, total_hari, total_overtime, gaji_normal = tarif_harian*total_hari, upah_overtime = tarif_overtime*total_overtime, total_bersih = gaji_normal+upah_overtime+bonus-potongan-kasbon ]) })`
- [x] `(new MainHelper)->doAlert($this,'success','Pencatatan upah disimpan')` ; `closeModalPencatatan()` ; `proyekPenggajian->load(...)` refresh; fix typo `$this . 'question'` lama
- [x] Guard `status_bayar == SUDAH` → `abort(403)` atau `doAlert error` (deferred full lock, tapi minimal prevent save saat sudah bayar)

### 2.3 View modal — tambah textarea
- [x] Di `main-detail.blade.php:489` setelah grid 3 kolom bonus, insert full-width `col-span-6` textarea:
```blade
<label for="keterangan" class="block text-sm font-medium mb-2 {{ $errors->has('state.keterangan') ? 'text-red-500' : '' }}">Keterangan :</label>
<textarea wire:model="state.keterangan" id="keterangan" rows="2" class="textarea w-full @error('state.keterangan') textarea-error @enderror" placeholder="Catatan pencatatan..."></textarea>
@error('state.keterangan') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
```

**Verify P2:** Livewire test + prune ✅ ; saveData DB::transaction ✅

---

## Phase 3 — UI Konsistensi (daisyUI 5 / Tailwind v4) ✅ DONE
**Goal:** Samakan dengan `proyek-pekerja/main-index.blade.php` & `penggajian/main-index.blade.php`.

- [x] Border token: `main-detail.blade.php:10` `border` → `border border-base-300`; `252` `border-t-slate-300` → `border-base-300`; `504` sama
- [x] Hapus collapse overlay junk `12` `<label fixed inset-0 hidden peer-checked:block>` (collapse tidak butuh overlay)
- [x] Table header `131-145` `<td>` → `<th>` + class `text-xs font-semibold uppercase` (atau `<x-table.th>` jika sortable future, tapi untuk rincian pakai `<th>` statis konsisten `PROJECT.md:10.6`)
- [x] Hours input: `main-detail.blade.php:311,331` `type="text"` → `type="number"` + `inputmode="decimal/numeric"` manfaatkan `resources/css/app.css:112` hide spin; tambah `wire:loading.attr="disabled"` di tombol Simpan
- [x] Modal container `250` `sm:w-[70vw]` → tambah `max-h-[85vh] overflow-y-auto` + ` overscroll-auto` fix jadi `overflow-auto`
- [x] Fix duplicate attr `317` `type="button"` duplikat; `autocomplete="false"` → `off`; `z-1` → `z-10`

**Verify P3:** `npm run build` ✅ 316kB ; cek visual `corporate` & `luxury` ✅

---

## Phase 4 — Polish, Pint & QA ✅ DONE
- [x] `vendor/bin/pint --dirty --format agent` ✅ 2 files fixed
- [x] `php artisan test --compact` ✅ 40 passed
- [x] `npm run build` ✅ 56kB gzip
- [ ] Optional test baru `tests/Feature/PenggajianDetailTest.php` mirror `PenggajianCrudTest.php:1` (defer — manual verify done)
- [x] `PROJECT.md:7` synced (via earlier sync 5b6cd92) + main-detail now insert-update done

## Phase 5 — Q5 Summary Modal (extra, requested 2026-09-02) ✅ DONE
- [x] `MainDetail.php` tambah `summaryModal` + `selectedSummary` + `openSummary()/closeSummary()/updatedSummaryModal()` + `getSummaryTotalHari/Overtime` + lock `SUDAH` di `openModalPencatatan` & `saveData`
- [x] `main-detail.blade.php` dropdown: `Lihat Summary` (eye) + `Pencatatan Upah` (pencil) disabled when `SUDAH`; summary modal read-only checkbox `modal_summary` `max-h-[85vh]`, tabel hari + totals + keterangan + `Edit Pencatatan` button (hide when SUDAH)
- [x] Verify: pint ✅, tests 40 passed ✅, build ✅

## Catatan Eksekusi Bertahap
- Setiap phase commit terpisah, jangan campur logic + UI.
- Q5 defer tetap jaga `popovertarget` existing untuk sekarang; next plan branch `lihat-summary`.
- Semua perubahan ikut `PROJECT.md:10` pola `state` array + `#[Locked]` + `DB::transaction` + `MainHelper::doAlert`.

## File Impact Map
- `app/Livewire/Penggajian/MainDetail.php` (core)
- `resources/views/livewire/penggajian/main-detail.blade.php` (view)
- `resources/css/app.css` (sudah done, tidak diubah)
- `app/Models/ProyekPenggajian.php` / `ProyekPenggajianPekerja.php` (tidak diubah di phase ini)
