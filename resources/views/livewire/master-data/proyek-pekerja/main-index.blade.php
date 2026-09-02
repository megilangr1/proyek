<div class="flex flex-col gap-3">
    <x-main.page-header title="Pekerja Proyek">
        <a href="{{ route('proyek.index') }}" wire:navigate class="btn btn-neutral btn-sm">Kembali</a>
        <button type="button" class="btn btn-neutral btn-sm" wire:click="showForm(true)"
            @if ($form) disabled @endif>Tambah Data</button>
    </x-main.page-header>

    {{-- Compact Hero + Tabs — user-friendly, less scroll --}}
    <div class="card border border-base-300 bg-base-100 overflow-hidden" data-motion="fade-up">
        <div class="bg-gradient-to-r from-primary/10 via-base-100 to-secondary/10 px-5 py-3 flex flex-col lg:flex-row lg:items-center justify-between gap-3">
            <div class="flex items-center gap-3 min-w-0">
                <div class="size-9 rounded-xl bg-primary text-primary-content grid place-items-center shrink-0">
                    <x-lucide-building-2 class="size-5" />
                </div>
                <div class="min-w-0">
                    <h3 class="font-bold leading-none truncate flex items-center gap-2">
                        {{ $proyek->nama_proyek ?? '-' }}
                        <span class="badge badge-sm font-mono badge-ghost">{{ $proyek->kode_proyek ?? '-' }}</span>
                        <span
                            class="badge badge-xs @if ($proyek->status === \App\Enums\StatusProyek::AKTIF) badge-success @else badge-ghost @endif">{{ $proyek->status->label() }}</span>
                    </h3>
                    <p class="text-xs text-base-content/60 truncate mt-1 flex items-center gap-1.5">
                        <x-lucide-user class="size-3" /> {{ $proyek->pemilik ?? '-' }}
                        <span class="opacity-30">•</span> <x-lucide-map-pin class="size-3" /> {{ $proyek->lokasi ?? '-' }}
                        <span class="opacity-30 hidden sm:inline">•</span>
                        <span class="hidden sm:inline-flex items-center gap-1"><x-lucide-calendar-range class="size-3" />
                            {{ $proyek->tanggal_mulai?->format('d/m/Y') }}–{{ $proyek->tanggal_selesai?->format('d/m/Y') }}</span>
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <div class="hidden sm:flex items-center gap-1 text-xs">
                    <span class="badge badge-neutral badge-sm gap-1"><x-lucide-users class="size-3" /> {{ $stats['total'] ?? 0 }} pekerja</span>
                    <span class="badge badge-ghost badge-sm">Rp {{ number_format($stats['sumHarian'] ?? 0, 0, ',', '.') }}/hr</span>
                </div>
                <a href="{{ route('proyek.index') }}" wire:navigate class="btn btn-sm btn-ghost gap-1">
                    <x-lucide-arrow-left class="size-4" /> Kembali
                </a>
                <button type="button" class="btn btn-sm btn-primary gap-1" wire:click="showForm(true)" @if ($form) disabled @endif>
                    <x-lucide-user-plus class="size-4" /> Tambah
                </button>
            </div>
        </div>
        {{-- Inline compact meta --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 divide-x divide-base-300 border-t border-base-300 bg-base-200/30 text-xs">
            <div class="px-4 py-2.5 flex items-center justify-between">
                <span class="text-base-content/50 flex items-center gap-1"><x-lucide-users class="size-3" /> Pekerja</span>
                <span class="font-semibold">{{ $stats['total'] ?? 0 }} <span class="text-base-content/50 font-normal">({{ $stats['aktif'] ?? 0 }} aktif)</span></span>
            </div>
            <div class="px-4 py-2.5 flex items-center justify-between">
                <span class="text-base-content/50">Durasi</span>
                <span class="font-semibold">{{ $stats['durasiHari'] ?? '-' }} hari</span>
            </div>
            <div class="px-4 py-2.5 flex items-center justify-between">
                <span class="text-base-content/50">Avg Tarif</span>
                <span class="font-mono font-semibold text-xs">Rp {{ number_format($stats['avgHarian'] ?? 0, 0, ',', '.') }}</span>
            </div>
            <div class="px-4 py-2.5 flex items-center justify-between">
                <span class="text-base-content/50">Jabatan Top</span>
                <span class="font-semibold truncate max-w-[10rem] text-xs">
                    @if (($stats['jabatans'] ?? collect())->isNotEmpty())
                        {{ $stats['jabatans']->keys()->first() }} ({{ $stats['jabatans']->first() }})
                    @else
                        -
                    @endif
                </span>
            </div>
        </div>
    </div>

    {{-- Tabs antar modul — clear hierarchy --}}
    <div role="tablist" class="tabs tabs-boxed bg-base-200 p-1 w-fit" data-motion="fade-up">
        <a role="tab" class="tab tab-active gap-1"><x-lucide-users class="size-4" /> Pekerja</a>
        <a href="{{ route('proyek.pengeluaran.index', $proyek->id) }}" wire:navigate role="tab" class="tab gap-1">
            <x-lucide-receipt class="size-4" /> Pengeluaran
        </a>
        <a href="{{ route('penggajian.index', ['filterProyekId' => $proyek->id]) }}" wire:navigate role="tab" class="tab gap-1">
            <x-lucide-wallet class="size-4" /> Penggajian
        </a>
    </div>

    <div class="card border border-base-300 bg-base-100 w-full {{ $form ? 'block' : 'hidden' }}" data-motion="scale-in">
        <div class="card-body p-0">
            <div class="card-title px-5 py-3 border-b border-base-300 text-sm flex items-center justify-between">
                <div class="flex-auto flex items-center gap-2">
                    <x-lucide-file-pen-line class="size-4 text-primary" />
                    Formulir {{ $editData ? 'Ubah' : 'Tambah' }} Pekerja Proyek
                </div>

                <button type="button" class="btn btn-ghost btn-xs gap-1" wire:click="showForm(false)">
                    <x-lucide-x class="size-3" /> Tutup
                </button>
            </div>
            <form wire:submit="actionForm">
                <div class="w-full grid grid-cols-6 px-6 pb-2 gap-3">

                    <div class="col-span-6 md:col-span-6 lg:col-span-3">
                        <label for="nama_pekerja"
                            class="block text-sm font-medium mb-2 {{ $errors->has('pekerjaState.nama_pekerja') ? 'text-red-500' : '' }}">
                            Nama Pekerja :
                            <span class="text-red-500 text-xs">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" wire:model="pekerjaState.nama_pekerja" id="nama_pekerja"
                                name="nama_pekerja"
                                class="w-full input @error('pekerjaState.nama_pekerja') input-error @enderror"
                                aria-describedby="nama_pekerja-helper" placeholder="Masukan Nama Pekerja..."
                                @if (!isset($editData)) required @endif autocomplete="false">
                            <div
                                class="absolute inset-y-0 inset-e-0 {{ $errors->has('pekerjaState.nama_pekerja') ? 'flex' : 'hidden' }} items-center pointer-events-none pe-3">
                                <svg class="shrink-0 size-4 text-red-500" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" x2="12" y1="8" y2="12"></line>
                                    <line x1="12" x2="12.01" y1="16" y2="16"></line>
                                </svg>
                            </div>
                        </div>
                        @error('pekerjaState.nama_pekerja')
                            <p class="text-xs text-red-600 mt-1" id="nama_pekerja-helper">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="col-span-6 md:col-span-6 lg:col-span-3">
                        <label for="nomor_hp"
                            class="block text-sm font-medium mb-2 {{ $errors->has('pekerjaState.nomor_hp') ? 'text-red-500' : '' }}">
                            Nomor HP :
                            <span class="text-red-500 text-xs">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" wire:model="pekerjaState.nomor_hp" id="nomor_hp" name="nomor_hp"
                                class="w-full input @error('pekerjaState.nomor_hp') input-error @enderror"
                                aria-describedby="nomor_hp-helper" placeholder="Masukan Nomor HP..."
                                @if (!isset($editData)) required @endif autocomplete="false">
                            <div
                                class="absolute inset-y-0 inset-e-0 {{ $errors->has('pekerjaState.nomor_hp') ? 'flex' : 'hidden' }} items-center pointer-events-none pe-3">
                                <svg class="shrink-0 size-4 text-red-500" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" x2="12" y1="8" y2="12"></line>
                                    <line x1="12" x2="12.01" y1="16" y2="16"></line>
                                </svg>
                            </div>
                        </div>
                        @error('pekerjaState.nomor_hp')
                            <p class="text-xs text-red-600 mt-1" id="nomor_hp-helper">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="col-span-6 md:col-span-6 lg:col-span-3">
                        <label for="status_jabatan"
                            class="block text-sm font-medium mb-2 {{ $errors->has('pekerjaState.status_jabatan') ? 'text-red-500' : '' }}">
                            Status Jabatan :
                            <span class="text-red-500 text-xs">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" wire:model="pekerjaState.status_jabatan" id="status_jabatan"
                                name="status_jabatan"
                                class="w-full input @error('pekerjaState.status_jabatan') input-error @enderror"
                                aria-describedby="status_jabatan-helper" placeholder="Masukan Status Jabatan..."
                                @if (!isset($editData)) required @endif autocomplete="false">
                            <div
                                class="absolute inset-y-0 inset-e-0 {{ $errors->has('pekerjaState.status_jabatan') ? 'flex' : 'hidden' }} items-center pointer-events-none pe-3">
                                <svg class="shrink-0 size-4 text-red-500" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" x2="12" y1="8" y2="12"></line>
                                    <line x1="12" x2="12.01" y1="16" y2="16"></line>
                                </svg>
                            </div>
                        </div>
                        @error('pekerjaState.status_jabatan')
                            <p class="text-xs text-red-600 mt-1" id="status_jabatan-helper">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="col-span-6 md:col-span-6 lg:col-span-3">
                        <label for="tarif_harian"
                            class="block text-sm font-medium mb-2 {{ $errors->has('pekerjaState.tarif_harian') ? 'text-red-500' : '' }}">
                            Tarif Harian :
                            <span class="text-red-500 text-xs">*</span>
                        </label>

                        <div class="relative">
                            <input type="text" wire:model="pekerjaState.tarif_harian_text" id="tarif_harian"
                                name="tarif_harian"
                                class="w-full ps-14 text-right input @error('pekerjaState.tarif_harian') input-error @enderror"
                                aria-describedby="tarif_harian-helper" placeholder="Contoh : 150.000"
                                @if (!isset($editData)) required @endif autocomplete="false" x-data
                                x-on:input="
                                    const raw = $el.value.replace(/[^\d]/g, '');
                                    $wire.set('pekerjaState.tarif_harian', raw);
                                    $wire.set('pekerjaState.tarif_harian_text', new Intl.NumberFormat('id-ID').format(raw));
                                "
                                required>

                            <div class="absolute inset-y-0 inset-s-0 flex items-center pointer-events-none z-20 ps-4">
                                <span class="text-gray-500 text-sm font-semibold">
                                    Rp.
                                </span>
                            </div>

                            <div
                                class="absolute inset-y-0 inset-e-0 {{ $errors->has('pekerjaState.tarif_harian') ? 'flex' : 'hidden' }} items-center pointer-events-none pe-3">
                                <svg class="shrink-0 size-4 text-red-500" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" x2="12" y1="8" y2="12">
                                    </line>
                                    <line x1="12" x2="12.01" y1="16" y2="16">
                                    </line>
                                </svg>
                            </div>
                        </div>
                        @error('pekerjaState.tarif_harian')
                            <p class="text-xs text-red-600 mt-1" id="tarif_harian-helper">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>


                    <div class="col-span-6 md:col-span-6 lg:col-span-3">
                        <label for="tarif_overtime"
                            class="block text-sm font-medium mb-2 {{ $errors->has('pekerjaState.tarif_overtime') ? 'text-red-500' : '' }}">
                            Tarif Overtime (Per Jam) :
                        </label>
                        <div class="relative">
                            <input type="text" wire:model="pekerjaState.tarif_overtime_text" id="tarif_overtime"
                                name="tarif_overtime"
                                class="w-full ps-14 text-right input @error('pekerjaState.tarif_overtime') input-error @enderror"
                                aria-describedby="tarif_overtime-helper" placeholder="Contoh : 150.000"
                                @if (!isset($editData)) required @endif autocomplete="false" x-data
                                x-on:input="
                                    const raw = $el.value.replace(/[^\d]/g, '');
                                    $wire.set('pekerjaState.tarif_overtime', raw);
                                    $wire.set('pekerjaState.tarif_overtime_text', new Intl.NumberFormat('id-ID').format(raw));
                                "
                                required>

                            <div class="absolute inset-y-0 inset-s-0 flex items-center pointer-events-none z-20 ps-4">
                                <span class="text-gray-500 text-sm font-semibold">
                                    Rp.
                                </span>
                            </div>

                            <div
                                class="absolute inset-y-0 inset-e-0 {{ $errors->has('pekerjaState.tarif_overtime') ? 'flex' : 'hidden' }} items-center pointer-events-none pe-3">
                                <svg class="shrink-0 size-4 text-red-500" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" x2="12" y1="8" y2="12">
                                    </line>
                                    <line x1="12" x2="12.01" y1="16" y2="16">
                                    </line>
                                </svg>
                            </div>
                        </div>

                        @error('pekerjaState.tarif_overtime')
                            <p class="text-xs text-red-600 mt-1" id="tarif_overtime-helper">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="col-span-6 lg:col-span-3">
                        <label for="status"
                            class="block text-sm font-medium mb-2 {{ $errors->has('pekerjaState.status') ? 'text-red-500' : '' }}">
                            Status Pekerja :
                            <span class="text-red-500 text-xs">*</span>
                        </label>
                        <div class="relative">
                            <select wire:model="pekerjaState.status" id="status" name="status"
                                class="w-full select @error('pekerjaState.status') select-error @enderror"
                                aria-describedby="status-helper" required autocomplete="false">
                                <option disabled>Pilih Status</option>
                                @foreach ($statusOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            <div
                                class="absolute inset-y-0 inset-e-6 {{ $errors->has('pekerjaState.status') ? 'flex' : 'hidden' }} items-center pointer-events-none pe-3">
                                <svg class="shrink-0 size-4 text-red-500" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" x2="12" y1="8" y2="12"></line>
                                    <line x1="12" x2="12.01" y1="16" y2="16"></line>
                                </svg>
                            </div>
                        </div>
                        @error('pekerjaState.status')
                            <p class="text-xs text-red-600 mt-1" id="status-helper">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="col-span-6">
                        <label for="catatan"
                            class="block text-sm font-medium mb-2 {{ $errors->has('pekerjaState.catatan') ? 'text-red-500' : '' }}">
                            Catatan :
                        </label>
                        <div class="relative">
                            <textarea wire:model="pekerjaState.catatan" id="catatan" name="catatan" rows="3"
                                class="w-full textarea @error('pekerjaState.catatan') textarea-error @enderror" aria-describedby="catatan-helper"
                                placeholder="Masukan Catatan..."></textarea>
                            @error('pekerjaState.catatan')
                                <p class="text-xs text-red-600 mt-1" id="catatan-helper">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="col-span-6">
                        <hr class="border-t border-base-300">
                    </div>

                    <div class="col-span-6 md:col-span-2 xl:col-span-1">
                        <button type="submit" class="btn btn-primary w-full btn-sm gap-1">
                            <x-lucide-save class="size-3" /> {{ isset($editData) ? 'Simpan Data' : 'Buat Data' }}
                        </button>
                    </div>
                    <div class="col-span-6 md:col-span-2 xl:col-span-1">
                        <button type="{{ $editData ? 'button' : 'reset' }}" class="btn btn-ghost w-full btn-sm border border-base-300"
                            @isset($editData) wire:click="showForm(false)" @endisset>
                            {{ isset($editData) ? 'Batalkan' : 'Reset Input' }}
                        </button>
                    </div>
                </div>
            </form>
            <div class="card-actions text-xs font-semibold text-base-content/60 bg-base-200 rounded-b-lg px-5 py-2">
                <x-lucide-info class="size-3" /> Formulir {{ $editData ? 'Ubah' : 'Tambah' }} Pekerja Proyek — tarif pakai format Rupiah Indonesia
            </div>
        </div>
    </div>

    <div class="w-full grid grid-cols-12 {{ $form ? 'hidden' : 'block' }}" data-motion="fade-up">
        <div class="relative w-full col-span-12 md:col-span-8 lg:col-span-4">
            <label class="sr-only" for="filter-search-data-pekerja">Cari Data :</label>
            <input type="text" name="filter-search-data-pekerja" id="filter-search-data-pekerja"
                wire:model.live.debounce.500ms="search"
                class="py-2.5 px-3 ps-9 block w-full border border-base-300 bg-base-100 text-sm rounded-lg outline-none focus:border-primary focus:ring-1 focus:ring-primary"
                placeholder="Cari pekerja, HP, jabatan...">
            <div class="absolute inset-y-0 inset-s-0 flex items-center pointer-events-none ps-3">
                <x-lucide-search class="size-4 text-base-content/40" />
            </div>
        </div>
        <div class="hidden lg:flex col-span-8 justify-end items-center gap-2 text-xs text-base-content/60">
            <span class="badge badge-sm badge-ghost gap-1"><x-lucide-filter class="size-3" /> {{ $data->total() }} data</span>
            <span class="hidden md:inline">• klik header untuk sort</span>
        </div>
    </div>

    <div class="overflow-x-auto border rounded-lg border-base-300 bg-base-100 shadow-sm {{ $form ? 'hidden' : 'block' }}">
        <table class="table table-sm table-pin-rows table-pin-cols">
            <thead>
                <tr class="bg-base-200/50">
                    <th class="text-center" width="6%">No.</th>
                    <th>
                        <x-table.th label="Nama Pekerja" field="nama_pekerja" :orderBy="$order_by" :orderType="$order_type" />
                    </th>
                    <th>Nomor HP</th>
                    <th>
                        <x-table.th label="Jabatan" field="status_jabatan" :orderBy="$order_by" :orderType="$order_type" />
                    </th>
                    <th>
                        <x-table.th label="Tarif Harian" field="tarif_harian" :orderBy="$order_by" :orderType="$order_type" />
                    </th>
                    <th>Tarif OT</th>
                    <th>Catatan</th>
                    <th>Status</th>
                    <th class="text-center" width="10%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $item)
                    <tr class="hover:bg-base-200/50 transition-colors">
                        <td class="text-center bg-base-200 font-mono text-xs">
                            {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}.</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="avatar placeholder">
                                    <div class="bg-neutral text-neutral-content rounded-full size-7 grid place-items-center text-[10px]">
                                        {{ strtoupper(substr($item->nama_pekerja ?? '?', 0, 2)) }}
                                    </div>
                                </div>
                                <span class="font-medium">{{ $item->nama_pekerja ?? '-' }}</span>
                            </div>
                        </td>
                        <td><span class="font-mono text-xs">{{ $item->nomor_hp ?? '-' }}</span></td>
                        <td><span class="badge badge-outline badge-sm gap-1"><x-lucide-briefcase class="size-3" />{{ $item->status_jabatan ?? '-' }}</span></td>
                        <td class="text-right whitespace-nowrap font-mono text-xs">Rp
                            {{ number_format((float) $item->tarif_harian, 0, ',', '.') }}</td>
                        <td class="text-right whitespace-nowrap font-mono text-xs">Rp
                            {{ number_format((float) ($item->tarif_overtime ?? 0), 0, ',', '.') }}</td>
                        <td class="max-w-xs truncate text-xs" title="{{ $item->catatan }}">{{ $item->catatan ?? '-' }}</td>
                        <td>
                            @if ($item->status === \App\Enums\StatusPekerja::AKTIF)
                                <span class="badge badge-success badge-sm gap-1"><span class="size-1.5 rounded-full bg-white"></span>{{ $item->status->label() }}</span>
                            @else
                                <span class="badge badge-ghost badge-sm">{{ $item->status->label() }}</span>
                            @endif
                        </td>
                        <th class="text-center">
                            <button type="button" class="btn btn-xs btn-neutral w-full font-normal tracking-wider"
                                popovertarget="popover-pekerja-{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}"
                                style="anchor-name:--anchor-pekerja-{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}">
                                Aksi
                            </button>
                            <div class="dropdown dropdown-end menu w-auto rounded-box bg-base-100 border border-base-300 shadow-lg text-xs flex flex-col gap-1 px-4"
                                popover
                                id="popover-pekerja-{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}"
                                style="position-anchor:--anchor-pekerja-{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}">
                                <h5 class="text-center flex items-center justify-center gap-1"><x-lucide-settings-2 class="size-3" /> Aksi Data</h5>
                                <hr class="border-t border-base-300 my-1">
                                <button type="button"
                                    class="btn btn-xs btn-outline w-full font-normal tracking-wider gap-1"
                                    popovertarget="popover-pekerja-{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}"
                                    wire:click="doEdit('{{ $item->id }}')">
                                    <x-lucide-pencil class="size-3" /> Edit Data
                                </button>
                                <button type="button"
                                    popovertarget="popover-pekerja-{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}"
                                    class="btn btn-xs btn-outline w-full font-normal tracking-wider gap-1 delete-btn text-error"
                                    data-id="{{ $item->id }}"
                                    data-target="master-data.proyek-pekerja.main-index">
                                    <x-lucide-trash-2 class="size-3" /> Hapus Data
                                </button>
                            </div>
                        </th>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center p-8">
                            <div class="flex flex-col items-center gap-2 text-base-content/60">
                                <x-lucide-inbox class="size-8" />
                                <span class="text-sm">Belum ada pekerja — tambah data pertama!</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="w-full {{ $form ? 'hidden' : 'block' }}">
        {{ $data->onEachSide(1)->links() }}
    </div>
</div>
