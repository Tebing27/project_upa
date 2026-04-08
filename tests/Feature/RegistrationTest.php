<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('a user can log in with nim', function () {
    $user = createMahasiswaUser(
        user: ['password' => Hash::make('password')],
        mahasiswaProfile: ['nim' => '123456789'],
    );

    $response = $this->post('/login', [
        'nim' => '123456789',
        'password' => 'password',
    ]);

    $this->assertAuthenticatedAs($user);
    $response->assertRedirect('/dashboard');
});

test('a user can log in with email entered into the same login field', function () {
    $user = createMahasiswaUser([
        'email' => 'mahasiswa@example.com',
        'password' => Hash::make('password'),
    ]);

    $response = $this->post('/login', [
        'nim' => 'mahasiswa@example.com',
        'password' => 'password',
    ]);

    $this->assertAuthenticatedAs($user);
    $response->assertRedirect('/dashboard');
});

test('non mahasiswa can register with email and password', function () {
    config(['mail.default' => 'log']);

    $response = $this->post('/register', [
        'email' => 'johndoe@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $user = User::query()->where('email', 'johndoe@example.com')->first();

    expect($user)->not->toBeNull()
        ->and($user->name)->toBe('Johndoe')
        ->and($user->nim)->toBeNull()
        ->and($user->user_type)->toBe('umum')
        ->and($user->profile_completed_at)->toBeNull()
        ->and($user->email_verified_at)->toBeNull()
        ->and($user->umumProfile)->not->toBeNull()
        ->and($user->profile)->not->toBeNull();

    $response->assertRedirect('/dashboard');
});
