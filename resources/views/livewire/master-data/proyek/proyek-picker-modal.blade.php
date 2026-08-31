<div>
    @if ($open)
        <dialog class="modal modal-open" wire:click.self="closeModal">
            <div class="modal-box w-full max-w-4xl">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold">Pilih Proyek</h3>
                    <button type="button" class="btn btn-sm btn-circle btn-ghost" wire:click="closeModal">
                        <x-lucide-x class="size-4" />
                    </button>
                </div>

                <div class="relative mb-4">
                    <input type="text" wire:model.live.debounce.300ms="search"
                        class="w-full input input-bordered ps-10"
                        placeholder="Cari kode, nama, pemilik, atau lokasi proyek...">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <x-lucide-search class="size-4 text-gray-400" />
                    </div>
                </div>

                <div class="overflow-x-auto max-h-80">
                    <table class="table table-sm table-pin-rows table-pin-cols">
                        <thead>
                            <tr>
                                <td class="text-center" width="6%">No.</td>
                                <td>Kode</td>
                                <td>Nama Proyek</td>
                                <td>Pemilik</td>
                                <td>Lokasi</td>
                                <td>Status</td>
                                <th class="text-center" width="10%">Pilih</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($results as $item)
                                <tr class="hover:bg-base-200 cursor-pointer"
                                    wire:click="selectProyek({{ $item->id }})">
                                    <td class="text-center bg-slate-200">{{ $loop->iteration }}.</td>
                                    <td>{{ $item->kode_proyek }}</td>
                                    <td>{{ $item->nama_proyek }}</td>
                                    <td>{{ $item->pemilik }}</td>
                                    <td class="max-w-xs truncate" title="{{ $item->lokasi }}">{{ $item->lokasi }}</td>
                                    <td>
                                        @if ($item->status === \App\Enum\StatusProyek::AKTIF)
                                            <span class="badge badge-success badge-sm">{{ $item->status->label() }}</span>
                                        @else
                                            <span class="badge badge-ghost badge-sm">{{ $item->status->label() }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button type="button"
                                            class="btn btn-xs btn-primary btn-outline"
                                            wire:click.stop="selectProyek({{ $item->id }})">
                                            Pilih
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center p-4">Tidak ada data proyek ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="modal-action">
                    <button type="button" class="btn btn-sm btn-ghost" wire:click="closeModal">Tutup</button>
                </div>
            </div>
            <form method="dialog" class="modal-backdrop">
                <button type="button" wire:click="closeModal">close</button>
            </form>
        </dialog>
    @endif
</div>
