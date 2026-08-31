<?php

use App\Livewire\MasterData\Penggajian\MainIndex;
use App\Models\Proyek;
use App\Models\ProyekPenggajian;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'administrator']);
    Role::firstOrCreate(['name' => 'meggi']);
    Role::firstOrCreate(['name' => 'operator']);
});

test('administrator can view the penggajian page', function () {
    $admin = User::factory()->create();
    $admin->assignRole('administrator');

    Livewire::actingAs($admin)
        ->test(MainIndex::class)
        ->assertOk();
});

test('operator can view the penggajian page', function () {
    $operator = User::factory()->create();
    $operator->assignRole('operator');

    Livewire::actingAs($operator)
        ->test(MainIndex::class)
        ->assertOk();
});

test('user without role cannot access the penggajian page', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(MainIndex::class)
        ->assertForbidden();
});

test('administrator can create a penggajian', function () {
    $admin = User::factory()->create();
    $admin->assignRole('administrator');

    $proyek = Proyek::factory()->create();

    Livewire::actingAs($admin)
        ->test(MainIndex::class)
        ->set('state.proyek_id', $proyek->id)
        ->set('state.nama_periode', 'Minggu 1')
        ->set('state.periode_mulai', '2026-01-01')
        ->set('state.periode_selesai', '2026-01-07')
        ->set('state.jam_kerja', 40)
        ->set('state.keterangan', 'Catatan test')
        ->set('state.status', 1)
        ->call('actionForm')
        ->assertHasNoErrors();

    expect(ProyekPenggajian::where('nama_periode', 'Minggu 1')->first())
        ->proyek_id->toBe($proyek->id);
});

test('create validates date order and jam_kerja', function () {
    $admin = User::factory()->create();
    $admin->assignRole('administrator');

    $proyek = Proyek::factory()->create();

    Livewire::actingAs($admin)
        ->test(MainIndex::class)
        ->set('state.proyek_id', $proyek->id)
        ->set('state.nama_periode', 'Minggu Salah')
        ->set('state.periode_mulai', '2026-01-07')
        ->set('state.periode_selesai', '2026-01-01')
        ->set('state.jam_kerja', 'bukan-angka')
        ->set('state.status', 1)
        ->call('actionForm')
        ->assertHasErrors(['state.periode_selesai', 'state.jam_kerja']);

    expect(ProyekPenggajian::where('nama_periode', 'Minggu Salah')->exists())->toBeFalse();
});

test('administrator can edit a penggajian', function () {
    $admin = User::factory()->create();
    $admin->assignRole('administrator');

    $target = ProyekPenggajian::factory()->create(['nama_periode' => 'Nama Lama']);

    Livewire::actingAs($admin)
        ->test(MainIndex::class)
        ->call('doEdit', $target->id)
        ->set('state.nama_periode', 'Nama Baru')
        ->call('actionForm')
        ->assertHasNoErrors();

    expect(ProyekPenggajian::find($target->id)->nama_periode)->toBe('Nama Baru');
});

test('administrator can soft delete a penggajian', function () {
    $admin = User::factory()->create();
    $admin->assignRole('administrator');

    $target = ProyekPenggajian::factory()->create();

    Livewire::actingAs($admin)
        ->test(MainIndex::class)
        ->call('doDelete', $target->id);

    $this->assertSoftDeleted('proyek_penggajians', ['id' => $target->id]);
});
