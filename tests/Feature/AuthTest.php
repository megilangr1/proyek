<?php

use App\Livewire\Auth\Login;
use App\Models\User;
use Livewire\Livewire;

test('login page can be rendered', function () {
    $this->get('/login')->assertStatus(200)->assertSee('email');
});

test('users can authenticate', function () {
    $user = User::factory()->create(['password' => 'password']);

    Livewire::test(Login::class)
        ->set(['email' => $user->email, 'password' => 'password'])
        ->call('login')
        ->assertRedirectToRoute('dashboard');

    $this->assertAuthenticated();
});

test('users cannot authenticate with invalid password', function () {
    $user = User::factory()->create(['password' => 'password']);

    Livewire::test(Login::class)
        ->set(['email' => $user->email, 'password' => 'wrong-password'])
        ->call('login')
        ->assertHasErrors('email')
        ->assertNoRedirect();

    $this->assertGuest();
});

test('users cannot authenticate with missing fields', function () {
    Livewire::test(Login::class)
        ->set(['email' => '', 'password' => ''])
        ->call('login')
        ->assertHasErrors(['email', 'password']);
});

test('authenticated users cannot view login', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get('/login')->assertRedirectToRoute('dashboard');
});

test('guests cannot view dashboard', function () {
    $this->get('/dashboard')->assertRedirect('/login');
});

test('users can logout', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post('/logout')
        ->assertRedirect('/login');

    $this->assertGuest();
});
