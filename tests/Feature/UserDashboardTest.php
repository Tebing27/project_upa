<?php

use App\Models\Certificate;
use App\Models\Registration;
use App\Models\Scheme;
use App\Models\User;
use Carbon\Carbon;

it('renders the user dashboard with the new summary cards', function () {
    $user = User::factory()->create([
        'nim' => '2210511042',
        'program_studi' => 'Teknik Informatika',
    ]);
    $scheme = Scheme::factory()->create(['name' => 'Junior Web Developer']);

    Registration::factory()->create([
        'user_id' => $user->id,
        'scheme_id' => $scheme->id,
        'status' => 'pending_payment',
        'payment_reference' => 'REF-2024-0042',
    ]);

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertOk()
        ->assertSee('Sertifikat Aktif')
        ->assertSee('Status Pendaftaran')
        ->assertSee('Kode Referensi')
        ->assertSee('REF-2024-0042')
        ->assertSee('Progress Pendaftaran')
        ->assertSee('Verifikasi Data & Dokumen')
        ->assertSee('Detail Pendaftaran')
        ->assertSee('Tidak ada');
});

it('displays the active certificate summary from the database', function () {
    $user = User::factory()->create();

    Certificate::factory()->count(3)->create([
        'user_id' => $user->id,
        'status' => 'active',
        'scheme_name' => 'Skema Dummy',
    ]);

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertOk()
        ->assertSee('Sertifikat Aktif')
        ->assertSee('3')
        ->assertSee('Skema Dummy');
});

it('shows rejected document details in progress step two', function () {
    $user = User::factory()->create();
    $scheme = Scheme::factory()->create(['name' => 'Junior Web Developer']);

    Registration::factory()->create([
        'user_id' => $user->id,
        'scheme_id' => $scheme->id,
        'status' => 'dokumen_ditolak',
        'document_statuses' => [
            'khs_path' => [
                'status' => 'rejected',
                'note' => 'Dokumen buram dan tidak terbaca.',
            ],
        ],
    ]);

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertOk()
        ->assertSee('Dokumen yang perlu diperbaiki')
        ->assertSee('KHS ditolak')
        ->assertSee('Dokumen buram dan tidak terbaca.')
        ->assertSee('Lihat status pendaftaran');
});

it('shows the exam schedule in progress step three', function () {
    $user = User::factory()->create();
    $scheme = Scheme::factory()->create(['name' => 'Junior Web Developer']);

    Registration::factory()->create([
        'user_id' => $user->id,
        'scheme_id' => $scheme->id,
        'status' => 'terjadwal',
        'exam_date' => Carbon::parse('2026-04-10 09:00:00'),
        'exam_location' => 'Lab Sertifikasi Gedung A',
        'assessor_name' => 'Budi Santoso',
    ]);

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertOk()
        ->assertSee('Jadwal ujian Anda')
        ->assertSee('Lab Sertifikasi Gedung A')
        ->assertSee('Budi Santoso');
});

it('shows certificate and exam result download actions when files are available', function () {
    $user = User::factory()->create();
    $scheme = Scheme::factory()->create(['name' => 'Junior Web Developer']);

    Registration::factory()->create([
        'user_id' => $user->id,
        'scheme_id' => $scheme->id,
        'status' => 'sertifikat_terbit',
    ]);

    Certificate::factory()->create([
        'user_id' => $user->id,
        'scheme_name' => 'Junior Web Developer',
        'status' => 'active',
        'file_path' => 'certificates/jwd.pdf',
        'result_file_path' => 'exam-results/jwd-result.pdf',
    ]);

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertOk()
        ->assertSee('Unduh Sertifikat')
        ->assertSee('Unduh Hasil Ujian');
});
