<div class="flex flex-col gap-3">
    <x-main.page-header title="Detail Penggajian - {{ $proyekPenggajian->nama_periode }}"
        subtitle="{{ $proyekPenggajian->periode_mulai?->format('d/m/Y') ?? '-' }} - {{ $proyekPenggajian->periode_selesai?->format('d/m/Y') ?? '-' }}">
        <a href="{{ route('penggajian.index') }}" class="btn btn-neutral btn-sm" wire:navigate>
            <x-lucide-arrow-left class="size-4" />
            Kembali
        </a>
    </x-main.page-header>

    <div class="collapse bg-base-100 border border-base-300">
        <input id="informasi-proyek" type="checkbox" />
        <div class="collapse-title font-semibold p-0">
            <div class="w-full flex items-center justify-between px-3 py-2 gap-2">
                <h6 class="flex-auto">Informasi Proyek</h6>

                <span class="badge badge-xs badge-neutral h-auto text-[10px]">Buka</span>
            </div>
        </div>
        <div class="collapse-content text-sm z-10 px-3">
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

    <div class="collapse bg-base-100 border border-base-300">
        <input id="informasi-payroll" type="checkbox" />
        <div class="collapse-title font-semibold p-0">
            <div class="w-full flex items-center justify-between px-3 py-2 gap-2">
                <h6 class="flex-auto">Informasi Payroll / Penggajian</h6>

                <span class="badge badge-xs badge-neutral h-auto text-[10px]">Buka</span>
            </div>
        </div>
        <div class="collapse-content text-sm z-10 px-3 pb-0">
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
                    <th class="text-center" width="6%">No.</th>
                    <th>Nama Pekerja</th>
                    <th>Jabatan</th>
                    <th>Tarif Harian</th>
                    <th>Tarif Overtime</th>
                    <th>Total Hari</th>
                    <th>Total Overtime</th>
                    <th>Gaji Normal</th>
                    <th>Upah Overtime</th>
                    <th>Bonus</th>
                    <th>Potongan</th>
                    <th>Kasbon</th>
                    <th>Total Bersih</th>
                    <th>Status Bayar</th>
                    <th class="text-center" width="10%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($proyekPenggajian->proyekPenggajianPekerja as $item)
                    <tr>
                        <td class="text-center bg-slate-200">
                            {{ $loop->iteration }}.
                        </td>
                        <td>{{ $item->proyekPekerja?->nama_pekerja ?? '-' }}</td>
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
                                <hr class="border-t border-base-300 my-1">
                                <button type="button" class="btn btn-xs btn-outline w-full font-normal tracking-wider"
                                    popovertarget="popover-penggajian-{{ $loop->iteration }}"
                                    wire:click="openSummary('{{ $item->id }}')">
                                    <x-lucide-eye class="size-3 shrink-0" />
                                    Lihat Summary
                                </button>
                                <button type="button"
                                    class="btn btn-xs w-full font-normal tracking-wider {{ $item->status_bayar === \App\Enums\StatusBayar::SUDAH ? 'btn-disabled' : 'btn-outline' }}"
                                    popovertarget="popover-penggajian-{{ $loop->iteration }}"
                                    @if ($item->status_bayar !== \App\Enums\StatusBayar::SUDAH) wire:click="openModalPencatatan('{{ $item->id }}')" @endif
                                    @if ($item->status_bayar === \App\Enums\StatusBayar::SUDAH) disabled title="Sudah dibayar — terkunci" @endif>
                                    <x-lucide-pencil class="size-3 shrink-0" />
                                    Pencatatan Upah
                                </button>
                                <button type="button" class="btn btn-xs btn-outline w-full font-normal tracking-wider"
                                    popovertarget="popover-penggajian-{{ $loop->iteration }}"
                                    wire:click="openBayarModal('{{ $item->id }}')">
                                    <x-lucide-credit-card class="size-3 shrink-0" />
                                    Ubah Status Bayar
                                </button>
                            </div>
                        </th>
                    </tr>
                @empty
                    <tr>
                        <td colspan="15" class="text-center p-2">Belum Ada Data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <input type="checkbox" id="modal_pencatatan_upah" class="modal-toggle" wire:model.live="modal" />
    <div class="modal modal-bottom sm:modal-middle" role="dialog">
        <div class="modal-box flex flex-col p-0 sm:w-[70vw] sm:max-w-full max-h-[85vh] overflow-y-auto">
            <h3 class="text-lg font-bold px-4 py-3">Formulir Pencatatan Upah</h3>
            <hr class="border-t border-base-300">

            <div class="flex flex-col gap-2 overflow-auto">
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

                                            <input type="number" min="0" max="1" step="0.5"
                                                x-model.number="hours" inputmode="decimal"
                                                class="input input-sm input-bordered w-12 md:w-16 lg:w-24 text-center px-3" />

                                            <button type="button" class="btn btn-sm btn-primary"
                                                @click="hours = Math.min(1, Number(hours || 0) + 0.5)"
                                                :disabled="Number(hours || 0) >= 1">
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

                                            <input type="number" min="0" step="1"
                                                x-model.number="hours" inputmode="numeric"
                                                class="input input-sm input-bordered w-12 md:w-16 lg:w-24 text-center px-3" />

                                            <button type="button" class="btn btn-sm btn-primary"
                                                @click="hours = Number(hours || 0) + 1">
                                                <x-lucide-plus class="size-3 shrink-0" />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center p-2">Belum Ada Data</td>
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
                                aria-describedby="bonus-helper" placeholder="Contoh : 150.000" autocomplete="off"
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
                        @error('state.bonus')
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
                                autocomplete="off" x-data
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
                        @error('state.potongan')
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
                                aria-describedby="kasbon-helper" placeholder="Contoh : 150.000" autocomplete="off"
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
                        @error('state.kasbon')
                            <p class="text-xs text-red-600 mt-1" id="kasbon-helper">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-6 gap-2 px-3 py-2">
                    <div class="col-span-6">
                        <label for="keterangan"
                            class="block text-sm font-medium mb-2 {{ $errors->has('state.keterangan') ? 'text-red-500' : '' }}">
                            Keterangan :
                        </label>
                        <div class="relative">
                            <textarea wire:model="state.keterangan" id="keterangan" name="keterangan" rows="2"
                                class="w-full textarea @error('state.keterangan') textarea-error @enderror"
                                aria-describedby="keterangan-helper" placeholder="Catatan pencatatan (opsional)..."></textarea>
                        </div>
                        @error('state.keterangan')
                            <p class="text-xs text-red-600 mt-1" id="keterangan-helper">
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
                class="px-4 py-2 border-t border-base-300 flex flex-col lg:flex-row items-center lg:justify-end justify-center gap-2 text-xs font-semibold">
                <button type="button" class="btn btn-neutral btn-sm w-full lg:w-auto" wire:click="saveData" wire:loading.attr="disabled">
                    <x-lucide-save class="shrink-0 size-4" />

                    Simpan Pencatatan Upah
                </button>
            </div>
        </div>
        <label class="modal-backdrop" for="modal_pencatatan_upah">Close</label>
    </div>

    {{-- Summary Modal (read-only) --}}
    <input type="checkbox" id="modal_summary" class="modal-toggle" wire:model.live="summaryModal" />
    <div class="modal modal-bottom sm:modal-middle" role="dialog">
        <div class="modal-box flex flex-col p-0 sm:w-[70vw] sm:max-w-full max-h-[85vh] overflow-y-auto">
            <h3 class="text-lg font-bold px-4 py-3 flex items-center gap-2">
                <x-lucide-eye class="size-5" />
                Ringkasan Upah — {{ $selectedSummary?->proyekPekerja?->nama_pekerja ?? '-' }}
            </h3>
            <hr class="border-t border-base-300">

            @if ($selectedSummary)
                <div class="flex flex-col gap-3 px-4 py-3">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="text-xs font-semibold uppercase">Nama Pekerja</div>
                            <div class="text-sm mt-1">{{ $selectedSummary->proyekPekerja?->nama_pekerja ?? '-' }}</div>
                        </div>
                        <div>
                            <div class="text-xs font-semibold uppercase">Jabatan</div>
                            <div class="text-sm mt-1">{{ $selectedSummary->jabatan ?? '-' }}</div>
                        </div>
                        <div>
                            <div class="text-xs font-semibold uppercase">Tarif Harian</div>
                            <div class="text-sm mt-1">Rp {{ number_format((float) ($selectedSummary->proyekPekerja?->tarif_harian ?? $selectedSummary->tarif_harian ?? 0), 0, ',', '.') }} / Hari</div>
                        </div>
                        <div>
                            <div class="text-xs font-semibold uppercase">Tarif Overtime</div>
                            <div class="text-sm mt-1">Rp {{ number_format((float) ($selectedSummary->proyekPekerja?->tarif_overtime ?? $selectedSummary->tarif_overtime ?? 0), 0, ',', '.') }} / Jam</div>
                        </div>
                        <div>
                            <div class="text-xs font-semibold uppercase">Status Bayar</div>
                            <div class="text-sm mt-1">
                                <span class="badge badge-xs h-auto text-[10px] {{ $selectedSummary->status_bayar === \App\Enums\StatusBayar::SUDAH ? 'badge-success' : 'badge-neutral' }}">{{ $selectedSummary->status_bayar->label() }}</span>
                                @if ($selectedSummary->tanggal_bayar)
                                    <span class="text-xs ms-1">{{ $selectedSummary->tanggal_bayar->format('d/m/Y') }}</span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <div class="text-xs font-semibold uppercase">Periode</div>
                            <div class="text-sm mt-1">{{ $proyekPenggajian->periode_mulai?->format('d/m/Y') }} — {{ $proyekPenggajian->periode_selesai?->format('d/m/Y') }}</div>
                        </div>
                    </div>

                    <div class="overflow-x-auto border border-base-300 rounded-lg">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th class="text-center">Hari</th>
                                    <th class="text-center">Overtime (Jam)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($selectedSummary->proyekPenggajianPekerjaHari->sortBy('tanggal') as $hari)
                                    <tr>
                                        <td>
                                            <div class="flex flex-col">
                                                <span class="font-semibold text-xs">{{ $hari->tanggal->locale('id')->translatedFormat('l') }}</span>
                                                <span class="text-xs">{{ $hari->tanggal->locale('id')->format('d M Y') }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ rtrim(rtrim(number_format((float) $hari->hari_normal, 1, '.', ''), '0'), '.') ?: '0' }}</td>
                                        <td class="text-center">{{ (int) $hari->jam_overtime }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center p-3 text-sm">Belum ada pencatatan harian.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div class="flex justify-between border-b py-1"><span>Total Hari</span><span class="font-semibold">{{ rtrim(rtrim(number_format((float) $selectedSummary->total_hari, 1, '.', ''), '0'), '.') ?: '0' }} Hari</span></div>
                        <div class="flex justify-between border-b py-1"><span>Total Overtime</span><span class="font-semibold">{{ number_format((float) $selectedSummary->total_overtime, 0, ',', '.') }} Jam</span></div>
                        <div class="flex justify-between border-b py-1"><span>Gaji Normal</span><span>Rp {{ number_format((float) $selectedSummary->gaji_normal, 0, ',', '.') }}</span></div>
                        <div class="flex justify-between border-b py-1"><span>Upah Overtime</span><span>Rp {{ number_format((float) $selectedSummary->upah_overtime, 0, ',', '.') }}</span></div>
                        <div class="flex justify-between border-b py-1"><span>Bonus</span><span>Rp {{ number_format((float) $selectedSummary->bonus, 0, ',', '.') }}</span></div>
                        <div class="flex justify-between border-b py-1"><span>Potongan</span><span>Rp {{ number_format((float) $selectedSummary->potongan, 0, ',', '.') }}</span></div>
                        <div class="flex justify-between border-b py-1"><span>Kasbon</span><span>Rp {{ number_format((float) $selectedSummary->kasbon, 0, ',', '.') }}</span></div>
                        <div class="flex justify-between border-b py-2 font-bold text-base"><span>Total Bersih</span><span>Rp {{ number_format((float) $selectedSummary->total_bersih, 0, ',', '.') }}</span></div>
                    </div>

                    @if ($selectedSummary->keterangan)
                        <div>
                            <div class="text-xs font-semibold uppercase">Keterangan</div>
                            <div class="text-sm mt-1 whitespace-pre-wrap border border-base-300 rounded p-2 bg-base-200">{{ $selectedSummary->keterangan }}</div>
                        </div>
                    @endif
                </div>
            @else
                <div class="p-8 text-center text-sm">Memuat...</div>
            @endif

            <div class="px-4 py-3 border-t border-base-300 flex justify-end gap-2">
                <label for="modal_summary" class="btn btn-neutral btn-sm">Tutup</label>
                @if ($selectedSummary && $selectedSummary->status_bayar !== \App\Enums\StatusBayar::SUDAH)
                    <button type="button" class="btn btn-primary btn-sm" wire:click="openModalPencatatan('{{ $selectedSummary->id }}')" wire:loading.attr="disabled">
                        <x-lucide-pencil class="size-3" />
                        Edit Pencatatan
                    </button>
                @endif
            </div>
        </div>
        <label class="modal-backdrop" for="modal_summary">Close</label>
    </div>

    {{-- Bayar Modal (status + tanggal + catatan) --}}
    <input type="checkbox" id="modal_bayar" class="modal-toggle" wire:model.live="bayarModal" />
    <div class="modal modal-bottom sm:modal-middle" role="dialog">
        <div class="modal-box flex flex-col p-0 sm:w-[50vw] sm:max-w-full">
            <h3 class="text-lg font-bold px-4 py-3 flex items-center gap-2">
                <x-lucide-credit-card class="size-5" />
                Ubah Status Bayar — {{ $selectedBayar?->proyekPekerja?->nama_pekerja ?? '-' }}
            </h3>
            <hr class="border-t border-base-300">

            <div class="flex flex-col gap-4 px-4 py-4">
                <div>
                    <label for="bayar_status"
                        class="block text-sm font-medium mb-2 {{ $errors->has('bayarState.status_bayar') ? 'text-red-500' : '' }}">
                        Status Bayar :
                        <span class="text-red-500 text-xs">*</span>
                    </label>
                    <select wire:model.live="bayarState.status_bayar" id="bayar_status"
                        class="w-full select @error('bayarState.status_bayar') select-error @enderror">
                        <option value="">Pilih Status</option>
                        @foreach (\App\Enums\StatusBayar::cases() as $case)
                            <option value="{{ $case->value }}">{{ $case->label() }}</option>
                        @endforeach
                    </select>
                    @error('bayarState.status_bayar')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="bayar_tanggal"
                        class="block text-sm font-medium mb-2 {{ $errors->has('bayarState.tanggal_bayar') ? 'text-red-500' : '' }}">
                        Tanggal Bayar :
                        @if (($bayarState['status_bayar'] ?? null) == \App\Enums\StatusBayar::SUDAH->value)
                            <span class="text-red-500 text-xs">*</span>
                        @endif
                    </label>
                    <input type="date" wire:model="bayarState.tanggal_bayar" id="bayar_tanggal"
                        class="w-full input @error('bayarState.tanggal_bayar') input-error @enderror"
                        @if (($bayarState['status_bayar'] ?? null) != \App\Enums\StatusBayar::SUDAH->value) disabled @endif>
                    @error('bayarState.tanggal_bayar')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-base-content/60 mt-1">Wajib jika status Sudah Dibayar, kosongkan jika Belum.</p>
                </div>

                <div>
                    <label for="bayar_keterangan"
                        class="block text-sm font-medium mb-2 {{ $errors->has('bayarState.keterangan') ? 'text-red-500' : '' }}">
                        Catatan :
                    </label>
                    <textarea wire:model="bayarState.keterangan" id="bayar_keterangan" rows="2"
                        class="w-full textarea @error('bayarState.keterangan') textarea-error @enderror"
                        placeholder="Catatan pembayaran (opsional)..."></textarea>
                    @error('bayarState.keterangan')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                @if ($selectedBayar)
                    <div class="bg-base-200 border border-base-300 rounded p-3 text-xs">
                        <div class="flex justify-between"><span>Total Bersih</span><span class="font-semibold">Rp {{ number_format((float) $selectedBayar->total_bersih, 0, ',', '.') }}</span></div>
                        <div class="flex justify-between mt-1"><span>Status saat ini</span><span class="badge badge-xs h-auto text-[10px] {{ $selectedBayar->status_bayar === \App\Enums\StatusBayar::SUDAH ? 'badge-success' : 'badge-neutral' }}">{{ $selectedBayar->status_bayar->label() }}</span></div>
                    </div>
                @endif
            </div>

            <div class="px-4 py-3 border-t border-base-300 flex justify-end gap-2">
                <label for="modal_bayar" class="btn btn-ghost btn-sm">Batal</label>
                <button type="button" class="btn btn-primary btn-sm" wire:click="saveBayar" wire:loading.attr="disabled">
                    <x-lucide-save class="size-3" />
                    Simpan Status
                </button>
            </div>
        </div>
        <label class="modal-backdrop" for="modal_bayar">Close</label>
    </div>
</div>
