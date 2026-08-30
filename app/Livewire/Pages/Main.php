<?php

namespace App\Livewire\Pages;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.public')]
class Main extends Component
{
    public function render()
    {
        return view('livewire.pages.main');
    }
}
