<?php

use App\Enums\KategoriPengeluaran;
use App\Livewire\MasterData\ProyekPengeluaran\MainIndex;
use App\Models\Proyek;
use App\Models\ProyekPengeluaran;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

beforeEach(function (): void {
    Role::firstOrCreate(['name' => 'administrator']);
    Role::firstOrCreate(['name' => 'meggi']);
    Role::firstOrCreate(['name' => 'operator']);
});

test('administrator can view the proyek pengeluaran page', function (): void {
    $admin = User::factory()->create();
    $admin->assignRole('administrator');

    $proyek = Proyek::factory()->create();

    Livewire::actingAs($admin)
        ->test(MainIndex::class, ['proyek' => $proyek->id])
        ->assertOk();
});

test('operator can view the proyek pengeluaran page', function (): void {
    $operator = User::factory()->create();
    $operator->assignRole('operator');

    $proyek = Proyek::factory()->create();

    Livewire::actingAs($operator)
        ->test(MainIndex::class, ['proyek' => $proyek->id])
        ->assertOk();
});

test('user without role cannot access the proyek pengeluaran page', function (): void {
    $user = User::factory()->create();

    $proyek = Proyek::factory()->create();

    Livewire::actingAs($user)
        ->test(MainIndex::class, ['proyek' => $proyek->id])
        ->assertForbidden();
});

test('administrator can create a proyek pengeluaran', function (): void {
    $admin = User::factory()->create();
    $admin->assignRole('administrator');

    $proyek = Proyek::factory()->create();

    Livewire::actingAs($admin)
        ->test(MainIndex::class, ['proyek' => $proyek->id])
        ->set('state.tanggal', '2026-09-02')
        ->set('state.kategori', KategoriPengeluaran::SEMEN->value)
        ->set('state.nama_item', 'Semen 50kg x 10')
        ->set('state.nominal', 1500000)
        ->set('state.keterangan', 'Pembelian awal')
        ->set('state.status', 1)
        ->call('actionForm')
        ->assertHasNoErrors();

    expect(ProyekPengeluaran::where('nama_item', 'Semen 50kg x 10')->first())
        ->proyek_id->toBe($proyek->id)
        ->nominal->toBe('1500000.00');
});

test('create validates required fields', function (): void {
    $admin = User::factory()->create();
    $admin->assignRole('administrator');

    $proyek = Proyek::factory()->create();

    Livewire::actingAs($admin)
        ->test(MainIndex::class, ['proyek' => $proyek->id])
        ->set('state.tanggal', '')
        ->set('state.kategori', '')
        ->set('state.nama_item', '')
        ->set('state.nominal', 'bukan-angka')
        ->call('actionForm')
        ->assertHasErrors(['state.tanggal', 'state.kategori', 'state.nama_item', 'state.nominal']);

    expect(ProyekPengeluaran::where('proyek_id', $proyek->id)->exists())->toBeFalse();
});

test('administrator can edit a proyek pengeluaran', function (): void {
    $admin = User::factory()->create();
    $admin->assignRole('administrator');

    $proyek = Proyek::factory()->create();
    $target = ProyekPengeluaran::factory()->create([
        'proyek_id' => $proyek->id,
        'nama_item' => 'Nama Lama',
    ]);

    Livewire::actingAs($admin)
        ->test(MainIndex::class, ['proyek' => $proyek->id])
        ->call('doEdit', $target->id)
        ->set('state.nama_item', 'Nama Baru')
        ->call('actionForm')
        ->assertHasNoErrors();

    expect(ProyekPengeluaran::find($target->id)->nama_item)->toBe('Nama Baru');
});

test('administrator can soft delete a proyek pengeluaran', function (): void {
    $admin = User::factory()->create();
    $admin->assignRole('administrator');

    $proyek = Proyek::factory()->create();
    $target = ProyekPengeluaran::factory()->create(['proyek_id' => $proyek->id]);

    Livewire::actingAs($admin)
        ->test(MainIndex::class, ['proyek' => $proyek->id])
        ->call('doDelete', $target->id);

    $this->assertSoftDeleted('proyek_pengeluarans', ['id' => $target->id]);
});
