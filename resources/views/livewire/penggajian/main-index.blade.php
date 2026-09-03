<div class="flex flex-col gap-3">
    <div class="flex items-center justify-between gap-2">
        <div class="flex items-center gap-2 text-sm text-base-content/60">
            <span class="badge badge-ghost badge-sm gap-1">
                <x-lucide-wallet class="size-3" /> {{ $data->total() }} periode
            </span>
            @unless ($proyek->proyekPekerja()->where('status', \App\Enums\StatusPekerja::AKTIF)->exists())
                <span class="badge badge-warning badge-sm gap-1">
                    <x-lucide-alert-circle class="size-3" /> Tambah pekerja aktif dulu
                </span>
            @endunless
        </div>
        <button type="button" class="btn btn-sm btn-primary gap-1" wire:click="showForm(true)"
            @if ($form) disabled @endif
            @unless ($proyek->proyekPekerja()->where('status', \App\Enums\StatusPekerja::AKTIF)->exists()) disabled @endunless>
            <x-lucide-plus class="size-4" /> Tambah Periode
        </button>
    </div>

    <div class="card border border-base-300 bg-base-100 w-full {{ $form ? 'block' : 'hidden' }}">
        <div class="card-body p-0">
            <div class="card-title px-5 py-3 border-b border-b-slate-300 text-sm flex items-center justify-between">
                <div class="flex-auto">
                    Formulir {{ $editData ? 'Ubah' : 'Tambah' }} Penggajian Proyek
                </div>

                <button type="button" class="btn bg-red-500 text-white btn-xs" wire:click="showForm(false)">
                    Tutup Formulir
                </button>
            </div>
            <form wire:submit="actionForm">
                <div class="w-full grid grid-cols-6 px-6 pb-2 gap-3">

                    <div class="col-span-6 md:col-span-6 lg:col-span-3">
                        <label for="nama_periode"
                            class="block text-sm font-medium mb-2 {{ $errors->has('state.nama_periode') ? 'text-red-500' : '' }}">
                            Nama Periode :
                            <span class="text-red-500 text-xs">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" wire:model="state.nama_periode" id="nama_periode" name="nama_periode"
                                class="w-full input @error('state.nama_periode') input-error @enderror"
                                aria-describedby="nama_periode-helper" placeholder="Masukan Nama Periode..."
                                @if (!isset($editData)) required @endif autocomplete="false">
                            <div
                                class="absolute inset-y-0 inset-e-0 {{ $errors->has('state.nama_periode') ? 'flex' : 'hidden' }} items-center pointer-events-none pe-3">
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
                        @error('state.nama_periode')
                            <p class="text-xs text-red-600 mt-1" id="nama_periode-helper">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="col-span-6 lg:col-span-3">
                        <label for="periode_mulai"
                            class="block text-sm font-medium mb-2 {{ $errors->has('state.periode_mulai') ? 'text-red-500' : '' }}">
                            Periode Mulai :
                            <span class="text-red-500 text-xs">*</span>
                        </label>
                        <div class="relative">
                            <input type="date" wire:model="state.periode_mulai" id="periode_mulai"
                                name="periode_mulai"
                                class="w-full input @error('state.periode_mulai') input-error @enderror"
                                aria-describedby="periode_mulai-helper"
                                @if (!isset($editData)) required @endif autocomplete="false">
                            <div
                                class="absolute inset-y-0 inset-e-0 {{ $errors->has('state.periode_mulai') ? 'flex' : 'hidden' }} items-center pointer-events-none pe-3">
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
                        @error('state.periode_mulai')
                            <p class="text-xs text-red-600 mt-1" id="periode_mulai-helper">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="col-span-6 lg:col-span-3">
                        <label for="periode_selesai"
                            class="block text-sm font-medium mb-2 {{ $errors->has('state.periode_selesai') ? 'text-red-500' : '' }}">
                            Periode Selesai :
                            <span class="text-red-500 text-xs">*</span>
                        </label>
                        <div class="relative">
                            <input type="date" wire:model="state.periode_selesai" id="periode_selesai"
                                name="periode_selesai"
                                class="w-full input @error('state.periode_selesai') input-error @enderror"
                                aria-describedby="periode_selesai-helper"
                                @if (!isset($editData)) required @endif autocomplete="false">
                            <div
                                class="absolute inset-y-0 inset-e-0 {{ $errors->has('state.periode_selesai') ? 'flex' : 'hidden' }} items-center pointer-events-none pe-3">
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
                        @error('state.periode_selesai')
                            <p class="text-xs text-red-600 mt-1" id="periode_selesai-helper">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="col-span-6 lg:col-span-3">
                        <label for="jam_kerja"
                            class="block text-sm font-medium mb-2 {{ $errors->has('state.jam_kerja') ? 'text-red-500' : '' }}">
                            Jam Kerja :
                            <span class="text-red-500 text-xs">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" wire:model="state.jam_kerja" id="jam_kerja" name="jam_kerja"
                                min="0" max="255" step="1"
                                class="w-full input @error('state.jam_kerja') input-error @enderror"
                                aria-describedby="jam_kerja-helper" placeholder="Masukan Jam Kerja..."
                                @if (!isset($editData)) required @endif autocomplete="false">
                            <div
                                class="absolute inset-y-0 inset-e-0 {{ $errors->has('state.jam_kerja') ? 'flex' : 'hidden' }} items-center pointer-events-none pe-3">
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
                        @error('state.jam_kerja')
                            <p class="text-xs text-red-600 mt-1" id="jam_kerja-helper">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="col-span-6 lg:col-span-3">
                        <label for="status"
                            class="block text-sm font-medium mb-2 {{ $errors->has('state.status') ? 'text-red-500' : '' }}">
                            Status Penggajian :
                            <span class="text-red-500 text-xs">*</span>
                        </label>
                        <div class="relative">
                            <select wire:model="state.status" id="status" name="status"
                                class="w-full select @error('state.status') select-error @enderror"
                                aria-describedby="status-helper" required autocomplete="false">
                                <option disabled>Pilih Status</option>
                                @foreach ($statusOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            <div
                                class="absolute inset-y-0 inset-e-6 {{ $errors->has('state.status') ? 'flex' : 'hidden' }} items-center pointer-events-none pe-3">
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
                        @error('state.status')
                            <p class="text-xs text-red-600 mt-1" id="status-helper">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="col-span-6">
                        <label for="keterangan"
                            class="block text-sm font-medium mb-2 {{ $errors->has('state.keterangan') ? 'text-red-500' : '' }}">
                            Keterangan :
                        </label>
                        <div class="relative">
                            <textarea wire:model="state.keterangan" id="keterangan" name="keterangan" rows="3"
                                class="w-full textarea @error('state.keterangan') textarea-error @enderror" aria-describedby="keterangan-helper"
                                placeholder="Masukan Keterangan..."></textarea>
                            @error('state.keterangan')
                                <p class="text-xs text-red-600 mt-1" id="keterangan-helper">
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
                Formulir {{ $editData ? 'Ubah' : 'Tambah' }} Penggajian Proyek
            </div>
        </div>
    </div>

    <div class="w-full grid grid-cols-12 {{ $form ? 'hidden' : 'block' }}">
        <div class="relative w-full col-span-12 md:col-span-8 lg:col-span-4">
            <label class="sr-only" for="filter-search-data-penggajian">Cari Data :</label>
            <input type="text" name="filter-search-data-penggajian" id="filter-search-data-penggajian"
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

    <div class="overflow-x-auto border rounded-lg border-base-300 {{ $form ? 'hidden' : 'block' }}">
        <table class="table table-sm table-pin-rows table-pin-cols">
            <thead>
                <tr>
                    <td class="text-center" width="6%">No.</td>
                    <td>
                        <x-table.th label="Nama Periode" field="nama_periode" :orderBy="$order_by" :orderType="$order_type" />
                    </td>
                    <td>
                        <x-table.th label="Mulai" field="periode_mulai" :orderBy="$order_by" :orderType="$order_type" />
                    </td>
                    <td>
                        <x-table.th label="Selesai" field="periode_selesai" :orderBy="$order_by" :orderType="$order_type" />
                    </td>
                    <td>
                        <x-table.th label="Jam Kerja" field="jam_kerja" :orderBy="$order_by" :orderType="$order_type" />
                    </td>
                    <td>Keterangan</td>
                    <td>Status</td>
                    <th class="text-center" width="10%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $item)
                    <tr>
                        <td class="text-center bg-slate-200">
                            {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}.</td>
                        <td>{{ $item->nama_periode ?? '-' }}</td>
                        <td>{{ $item->periode_mulai?->format('d/m/Y') ?? '-' }}</td>
                        <td>{{ $item->periode_selesai?->format('d/m/Y') ?? '-' }}</td>
                        <td class="text-right">{{ $item->jam_kerja ?? '-' }} Jam</td>
                        <td class="max-w-xs truncate" title="{{ $item->keterangan }}">{{ $item->keterangan ?? '-' }}
                        </td>
                        <td>
                            @if ($item->status === \App\Enums\StatusPenggajian::AKTIF)
                                <span class="badge badge-success">{{ $item->status->label() }}</span>
                            @else
                                <span class="badge badge-ghost">{{ $item->status->label() }}</span>
                            @endif
                        </td>
                        <th class="text-center">
                            <button type="button" class="btn btn-xs btn-neutral w-full font-normal tracking-wider"
                                popovertarget="popover-penggajian-{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}"
                                style="anchor-name:--anchor-penggajian-{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}">
                                Aksi
                            </button>
                            <div class="dropdown dropdown-end menu w-auto rounded-box bg-base-100 border border-base-300 shadow-lg text-xs flex flex-col gap-1 px-4"
                                popover
                                id="popover-penggajian-{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}"
                                style="position-anchor:--anchor-penggajian-{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}">
                                <h5 class="text-center">Aksi Data</h5>
                                <hr class="border-t border-t-slate-300 my-1">
                                <a href="{{ route('penggajian.detail', ['penggajian' => $item->id]) }}"
                                    class="btn btn-xs btn-outline w-full font-normal tracking-wider"
                                    popovertarget="popover-penggajian-{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}"
                                    wire:navigate>
                                    Detail Data
                                </a>
                                <button type="button"
                                    class="btn btn-xs btn-outline w-full font-normal tracking-wider"
                                    popovertarget="popover-penggajian-{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}"
                                    wire:click="doEdit('{{ $item->id }}')">
                                    Edit Data
                                </button>
                                <button type="button"
                                    popovertarget="popover-penggajian-{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}"
                                    class="btn btn-xs btn-outline w-full font-normal tracking-wider delete-btn"
                                    data-id="{{ $item->id }}" data-target="master-data.penggajian.main-index">
                                    Hapus Data
                                </button>
                            </div>
                        </th>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center p-2">Belum Ada Data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="w-full {{ $form ? 'hidden' : 'block' }}">
        {{ $data->onEachSide(1)->links() }}
    </div>
</div>
