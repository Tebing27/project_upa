<?php

use App\Models\AppSetting;
use App\Models\User;
use Carbon\Carbon;

it('renders the user dashboard with the new summary cards', function () {
    $user = createMahasiswaUser();
    $scheme = createScheme(['nama' => 'Junior Web Developer']);

    createRegistrationWithRelations($user, $scheme, [
        'status' => 'pending_payment',
        'payment_reference' => 'REF-2024-0042',
    ]);

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertOk()
        ->assertSee('UPA LUK')
        ->assertSee('Sertifikat Aktif')
        ->assertSee('Status Pendaftaran')
        ->assertSee('Kode Referensi')
        ->assertSee('REF-2024-0042')
        ->assertSee('Progress Pendaftaran')
        ->assertSee('Tahap 3 dari 5')
        ->assertSee('Daftar')
        ->assertSee('Pembayaran')
        ->assertSee('Detail Pendaftaran')
        ->assertSee('Tahap Pembayaran')
        ->assertSee('Kelola Pembayaran')
        ->assertSee('Daftar Skema Baru')
        ->assertSee('Selesaikan satu skema sertifikasi hingga tahap sertifikat terbit terlebih dahulu sebelum mendaftar skema baru.');
});

it('displays the active certificate summary from the database', function () {
    $user = User::factory()->create();
    $scheme = createScheme(['nama' => 'Skema Dummy']);

    createCertificateForUser($user, $scheme, ['status' => 'active']);
    createCertificateForUser($user, $scheme, ['status' => 'active']);
    createCertificateForUser($user, $scheme, ['status' => 'active']);

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertOk()
        ->assertSee('Sertifikat Aktif')
        ->assertSee('3')
        ->assertSee('Skema Dummy');
});

it('shows rejected document details in progress step two', function () {
    $user = User::factory()->create();
    $scheme = createScheme(['nama' => 'Junior Web Developer']);

    createRegistrationWithRelations($user, $scheme, [
        'status' => 'dokumen_ditolak',
    ], [], [
        'khs_path' => [
            'status' => 'rejected',
            'note' => 'Dokumen buram dan tidak terbaca.',
        ],
    ]);

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertOk()
        ->assertSee('Lengkapi Dokumen')
        ->assertSee('KHS ditolak')
        ->assertSee('Dokumen buram dan tidak terbaca.')
        ->assertSee('Upload Ulang Dokumen')
        ->assertSee('Lihat status pendaftaran');
});

it('shows the exam schedule in progress step four', function () {
    $user = User::factory()->create();
    $scheme = createScheme(['nama' => 'Junior Web Developer']);

    createRegistrationWithRelations($user, $scheme, [
        'status' => 'terjadwal',
    ], [], [], [
        'exam_date' => Carbon::parse('2026-04-10 09:00:00'),
        'exam_location' => 'Lab Sertifikasi Gedung A',
        'assessor_name' => 'Budi Santoso',
    ]);

    AppSetting::put('whatsapp_channel_link', 'https://chat.whatsapp.com/dashboard-jadwal');

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertOk()
        ->assertSee('Persiapan Ujian')
        ->assertSee('Tanggal Ujian')
        ->assertSee('Jam Ujian')
        ->assertSee('Jam 09:00')
        ->assertSee('Lab Sertifikasi Gedung A')
        ->assertSee('Budi Santoso')
        ->assertSee('Link WhatsApp')
        ->assertSee('Buka Grup / Chat');
});

it('shows certificate and exam result download actions when files are available', function () {
    $user = User::factory()->create();
    $scheme = createScheme(['nama' => 'Junior Web Developer']);

    createRegistrationWithRelations($user, $scheme, [
        'status' => 'sertifikat_terbit',
    ], [], [], [
        'exam_result_path' => 'exam-results/jwd-result.pdf',
    ]);

    createCertificateForUser($user, $scheme, [
        'status' => 'active',
        'file_path' => 'certificates/jwd.pdf',
        'result_file_path' => 'exam-results/jwd-result.pdf',
    ]);

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertOk()
        ->assertSee('Daftar Skema Baru')
        ->assertSee(route('dashboard.skema'), false)
        ->assertSee('Unduh Sertifikat')
        ->assertSee('Unduh Hasil Ujian');
});

it('shows Daftar Ulang button when status is tidak_kompeten', function () {
    $user = User::factory()->create();
    $scheme = createScheme(['nama' => 'Junior Web Developer']);

    createRegistrationWithRelations($user, $scheme, [
        'status' => 'tidak_kompeten',
    ], [], [], [
        'exam_result_path' => 'exam-results/failed.pdf',
    ]);

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertOk()
        ->assertSee('Daftar Skema Baru')
        ->assertSee('Selesaikan satu skema sertifikasi hingga tahap sertifikat terbit terlebih dahulu sebelum mendaftar skema baru.')
        ->assertSee('Tahap 5 dari 5')
        ->assertSee('Hasil Ujian')
        ->assertSee('Belum Kompeten')
        ->assertSee('Unduh Hasil Ujian')
        ->assertSee(e(route('dashboard.daftar-skema', [
            'type' => 'perpanjangan',
            'scheme' => $scheme->id,
            'source' => 'dashboard-skema',
        ])), false)
        ->assertSee('Daftar Ulang Skema Ini');
});

it('shows review status when documents are being verified', function () {
    $user = User::factory()->create();
    $scheme = createScheme(['nama' => 'Junior Web Developer']);

    createRegistrationWithRelations($user, $scheme, [
        'status' => 'menunggu_verifikasi',
    ]);

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertOk()
        ->assertSee('Tahap Review')
        ->assertSee('Sedang Direview')
        ->assertSee('Lihat Detail Status');
});

it('shows payment instructions and recent scheme history during payment stage', function () {
    $user = User::factory()->create();
    $scheme = createScheme(['nama' => 'Junior Web Developer']);

    createRegistrationWithRelations($user, $scheme, [
        'status' => 'dokumen_ok',
        'payment_reference' => 'PAY-001',
    ]);

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertOk()
        ->assertSee('Tahap Pembayaran')
        ->assertSee('Kode Instruksi Pembayaran')
        ->assertSee('PAY-001')
        ->assertSee('Riwayat Skema')
        ->assertSee('Junior Web Developer');
});

it('shows biodata completion call to action for incomplete general users', function () {
    $user = createGeneralUser(completed: false);

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertOk()
        ->assertSee('Biodata Belum Lengkap')
        ->assertSee('Lengkapi biodata di tahap kedua daftar skema')
        ->assertSee('Lihat Skema')
        ->assertSee('Daftar Skema');
});
