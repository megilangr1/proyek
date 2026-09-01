<?php

namespace App\Livewire\Penggajian;

use App\Helpers\MainHelper;
use App\Models\ProyekPenggajian;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;

#[Layout('layouts.app')]
class MainDetail extends Component
{
    #[Locked]
    public ?ProyekPenggajian $penggajianData = null;

    public function mount($penggajian): void
    {
        $this->ensureCanManage();
        $this->penggajianData = ProyekPenggajian::query()->where('id', '=', $penggajian)->firstOrFail();
    }

    private function ensureCanManage(): void
    {
        $user = (new MainHelper)->userData();

        abort_unless($user->isAdmin() || $user->isOperator(), 403);
    }

    public function render(): View
    {
        return view('livewire.penggajian.main-detail');
    }
}
