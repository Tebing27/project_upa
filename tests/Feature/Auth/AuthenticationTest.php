<?php

use App\Models\User;
use Laravel\Fortify\Features;

test('login screen can be rendered', function () {
    $response = $this->get(route('login'));

    $response->assertOk();
});

test('users can authenticate using the login screen', function () {
    $user = createMahasiswaUser();

    $response = $this->post(route('login.store'), [
        'nim' => $user->nim,
        'password' => 'password',
    ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticated();
});

test('admins are redirected to the admin dashboard after login', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->post(route('login.store'), [
        'nim' => $admin->email,
        'password' => 'password',
    ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticated();
});

test('users can authenticate using email on the login screen', function () {
    $user = User::factory()->create([
        'email' => 'user@example.com',
    ]);

    $response = $this->post(route('login.store'), [
        'nim' => 'user@example.com',
        'password' => 'password',
    ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticatedAs($user);
});

test('users can not authenticate with invalid password', function () {
    $user = createMahasiswaUser();

    $response = $this->post(route('login.store'), [
        'nim' => $user->nim,
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrorsIn('nim');

    $this->assertGuest();
});

test('users with two factor enabled are redirected to two factor challenge', function () {
    $this->skipUnlessFortifyFeature(Features::twoFactorAuthentication());

    Features::twoFactorAuthentication([
        'confirm' => true,
        'confirmPassword' => true,
    ]);

    $user = User::factory()->withTwoFactor()->create([
        'nim' => '2210511999',
    ]);

    $response = $this->post(route('login.store'), [
        'nim' => $user->nim,
        'password' => 'password',
    ]);

    $response->assertRedirect(route('two-factor.login'));
    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('logout'));

    $response->assertRedirect(route('home'));

    $this->assertGuest();
});
