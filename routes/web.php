<?php

use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::main')->name('main');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::livewire('/', 'admin::main')->name('main');
});
