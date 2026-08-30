<div class="flex flex-col gap-3">
    <x-main.page-header title="Data Pengguna Aplikasi">
        <button type="button" class="btn btn-neutral btn-sm" wire:click="showForm(true)"
            @if ($form) disabled @endif>Tambah Data</button>
    </x-main.page-header>

    <div class="card border border-slate-300 bg-base-100 w-full {{ $form ? 'block' : 'hidden' }}">
        <div class="card-body p-0">
            <div class="card-title px-5 py-3 border-b border-b-slate-300 text-sm flex items-center justify-between">
                <div class="flex-auto">
                    Formulir {{ $editData ? 'Ubah' : 'Tambah' }} Pengguna Aplikasi
                </div>

                <button type="button" class="btn bg-red-500 text-white btn-xs" wire:click="showForm(false)">
                    Tutup Formulir
                </button>
            </div>
            <form wire:submit="actionForm">
                <div class="w-full grid grid-cols-6 px-6 pb-2 gap-3">

                    <div class="col-span-6 md:col-span-6 lg:col-span-6">
                        <label for="name"
                            class="block text-sm font-medium mb-2 {{ $errors->has('state.name') ? 'text-red-500' : '' }}">
                            Nama Lengkap :
                            <span class="text-red-500 text-xs">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" wire:model="state.name" id="name" name="name"
                                class="w-full input @error('state.name') input-error @enderror"
                                aria-describedby="name-helper" placeholder="Masukan Nama Lengkap..." required
                                autocomplete="false">
                            <div
                                class="absolute inset-y-0 inset-e-0 {{ $errors->has('state.name') ? 'flex' : 'hidden' }} items-center pointer-events-none pe-3">
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
                        @error('state.name')
                            <p class="text-xs text-red-600 mt-1" id="name-helper">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="col-span-6 lg:col-span-2">
                        <label for="email"
                            class="block text-sm font-medium mb-2 {{ $errors->has('state.email') ? 'text-red-500' : '' }}">
                            Email Pengguna :
                            <span class="text-red-500 text-xs">*</span>
                        </label>
                        <div class="relative">
                            <input type="email" wire:model="state.email" id="email" name="email"
                                class="w-full input @error('state.email') input-error @enderror"
                                aria-describedby="email-helper" placeholder="Ex. example@mail.com" required
                                autocomplete="false">
                            <div
                                class="absolute inset-y-0 inset-e-0 {{ $errors->has('state.email') ? 'flex' : 'hidden' }} items-center pointer-events-none pe-3">
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
                        @error('state.email')
                            <p class="text-xs text-red-600 mt-1" id="email-helper">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="col-span-6 lg:col-span-2">
                        <label for="password"
                            class="block text-sm font-medium mb-2 {{ $errors->has('state.password') ? 'text-red-500' : '' }}">
                            Password Pengguna :
                            <span class="text-red-500 text-xs">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" wire:model="state.password" id="password" name="password"
                                class="w-full input @error('state.password') input-error @enderror"
                                aria-describedby="password-helper" placeholder="********"
                                @if (!isset($editData)) required @endif autocomplete="false">
                            <div
                                class="absolute inset-y-0 inset-e-0 {{ $errors->has('state.password') ? 'flex' : 'hidden' }} items-center pointer-events-none pe-3">
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
                        @error('state.password')
                            <p class="text-xs text-red-600 mt-1" id="password-helper">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="col-span-6 lg:col-span-2">
                        <label for="password_confirmation"
                            class="block text-sm font-medium mb-2 {{ $errors->has('state.password_confirmation') ? 'text-red-500' : '' }}">
                            Konfirmasi Password :
                            <span class="text-red-500 text-xs">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" wire:model="state.password_confirmation"
                                id="password_confirmation" name="password_confirmation"
                                class="w-full input @error('state.password_confirmation') input-error @enderror"
                                aria-describedby="password_confirmation-helper" placeholder="Ex. example@mail.com"
                                @if (!isset($editData)) required @endif autocomplete="false">
                            <div
                                class="absolute inset-y-0 inset-e-0 {{ $errors->has('state.password_confirmation') ? 'flex' : 'hidden' }} items-center pointer-events-none pe-3">
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
                        @error('state.password_confirmation')
                            <p class="text-xs text-red-600 mt-1" id="password_confirmation-helper">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="col-span-6">
                        <label for="roles"
                            class="block text-sm font-medium mb-2 {{ $errors->has('state.name') ? 'text-red-500' : '' }}">
                            Hak Akses Pengguna:
                            <span class="text-red-500 text-xs">*</span>
                        </label>
                        <div class="relative">
                            <select wire:model="state.roles" id="roles" name="roles"
                                class="w-full select @error('state.roles') select-error @enderror"
                                aria-describedby="roles-helper" placeholder="Masukan Level Pengguna..." required
                                autocomplete="false">
                                <option disabled>Pilih Hak Akses</option>
                                @foreach ($staticData['roles'] as $item)
                                    <option value="{{ $item['name'] }}">{{ $item['name'] }}</option>
                                @endforeach
                            </select>
                            <div
                                class="absolute inset-y-0 inset-e-6 {{ $errors->has('state.roles') ? 'flex' : 'hidden' }} items-center pointer-events-none pe-3">
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
                        @error('state.roles')
                            <p class="text-xs text-red-600 mt-1" id="roles-helper">
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
                Formulir {{ $editData ? 'Ubah' : 'Tambah' }} Pengguna Aplikasi
            </div>
        </div>
    </div>

    <div class="w-full grid grid-cols-12 {{ $form ? 'hidden' : 'block' }}">
        <div class="relative w-full col-span-12 md:col-span-8 lg:col-span-4">
            <label class="sr-only" for="filter-search-data-pengguna">Cari Data :</label>
            <input type="text" name="filter-search-data-pengguna" id="filter-search-data-pengguna"
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
                    <td class="text-center" width="8%">No.</td>
                    <td>
                        <x-table.th label="Nama" field="name" :orderBy="$order_by" :orderType="$order_type" />
                    </td>
                    <td>
                        <x-table.th label="Email" field="email" :orderBy="$order_by" :orderType="$order_type" />
                    </td>
                    <td>Hak Akses</td>
                    <th class="text-center" width="10%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $item)
                    <tr>
                        <td class="text-center bg-slate-200">
                            {{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}.</td>
                        <td>{{ $item->name ?? '-' }}</td>
                        <td>{{ $item->email ?? '-' }}</td>
                        <td>{{ $item->user_role ?? '-' }}</td>
                        <th class="text-center">
                            <button type="button" class="btn btn-xs btn-neutral w-full font-normal tracking-wider"
                                popovertarget="popover-{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}"
                                style="anchor-name:--anchor-{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}">
                                Aksi
                            </button>
                            <div class="dropdown dropdown-end menu w-auto rounded-box bg-base-100 border border-slate-300 shadow-lg text-xs flex flex-col gap-1 px-4"
                                popover
                                id="popover-{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}"
                                style="position-anchor:--anchor-{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}">
                                <h5 class="text-center">Aksi Data</h5>
                                <hr class="border-t border-t-slate-300 my-1">
                                <button type="button"
                                    class="btn btn-xs btn-outline w-full font-normal tracking-wider"
                                    popovertarget="popover-{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}"
                                    wire:click="doEdit('{{ $item->id }}')">
                                    Edit Data
                                </button>
                                <button type="button"
                                    popovertarget="popover-{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}"
                                    class="btn btn-xs btn-outline w-full font-normal tracking-wider delete-btn"
                                    popovertarget="popover-{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}"
                                    data-id="{{ $item->id }}" data-target="master-data.pengguna.main-index">
                                    Hapus Data
                                </button>
                            </div>
                        </th>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center p-2">Belum Ada Data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="w-full {{ $form ? 'hidden' : 'block' }}">
        {{ $data->onEachSide(1)->links() }}
    </div>
</div>
