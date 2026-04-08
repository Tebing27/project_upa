<?php

use App\Livewire\CekSertifikat;
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
    $user = createMahasiswaUser(
        user: ['nama' => 'Budi Santoso'],
        mahasiswaProfile: ['nim' => '112233445'],
    );
    $scheme = createScheme(['nama' => 'Junior Web Developer']);

    createCertificateForUser($user, $scheme, [
        'certificate_number' => 'CERT-112233445',
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
    $user = createGeneralUser(
        user: ['nama' => 'Siti Umum'],
        umumProfile: ['no_ktp' => '3174000000000001'],
        completed: true,
    );
    $scheme = createScheme(['nama' => 'Junior Web Developer']);

    $certificate = createCertificateForUser($user, $scheme, [
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
    $user = createMahasiswaUser(
        user: ['nama' => 'Budi Santoso'],
        mahasiswaProfile: ['nim' => '112233445'],
    );
    $scheme = createScheme(['nama' => 'Junior Web Developer']);

    createCertificateForUser($user, $scheme, [
        'certificate_number' => 'CERT-112233445',
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
    $user = createMahasiswaUser(
        user: ['nama' => 'Budi'],
        mahasiswaProfile: ['nim' => '998877665'],
    );
    $scheme = createScheme(['nama' => 'Data Analyst']);

    createCertificateForUser($user, $scheme, [
        'certificate_number' => 'CERT-998877665',
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
    $user = createMahasiswaUser(
        user: ['nama' => 'Budi'],
        mahasiswaProfile: ['nim' => '556677889'],
    );
    $scheme = createScheme(['nama' => 'Old Certificate']);

    createCertificateForUser($user, $scheme, [
        'certificate_number' => 'CERT-556677889',
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
