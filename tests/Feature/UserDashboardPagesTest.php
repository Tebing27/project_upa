<?php

use App\Livewire\UserRegistrationStatus;
use App\Models\Certificate;
use App\Models\Registration;
use App\Models\Scheme;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

it('renders the registration status page', function () {
    $user = User::factory()->create(['nim' => '2210511042']);
    $scheme = Scheme::factory()->create(['name' => 'Junior Web Developer']);

    Registration::factory()->create([
        'user_id' => $user->id,
        'scheme_id' => $scheme->id,
        'status' => 'dokumen_ditolak',
        'document_statuses' => [
            'khs_path' => [
                'status' => 'rejected',
                'note' => 'Dokumen KHS buram.',
            ],
        ],
    ]);

    $this->actingAs($user)
        ->get(route('dashboard.status'))
        ->assertOk()
        ->assertSee('Status Pendaftaran')
        ->assertSee('Dokumen')
        ->assertSee('Riwayat Status')
        ->assertSee('Dokumen KHS buram.');
});

it('allows users to reupload rejected documents', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $scheme = Scheme::factory()->create();
    $registration = Registration::factory()->create([
        'user_id' => $user->id,
        'scheme_id' => $scheme->id,
        'status' => 'dokumen_ditolak',
        'document_statuses' => [
            'khs_path' => [
                'status' => 'rejected',
                'note' => 'Dokumen KHS buram.',
            ],
        ],
    ]);

    $file = UploadedFile::fake()->create('khs.pdf', 200, 'application/pdf');

    Livewire::actingAs($user)
        ->test(UserRegistrationStatus::class)
        ->set('reuploadFiles.khs_path', $file)
        ->call('reuploadDocument', 'khs_path')
        ->assertSet('successMessage', 'Dokumen berhasil diupload ulang dan sedang menunggu verifikasi admin.');

    $registration->refresh();

    expect($registration->status)->toBe('menunggu_verifikasi');
    expect($registration->khs_path)->not->toBeNull();
    expect($registration->document_statuses['khs_path']['status'])->toBe('pending');

    Storage::disk('public')->assertExists($registration->khs_path);
});

it('renders the certificates page with the active certificate and table', function () {
    $user = User::factory()->create();

    Certificate::factory()->create([
        'user_id' => $user->id,
        'scheme_name' => 'Junior Web Developer',
        'level' => 'KKNI Level 6',
        'status' => 'active',
        'file_path' => 'certificates/jwd.pdf',
        'result_file_path' => 'exam-results/jwd-result.pdf',
        'expired_date' => now()->addMonths(9),
    ]);

    $this->actingAs($user)
        ->get(route('dashboard.certificates'))
        ->assertOk()
        ->assertSee('Sertifikat Saya')
        ->assertSee('Junior Web Developer')
        ->assertSee('Semua Sertifikat')
        ->assertSee('Unduh Sertifikat')
        ->assertSee('Unduh Hasil Ujian');
});
