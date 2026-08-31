<?php

use App\Livewire\MasterData\ProyekPekerja\MainIndex;
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

test('administrator can view the proyek pekerja page', function () {
    $admin = User::factory()->create();
    $admin->assignRole('administrator');

    $proyek = Proyek::factory()->create();

    Livewire::actingAs($admin)
        ->test(MainIndex::class, ['proyek' => $proyek->id])
        ->assertOk();
});

test('operator can view the proyek pekerja page', function () {
    $operator = User::factory()->create();
    $operator->assignRole('operator');

    $proyek = Proyek::factory()->create();

    Livewire::actingAs($operator)
        ->test(MainIndex::class, ['proyek' => $proyek->id])
        ->assertOk();
});

test('user without role cannot access the proyek pekerja page', function () {
    $user = User::factory()->create();

    $proyek = Proyek::factory()->create();

    Livewire::actingAs($user)
        ->test(MainIndex::class, ['proyek' => $proyek->id])
        ->assertForbidden();
});

test('administrator can create a proyek pekerja', function () {
    $admin = User::factory()->create();
    $admin->assignRole('administrator');

    $proyek = Proyek::factory()->create();

    Livewire::actingAs($admin)
        ->test(MainIndex::class, ['proyek' => $proyek->id])
        ->set('pekerjaState.nama_pekerja', 'Budi Santoso')
        ->set('pekerjaState.nomor_hp', '08123456789')
        ->set('pekerjaState.status_jabatan', 'Mandor')
        ->set('pekerjaState.tarif_harian', 150000)
        ->set('pekerjaState.tarif_overtime', 20000)
        ->set('pekerjaState.catatan', 'Catatan test')
        ->set('pekerjaState.status', 1)
        ->call('actionForm')
        ->assertHasNoErrors();

    expect(ProyekPekerja::where('nama_pekerja', 'Budi Santoso')->first())
        ->proyek_id->toBe($proyek->id);
});

test('create validates required fields', function () {
    $admin = User::factory()->create();
    $admin->assignRole('administrator');

    $proyek = Proyek::factory()->create();

    Livewire::actingAs($admin)
        ->test(MainIndex::class, ['proyek' => $proyek->id])
        ->set('pekerjaState.tarif_harian', 'bukan-angka')
        ->call('actionForm')
        ->assertHasErrors(['pekerjaState.nama_pekerja', 'pekerjaState.nomor_hp', 'pekerjaState.status_jabatan', 'pekerjaState.tarif_harian']);

    expect(ProyekPekerja::where('proyek_id', $proyek->id)->exists())->toBeFalse();
});

test('administrator can edit a proyek pekerja', function () {
    $admin = User::factory()->create();
    $admin->assignRole('administrator');

    $proyek = Proyek::factory()->create();
    $target = ProyekPekerja::factory()->create([
        'proyek_id' => $proyek->id,
        'nama_pekerja' => 'Nama Lama',
    ]);

    Livewire::actingAs($admin)
        ->test(MainIndex::class, ['proyek' => $proyek->id])
        ->call('doEdit', $target->id)
        ->set('pekerjaState.nama_pekerja', 'Nama Baru')
        ->call('actionForm')
        ->assertHasNoErrors();

    expect(ProyekPekerja::find($target->id)->nama_pekerja)->toBe('Nama Baru');
});

test('administrator can soft delete a proyek pekerja', function () {
    $admin = User::factory()->create();
    $admin->assignRole('administrator');

    $proyek = Proyek::factory()->create();
    $target = ProyekPekerja::factory()->create(['proyek_id' => $proyek->id]);

    Livewire::actingAs($admin)
        ->test(MainIndex::class, ['proyek' => $proyek->id])
        ->call('doDelete', $target->id);

    $this->assertSoftDeleted('proyek_pekerjas', ['id' => $target->id]);
});
