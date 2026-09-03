<?php

use App\Enums\StatusPekerja;
use App\Livewire\Penggajian\MainIndex;
use App\Models\Proyek;
use App\Models\ProyekPekerja;
use App\Models\ProyekPenggajian;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'administrator']);
    Role::firstOrCreate(['name' => 'meggi']);
    Role::firstOrCreate(['name' => 'operator']);
});

test('administrator can view the penggajian panel', function () {
    $admin = User::factory()->create();
    $admin->assignRole('administrator');

    $proyek = Proyek::factory()->create();

    Livewire::actingAs($admin)
        ->test(MainIndex::class, ['proyek' => $proyek->id])
        ->assertOk();
});

test('operator can view the penggajian panel', function () {
    $operator = User::factory()->create();
    $operator->assignRole('operator');

    $proyek = Proyek::factory()->create();

    Livewire::actingAs($operator)
        ->test(MainIndex::class, ['proyek' => $proyek->id])
        ->assertOk();
});

test('user without role cannot access the penggajian panel', function () {
    $user = User::factory()->create();

    $proyek = Proyek::factory()->create();

    Livewire::actingAs($user)
        ->test(MainIndex::class, ['proyek' => $proyek->id])
        ->assertForbidden();
});

test('administrator can create a penggajian', function () {
    $admin = User::factory()->create();
    $admin->assignRole('administrator');

    $proyek = Proyek::factory()->create();
    ProyekPekerja::factory()->create(['proyek_id' => $proyek->id, 'status' => StatusPekerja::AKTIF]);

    Livewire::actingAs($admin)
        ->test(MainIndex::class, ['proyek' => $proyek->id])
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

test('create without active worker is rejected', function () {
    $admin = User::factory()->create();
    $admin->assignRole('administrator');

    $proyek = Proyek::factory()->create();

    Livewire::actingAs($admin)
        ->test(MainIndex::class, ['proyek' => $proyek->id])
        ->set('state.nama_periode', 'Minggu 1')
        ->set('state.periode_mulai', '2026-01-01')
        ->set('state.periode_selesai', '2026-01-07')
        ->set('state.jam_kerja', 40)
        ->set('state.status', 1)
        ->call('actionForm')
        ->assertHasNoErrors();

    expect(ProyekPenggajian::where('nama_periode', 'Minggu 1')->exists())->toBeFalse();
});

test('create validates date order and jam_kerja', function () {
    $admin = User::factory()->create();
    $admin->assignRole('administrator');

    $proyek = Proyek::factory()->create();
    ProyekPekerja::factory()->create(['proyek_id' => $proyek->id, 'status' => StatusPekerja::AKTIF]);

    Livewire::actingAs($admin)
        ->test(MainIndex::class, ['proyek' => $proyek->id])
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

    $proyek = Proyek::factory()->create();
    ProyekPekerja::factory()->create(['proyek_id' => $proyek->id, 'status' => StatusPekerja::AKTIF]);

    $target = ProyekPenggajian::factory()->create([
        'proyek_id' => $proyek->id,
        'nama_periode' => 'Nama Lama',
    ]);

    Livewire::actingAs($admin)
        ->test(MainIndex::class, ['proyek' => $proyek->id])
        ->call('doEdit', $target->id)
        ->set('state.nama_periode', 'Nama Baru')
        ->call('actionForm')
        ->assertHasNoErrors();

    expect(ProyekPenggajian::find($target->id)->nama_periode)->toBe('Nama Baru');
});

test('administrator can soft delete a penggajian', function () {
    $admin = User::factory()->create();
    $admin->assignRole('administrator');

    $proyek = Proyek::factory()->create();
    $target = ProyekPenggajian::factory()->create(['proyek_id' => $proyek->id]);

    Livewire::actingAs($admin)
        ->test(MainIndex::class, ['proyek' => $proyek->id])
        ->call('doDelete', $target->id);

    $this->assertSoftDeleted('proyek_penggajians', ['id' => $target->id]);
});
