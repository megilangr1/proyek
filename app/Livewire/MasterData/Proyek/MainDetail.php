<?php

namespace App\Livewire\MasterData\Proyek;

use App\Enums\StatusPekerja;
use App\Helpers\MainHelper;
use App\Models\Proyek;
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

        return view('livewire.master-data.proyek.main-detail', [
            'proyek' => $this->proyekData,
            'pekerjaCount' => $pekerjaCount,
            'pengeluaranCount' => $pengeluaranCount,
            'penggajianCount' => $penggajianCount,
        ]);
    }
}
