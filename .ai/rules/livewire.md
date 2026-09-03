---
paths:
  - 'app/Livewire/**'
---

# Livewire

## Modal picker for reference selects (legacy concept — files deleted)
Selecting FK/reference data (Proyek, User, etc.) with many rows must use a reusable Livewire modal picker (pattern: PROJECT.md §10.10), not a large native <select>. Trigger via dispatch('openXPicker', context) parent->child; return via dispatch('xSelected', id, nama, context) child->parent with #[On] in parent. Use native <select> only for few fixed options (enum status). Note: the canonical `ProyekPickerModal` + blade were DELETED in the 2026-09-02 per-proyek refactor (see PROJECT.md §4.8) — `Penggajian\MainIndex` now takes proyek implicitly via mount($proyek). Do not reference the picker as an existing file; follow the communication pattern when building a new picker.

## Child panels (nested Livewire)
Panels child di dalam parent detail (mis. `app/Livewire/MasterData/Proyek/MainDetail.php`) jangan pakai `#[Layout('layouts.app')]`. Saat dirender sebagai nested `<livewire:...>` child, layout tidak diterapkan — hanya dipakai saat full-page route mount. Contoh: `ProyekPekerja\MainIndex`, `ProyekPengeluaran\MainIndex`, `Penggajian\MainIndex`.

## Penggajian per-proyek (gating pekerja)
`Penggajian\MainIndex` bersifat child panel, mount($proyek), proyek implisit — bukan global. `doCreate` dan `doUpdate` wajib cek `proyekPekerja()->where('status',AKTIF)->exists()`. Tanpa pekerja aktif → tolak dengan alert. Tombol "Tambah" di-view juga disabled + badge warning. Referensi: `REFACTOR_DETAIL_PROYEK_PLAN.md` FASE 3.