---
paths:
  - 'app/Livewire/**'
---

# Livewire

## Modal picker for reference selects
Selecting FK/reference data (Proyek, User, etc.) must use a reusable Livewire modal picker (see PROJECT.md §4.8 / §10.10, canonical ProyekPickerModal), not a large native <select>. Trigger via dispatch('openProyekPicker', context) parent->child; return via dispatch('proyekSelected', id, nama, context) child->parent with #[On] in parent. Use native <select> only for few fixed options (enum status).

## Child panels (nested Livewire)
Panels child di dalam parent detail (mis. `app/Livewire/MasterData/Proyek/MainDetail.php`) jangan pakai `#[Layout('layouts.app')]`. Saat dirender sebagai nested `<livewire:...>` child, layout tidak diterapkan — hanya dipakai saat full-page route mount. Contoh: `ProyekPekerja\MainIndex`, `ProyekPengeluaran\MainIndex`, `Penggajian\MainIndex`.

## Penggajian per-proyek (gating pekerja)
`Penggajian\MainIndex` bersifat child panel, mount($proyek), proyek implisit — bukan global. `doCreate` dan `doUpdate` wajib cek `proyekPekerja()->where('status',AKTIF)->exists()`. Tanpa pekerja aktif → tolak dengan alert. Tombol "Tambah" di-view juga disabled + badge warning. Referensi: `REFACTOR_DETAIL_PROYEK_PLAN.md` FASE 3.