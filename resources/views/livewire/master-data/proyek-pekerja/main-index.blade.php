<div class="flex flex-col gap-3">
    <x-main.page-header title="Pekerja Proyek">
        <a href="{{ route('proyek.index') }}" wire:navigate class="btn btn-neutral btn-sm">Kembali</a>
        <button type="button" class="btn btn-neutral btn-sm" wire:click="showForm(true)"
            @if ($form) disabled @endif>Tambah Data</button>
    </x-main.page-header>

    <div class="card border border-slate-300 bg-base-100 w-full" data-motion="fade-up">
        <div class="card-body p-0 gap-0">
            <div class="card-title px-5 py-3 border-b border-b-slate-300 text-sm flex items-center justify-between">
                <div class="flex-auto">
                    Rincian Proyek
                </div>
                <span class="badge @if ($proyek->status === \App\Enums\StatusProyek::AKTIF) badge-success @else badge-ghost @endif">
                    {{ $proyek->status->label() }}
                </span>
            </div>
            <div class="w-full grid grid-cols-6 px-6 py-4 gap-3 text-sm">
                <div class="col-span-6 md:col-span-3">
                    <span class="block text-xs text-base-content/60">Kode Proyek</span>
                    <span class="font-semibold">{{ $proyek->kode_proyek ?? '-' }}</span>
                </div>
                <div class="col-span-6 md:col-span-3">
                    <span class="block text-xs text-base-content/60">Nama Proyek</span>
                    <span class="font-semibold">{{ $proyek->nama_proyek ?? '-' }}</span>
                </div>
                <div class="col-span-6 md:col-span-3">
                    <span class="block text-xs text-base-content/60">Pemilik</span>
                    <span class="font-semibold">{{ $proyek->pemilik ?? '-' }}</span>
                </div>
                <div class="col-span-6 md:col-span-3">
                    <span class="block text-xs text-base-content/60">Lokasi</span>
                    <span class="font-semibold">{{ $proyek->lokasi ?? '-' }}</span>
                </div>
                <div class="col-span-6 md:col-span-3">
                    <span class="block text-xs text-base-content/60">Tanggal Mulai</span>
                    <span class="font-semibold">{{ $proyek->tanggal_mulai?->format('d/m/Y') ?? '-' }}</span>
                </div>
                <div class="col-span-6 md:col-span-3">
                    <span class="block text-xs text-base-content/60">Tanggal Selesai</span>
                    <span class="font-semibold">{{ $proyek->tanggal_selesai?->format('d/m/Y') ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card border border-slate-300 bg-base-100 w-full {{ $form ? 'block' : 'hidden' }}">
        <div class="card-body p-0">
            <div class="card-title px-5 py-3 border-b border-b-slate-300 text-sm flex items-center justify-between">
                <div class="flex-auto">
                    Formulir {{ $editData ? 'Ubah' : 'Tambah' }} Pekerja Proyek
                </div>

                <button type="button" class="btn bg-red-500 text-white btn-xs" wire:click="showForm(false)">
                    Tutup Formulir
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
                        <hr class="border-t border-t-slate-300">
                    </div>

                    <div class="col-span-6 md:col-span-2 xl:col-span-1">
                        <button type="submit" class="btn btn-neutral w-full btn-sm">
                            {{ isset($editData) ? 'Simpan Data' : 'Buat Data' }}
                        </button>
                    </div>
                    <div class="col-span-6 md:col-span-2 xl:col-span-1">
                        <button type="{{ $editData ? 'button' : 'reset' }}" class="btn btn-error w-full btn-sm"
                            @isset($editData) wire:click="showForm(false)" @endisset>
                            {{ isset($editData) ? 'Batalkan' : 'Reset Input' }}
                        </button>
                    </div>
                </div>
            </form>
            <div class="card-actions text-xs font-semibold text-slate-600 bg-slate-200 rounded-b-lg px-5 py-2">
                Formulir {{ $editData ? 'Ubah' : 'Tambah' }} Pekerja Proyek
            </div>
        </div>
    </div>

    <div class="w-full grid grid-cols-12 {{ $form ? 'hidden' : 'block' }}">
        <div class="relative w-full col-span-12 md:col-span-8 lg:col-span-4">
            <label class="sr-only" for="filter-search-data-pekerja">Cari Data :</label>
            <input type="text" name="filter-search-data-pekerja" id="filter-search-data-pekerja"
                wire:model.live.debounce.500ms="search"
                class="py-2 px-3 ps-9 block w-full border border-gray-300 text-sm rounded outline-none"
                placeholder="Masukan Keyword Untuk Melakukan Pencarian...">
            <div class="absolute inset-y-0 inset-s-0 flex items-center pointer-events-none ps-3">
                <svg class="size-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.3-4.3"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto border rounded-lg border-slate-300 {{ $form ? 'hidden' : 'block' }}">
        <table class="table table-sm table-pin-rows table-pin-cols">
            <thead>
                <tr>
                    <td class="text-center" width="6%">No.</td>
                    <td>
                        <x-table.th label="Nama Pekerja" field="nama_pekerja" :orderBy="$order_by" :orderType="$order_type" />
                    </td>
                    <td>Nomor HP</td>
                    <td>
                        <x-table.th label="Jabatan" field="status_jabatan" :orderBy="$order_by" :orderType="$order_type" />
                    </td>
                    <td>
                        <x-table.th label="Tarif Harian" field="tarif_harian" :orderBy="$order_by" :orderType="$order_type" />
                    </td>
                    <td>Tarif OT</td>
                    <td>Catatan</td>
                    <td>Status</td>
                    <th class="text-center" width="10%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $item)
                    <tr>
                        <td class="text-center bg-slate-200">
                            {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}.</td>
                        <td>{{ $item->nama_pekerja ?? '-' }}</td>
                        <td>{{ $item->nomor_hp ?? '-' }}</td>
                        <td>{{ $item->status_jabatan ?? '-' }}</td>
                        <td class="text-right whitespace-nowrap">Rp
                            {{ number_format((float) $item->tarif_harian, 0, ',', '.') }}</td>
                        <td class="text-right whitespace-nowrap">Rp
                            {{ number_format((float) ($item->tarif_overtime ?? 0), 0, ',', '.') }}</td>
                        <td class="max-w-xs truncate" title="{{ $item->catatan }}">{{ $item->catatan ?? '-' }}</td>
                        <td>
                            @if ($item->status === \App\Enums\StatusPekerja::AKTIF)
                                <span class="badge badge-success">{{ $item->status->label() }}</span>
                            @else
                                <span class="badge badge-ghost">{{ $item->status->label() }}</span>
                            @endif
                        </td>
                        <th class="text-center">
                            <button type="button" class="btn btn-xs btn-neutral w-full font-normal tracking-wider"
                                popovertarget="popover-pekerja-{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}"
                                style="anchor-name:--anchor-pekerja-{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}">
                                Aksi
                            </button>
                            <div class="dropdown dropdown-end menu w-auto rounded-box bg-base-100 border border-slate-300 shadow-lg text-xs flex flex-col gap-1 px-4"
                                popover
                                id="popover-pekerja-{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}"
                                style="position-anchor:--anchor-pekerja-{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}">
                                <h5 class="text-center">Aksi Data</h5>
                                <hr class="border-t border-t-slate-300 my-1">
                                <button type="button"
                                    class="btn btn-xs btn-outline w-full font-normal tracking-wider"
                                    popovertarget="popover-pekerja-{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}"
                                    wire:click="doEdit('{{ $item->id }}')">
                                    Edit Data
                                </button>
                                <button type="button"
                                    popovertarget="popover-pekerja-{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}"
                                    class="btn btn-xs btn-outline w-full font-normal tracking-wider delete-btn"
                                    data-id="{{ $item->id }}"
                                    data-target="master-data.proyek-pekerja.main-index">
                                    Hapus Data
                                </button>
                            </div>
                        </th>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center p-2">Belum Ada Data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="w-full {{ $form ? 'hidden' : 'block' }}">
        {{ $data->onEachSide(1)->links() }}
    </div>
</div>
