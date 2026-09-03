<?php

use App\Enums\StatusPekerja;
use App\Livewire\MasterData\Proyek\MainDetail;
use App\Models\Proyek;
use App\Models\ProyekPekerja;
use App\Models\ProyekPengeluaran;
use App\Models\ProyekPenggajian;
use App\Models\ProyekPenggajianPekerja;
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

test('detail summary calculates sisa kas correctly', function () {
    $admin = User::factory()->create();
    $admin->assignRole('administrator');

    $proyek = Proyek::factory()->create(['nilai_proyek' => 10000000]);

    ProyekPengeluaran::factory()->create([
        'proyek_id' => $proyek->id,
        'nominal' => 2000000,
    ]);

    $penggajian = ProyekPenggajian::factory()->create(['proyek_id' => $proyek->id]);
    $pekerja = ProyekPekerja::factory()->create(['proyek_id' => $proyek->id]);
    ProyekPenggajianPekerja::create([
        'proyek_penggajian_id' => $penggajian->id,
        'proyek_pekerja_id' => $pekerja->id,
        'jabatan' => 'Tukang',
        'tarif_harian' => 150000,
        'tarif_overtime' => 20000,
        'total_hari' => 1,
        'total_overtime' => 0,
        'total_bersih' => 3000000,
    ]);

    Livewire::actingAs($admin)
        ->test(MainDetail::class, ['proyek' => $proyek->id])
        ->assertOk()
        ->assertViewHas('summary', function ($summary) {
            return (float) $summary['nilaiProyek'] === 10000000.0
                && (float) $summary['totalPengeluaran'] === 2000000.0
                && (float) $summary['totalPenggajian'] === 3000000.0
                && (float) $summary['totalBiaya'] === 5000000.0
                && (float) $summary['sisaKas'] === 5000000.0;
        });
});
