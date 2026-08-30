<?php

namespace App\Livewire\Dashboard;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class MainIndex extends Component
{
    public function render()
    {
        return view('livewire.dashboard.main-index');
    }
}
