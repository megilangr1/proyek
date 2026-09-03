<div class="flex flex-col gap-3">
    <div class="flex items-center justify-end">
        <button type="button" class="btn btn-sm btn-primary gap-1" wire:click="showForm(true)" @if ($form) disabled @endif>
            <x-lucide-plus class="size-4" /> Tambah Pengeluaran
        </button>
    </div>

    <div class="card border border-base-300 bg-base-100 w-full {{ $form ? 'block' : 'hidden' }}" data-motion="scale-in">
        <div class="card-body p-0">
            <div class="card-title px-5 py-3 border-b border-base-300 text-sm flex items-center justify-between">
                <div class="flex-auto flex items-center gap-2">
                    <x-lucide-file-pen-line class="size-4 text-primary" />
                    Formulir {{ $editData ? 'Ubah' : 'Tambah' }} Pengeluaran
                </div>

                <button type="button" class="btn btn-ghost btn-xs gap-1" wire:click="showForm(false)">
                    <x-lucide-x class="size-3" /> Tutup
                </button>
            </div>
            <form wire:submit="actionForm">
                <div class="w-full grid grid-cols-6 px-6 pb-2 gap-3">

                    <div class="col-span-6 md:col-span-6 lg:col-span-3">
                        <label for="tanggal"
                            class="block text-sm font-medium mb-2 {{ $errors->has('state.tanggal') ? 'text-red-500' : '' }}">
                            Tanggal :
                            <span class="text-red-500 text-xs">*</span>
                        </label>
                        <div class="relative">
                            <input type="date" wire:model="state.tanggal" id="tanggal" name="tanggal"
                                class="w-full input @error('state.tanggal') input-error @enderror"
                                aria-describedby="tanggal-helper" required autocomplete="off">
                            <div
                                class="absolute inset-y-0 inset-e-0 {{ $errors->has('state.tanggal') ? 'flex' : 'hidden' }} items-center pointer-events-none pe-3">
                                <svg class="shrink-0 size-4 text-red-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" x2="12" y1="8" y2="12"></line>
                                    <line x1="12" x2="12.01" y1="16" y2="16"></line>
                                </svg>
                            </div>
                        </div>
                        @error('state.tanggal')
                            <p class="text-xs text-red-600 mt-1" id="tanggal-helper">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="col-span-6 md:col-span-6 lg:col-span-3">
                        <label for="kategori"
                            class="block text-sm font-medium mb-2 {{ $errors->has('state.kategori') ? 'text-red-500' : '' }}">
                            Kategori :
                            <span class="text-red-500 text-xs">*</span>
                        </label>
                        <div class="relative">
                            <select wire:model="state.kategori" id="kategori" name="kategori"
                                class="w-full select @error('state.kategori') select-error @enderror" required>
                                <option disabled>Pilih Kategori</option>
                                @foreach ($kategoriOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            <div
                                class="absolute inset-y-0 inset-e-6 {{ $errors->has('state.kategori') ? 'flex' : 'hidden' }} items-center pointer-events-none pe-3">
                                <svg class="shrink-0 size-4 text-red-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" x2="12" y1="8" y2="12"></line>
                                    <line x1="12" x2="12.01" y1="16" y2="16"></line>
                                </svg>
                            </div>
                        </div>
                        @error('state.kategori')
                            <p class="text-xs text-red-600 mt-1" id="kategori-helper">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="col-span-6">
                        <label for="nama_item"
                            class="block text-sm font-medium mb-2 {{ $errors->has('state.nama_item') ? 'text-red-500' : '' }}">
                            Nama Item / Bahan :
                            <span class="text-red-500 text-xs">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" wire:model="state.nama_item" id="nama_item" name="nama_item"
                                class="w-full input @error('state.nama_item') input-error @enderror"
                                placeholder="Contoh: Semen 50kg, Besi 12mm..." required autocomplete="off">
                            <div
                                class="absolute inset-y-0 inset-e-0 {{ $errors->has('state.nama_item') ? 'flex' : 'hidden' }} items-center pointer-events-none pe-3">
                                <svg class="shrink-0 size-4 text-red-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" x2="12" y1="8" y2="12"></line>
                                    <line x1="12" x2="12.01" y1="16" y2="16"></line>
                                </svg>
                            </div>
                        </div>
                        @error('state.nama_item')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-6 md:col-span-6 lg:col-span-3">
                        <label for="nominal"
                            class="block text-sm font-medium mb-2 {{ $errors->has('state.nominal') ? 'text-red-500' : '' }}">
                            Nominal :
                            <span class="text-red-500 text-xs">*</span>
                        </label>

                        <div class="relative">
                            <input type="text" wire:model="state.nominal_text" id="nominal" name="nominal"
                                class="w-full ps-14 text-right input @error('state.nominal') input-error @enderror"
                                aria-describedby="nominal-helper" placeholder="Contoh : 1.500.000" autocomplete="off" x-data
                                x-on:input="
                                    const raw = $el.value.replace(/[^\d]/g, '');
                                    $wire.set('state.nominal', raw);
                                    $wire.set('state.nominal_text', new Intl.NumberFormat('id-ID').format(raw));
                                "
                                required>

                            <div class="absolute inset-y-0 inset-s-0 flex items-center pointer-events-none z-20 ps-4">
                                <span class="text-gray-500 text-sm font-semibold">
                                    Rp.
                                </span>
                            </div>

                            <div
                                class="absolute inset-y-0 inset-e-0 {{ $errors->has('state.nominal') ? 'flex' : 'hidden' }} items-center pointer-events-none pe-3">
                                <svg class="shrink-0 size-4 text-red-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" x2="12" y1="8" y2="12">
                                    </line>
                                    <line x1="12" x2="12.01" y1="16" y2="16">
                                    </line>
                                </svg>
                            </div>
                        </div>
                        @error('state.nominal')
                            <p class="text-xs text-red-600 mt-1" id="nominal-helper">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="col-span-6 md:col-span-6 lg:col-span-3">
                        <label for="status"
                            class="block text-sm font-medium mb-2 {{ $errors->has('state.status') ? 'text-red-500' : '' }}">
                            Status :
                            <span class="text-red-500 text-xs">*</span>
                        </label>
                        <div class="relative">
                            <select wire:model="state.status" id="status" name="status"
                                class="w-full select @error('state.status') select-error @enderror" required>
                                <option disabled>Pilih Status</option>
                                @foreach ($statusOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            <div
                                class="absolute inset-y-0 inset-e-6 {{ $errors->has('state.status') ? 'flex' : 'hidden' }} items-center pointer-events-none pe-3">
                                <svg class="shrink-0 size-4 text-red-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" x2="12" y1="8" y2="12"></line>
                                    <line x1="12" x2="12.01" y1="16" y2="16"></line>
                                </svg>
                            </div>
                        </div>
                        @error('state.status')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-6">
                        <label for="keterangan"
                            class="block text-sm font-medium mb-2 {{ $errors->has('state.keterangan') ? 'text-red-500' : '' }}">
                            Keterangan :
                        </label>
                        <div class="relative">
                            <textarea wire:model="state.keterangan" id="keterangan" name="keterangan" rows="2"
                                class="w-full textarea @error('state.keterangan') textarea-error @enderror"
                                placeholder="Catatan tambahan (opsional)..."></textarea>
                            @error('state.keterangan')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
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
                <x-lucide-info class="size-3" /> Formulir {{ $editData ? 'Ubah' : 'Tambah' }} Pengeluaran — nominal pakai format Rupiah
            </div>
        </div>
    </div>

    <div class="w-full grid grid-cols-12 {{ $form ? 'hidden' : 'block' }}" data-motion="fade-up">
        <div class="relative w-full col-span-12 md:col-span-8 lg:col-span-4">
            <label class="sr-only" for="filter-search">Cari Data :</label>
            <input type="text" wire:model.live.debounce.500ms="search"
                class="py-2.5 px-3 ps-9 block w-full border border-base-300 bg-base-100 text-sm rounded-lg outline-none focus:border-primary focus:ring-1 focus:ring-primary"
                placeholder="Cari nama item, keterangan...">
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
                    <th><x-table.th label="Tanggal" field="tanggal" :orderBy="$order_by" :orderType="$order_type" /></th>
                    <th><x-table.th label="Kategori" field="kategori" :orderBy="$order_by" :orderType="$order_type" /></th>
                    <th><x-table.th label="Nama Item" field="nama_item" :orderBy="$order_by" :orderType="$order_type" /></th>
                    <th><x-table.th label="Nominal" field="nominal" :orderBy="$order_by" :orderType="$order_type" /></th>
                    <th>Keterangan</th>
                    <th>Status</th>
                    <th class="text-center" width="10%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $item)
                    <tr class="hover:bg-base-200/50 transition-colors">
                        <td class="text-center bg-base-200 font-mono text-xs">
                            {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}.</td>
                        <td class="whitespace-nowrap text-xs">{{ $item->tanggal?->format('d/m/Y') ?? '-' }}</td>
                        <td><span class="badge badge-outline badge-sm">{{ $item->kategori->label() }}</span></td>
                        <td class="font-medium">{{ $item->nama_item }}</td>
                        <td class="text-right whitespace-nowrap font-mono text-xs">Rp {{ number_format((float) $item->nominal, 0, ',', '.') }}</td>
                        <td class="max-w-xs truncate text-xs" title="{{ $item->keterangan }}">{{ $item->keterangan ?? '-' }}</td>
                        <td>
                            @if ($item->status === \App\Enums\StatusPengeluaran::AKTIF)
                                <span class="badge badge-success badge-sm gap-1"><span class="size-1.5 rounded-full bg-white"></span>{{ $item->status->label() }}</span>
                            @else
                                <span class="badge badge-ghost badge-sm">{{ $item->status->label() }}</span>
                            @endif
                        </td>
                        <th class="text-center">
                            <button type="button" class="btn btn-xs btn-neutral w-full font-normal tracking-wider"
                                popovertarget="popover-{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}"
                                style="anchor-name:--anchor-{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}">
                                Aksi
                            </button>
                            <div class="dropdown dropdown-end menu w-auto rounded-box bg-base-100 border border-base-300 shadow-lg text-xs flex flex-col gap-1 px-4"
                                popover id="popover-{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}"
                                style="position-anchor:--anchor-{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}">
                                <h5 class="text-center flex items-center justify-center gap-1"><x-lucide-settings-2 class="size-3" /> Aksi Data</h5>
                                <hr class="border-t border-base-300 my-1">
                                <button type="button" class="btn btn-xs btn-outline w-full gap-1"
                                    popovertarget="popover-{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}"
                                    wire:click="doEdit('{{ $item->id }}')">
                                    <x-lucide-pencil class="size-3" /> Edit Data
                                </button>
                                <button type="button"
                                    popovertarget="popover-{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}"
                                    class="btn btn-xs btn-outline w-full gap-1 delete-btn text-error" data-id="{{ $item->id }}"
                                    data-target="master-data.proyek-pengeluaran.main-index">
                                    <x-lucide-trash-2 class="size-3" /> Hapus Data
                                </button>
                            </div>
                        </th>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center p-8">
                            <div class="flex flex-col items-center gap-2 text-base-content/60">
                                <x-lucide-inbox class="size-8" />
                                <span class="text-sm">Belum ada pengeluaran — tambah data pertama!</span>
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
