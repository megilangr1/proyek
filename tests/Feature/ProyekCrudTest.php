<?php

use App\Livewire\MasterData\Proyek\MainIndex;
use App\Models\Proyek;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'administrator']);
    Role::firstOrCreate(['name' => 'meggi']);
    Role::firstOrCreate(['name' => 'operator']);
});

test('administrator can view the proyek page', function () {
    $admin = User::factory()->create();
    $admin->assignRole('administrator');

    $this->actingAs($admin)
        ->get(route('proyek.index'))
        ->assertOk()
        ->assertSee('Data Proyek');
});

test('operator can view the proyek page', function () {
    $operator = User::factory()->create();
    $operator->assignRole('operator');

    Livewire::actingAs($operator)
        ->test(MainIndex::class)
        ->assertOk();
});

test('user without role cannot access the proyek page', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(MainIndex::class)
        ->assertForbidden();
});

test('administrator can create a proyek', function () {
    $admin = User::factory()->create();
    $admin->assignRole('administrator');

    Livewire::actingAs($admin)
        ->test(MainIndex::class)
        ->set('state.nama_proyek', 'Proyek Contoh')
        ->set('state.pemilik', 'PT Contoh')
        ->set('state.lokasi', 'Jakarta')
        ->set('state.tanggal_mulai', '2026-01-01')
        ->set('state.tanggal_selesai', '2026-12-31')
        ->set('state.status', 1)
        ->call('actionForm')
        ->assertHasNoErrors();

    expect(Proyek::where('nama_proyek', 'Proyek Contoh')->first()->kode_proyek)->toMatch('/^PRJ\d{3}$/');
});

test('operator can create a proyek', function () {
    $operator = User::factory()->create();
    $operator->assignRole('operator');

    Livewire::actingAs($operator)
        ->test(MainIndex::class)
        ->set('state.nama_proyek', 'Proyek Operator')
        ->set('state.pemilik', 'PT Operator')
        ->set('state.lokasi', 'Bandung')
        ->set('state.tanggal_mulai', '2026-02-01')
        ->set('state.tanggal_selesai', '2026-06-30')
        ->set('state.status', 2)
        ->call('actionForm')
        ->assertHasNoErrors();

    expect(Proyek::where('nama_proyek', 'Proyek Operator')->first()->kode_proyek)->toMatch('/^PRJ\d{3}$/');
});

test('create validates date order', function () {
    $admin = User::factory()->create();
    $admin->assignRole('administrator');

    Livewire::actingAs($admin)
        ->test(MainIndex::class)
        ->set('state.nama_proyek', 'Proyek Salah')
        ->set('state.pemilik', 'PT Salah')
        ->set('state.lokasi', 'Surabaya')
        ->set('state.tanggal_mulai', '2026-12-31')
        ->set('state.tanggal_selesai', '2026-01-01')
        ->set('state.status', 1)
        ->call('actionForm')
        ->assertHasErrors(['state.tanggal_selesai']);

    expect(Proyek::where('nama_proyek', 'Proyek Salah')->exists())->toBeFalse();
});

test('kode proyek auto generated and increments', function () {
    $admin = User::factory()->create();
    $admin->assignRole('administrator');

    Livewire::actingAs($admin)
        ->test(MainIndex::class)
        ->set('state.nama_proyek', 'Proyek Pertama')
        ->set('state.pemilik', 'PT A')
        ->set('state.lokasi', 'A')
        ->set('state.tanggal_mulai', '2026-01-01')
        ->set('state.tanggal_selesai', '2026-02-01')
        ->set('state.status', 1)
        ->call('actionForm');

    Livewire::actingAs($admin)
        ->test(MainIndex::class)
        ->set('state.nama_proyek', 'Proyek Kedua')
        ->set('state.pemilik', 'PT B')
        ->set('state.lokasi', 'B')
        ->set('state.tanggal_mulai', '2026-01-01')
        ->set('state.tanggal_selesai', '2026-02-01')
        ->set('state.status', 1)
        ->call('actionForm');

    expect(Proyek::where('nama_proyek', 'Proyek Pertama')->first()->kode_proyek)->toBe('PRJ001');
    expect(Proyek::where('nama_proyek', 'Proyek Kedua')->first()->kode_proyek)->toBe('PRJ002');
});

test('administrator can edit a proyek', function () {
    $admin = User::factory()->create();
    $admin->assignRole('administrator');

    $target = Proyek::factory()->create(['nama_proyek' => 'Nama Lama']);

    Livewire::actingAs($admin)
        ->test(MainIndex::class)
        ->call('doEdit', $target->id)
        ->set('state.nama_proyek', 'Nama Baru')
        ->call('actionForm')
        ->assertHasNoErrors();

    expect(Proyek::find($target->id)->nama_proyek)->toBe('Nama Baru');
});

test('administrator can soft delete a proyek', function () {
    $admin = User::factory()->create();
    $admin->assignRole('administrator');

    $target = Proyek::factory()->create();

    Livewire::actingAs($admin)
        ->test(MainIndex::class)
        ->call('doDelete', $target->id);

    $this->assertSoftDeleted('proyeks', ['id' => $target->id]);
});
