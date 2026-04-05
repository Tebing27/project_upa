<?php

use App\Models\User;
use Livewire\Livewire;

test('profile page is displayed', function () {
    $this->actingAs($user = User::factory()->create());

    $this->get(route('profile.edit'))->assertOk();
});

test('profile information can be updated', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Livewire::test('pages::settings.profile')
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->set('nim', '123456789')
        ->set('fakultas', 'Fakultas Teknik')
        ->call('updateProfileInformation');

    $response->assertHasNoErrors();

    $user->refresh();

    expect($user->name)->toEqual('Test User');
    expect($user->email)->toEqual('test@example.com');
    expect($user->nim)->toEqual('123456789');
    expect($user->fakultas)->toEqual('Fakultas Teknik');
    expect($user->email_verified_at)->toBeNull();
});

test('general user can complete biodata from profile page', function () {
    $user = User::factory()->general()->create([
        'email_verified_at' => now(),
    ]);

    $this->actingAs($user);

    Livewire::test('pages::settings.profile')
        ->set('name', 'Peserta Umum')
        ->set('email', 'umum@example.com')
        ->set('no_ktp', '3174000000000001')
        ->set('jenis_kelamin', 'L')
        ->set('tempat_lahir', 'Jakarta')
        ->set('tanggal_lahir', '1998-04-10')
        ->set('alamat_rumah', 'Jl. Contoh No. 1')
        ->set('domisili_provinsi', 'DKI Jakarta')
        ->set('domisili_kota', 'Jakarta Selatan')
        ->set('domisili_kecamatan', 'Setiabudi')
        ->set('no_wa', '081234567890')
        ->set('pendidikan_terakhir', 'S1')
        ->set('nama_institusi', 'Universitas Contoh')
        ->set('program_studi', 'Teknik Informatika')
        ->set('pekerjaan', 'Karyawan Swasta')
        ->call('updateProfileInformation')
        ->assertHasNoErrors();

    $user->refresh();

    expect($user->hasCompletedProfile())->toBeTrue()
        ->and($user->profile_completed_at)->not->toBeNull()
        ->and($user->nama_institusi)->toBe('Universitas Contoh')
        ->and($user->pekerjaan)->toBe('Karyawan Swasta');
});

test('email verification status is unchanged when email address is unchanged', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Livewire::test('pages::settings.profile')
        ->set('name', 'Test User')
        ->set('email', $user->email)
        ->call('updateProfileInformation');

    $response->assertHasNoErrors();

    expect($user->refresh()->email_verified_at)->not->toBeNull();
});

test('user can delete their account', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Livewire::test('pages::settings.delete-user-modal')
        ->set('password', 'password')
        ->call('deleteUser');

    $response
        ->assertHasNoErrors()
        ->assertRedirect('/');

    expect($user->fresh())->toBeNull();
    expect(auth()->check())->toBeFalse();
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Livewire::test('pages::settings.delete-user-modal')
        ->set('password', 'wrong-password')
        ->call('deleteUser');

    $response->assertHasErrors(['password']);

    expect($user->fresh())->not->toBeNull();
});
