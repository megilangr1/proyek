<?php

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
        ->get(route('admin.pengguna.index'))
        ->assertOk()
        ->assertSee('Akun Pengguna');
});

test('administrator can create a user with a role', function () {
    $admin = User::factory()->create();
    $admin->assignRole('administrator');

    Livewire::actingAs($admin)
        ->test('admin::master-data.pengguna.main-index')
        ->set([
            'name' => 'Budi Baru',
            'email' => 'budi@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'role' => 'operator',
        ])
        ->call('save')
        ->assertHasNoErrors();

    expect(User::where('email', 'budi@example.com')->exists())->toBeTrue();
    expect(User::where('email', 'budi@example.com')->first()->hasRole('operator'))->toBeTrue();
});

test('administrator can edit a user', function () {
    $admin = User::factory()->create();
    $admin->assignRole('administrator');

    $target = User::factory()->create(['name' => 'Nama Lama']);

    Livewire::actingAs($admin)
        ->test('admin::master-data.pengguna.main-index')
        ->call('edit', $target->id)
        ->set('name', 'Nama Baru')
        ->set('role', 'operator')
        ->call('save')
        ->assertHasNoErrors();

    expect(User::find($target->id)->name)->toBe('Nama Baru');
});

test('administrator can soft delete a user', function () {
    $admin = User::factory()->create();
    $admin->assignRole('administrator');

    $target = User::factory()->create();

    Livewire::actingAs($admin)
        ->test('admin::master-data.pengguna.main-index')
        ->call('delete', $target->id);

    $this->assertSoftDeleted('users', ['id' => $target->id]);
});

test('operator cannot access the users page', function () {
    $operator = User::factory()->create();
    $operator->assignRole('operator');

    Livewire::actingAs($operator)
        ->test('admin::master-data.pengguna.main-index')
        ->assertForbidden();
});

test('user cannot delete their own account', function () {
    $admin = User::factory()->create();
    $admin->assignRole('administrator');

    Livewire::actingAs($admin)
        ->test('admin::master-data.pengguna.main-index')
        ->call('delete', $admin->id);

    expect(User::find($admin->id))->not->toBeNull();
    expect(User::find($admin->id)->deleted_at)->toBeNull();
});
