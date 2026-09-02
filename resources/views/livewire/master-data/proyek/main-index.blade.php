<div class="flex flex-col gap-3">
    <x-main.page-header title="Data Proyek">
        <button type="button" class="btn btn-neutral btn-sm" wire:click="showForm(true)"
            @if ($form) disabled @endif>Tambah Data</button>
    </x-main.page-header>

    <div class="card border border-base-300 bg-base-100 w-full {{ $form ? 'block' : 'hidden' }}">
        <div class="card-body p-0">
            <div class="card-title px-5 py-3 border-b border-b-slate-300 text-sm flex items-center justify-between">
                <div class="flex-auto">
                    Formulir {{ $editData ? 'Ubah' : 'Tambah' }} Proyek
                </div>

                <button type="button" class="btn bg-red-500 text-white btn-xs" wire:click="showForm(false)">
                    Tutup Formulir
                </button>
            </div>
            <form wire:submit="actionForm">
                <div class="w-full grid grid-cols-6 px-6 pb-2 gap-3">

                    <div class="col-span-6 md:col-span-6 lg:col-span-3">
                        <label for="nama_proyek"
                            class="block text-sm font-medium mb-2 {{ $errors->has('state.nama_proyek') ? 'text-red-500' : '' }}">
                            Nama Proyek :
                            <span class="text-red-500 text-xs">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" wire:model="state.nama_proyek" id="nama_proyek" name="nama_proyek"
                                class="w-full input @error('state.nama_proyek') input-error @enderror"
                                aria-describedby="nama_proyek-helper" placeholder="Masukan Nama Proyek..."
                                @if (!isset($editData)) required @endif autocomplete="false">
                            <div
                                class="absolute inset-y-0 inset-e-0 {{ $errors->has('state.nama_proyek') ? 'flex' : 'hidden' }} items-center pointer-events-none pe-3">
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
                        @error('state.nama_proyek')
                            <p class="text-xs text-red-600 mt-1" id="nama_proyek-helper">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="col-span-6 md:col-span-6 lg:col-span-3">
                        <label for="pemilik"
                            class="block text-sm font-medium mb-2 {{ $errors->has('state.pemilik') ? 'text-red-500' : '' }}">
                            Pemilik :
                            <span class="text-red-500 text-xs">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" wire:model="state.pemilik" id="pemilik" name="pemilik"
                                class="w-full input @error('state.pemilik') input-error @enderror"
                                aria-describedby="pemilik-helper" placeholder="Masukan Pemilik..."
                                @if (!isset($editData)) required @endif autocomplete="false">
                            <div
                                class="absolute inset-y-0 inset-e-0 {{ $errors->has('state.pemilik') ? 'flex' : 'hidden' }} items-center pointer-events-none pe-3">
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
                        @error('state.pemilik')
                            <p class="text-xs text-red-600 mt-1" id="pemilik-helper">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="col-span-6 lg:col-span-3">
                        <label for="tanggal_mulai"
                            class="block text-sm font-medium mb-2 {{ $errors->has('state.tanggal_mulai') ? 'text-red-500' : '' }}">
                            Tanggal Mulai :
                            <span class="text-red-500 text-xs">*</span>
                        </label>
                        <div class="relative">
                            <input type="date" wire:model="state.tanggal_mulai" id="tanggal_mulai"
                                name="tanggal_mulai"
                                class="w-full input @error('state.tanggal_mulai') input-error @enderror"
                                aria-describedby="tanggal_mulai-helper"
                                @if (!isset($editData)) required @endif autocomplete="false">
                            <div
                                class="absolute inset-y-0 inset-e-0 {{ $errors->has('state.tanggal_mulai') ? 'flex' : 'hidden' }} items-center pointer-events-none pe-3">
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
                        @error('state.tanggal_mulai')
                            <p class="text-xs text-red-600 mt-1" id="tanggal_mulai-helper">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="col-span-6 lg:col-span-3">
                        <label for="tanggal_selesai"
                            class="block text-sm font-medium mb-2 {{ $errors->has('state.tanggal_selesai') ? 'text-red-500' : '' }}">
                            Tanggal Selesai :
                            <span class="text-red-500 text-xs">*</span>
                        </label>
                        <div class="relative">
                            <input type="date" wire:model="state.tanggal_selesai" id="tanggal_selesai"
                                name="tanggal_selesai"
                                class="w-full input @error('state.tanggal_selesai') input-error @enderror"
                                aria-describedby="tanggal_selesai-helper"
                                @if (!isset($editData)) required @endif autocomplete="false">
                            <div
                                class="absolute inset-y-0 inset-e-0 {{ $errors->has('state.tanggal_selesai') ? 'flex' : 'hidden' }} items-center pointer-events-none pe-3">
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
                        @error('state.tanggal_selesai')
                            <p class="text-xs text-red-600 mt-1" id="tanggal_selesai-helper">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="col-span-6">
                        <label for="lokasi"
                            class="block text-sm font-medium mb-2 {{ $errors->has('state.lokasi') ? 'text-red-500' : '' }}">
                            Lokasi :
                            <span class="text-red-500 text-xs">*</span>
                        </label>
                        <div class="relative">
                            <textarea wire:model="state.lokasi" id="lokasi" name="lokasi" rows="3"
                                class="w-full textarea @error('state.lokasi') textarea-error @enderror" aria-describedby="lokasi-helper"
                                placeholder="Masukan Lokasi..." @if (!isset($editData)) required @endif></textarea>
                            @error('state.lokasi')
                                <p class="text-xs text-red-600 mt-1" id="lokasi-helper">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="col-span-6 lg:col-span-6">
                        <label for="status"
                            class="block text-sm font-medium mb-2 {{ $errors->has('state.status') ? 'text-red-500' : '' }}">
                            Status Proyek :
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
                Formulir {{ $editData ? 'Ubah' : 'Tambah' }} Proyek
            </div>
        </div>
    </div>

    <div class="w-full grid grid-cols-12 {{ $form ? 'hidden' : 'block' }}">
        <div class="relative w-full col-span-12 md:col-span-8 lg:col-span-4">
            <label class="sr-only" for="filter-search-data-proyek">Cari Data :</label>
            <input type="text" name="filter-search-data-proyek" id="filter-search-data-proyek"
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
                        <x-table.th label="Kode" field="kode_proyek" :orderBy="$order_by" :orderType="$order_type" />
                    </td>
                    <td>
                        <x-table.th label="Nama Proyek" field="nama_proyek" :orderBy="$order_by" :orderType="$order_type" />
                    </td>
                    <td>
                        <x-table.th label="Pemilik" field="pemilik" :orderBy="$order_by" :orderType="$order_type" />
                    </td>
                    <td>Lokasi</td>
                    <td>
                        <x-table.th label="Mulai" field="tanggal_mulai" :orderBy="$order_by" :orderType="$order_type" />
                    </td>
                    <td>
                        <x-table.th label="Selesai" field="tanggal_selesai" :orderBy="$order_by" :orderType="$order_type" />
                    </td>
                    <td>Status</td>
                    <th class="text-center" width="10%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $item)
                    <tr>
                        <td class="text-center bg-slate-200">
                            {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}.</td>
                        <td>{{ $item->kode_proyek ?? '-' }}</td>
                        <td>{{ $item->nama_proyek ?? '-' }}</td>
                        <td>{{ $item->pemilik ?? '-' }}</td>
                        <td class="max-w-xs truncate" title="{{ $item->lokasi }}">{{ $item->lokasi ?? '-' }}</td>
                        <td>{{ $item->tanggal_mulai?->format('d/m/Y') ?? '-' }}</td>
                        <td>{{ $item->tanggal_selesai?->format('d/m/Y') ?? '-' }}</td>
                        <td>
                            @if ($item->status === \App\Enums\StatusProyek::AKTIF)
                                <span class="badge badge-success">{{ $item->status->label() }}</span>
                            @else
                                <span class="badge badge-ghost">{{ $item->status->label() }}</span>
                            @endif
                        </td>
                        <th class="text-center">
                            <button type="button" class="btn btn-xs btn-neutral w-full font-normal tracking-wider"
                                popovertarget="popover-proyek-{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}"
                                style="anchor-name:--anchor-proyek-{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}">
                                Aksi
                            </button>
                            <div class="dropdown dropdown-end menu w-auto rounded-box bg-base-100 border border-base-300 shadow-lg text-xs flex flex-col gap-1 px-4"
                                popover
                                id="popover-proyek-{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}"
                                style="position-anchor:--anchor-proyek-{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}">
                                <h5 class="text-center">Aksi Data</h5>
                                <hr class="border-t border-t-slate-300 my-1">
                                <a href="{{ route('proyek.pekerja.index', $item->id) }}" wire:navigate
                                    class="btn btn-xs btn-outline w-full font-normal tracking-wider"
                                    popovertarget="popover-proyek-{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}">
                                    Detail Pekerja
                                </a>
                                <button type="button"
                                    class="btn btn-xs btn-outline w-full font-normal tracking-wider"
                                    popovertarget="popover-proyek-{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}"
                                    wire:click="doEdit('{{ $item->id }}')">
                                    Edit Data
                                </button>
                                <button type="button"
                                    popovertarget="popover-proyek-{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}"
                                    class="btn btn-xs btn-outline w-full font-normal tracking-wider delete-btn"
                                    data-id="{{ $item->id }}" data-target="master-data.proyek.main-index">
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
