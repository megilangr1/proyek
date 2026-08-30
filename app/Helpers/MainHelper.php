<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MainHelper
{
    public function doAlert(Component $comp, $type = 'error', $message = 'Terjadi Kesalahan ! <br> Silahkan Hubungi Administrator !')
    {
        $comp->dispatch('toast', type: $type, message: $message);
    }

    public function userData()
    {
        return User::where('id', '=', Auth::id())->firstOrFail();
    }
}
