<?php

use App\Livewire\CekSertifikat;
use App\Models\Certificate;
use App\Models\Scheme;
use App\Models\User;
use Livewire\Livewire;

it('renders the cek sertifikat page without authentication', function () {
    $this->get(route('cek-sertifikat'))
        ->assertOk()
        ->assertSee('Cek Keaslian Sertifikat');
});

it('validates search input is required', function () {
    Livewire::test(CekSertifikat::class)
        ->set('search', '')
        ->call('cekSertifikat')
        ->assertHasErrors(['search' => 'required']);
});

it('validates search input minimum length', function () {
    Livewire::test(CekSertifikat::class)
        ->set('search', 'a')
        ->call('cekSertifikat')
        ->assertHasErrors(['search' => 'min']);
});

it('finds a certificate by owner name', function () {
    $user = User::factory()->create(['name' => 'Budi Santoso']);
    $scheme = Scheme::factory()->create(['name' => 'Junior Web Developer', 'faculty' => 'Teknik']);

    Certificate::factory()->create([
        'user_id' => $user->id,
        'scheme_id' => $scheme->id,
        'scheme_name' => 'Junior Web Developer',
        'status' => 'active',
        'expired_date' => now()->addYear(),
    ]);

    $component = Livewire::test(CekSertifikat::class)
        ->set('search', 'Budi')
        ->call('cekSertifikat')
        ->assertSet('hasSearched', true);

    expect($component->get('results'))->toHaveCount(1);
    expect($component->get('results.0.nama_pemilik'))->toBe('Budi Santoso');
    expect($component->get('results.0.skema'))->toBe('Junior Web Developer');
    expect($component->get('results.0.status'))->toBe('Aktif');
});

it('finds a certificate by certificate number', function () {
    $user = User::factory()->create(['name' => 'Andi Pratama']);

    $certificate = Certificate::factory()->create([
        'user_id' => $user->id,
        'scheme_name' => 'Data Analyst',
        'status' => 'active',
    ]);

    $certNumber = 'CERT-'.str_pad((string) $certificate->id, 5, '0', STR_PAD_LEFT);

    $component = Livewire::test(CekSertifikat::class)
        ->set('search', $certNumber)
        ->call('cekSertifikat');

    expect($component->get('results'))->toHaveCount(1);
    expect($component->get('results.0.nomor'))->toBe($certNumber);
});

it('shows empty state when no certificate is found', function () {
    Livewire::test(CekSertifikat::class)
        ->set('search', 'NonExistentPerson12345')
        ->call('cekSertifikat')
        ->assertSet('hasSearched', true)
        ->assertSet('results', []);
});

it('correctly identifies expired certificates', function () {
    $user = User::factory()->create(['name' => 'Expired User']);

    Certificate::factory()->create([
        'user_id' => $user->id,
        'scheme_name' => 'Old Certificate',
        'status' => 'active',
        'expired_date' => now()->subDay(),
    ]);

    $component = Livewire::test(CekSertifikat::class)
        ->set('search', 'Expired User')
        ->call('cekSertifikat');

    expect($component->get('results'))->toHaveCount(1);
    expect($component->get('results.0.status'))->toBe('Kedaluwarsa');
    expect($component->get('results.0.is_active'))->toBeFalse();
});
