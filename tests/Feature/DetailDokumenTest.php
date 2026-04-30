<?php

use App\Livewire\Admin\DetailDokumen;
use App\Models\AppSetting;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin']);

    $this->scheme = createScheme([
        'nama' => 'Junior Web Developer',
        'is_active' => true,
    ]);
});

it('sets status to dokumen_ok when all required documents are verified even without optional internship certificate', function () {
    Storage::fake('public');
    AppSetting::put('admin_signature_name', 'Admin Verifikator');
    AppSetting::put('admin_signature_path', 'documents/signatures/admin-global.png');
    Storage::disk('public')->put('documents/signatures/admin-global.png', 'signature');

    $registration = createRegistrationWithRelations(
        createMahasiswaUser(),
        $this->scheme,
        [
            'status' => 'menunggu_verifikasi',
            'payment_reference' => '982210511042',
        ],
        [
            'fr_apl_02_path' => 'documents/fr_apl_02/test.pdf',
            'ktm_path' => 'documents/ktm/test.pdf',
            'khs_path' => 'documents/khs/test.pdf',
            'ktp_path' => 'documents/ktp/test.pdf',
            'passport_photo_path' => 'documents/photo/test.jpg',
        ],
    );

    $requiredDocs = [
        'fr_apl_02_path',
        'ktm_path',
        'khs_path',
        'ktp_path',
        'passport_photo_path',
    ];

    $component = Livewire::actingAs($this->admin)
        ->test(DetailDokumen::class, ['registration' => $registration]);

    foreach ($requiredDocs as $doc) {
        $component->call('verifikasiDokumen', $doc);
    }

    $registration->refresh();

    expect($registration->status)->toBe('dokumen_ok')
        ->and($registration->admin_signatory_name)->toBe('Admin Verifikator')
        ->and($registration->admin_signature_path)->toContain('documents/signatures/admin/registration_');
});

it('sets status to dokumen_ok when all documents including optional internship are verified', function () {
    Storage::fake('public');
    AppSetting::put('admin_signature_name', 'Admin Lengkap');
    AppSetting::put('admin_signature_path', 'documents/signatures/admin-global.png');
    Storage::disk('public')->put('documents/signatures/admin-global.png', 'signature');

    $registration = createRegistrationWithRelations(
        createMahasiswaUser(),
        $this->scheme,
        [
            'status' => 'menunggu_verifikasi',
            'payment_reference' => '982210511042',
        ],
        [
            'fr_apl_02_path' => 'documents/fr_apl_02/test.pdf',
            'ktm_path' => 'documents/ktm/test.pdf',
            'khs_path' => 'documents/khs/test.pdf',
            'internship_certificate_path' => 'documents/internship/test.pdf',
            'ktp_path' => 'documents/ktp/test.pdf',
            'passport_photo_path' => 'documents/photo/test.jpg',
        ],
    );

    $allDocs = [
        'fr_apl_02_path',
        'ktm_path',
        'khs_path',
        'internship_certificate_path',
        'ktp_path',
        'passport_photo_path',
    ];

    $component = Livewire::actingAs($this->admin)
        ->test(DetailDokumen::class, ['registration' => $registration]);

    foreach ($allDocs as $doc) {
        $component->call('verifikasiDokumen', $doc);
    }

    $registration->refresh();

    expect($registration->status)->toBe('dokumen_ok');
});

it('sets status to dokumen_ditolak when any document is rejected', function () {
    $registration = createRegistrationWithRelations(
        createMahasiswaUser(),
        $this->scheme,
        [
            'status' => 'menunggu_verifikasi',
            'payment_reference' => '982210511042',
        ],
        [
            'fr_apl_02_path' => 'documents/fr_apl_02/test.pdf',
            'ktm_path' => 'documents/ktm/test.pdf',
            'khs_path' => 'documents/khs/test.pdf',
            'ktp_path' => 'documents/ktp/test.pdf',
            'passport_photo_path' => 'documents/photo/test.jpg',
        ],
    );

    $component = Livewire::actingAs($this->admin)
        ->test(DetailDokumen::class, ['registration' => $registration]);

    $component->call('verifikasiDokumen', 'fr_apl_02_path');

    $component->set('rejectDocType', 'ktm_path')
        ->set('rejectNote', 'KTM tidak jelas')
        ->call('tolakDokumen');

    $registration->refresh();

    expect($registration->status)->toBe('dokumen_ditolak');
});

it('sets status to dokumen_ok after verifying only FR APL 02 in the condensed flow', function () {
    Storage::fake('public');
    AppSetting::put('admin_signature_name', 'Admin Condensed');
    AppSetting::put('admin_signature_path', 'documents/signatures/admin-global.png');
    Storage::disk('public')->put('documents/signatures/admin-global.png', 'signature');

    $registration = createRegistrationWithRelations(
        createMahasiswaUser(),
        $this->scheme,
        [
            'status' => 'menunggu_verifikasi',
            'payment_reference' => '982210511042',
        ],
        [
            'fr_apl_02_path' => 'documents/fr_apl_02/test.pdf',
            'ktm_path' => 'documents/ktm/test.pdf',
            'khs_path' => 'documents/khs/test.pdf',
            'ktp_path' => 'documents/ktp/test.pdf',
            'passport_photo_path' => 'documents/photo/test.jpg',
        ],
        [
            '_meta_condensed_flow' => ['status' => 'verified'],
        ],
    );

    $component = Livewire::actingAs($this->admin)
        ->test(DetailDokumen::class, ['registration' => $registration])
        ->assertSee('Formulir APL-02')
        ->assertSee('KHS / Transkrip')
        ->assertSee('Pendukung')
        ->assertDontSeeHtml("wire:click=\"verifikasiDokumen('khs_path')\"")
        ->assertDontSeeHtml("wire:click=\"bukaModalTolak('khs_path')\"");

    $component->call('verifikasiDokumen', 'fr_apl_02_path');

    expect($registration->refresh()->status)->toBe('dokumen_ok');
});
