<div class="flex flex-col gap-3">
    <x-main.page-header title="Detail Proyek">
        <a href="{{ route('proyek.index') }}" wire:navigate class="btn btn-neutral btn-sm">
            <x-lucide-arrow-left class="size-4" /> Kembali
        </a>
    </x-main.page-header>

    {{-- Hero — rincian info proyek --}}
    <div class="relative overflow-hidden rounded-2xl border border-base-300 bg-base-100" data-motion="fade-up">
        <div class="absolute inset-0 bg-aurora opacity-40" data-motion="gradient-pan" data-motion-duration="8"></div>
        <div class="relative">
            <div
                class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 px-5 py-4 border-b border-base-300 bg-base-100/70 backdrop-blur">
                <div class="flex items-center gap-3">
                    <div class="size-10 rounded-xl bg-primary text-primary-content grid place-items-center" data-motion="pop-in">
                        <x-lucide-building-2 class="size-5" />
                    </div>
                    <div>
                        <h3 class="font-bold leading-none flex items-center gap-2">
                            {{ $proyek->nama_proyek ?? '-' }}
                            <span class="badge badge-sm font-mono">{{ $proyek->kode_proyek ?? '-' }}</span>
                        </h3>
                        <p class="text-xs text-base-content/60 mt-1 flex items-center gap-1">
                            <x-lucide-user class="size-3" /> {{ $proyek->pemilik ?? '-' }} •
                            <x-lucide-map-pin class="size-3" /> {{ $proyek->lokasi ?? '-' }} •
                            <x-lucide-calendar-range class="size-3" />
                            {{ $proyek->tanggal_mulai?->format('d/m/Y') ?? '-' }} — {{ $proyek->tanggal_selesai?->format('d/m/Y') ?? '-' }}
                        </p>
                    </div>
                </div>
                <span
                    class="badge @if ($proyek->status === \App\Enums\StatusProyek::AKTIF) badge-success @else badge-ghost @endif gap-1">
                    <span class="size-1.5 rounded-full bg-current"></span> {{ $proyek->status->label() }}
                </span>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 px-5 py-4 text-sm">
                <div>
                    <span class="block text-[10px] font-semibold tracking-widest uppercase text-base-content/50">Pekerja</span>
                    <span class="font-semibold flex items-center gap-1"><x-lucide-users class="size-3 text-base-content/50" />
                        {{ $pekerjaCount }} orang</span>
                </div>
                <div>
                    <span class="block text-[10px] font-semibold tracking-widest uppercase text-base-content/50">Pengeluaran</span>
                    <span class="font-semibold flex items-center gap-1"><x-lucide-receipt class="size-3 text-base-content/50" />
                        {{ $pengeluaranCount }} entri</span>
                </div>
                <div>
                    <span class="block text-[10px] font-semibold tracking-widest uppercase text-base-content/50">Penggajian</span>
                    <span class="font-semibold flex items-center gap-1"><x-lucide-wallet class="size-3 text-base-content/50" />
                        {{ $penggajianCount }} periode</span>
                </div>
                <div>
                    <span class="block text-[10px] font-semibold tracking-widest uppercase text-base-content/50">Syarat Penggajian</span>
                    <span class="font-semibold flex items-center gap-1">
                        @if ($this->hasPekerjaAktif)
                            <x-lucide-check-circle-2 class="size-3 text-success" /> Siap
                        @else
                            <x-lucide-alert-circle class="size-3 text-warning" /> Butuh pekerja
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Ringkasan kas proyek --}}
    <div class="rounded-2xl border border-base-300 bg-base-100 px-5 py-4" data-motion="fade-up">
        <div class="flex items-center justify-between gap-2 mb-3">
            <h4 class="text-sm font-bold flex items-center gap-2">
                <x-lucide-calculator class="size-4 text-primary" /> Ringkasan Kas Proyek
            </h4>
            <span class="badge badge-sm {{ $summary['sisaKas'] >= 0 ? 'badge-success' : 'badge-error' }}">
                {{ $summary['sisaKas'] >= 0 ? 'Surplus' : 'Defisit' }}
            </span>
        </div>
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 text-sm">
            <div>
                <span class="block text-[10px] font-semibold tracking-widest uppercase text-base-content/50">Nilai Proyek</span>
                <span class="font-bold flex items-center gap-1">
                    <x-lucide-banknote class="size-3 text-base-content/50" />
                    Rp {{ number_format($summary['nilaiProyek'], 0, ',', '.') }}
                </span>
                @if ($proyek->nilai_proyek === null)
                    <span class="block text-[11px] text-base-content/50">Belum diisi</span>
                @endif
            </div>
            <div>
                <span class="block text-[10px] font-semibold tracking-widest uppercase text-base-content/50">Total Pengeluaran</span>
                <span class="font-semibold flex items-center gap-1">
                    <x-lucide-receipt class="size-3 text-base-content/50" />
                    Rp {{ number_format($summary['totalPengeluaran'], 0, ',', '.') }}
                </span>
                <span class="block text-[11px] text-base-content/50">{{ $pengeluaranCount }} entri</span>
            </div>
            <div>
                <span class="block text-[10px] font-semibold tracking-widest uppercase text-base-content/50">Total Penggajian</span>
                <span class="font-semibold flex items-center gap-1">
                    <x-lucide-wallet class="size-3 text-base-content/50" />
                    Rp {{ number_format($summary['totalPenggajian'], 0, ',', '.') }}
                </span>
                <span class="block text-[11px] text-base-content/50">{{ $penggajianCount }} periode</span>
            </div>
            <div>
                <span class="block text-[10px] font-semibold tracking-widest uppercase text-base-content/50">Total Biaya Proyek</span>
                <span class="font-bold flex items-center gap-1">
                    <x-lucide-calculator class="size-3 text-base-content/50" />
                    Rp {{ number_format($summary['totalBiaya'], 0, ',', '.') }}
                </span>
                <span class="block text-[11px] text-base-content/50">Pengeluaran + Penggajian</span>
            </div>
            <div>
                <span class="block text-[10px] font-semibold tracking-widest uppercase text-base-content/50">Sisa Kas</span>
                <span class="font-bold flex items-center gap-1 {{ $summary['sisaKas'] >= 0 ? 'text-success' : 'text-error' }}">
                    <x-lucide-piggy-bank class="size-3" />
                    Rp {{ number_format($summary['sisaKas'], 0, ',', '.') }}
                </span>
                <span class="block text-[11px] text-base-content/50">Nilai - Total Biaya</span>
            </div>
        </div>
    </div>

    {{-- Tabs antar modul --}}
    <div role="tablist" class="tabs tabs-boxed bg-base-200 p-1 w-fit" data-motion="fade-up">
        <button type="button" role="tab" class="tab gap-1 {{ $tab === 'pekerja' ? 'tab-active' : '' }}"
            wire:click="setTab('pekerja')">
            <x-lucide-users class="size-4" /> Pekerja
        </button>
        <button type="button" role="tab" class="tab gap-1 {{ $tab === 'pengeluaran' ? 'tab-active' : '' }}"
            wire:click="setTab('pengeluaran')">
            <x-lucide-receipt class="size-4" /> Pengeluaran
        </button>
        <button type="button" role="tab" class="tab gap-1 {{ $tab === 'penggajian' ? 'tab-active' : '' }}"
            wire:click="setTab('penggajian')">
            <x-lucide-wallet class="size-4" /> Penggajian
        </button>
    </div>

    {{-- Panel per tab --}}
    @if ($tab === 'pekerja')
        <livewire:master-data.proyek-pekerja.main-index :key="'pekerja-' . $proyek->id" :proyek="$proyek->id" />
    @elseif ($tab === 'pengeluaran')
        <livewire:master-data.proyek-pengeluaran.main-index :key="'pengeluaran-' . $proyek->id" :proyek="$proyek->id" />
    @elseif ($tab === 'penggajian')
        <livewire:penggajian.main-index :key="'penggajian-' . $proyek->id" :proyek="$proyek->id" />
    @endif
</div>