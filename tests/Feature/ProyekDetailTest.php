<?php

use App\Enums\StatusPekerja;
use App\Livewire\MasterData\Proyek\MainDetail;
use App\Models\Proyek;
use App\Models\ProyekPekerja;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'administrator']);
    Role::firstOrCreate(['name' => 'meggi']);
    Role::firstOrCreate(['name' => 'operator']);
});

test('administrator can view the proyek detail page', function () {
    $admin = User::factory()->create();
    $admin->assignRole('administrator');

    $proyek = Proyek::factory()->create();

    Livewire::actingAs($admin)
        ->test(MainDetail::class, ['proyek' => $proyek->id])
        ->assertOk();
});

test('operator can view the proyek detail page', function () {
    $operator = User::factory()->create();
    $operator->assignRole('operator');

    $proyek = Proyek::factory()->create();

    Livewire::actingAs($operator)
        ->test(MainDetail::class, ['proyek' => $proyek->id])
        ->assertOk();
});

test('user without role cannot access the proyek detail page', function () {
    $user = User::factory()->create();

    $proyek = Proyek::factory()->create();

    Livewire::actingAs($user)
        ->test(MainDetail::class, ['proyek' => $proyek->id])
        ->assertForbidden();
});

test('setTab switches tab', function () {
    $admin = User::factory()->create();
    $admin->assignRole('administrator');

    $proyek = Proyek::factory()->create();

    Livewire::actingAs($admin)
        ->test(MainDetail::class, ['proyek' => $proyek->id])
        ->assertSet('tab', 'pekerja')
        ->call('setTab', 'penggajian')
        ->assertSet('tab', 'penggajian');
});

test('setTab rejects invalid tab', function () {
    $admin = User::factory()->create();
    $admin->assignRole('administrator');

    $proyek = Proyek::factory()->create();

    Livewire::actingAs($admin)
        ->test(MainDetail::class, ['proyek' => $proyek->id])
        ->call('setTab', 'invalid')
        ->assertSet('tab', 'pekerja');
});

test('hasPekerjaAktif returns true when active worker exists', function () {
    $admin = User::factory()->create();
    $admin->assignRole('administrator');

    $proyek = Proyek::factory()->create();
    ProyekPekerja::factory()->create(['proyek_id' => $proyek->id, 'status' => StatusPekerja::AKTIF]);

    Livewire::actingAs($admin)
        ->test(MainDetail::class, ['proyek' => $proyek->id])
        ->assertSet('hasPekerjaAktif', true);
});

test('hasPekerjaAktif returns false when no active worker', function () {
    $admin = User::factory()->create();
    $admin->assignRole('administrator');

    $proyek = Proyek::factory()->create();

    Livewire::actingAs($admin)
        ->test(MainDetail::class, ['proyek' => $proyek->id])
        ->assertSet('hasPekerjaAktif', false);
});
