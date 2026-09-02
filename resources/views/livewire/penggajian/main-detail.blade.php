<div class="flex flex-col gap-3">
    <x-main.page-header title="Detail Penggajian - {{ $proyekPenggajian->nama_periode }}"
        subtitle="{{ $proyekPenggajian->periode_mulai?->format('d/m/Y') ?? '-' }} - {{ $proyekPenggajian->periode_selesai?->format('d/m/Y') ?? '-' }}">
        <a href="{{ route('penggajian.index') }}" class="btn btn-neutral btn-sm" wire:navigate>
            <x-lucide-arrow-left class="size-4" />
            Kembali
        </a>
    </x-main.page-header>

    <div class="collapse bg-base-100 border">
        <input id="informasi-proyek" type="checkbox" />
        <label for="informasi-proyek" class="fixed inset-0 hidden peer-checked:block"></label>
        <div class="collapse-title font-semibold p-0">
            <div class="w-full flex items-center justify-between px-3 py-2 gap-2">
                <h6 class="flex-auto">Informasi Proyek</h6>

                <span class="badge badge-xs badge-neutral h-auto text-[10px]">Buka</span>
            </div>
        </div>
        <div class="collapse-content text-sm z-1 px-3">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                <div>
                    <div class="text-xs font-semibold uppercase">Kode Proyek</div>
                    <div class="text-sm mt-1">{{ $proyekPenggajian->proyek?->kode_proyek ?? '-' }}</div>
                </div>

                <div>
                    <div class="text-xs font-semibold uppercase">Nama Proyek</div>
                    <div class="text-sm mt-1">{{ $proyekPenggajian->proyek?->nama_proyek ?? '-' }}</div>
                </div>

                <div>
                    <div class="text-xs font-semibold uppercase">Pemilik</div>
                    <div class="text-sm mt-1">{{ $proyekPenggajian->proyek?->pemilik ?? '-' }}</div>
                </div>

                <div>
                    <div class="text-xs font-semibold uppercase">Lokasi</div>
                    <div class="text-sm mt-1">{{ $proyekPenggajian->proyek?->lokasi ?? '-' }}</div>
                </div>

                <div>
                    <div class="text-xs font-semibold uppercase">Tanggal Pekerjaan Mulai</div>
                    <div class="text-sm mt-1">
                        {{ $proyekPenggajian->proyek?->tanggal_mulai?->format('d/m/Y') ?? '-' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs font-semibold uppercase">Tanggal Pekerjaan Selesai</div>
                    <div class="text-sm mt-1">
                        {{ $proyekPenggajian->proyek?->tanggal_selesai?->format('d/m/Y') ?? '-' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs font-semibold uppercase">Jumlah Pekerja</div>
                    <div class="text-sm mt-1">{{ $proyekPenggajian->proyek?->proyekPekerja?->count() ?? '??' }}
                        Pekerja
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="collapse bg-base-100 border">
        <input id="informasi-payroll" type="checkbox" />
        <label for="informasi-payroll" class="fixed inset-0 hidden peer-checked:block"></label>
        <div class="collapse-title font-semibold p-0">
            <div class="w-full flex items-center justify-between px-3 py-2 gap-2">
                <h6 class="flex-auto">Informasi Payroll / Penggajian</h6>

                <span class="badge badge-xs badge-neutral h-auto text-[10px]">Buka</span>
            </div>
        </div>
        <div class="collapse-content text-sm z-1 px-3 pb-0">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                <div class="md:col-span-2">
                    <div class="text-xs font-semibold uppercase">Nama Periode</div>
                    <div class="text-sm mt-1">{{ $proyekPenggajian->nama_periode ?? '-' }}</div>
                </div>

                <div>
                    <div class="text-xs font-semibold uppercase">Periode Mulai</div>
                    <div class="text-sm mt-1">{{ $proyekPenggajian->periode_mulai?->format('d/m/Y') ?? '-' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs font-semibold uppercase">Periode Selesai</div>
                    <div class="text-sm mt-1">{{ $proyekPenggajian->periode_selesai?->format('d/m/Y') ?? '-' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs font-semibold uppercase">Jam Kerja</div>
                    <div class="text-sm mt-1">{{ $proyekPenggajian->jam_kerja ?? '-' }} Jam</div>
                </div>

                <div>
                    <div class="text-xs font-semibold uppercase">Status</div>
                    <div class="text-sm mt-1">
                        @if ($proyekPenggajian->status === \App\Enums\StatusPenggajian::AKTIF)
                            <span class="badge badge-success">{{ $proyekPenggajian->status->label() }}</span>
                        @else
                            <span class="badge badge-ghost">{{ $proyekPenggajian->status->label() }}</span>
                        @endif
                    </div>
                </div>

                <div class="md:col-span-2">
                    <div class="text-xs font-semibold uppercase">Keterangan</div>
                    <div class="text-sm mt-1 whitespace-pre-wrap">{{ $proyekPenggajian->keterangan ?? '-' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col gap-2">
        <h6 class="font-semibold">
            Rincian Penggajian Pekerja Proyek
        </h6>
        <hr class="w-[44%] border-t-3">
    </div>

    <div class="overflow-x-auto border rounded-lg border-base-300">
        <table class="table table-sm table-pin-rows table-pin-cols">
            <thead>
                <tr>
                    <td class="text-center" width="6%">No.</td>
                    <td>Nama Pekerja</td>
                    <td>Jabatan</td>
                    <td>Tarif Harian</td>
                    <td>Tarif Overtime</td>
                    <td>Total Hari</td>
                    <td>Total Overtime</td>
                    <td>Gaji Normal</td>
                    <td>Upah Overtime</td>
                    <td>Bonus</td>
                    <td>Potongan</td>
                    <td>Kasbon</td>
                    <td>Total Bersih</td>
                    <td>Status Bayar</td>
                    <th class="text-center" width="10%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($proyekPenggajian->proyekPenggajianPekerja as $item)
                    <tr>
                        <td class="text-center bg-slate-200">
                            {{ $loop->iteration }}.
                        </td>
                        <td>{{ $item->proyekPekerja->nama_pekerja ?? '-' }}</td>
                        <td>{{ $item->jabatan ?? '-' }}</td>
                        <td>
                            <div class="w-full flex items-center justify-between">
                                <span>Rp.</span>
                                {{ number_format($item->tarif_harian ?? 0, 0, ',', '.') }}
                            </div>
                        </td>
                        <td>
                            <div class="w-full flex items-center justify-between">
                                <span>Rp.</span>
                                {{ number_format($item->tarif_overtime ?? 0, 0, ',', '.') }}
                            </div>
                        </td>
                        <td>
                            <div class="w-full flex items-center justify-between">
                                {{ number_format($item->total_hari ?? 0, 0, ',', '.') }}
                                <span>Hari</span>
                            </div>
                        </td>
                        <td>
                            <div class="w-full flex items-center justify-between">
                                {{ number_format($item->total_overtime ?? 0, 0, ',', '.') }}
                                <span>Jam</span>
                            </div>
                        </td>
                        <td>
                            <div class="w-full flex items-center justify-between">
                                <span>Rp.</span>
                                {{ number_format($item->gaji_normal ?? 0, 0, ',', '.') }}
                            </div>
                        </td>
                        <td>
                            <div class="w-full flex items-center justify-between">
                                <span>Rp.</span>
                                {{ number_format($item->upah_overtime ?? 0, 0, ',', '.') }}
                            </div>
                        </td>
                        <td>
                            <div class="w-full flex items-center justify-between">
                                <span>Rp.</span>
                                {{ number_format($item->bonus ?? 0, 0, ',', '.') }}
                            </div>
                        </td>
                        <td>
                            <div class="w-full flex items-center justify-between">
                                <span>Rp.</span>
                                {{ number_format($item->potongan ?? 0, 0, ',', '.') }}
                            </div>
                        </td>
                        <td>
                            <div class="w-full flex items-center justify-between">
                                <span>Rp.</span>
                                {{ number_format($item->kasbon ?? 0, 0, ',', '.') }}
                            </div>
                        </td>
                        <td>
                            <div class="w-full flex items-center justify-between">
                                <span>Rp.</span>
                                {{ number_format($item->total_bersih ?? 0, 0, ',', '.') }}
                            </div>
                        </td>
                        <td>
                            <span
                                class="badge badge-neutral badge-xs h-auto text-[10px] whitespace-nowrap">{{ $item->status_bayar->label() }}</span>
                        </td>
                        <th class="text-center">
                            <button type="button" class="btn btn-xs btn-neutral w-full font-normal tracking-wider"
                                popovertarget="popover-penggajian-{{ $loop->iteration }}"
                                style="anchor-name:--anchor-penggajian-{{ $loop->iteration }}">
                                Aksi
                            </button>
                            <div class="dropdown dropdown-end menu w-auto rounded-box bg-base-100 border border-base-300 shadow-lg text-xs flex flex-col gap-1 px-4"
                                popover id="popover-penggajian-{{ $loop->iteration }}"
                                style="position-anchor:--anchor-penggajian-{{ $loop->iteration }}">
                                <h5 class="text-center">Aksi Data</h5>
                                <hr class="border-t border-t-slate-300 my-1">
                                <button type="button" class="btn btn-xs btn-outline w-full font-normal tracking-wider"
                                    popovertarget="popover-penggajian-{{ $loop->iteration }}"
                                    wire:click="openModalPencatatan('{{ $item->id }}')">
                                    Pencatatan Upah
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

    <input type="checkbox" id="modal_pencatatan_upah" class="modal-toggle" wire:model.live="modal" />
    <div class="modal modal-bottom sm:modal-middle" role="dialog">
        <div class="modal-box flex flex-col p-0 sm:w-[70vw] sm:max-w-full">
            <h3 class="text-lg font-bold px-4 py-3">Formulir Pencatatan Upah</h3>
            <hr class="border-t border-t-slate-300">

            <div class="flex flex-col gap-2 overscroll-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 px-4 py-2">
                    <div>
                        <div class="text-xs font-semibold uppercase">Nama Pekerja</div>
                        <div class="text-sm mt-1">{{ $state['nama_pekerja'] ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-xs font-semibold uppercase">Jabatan</div>
                        <div class="text-sm mt-1">{{ $state['jabatan'] ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-xs font-semibold uppercase">Tarif Harian</div>
                        <div class="text-sm mt-1">Rp. {{ $state['tarif_harian'] ?? '-' }} / Hari</div>
                    </div>
                    <div>
                        <div class="text-xs font-semibold uppercase">Tarif Overtime</div>
                        <div class="text-sm mt-1">Rp. {{ $state['tarif_overtime'] ?? '-' }} / Jam</div>
                    </div>
                </div>

                <div class="overflow-x-auto border-base-300">
                    <table class="table table-sm table-pin-rows table-pin-cols">
                        <thead>
                            <tr>
                                <th class="font-semibold uppercase" width="50%">
                                    Hari
                                </th>
                                <th class="font-semibold uppercase text-center">
                                    Normal
                                </th>
                                <th class="font-semibold uppercase text-center">
                                    Overtime
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($state['hari'] as $key => $item)
                                <tr>
                                    <td>
                                        <div class="w-full flex flex-col items-start justify-center gap-1">
                                            <p class="font-semibold">
                                                {{ $item['hari'] }}
                                            </p>
                                            <span class="text-xs">
                                                {{ $item['tanggal_text'] }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div x-data="{ hours: @entangle('state.hari.' . $key . '.hari_normal').live }"
                                            class="w-full flex items-center justify-center gap-1">
                                            <button type="button" class="btn btn-sm btn-primary"
                                                @click="hours = Math.max(0, Number(hours || 0) - 0.5)"
                                                :disabled="Number(hours || 0) <= 0">
                                                <x-lucide-minus class="size-3 shrink-0" />
                                            </button>

                                            <input type="text" min="0" max="1" step="0.5"
                                                x-model.number="hours"
                                                class="input input-sm input-bordered w-12 md:w-16 lg:w-24 text-center px-3" />

                                            <button type="button" class="btn btn-sm btn-primary"
                                                @click="hours = Math.min(1, Number(hours || 0) + 0.5)"
                                                :disabled="Number(hours || 0) >= 1" type="button">
                                                <x-lucide-plus class="size-3 shrink-0" />
                                            </button>
                                        </div>
                                    </td>
                                    <td>
                                        <div x-data="{ hours: @entangle('state.hari.' . $key . '.jam_overtime').live }"
                                            class="w-full flex items-center justify-center gap-1">
                                            <button type="button" class="btn btn-sm btn-primary"
                                                @click="hours = Math.max(0, Number(hours || 0) - 1)"
                                                :disabled="Number(hours || 0) <= 0">
                                                <x-lucide-minus class="size-3 shrink-0" />
                                            </button>

                                            <input type="text" min="0" step="1"
                                                x-model.number="hours"
                                                class="input input-sm input-bordered w-12 md:w-16 lg:w-24 text-center px-3" />

                                            <button type="button" class="btn  btn-sm btn-primary"
                                                @click="hours = Number(hours || 0) + 1">
                                                <x-lucide-plus class="size-3 shrink-0" />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center p-2">Belum Ada Data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="grid grid-cols-6 gap-2 px-3 py-2">
                    <div class="col-span-6 md:col-span-6 lg:col-span-2">
                        <label for="bonus"
                            class="block text-sm font-medium mb-2 {{ $errors->has('state.bonus') ? 'text-red-500' : '' }}">
                            Bonus :
                            <span class="text-red-500 text-xs">*</span>
                        </label>

                        <div class="relative">
                            <input type="text" wire:model="state.bonus_text" id="bonus" name="bonus"
                                class="w-full ps-14 text-right input @error('state.bonus') input-error @enderror"
                                aria-describedby="bonus-helper" placeholder="Contoh : 150.000" autocomplete="false"
                                x-data
                                x-on:input="
                                    const raw = $el.value.replace(/[^\d]/g, '');
                                    $wire.set('state.bonus', raw);
                                    $wire.set('state.bonus_text', new Intl.NumberFormat('id-ID').format(raw));
                                "
                                required>

                            <div class="absolute inset-y-0 inset-s-0 flex items-center pointer-events-none z-20 ps-4">
                                <span class="text-gray-500 text-sm font-semibold">
                                    Rp.
                                </span>
                            </div>

                            <div
                                class="absolute inset-y-0 inset-e-0 {{ $errors->has('state.bonus') ? 'flex' : 'hidden' }} items-center pointer-events-none pe-3">
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
                        @error('pekerjaState.bonus')
                            <p class="text-xs text-red-600 mt-1" id="bonus-helper">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="col-span-6 md:col-span-6 lg:col-span-2">
                        <label for="potongan"
                            class="block text-sm font-medium mb-2 {{ $errors->has('state.potongan') ? 'text-red-500' : '' }}">
                            Potongan :
                            <span class="text-red-500 text-xs">*</span>
                        </label>

                        <div class="relative">
                            <input type="text" wire:model="state.potongan_text" id="potongan" name="potongan"
                                class="w-full ps-14 text-right input @error('state.potongan') input-error @enderror"
                                aria-describedby="potongan-helper" placeholder="Contoh : 150.000"
                                autocomplete="false" x-data
                                x-on:input="
                                    const raw = $el.value.replace(/[^\d]/g, '');
                                    $wire.set('state.potongan', raw);
                                    $wire.set('state.potongan_text', new Intl.NumberFormat('id-ID').format(raw));
                                "
                                required>

                            <div class="absolute inset-y-0 inset-s-0 flex items-center pointer-events-none z-20 ps-4">
                                <span class="text-gray-500 text-sm font-semibold">
                                    Rp.
                                </span>
                            </div>

                            <div
                                class="absolute inset-y-0 inset-e-0 {{ $errors->has('state.potongan') ? 'flex' : 'hidden' }} items-center pointer-events-none pe-3">
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
                        @error('pekerjaState.potongan')
                            <p class="text-xs text-red-600 mt-1" id="potongan-helper">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="col-span-6 md:col-span-6 lg:col-span-2">
                        <label for="kasbon"
                            class="block text-sm font-medium mb-2 {{ $errors->has('state.kasbon') ? 'text-red-500' : '' }}">
                            Kasbon :
                            <span class="text-red-500 text-xs">*</span>
                        </label>

                        <div class="relative">
                            <input type="text" wire:model="state.kasbon_text" id="kasbon" name="kasbon"
                                class="w-full ps-14 text-right input @error('state.kasbon') input-error @enderror"
                                aria-describedby="kasbon-helper" placeholder="Contoh : 150.000" autocomplete="false"
                                x-data
                                x-on:input="
                                    const raw = $el.value.replace(/[^\d]/g, '');
                                    $wire.set('state.kasbon', raw);
                                    $wire.set('state.kasbon_text', new Intl.NumberFormat('id-ID').format(raw));
                                "
                                required>

                            <div class="absolute inset-y-0 inset-s-0 flex items-center pointer-events-none z-20 ps-4">
                                <span class="text-gray-500 text-sm font-semibold">
                                    Rp.
                                </span>
                            </div>

                            <div
                                class="absolute inset-y-0 inset-e-0 {{ $errors->has('state.kasbon') ? 'flex' : 'hidden' }} items-center pointer-events-none pe-3">
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
                        @error('pekerjaState.kasbon')
                            <p class="text-xs text-red-600 mt-1" id="kasbon-helper">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <div class="w-full flex px-3">
                    <hr class="w-full">
                </div>

                <div class="w-full grid grid-cols-1 lg:grid-cols-2 items-center justify-center px-3 pb-2 gap-2">
                    <span class="text-lg underline underline-offset-8">Total Upah</span>
                    <div class="flex items-center justify-between ps-px pe-2">
                        <span class="text-base">Rp. </span>
                        <span class="text-lg font-semibold">{{ number_format($this->totalUpah, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            <div
                class="px-4 py-2 border-t border-t-slate-300 flex flex-col lg:flex-row items-center lg:justify-end justify-center gap-2 text-xs font-semibold">
                <button type="button" class="btn btn-neutral btn-sm w-full lg:w-auto" wire:click="saveData">
                    <x-lucide-save class="shrink-0 size-4" />

                    Simpan Pencatatan Upah
                </button>
            </div>
        </div>
        <label class="modal-backdrop" for="modal_pencatatan_upah">Close</label>
    </div>
</div>
