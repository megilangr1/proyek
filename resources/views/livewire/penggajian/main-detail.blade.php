<div class="flex flex-col gap-3">
    <x-main.page-header title="Detail Penggajian - {{ $penggajianData->nama_periode }}"
        subtitle="{{ $penggajianData->periode_mulai?->format('d/m/Y') ?? '-' }} - {{ $penggajianData->periode_selesai?->format('d/m/Y') ?? '-' }}">
        <a href="{{ route('penggajian.index') }}" class="btn btn-neutral btn-sm" wire:navigate>
            <x-lucide-arrow-left class="size-4" />
            Kembali
        </a>
    </x-main.page-header>

    <div class="collapse bg-base-100 border">
        <input id="informasi-proyek" type="checkbox" />
        <label for="informasi-proyek" class="fixed inset-0 hidden peer-checked:block"></label>
        <div class="collapse-title font-semibold p-0">
            <div class="w-full flex items-center justify-between px-3 py-2">
                <h6>Informasi Proyek</h6>

                <span class="badge badge-xs badge-neutral text-[10px]">Buka / Tutup</span>
            </div>
        </div>
        <div class="collapse-content text-sm z-1 px-3">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                <div>
                    <div class="text-xs font-semibold uppercase">Kode Proyek</div>
                    <div class="text-sm mt-1">{{ $penggajianData->proyek?->kode_proyek ?? '-' }}</div>
                </div>

                <div>
                    <div class="text-xs font-semibold uppercase">Nama Proyek</div>
                    <div class="text-sm mt-1">{{ $penggajianData->proyek?->nama_proyek ?? '-' }}</div>
                </div>

                <div>
                    <div class="text-xs font-semibold uppercase">Pemilik</div>
                    <div class="text-sm mt-1">{{ $penggajianData->proyek?->pemilik ?? '-' }}</div>
                </div>

                <div>
                    <div class="text-xs font-semibold uppercase">Lokasi</div>
                    <div class="text-sm mt-1">{{ $penggajianData->proyek?->lokasi ?? '-' }}</div>
                </div>

                <div>
                    <div class="text-xs font-semibold uppercase">Tanggal Pekerjaan Mulai</div>
                    <div class="text-sm mt-1">
                        {{ $penggajianData->proyek?->tanggal_mulai?->format('d/m/Y') ?? '-' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs font-semibold uppercase">Tanggal Pekerjaan Selesai</div>
                    <div class="text-sm mt-1">
                        {{ $penggajianData->proyek?->tanggal_selesai?->format('d/m/Y') ?? '-' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs font-semibold uppercase">Jumlah Pekerja</div>
                    <div class="text-sm mt-1">{{ $penggajianData->proyek?->proyekPekerja?->count() ?? '??' }}
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
            <div class="w-full flex items-center justify-between px-3 py-2">
                <h6>Informasi Payroll / Penggajian</h6>

                <span class="badge badge-xs badge-neutral text-[10px]">Buka / Tutup</span>
            </div>
        </div>
        <div class="collapse-content text-sm z-1 px-3 pb-0">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                <div class="md:col-span-2">
                    <div class="text-xs font-semibold uppercase">Nama Periode</div>
                    <div class="text-sm mt-1">{{ $penggajianData->nama_periode ?? '-' }}</div>
                </div>

                <div>
                    <div class="text-xs font-semibold uppercase">Periode Mulai</div>
                    <div class="text-sm mt-1">{{ $penggajianData->periode_mulai?->format('d/m/Y') ?? '-' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs font-semibold uppercase">Periode Selesai</div>
                    <div class="text-sm mt-1">{{ $penggajianData->periode_selesai?->format('d/m/Y') ?? '-' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs font-semibold uppercase">Jam Kerja</div>
                    <div class="text-sm mt-1">{{ $penggajianData->jam_kerja ?? '-' }} Jam</div>
                </div>

                <div>
                    <div class="text-xs font-semibold uppercase">Status</div>
                    <div class="text-sm mt-1">
                        @if ($penggajianData->status === \App\Enums\StatusPenggajian::AKTIF)
                            <span class="badge badge-success">{{ $penggajianData->status->label() }}</span>
                        @else
                            <span class="badge badge-ghost">{{ $penggajianData->status->label() }}</span>
                        @endif
                    </div>
                </div>

                <div class="md:col-span-2">
                    <div class="text-xs font-semibold uppercase">Keterangan</div>
                    <div class="text-sm mt-1 whitespace-pre-wrap">{{ $penggajianData->keterangan ?? '-' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
