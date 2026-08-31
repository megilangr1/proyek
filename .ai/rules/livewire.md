---
paths:
  - 'app/Livewire/**'
---

# Livewire

## Modal picker for reference selects
Selecting FK/reference data (Proyek, User, etc.) must use a reusable Livewire modal picker (see PROJECT.md §4.8 / §10.10, canonical ProyekPickerModal), not a large native <select>. Trigger via dispatch('openProyekPicker', context) parent->child; return via dispatch('proyekSelected', id, nama, context) child->parent with #[On] in parent. Use native <select> only for few fixed options (enum status).
