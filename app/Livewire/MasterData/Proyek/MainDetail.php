<?php

namespace App\Livewire\MasterData\Proyek;

use App\Enums\StatusPekerja;
use App\Helpers\MainHelper;
use App\Models\Proyek;
use App\Models\ProyekPenggajianPekerja;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.app')]
class MainDetail extends Component
{
    private const ALLOWED_TABS = ['pekerja', 'pengeluaran', 'penggajian'];

    #[Locked]
    public ?Proyek $proyekData = null;

    #[Url(except: 'pekerja')]
    public string $tab = 'pekerja';

    public function mount($proyek): void
    {
        $this->proyekData = Proyek::query()->where('id', '=', $proyek)->firstOrFail();

        $this->ensureCanManage();
    }

    private function ensureCanManage(): void
    {
        $user = (new MainHelper)->userData();

        abort_unless($user->isAdmin() || $user->isOperator(), 403);
    }

    public function setTab(string $tab): void
    {
        if (! in_array($tab, self::ALLOWED_TABS, true)) {
            return;
        }

        $this->tab = $tab;
    }

    public function getHasPekerjaAktifProperty(): bool
    {
        return $this->proyekData->proyekPekerja()
            ->where('status', StatusPekerja::AKTIF)
            ->exists();
    }

    public function render(): View
    {
        $pekerjaCount = $this->proyekData->proyekPekerja()->count();
        $pengeluaranCount = $this->proyekData->proyekPengeluaran()->count();
        $penggajianCount = $this->proyekData->proyekPenggajian()->count();

        $nilaiProyek = (float) ($this->proyekData->nilai_proyek ?? 0);
        $totalPengeluaran = (float) $this->proyekData->proyekPengeluaran()->sum('nominal');

        $penggajianIds = $this->proyekData->proyekPenggajian()->pluck('id');
        $totalPenggajian = $penggajianIds->isNotEmpty()
            ? (float) ProyekPenggajianPekerja::query()->whereIn('proyek_penggajian_id', $penggajianIds)->sum('total_bersih')
            : 0.0;

        $totalBiaya = $totalPengeluaran + $totalPenggajian;
        $sisaKas = $nilaiProyek - $totalBiaya;

        return view('livewire.master-data.proyek.main-detail', [
            'proyek' => $this->proyekData,
            'pekerjaCount' => $pekerjaCount,
            'pengeluaranCount' => $pengeluaranCount,
            'penggajianCount' => $penggajianCount,
            'summary' => [
                'nilaiProyek' => $nilaiProyek,
                'totalPengeluaran' => $totalPengeluaran,
                'totalPenggajian' => $totalPenggajian,
                'totalBiaya' => $totalBiaya,
                'sisaKas' => $sisaKas,
            ],
        ]);
    }
}
