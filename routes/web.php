<?php

use App\Http\Controllers\MainController;
use App\Livewire\Auth\Login;
use App\Livewire\Dashboard\MainIndex as DashboardMainIndex;
use App\Livewire\MasterData\Pengguna\MainIndex as PenggunaMainIndex;
use App\Livewire\MasterData\Proyek\MainDetail as ProyekMainDetail;
use App\Livewire\MasterData\Proyek\MainIndex as ProyekMainIndex;
use App\Livewire\Pages\Main;
use App\Livewire\Penggajian\MainDetail as PenggajianMainDetail;
use Illuminate\Support\Facades\Route;

Route::livewire('/', Main::class)->name('main');

Route::middleware('guest')->group(function () {
    Route::livewire('/login', Login::class)->name('login');
});

Route::post('/logout', [MainController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::livewire('/dashboard', DashboardMainIndex::class)->name('dashboard');

    Route::prefix('master-data')->group(function () {
        Route::livewire('/pengguna', PenggunaMainIndex::class)->name('pengguna.index');
        Route::livewire('/proyek', ProyekMainIndex::class)->name('proyek.index');
        Route::livewire('/proyek/{proyek}/detail', ProyekMainDetail::class)->name('proyek.detail');
    });

    Route::prefix('penggajian')->name('penggajian.')->group(function () {
        Route::livewire('/{penggajian}/detail', PenggajianMainDetail::class)->name('detail');
    });
});
