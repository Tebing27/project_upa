<?php

use App\Livewire\CekSertifikat;
use App\Models\Certificate;
use App\Models\Scheme;
use App\Models\User;
use Livewire\Livewire;

it('renders the cek sertifikat page without authentication', function () {
    $this->get(route('cek-sertifikat'))
        ->assertOk()
        ->assertSee('Cek Keaslian Sertifikat')
        ->assertSee('Welcome to UPA LUK')
        ->assertDontSee('Register');
});

it('validates search input is required', function () {
    Livewire::test(CekSertifikat::class)
        ->set('name', '')
        ->set('search', '')
        ->call('cekSertifikat')
        ->assertHasErrors(['name' => 'required', 'search' => 'required']);
});

it('validates search input minimum length', function () {
    Livewire::test(CekSertifikat::class)
        ->set('name', 'Budi')
        ->set('search', 'a')
        ->call('cekSertifikat')
        ->assertHasErrors(['search' => 'min']);
});

it('finds a certificate by certificate number using NIM and name', function () {
    $user = User::factory()->create(['name' => 'Budi Santoso', 'nim' => '112233445']);
    $scheme = Scheme::factory()->create(['name' => 'Junior Web Developer', 'faculty' => 'Teknik']);

    Certificate::factory()->create([
        'user_id' => $user->id,
        'scheme_id' => $scheme->id,
        'scheme_name' => 'Junior Web Developer',
        'status' => 'active',
        'expired_date' => now()->addYear(),
    ]);

    $component = Livewire::test(CekSertifikat::class)
        ->set('name', 'Budi Santoso')
        ->set('search', 'CERT-112233445')
        ->call('cekSertifikat')
        ->assertSet('hasSearched', true);

    expect($component->get('results'))->toHaveCount(1);
    expect($component->get('results.0.nomor'))->toBe('CERT-112233445');
    expect($component->get('results.0.nama_pemilik'))->toBe('Budi Santoso');
    expect($component->get('results.0.skema'))->toBe('Junior Web Developer');
    expect($component->get('results.0.status'))->toBe('Aktif');
});

it('finds a general user certificate by stored certificate number', function () {
    $user = User::factory()->completedGeneralProfile()->create([
        'name' => 'Siti Umum',
        'no_ktp' => '3174000000000001',
    ]);

    $certificate = Certificate::factory()->create([
        'user_id' => $user->id,
        'scheme_name' => 'Junior Web Developer',
        'certificate_number' => 'CERT-000000000001',
        'status' => 'active',
    ]);

    $component = Livewire::test(CekSertifikat::class)
        ->set('name', 'Siti Umum')
        ->set('search', $certificate->certificate_number)
        ->call('cekSertifikat');

    expect($component->get('results'))->toHaveCount(1);
    expect($component->get('results.0.nomor'))->toBe('CERT-000000000001');
});

it('does not find a certificate by wrong owner name', function () {
    $user = User::factory()->create(['name' => 'Budi Santoso', 'nim' => '112233445']);

    Certificate::factory()->create([
        'user_id' => $user->id,
        'scheme_name' => 'Junior Web Developer',
        'status' => 'active',
    ]);

    Livewire::test(CekSertifikat::class)
        ->set('name', 'Andi')
        ->set('search', 'CERT-112233445')
        ->call('cekSertifikat')
        ->assertSet('hasSearched', true)
        ->assertSet('results', []);
});

it('finds a certificate by certificate number', function () {
    $user = User::factory()->create(['name' => 'Budi', 'nim' => '998877665']);

    Certificate::factory()->create([
        'user_id' => $user->id,
        'scheme_name' => 'Data Analyst',
        'status' => 'active',
    ]);

    $component = Livewire::test(CekSertifikat::class)
        ->set('name', 'Budi')
        ->set('search', 'CERT-998877665')
        ->call('cekSertifikat');

    expect($component->get('results'))->toHaveCount(1);
    expect($component->get('results.0.nomor'))->toBe('CERT-998877665');
});

it('shows empty state when no certificate is found', function () {
    Livewire::test(CekSertifikat::class)
        ->set('name', 'Budi')
        ->set('search', 'NonExistentPerson12345')
        ->call('cekSertifikat')
        ->assertSet('hasSearched', true)
        ->assertSet('results', []);
});

it('correctly identifies expired certificates', function () {
    $user = User::factory()->create(['name' => 'Budi', 'nim' => '556677889']);

    Certificate::factory()->create([
        'user_id' => $user->id,
        'scheme_name' => 'Old Certificate',
        'status' => 'active',
        'expired_date' => now()->subDay(),
    ]);

    $component = Livewire::test(CekSertifikat::class)
        ->set('name', 'Budi')
        ->set('search', 'CERT-556677889')
        ->call('cekSertifikat');

    expect($component->get('results'))->toHaveCount(1);
    expect($component->get('results.0.status'))->toBe('Kedaluwarsa');
    expect($component->get('results.0.is_active'))->toBeFalse();
});
