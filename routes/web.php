<?php

use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::main')->name('main');

Route::middleware('guest')->group(function () {
    Route::livewire('/login', 'auth::login')->name('login');
});

Route::post('/logout', [MainController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::livewire('/', 'admin::main')->name('main');

    Route::prefix('master-data')->group(function () {
        Route::livewire('/pengguna', 'admin::master-data.pengguna.main-index')->name('pengguna.index');
    });
});
