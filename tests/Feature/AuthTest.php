<?php

use App\Models\User;
use Livewire\Livewire;

test('login page can be rendered', function () {
    $this->get('/login')->assertStatus(200)->assertSee('email');
});

test('users can authenticate', function () {
    $user = User::factory()->create(['password' => 'password']);

    Livewire::test('auth::login')
        ->set(['email' => $user->email, 'password' => 'password'])
        ->call('login')
        ->assertRedirectToRoute('admin.main');

    $this->assertAuthenticated();
});

test('users cannot authenticate with invalid password', function () {
    $user = User::factory()->create(['password' => 'password']);

    Livewire::test('auth::login')
        ->set(['email' => $user->email, 'password' => 'wrong-password'])
        ->call('login')
        ->assertHasErrors('email')
        ->assertNoRedirect();

    $this->assertGuest();
});

test('users cannot authenticate with missing fields', function () {
    Livewire::test('auth::login')
        ->set(['email' => '', 'password' => ''])
        ->call('login')
        ->assertHasErrors(['email', 'password']);
});

test('authenticated users cannot view login', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get('/login')->assertRedirect('/');
});

test('guests cannot view admin', function () {
    $this->get('/admin')->assertRedirect('/login');
});

test('users can logout', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post('/logout')
        ->assertRedirect('/login');

    $this->assertGuest();
});
