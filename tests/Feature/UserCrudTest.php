<?php

use App\Livewire\MasterData\Pengguna\MainIndex;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'administrator']);
    Role::firstOrCreate(['name' => 'meggi']);
    Role::firstOrCreate(['name' => 'operator']);
});

test('administrator can view the users page', function () {
    $admin = User::factory()->create();
    $admin->assignRole('administrator');

    $this->actingAs($admin)
        ->get(route('pengguna.index'))
        ->assertOk()
        ->assertSee('Data Pengguna Aplikasi');
});

test('administrator can create a user with a role', function () {
    $admin = User::factory()->create();
    $admin->assignRole('administrator');

    Livewire::actingAs($admin)
        ->test(MainIndex::class)
        ->set('state.name', 'Budi Baru')
        ->set('state.email', 'budi@example.com')
        ->set('state.password', 'secret123')
        ->set('state.password_confirmation', 'secret123')
        ->set('state.roles', 'operator')
        ->call('actionForm')
        ->assertHasNoErrors();

    expect(User::where('email', 'budi@example.com')->exists())->toBeTrue();
    expect(User::where('email', 'budi@example.com')->first()->hasRole('operator'))->toBeTrue();
});

test('administrator can edit a user', function () {
    $admin = User::factory()->create();
    $admin->assignRole('administrator');

    $target = User::factory()->create(['name' => 'Nama Lama']);

    Livewire::actingAs($admin)
        ->test(MainIndex::class)
        ->call('doEdit', $target->id)
        ->set('state.name', 'Nama Baru')
        ->set('state.roles', 'operator')
        ->call('actionForm')
        ->assertHasNoErrors();

    expect(User::find($target->id)->name)->toBe('Nama Baru');
});

test('administrator can soft delete a user', function () {
    $admin = User::factory()->create();
    $admin->assignRole('administrator');

    $target = User::factory()->create();

    Livewire::actingAs($admin)
        ->test(MainIndex::class)
        ->call('doDelete', $target->id);

    $this->assertSoftDeleted('users', ['id' => $target->id]);
});

test('operator cannot access the users page', function () {
    $operator = User::factory()->create();
    $operator->assignRole('operator');

    Livewire::actingAs($operator)
        ->test(MainIndex::class)
        ->assertForbidden();
});

test('user cannot delete their own account', function () {
    $admin = User::factory()->create();
    $admin->assignRole('administrator');

    Livewire::actingAs($admin)
        ->test(MainIndex::class)
        ->call('doDelete', $admin->id);

    expect(User::find($admin->id))->not->toBeNull();
    expect(User::find($admin->id)->deleted_at)->toBeNull();
});
