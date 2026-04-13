<?php

use App\Livewire\UserRegistrationStatus;
use App\Livewire\UserSchemesPage;
use App\Models\AppSetting;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

it('renders the registration status page', function () {
    $user = createMahasiswaUser([], [], ['nim' => '2210511042']);
    $scheme = createScheme(['nama' => 'Junior Web Developer']);

    $registration = createRegistrationWithRelations($user, $scheme, [
        'status' => 'dokumen_ditolak',
    ], [
        'khs_path' => 'documents/khs/khs.pdf',
    ], [
        'khs_path' => [
            'status' => 'rejected',
            'note' => 'Dokumen KHS buram.',
        ],
    ]);

    $this->actingAs($user)
        ->get(route('dashboard.status', $registration))
        ->assertOk()
        ->assertSee('Status Pendaftaran')
        ->assertSee('Dokumen')
        ->assertSee('Pembayaran')
        ->assertSee('Riwayat Status')
        ->assertSee('Dokumen KHS buram.')
        ->assertSee('Lihat File');
});

it('shows the published exam schedule and WhatsApp link on the registration status page', function () {
    $user = User::factory()->create();
    $scheme = createScheme(['nama' => 'Junior Web Developer']);
    $registration = createRegistrationWithRelations($user, $scheme, [
        'status' => 'terjadwal',
    ], [], [], [
        'exam_date' => now()->addDays(5)->setTime(9, 0),
        'exam_location' => 'Lab Sertifikasi Gedung B',
        'assessor_name' => 'Siti Rahma',
    ]);

    AppSetting::put('whatsapp_channel_link', 'https://chat.whatsapp.com/status-jadwal');

    $this->actingAs($user)
        ->get(route('dashboard.status', $registration))
        ->assertOk()
        ->assertSee('Jadwal Ujian')
        ->assertSee('Lab Sertifikasi Gedung B')
        ->assertSee('Siti Rahma')
        ->assertSee('Link WhatsApp')
        ->assertSee('Buka Link WhatsApp');
});

it('shows hasil ujian as the fifth step on the registration status page when user is belum kompeten', function () {
    $user = User::factory()->create();
    $scheme = createScheme(['nama' => 'Junior Web Developer']);
    $registration = createRegistrationWithRelations($user, $scheme, [
        'status' => 'tidak_kompeten',
    ], [], [], [
        'exam_date' => now()->addDays(3)->setTime(8, 30),
        'exam_location' => 'Lab Sertifikasi Gedung B',
        'assessor_name' => 'Siti Rahma',
        'exam_result_path' => 'exam-results/failed.pdf',
    ]);

    $this->actingAs($user)
        ->get(route('dashboard.status', $registration))
        ->assertOk()
        ->assertSee('Hasil Ujian')
        ->assertSee('Belum Kompeten');
});

it('defaults to dokumen tab during document verification stage', function () {
    $user = User::factory()->create();
    $scheme = createScheme();
    $registration = createRegistrationWithRelations($user, $scheme, [
        'status' => 'menunggu_verifikasi',
    ]);

    Livewire::actingAs($user)
        ->test(UserRegistrationStatus::class, ['registration' => $registration])
        ->assertSet('activeTab', 'dokumen')
        ->assertSee('Dokumen')
        ->assertDontSee('Buka Link WhatsApp');
});

it('defaults to pembayaran tab during payment stage and allows manual tab switching', function () {
    $user = User::factory()->create();
    $scheme = createScheme();
    $registration = createRegistrationWithRelations($user, $scheme, [
        'status' => 'pending_payment',
        'payment_reference' => 'PAY-777',
    ]);

    Livewire::actingAs($user)
        ->test(UserRegistrationStatus::class, ['registration' => $registration])
        ->assertSet('activeTab', 'pembayaran')
        ->call('setActiveTab', 'dokumen')
        ->assertSet('activeTab', 'dokumen')
        ->call('setActiveTab', 'pembayaran')
        ->assertSet('activeTab', 'pembayaran');
});

it('defaults to jadwal tab when exam schedule is published and allows manual tab switching', function () {
    $user = User::factory()->create();
    $scheme = createScheme();
    $registration = createRegistrationWithRelations($user, $scheme, [
        'status' => 'terjadwal',
    ], [], [], [
        'exam_date' => now()->addDays(3)->setTime(8, 30),
        'exam_location' => 'Lab Sertifikasi',
        'assessor_name' => 'Asesor Uji',
    ]);

    Livewire::actingAs($user)
        ->test(UserRegistrationStatus::class, ['registration' => $registration])
        ->assertSet('activeTab', 'jadwal')
        ->call('setActiveTab', 'biodata')
        ->assertSet('activeTab', 'biodata')
        ->call('setActiveTab', 'jadwal')
        ->assertSet('activeTab', 'jadwal');
});

it('shows empty state when user opens status page without any registration', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('dashboard.status'))
        ->assertOk()
        ->assertSee('Belum Ada Pendaftaran')
        ->assertSee('Daftar Skema');
});

it('allows users to reupload rejected documents', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $scheme = createScheme();
    $registration = createRegistrationWithRelations($user, $scheme, [
        'status' => 'dokumen_ditolak',
    ], [], [
        'khs_path' => [
            'status' => 'rejected',
            'note' => 'Dokumen KHS buram.',
        ],
    ]);

    $file = UploadedFile::fake()->create('khs.pdf', 200, 'application/pdf');

    Livewire::actingAs($user)
        ->test(UserRegistrationStatus::class, ['registration' => $registration])
        ->set('reuploadFiles.khs_path', $file)
        ->call('reuploadDocument', 'khs_path')
        ->assertSet('successMessage', 'Dokumen berhasil diupload ulang dan sedang menunggu verifikasi admin.');

    $registration->refresh();

    expect($registration->status)->toBe('menunggu_verifikasi');
    expect($registration->khs_path)->not->toBeNull();
    expect($registration->document_statuses['khs_path']['status'])->toBe('pending');

    Storage::disk('public')->assertExists($registration->khs_path);
});

it('allows users to upload the optional internship certificate from the registration status page', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $scheme = createScheme();
    $registration = createRegistrationWithRelations($user, $scheme, [
        'status' => 'menunggu_verifikasi',
    ]);

    $file = UploadedFile::fake()->create('internship.pdf', 200, 'application/pdf');

    Livewire::actingAs($user)
        ->test(UserRegistrationStatus::class, ['registration' => $registration])
        ->set('reuploadFiles.internship_certificate_path', $file)
        ->call('reuploadDocument', 'internship_certificate_path')
        ->assertSet('successMessage', 'Dokumen berhasil diupload ulang dan sedang menunggu verifikasi admin.');

    $registration->refresh();

    expect($registration->internship_certificate_path)->not->toBeNull()
        ->and($registration->document_statuses['internship_certificate_path']['status'])->toBe('pending');

    Storage::disk('public')->assertExists($registration->internship_certificate_path);
});

it('allows users to upload payment proof from the registration status page', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $scheme = createScheme();
    $registration = createRegistrationWithRelations($user, $scheme, [
        'status' => 'dokumen_ok',
        'payment_reference' => 'PAY-009',
    ]);

    $file = UploadedFile::fake()->create('payment-proof.pdf', 200, 'application/pdf');

    Livewire::actingAs($user)
        ->test(UserRegistrationStatus::class, ['registration' => $registration])
        ->set('paymentProof', $file)
        ->call('uploadPaymentProof')
        ->assertSet('successMessage', 'Bukti pembayaran berhasil diupload dan sedang menunggu validasi admin.');

    $registration->refresh();

    expect($registration->status)->toBe('pending_payment')
        ->and($registration->payment_proof_path)->not->toBeNull()
        ->and($registration->document_statuses['payment_proof_path']['status'])->toBe('pending');

    Storage::disk('public')->assertExists($registration->payment_proof_path);
});

it('allows biodata updates from the registration status page when a document is rejected', function () {
    $user = createGeneralUser([
        'nama' => 'Peserta Lama',
        'email' => 'lama@example.com',
    ]);
    $scheme = createScheme();
    $registration = createRegistrationWithRelations($user, $scheme, [
        'status' => 'dokumen_ditolak',
    ], [], [
        'fr_apl_01_path' => [
            'status' => 'rejected',
            'note' => 'Nama pada formulir perlu diperbarui.',
        ],
    ]);

    Livewire::actingAs($user)
        ->test(UserRegistrationStatus::class, ['registration' => $registration])
        ->call('startEditingBiodata')
        ->assertSet('isEditingBiodata', true)
        ->set('profile.nama', 'Peserta Baru')
        ->set('profile.email', 'baru@example.com')
        ->set('profile.no_ktp', '3174000000000001')
        ->set('profile.tempat_lahir', 'Jakarta')
        ->set('profile.tanggal_lahir', '1998-04-10')
        ->set('profile.jenis_kelamin', 'L')
        ->set('profile.alamat_rumah', 'Jl. Contoh No. 1')
        ->set('profile.domisili_provinsi', 'DKI Jakarta')
        ->set('profile.domisili_kota', 'Jakarta Selatan')
        ->set('profile.domisili_kecamatan', 'Setiabudi')
        ->set('profile.no_wa', '081234567890')
        ->set('profile.pendidikan_terakhir', 'S1')
        ->set('profile.nama_institusi', 'Universitas Contoh')
        ->set('profile.pekerjaan', 'Karyawan Swasta')
        ->call('saveBiodata')
        ->assertSet('isEditingBiodata', false)
        ->assertSet('successMessage', 'Biodata berhasil diperbarui. Silakan lanjutkan perbaikan dokumen yang ditolak.');

    expect($user->refresh()->name)->toBe('Peserta Baru')
        ->and($user->email)->toBe('baru@example.com')
        ->and($user->no_ktp)->toBe('3174000000000001');
});

it('shows a biodata edit notice on the dokumen tab when documents are rejected', function () {
    $user = createGeneralUser();
    $scheme = createScheme();
    $registration = createRegistrationWithRelations($user, $scheme, [
        'status' => 'dokumen_ditolak',
    ], [], [
        'fr_apl_01_path' => [
            'status' => 'rejected',
            'note' => 'Perlu revisi biodata.',
        ],
    ]);

    $this->actingAs($user)
        ->get(route('dashboard.status', $registration))
        ->assertOk()
        ->assertSee('Biodata')
        ->assertSee('Bisa Edit')
        ->assertSee('Biodata masih bisa diperbarui')
        ->assertSee('Buka Biodata');
});

it('shows supporting documents as non-review items in the condensed registration status flow', function () {
    $user = User::factory()->create();
    $scheme = createScheme();
    $registration = createRegistrationWithRelations($user, $scheme, [
        'status' => 'menunggu_verifikasi',
    ], [
        'fr_apl_01_path' => 'documents/fr_apl_01/test.pdf',
        'fr_apl_02_path' => 'documents/fr_apl_02/test.pdf',
        'ktm_path' => 'documents/ktm/test.pdf',
        'khs_path' => 'documents/khs/test.pdf',
        'ktp_path' => 'documents/ktp/test.pdf',
        'passport_photo_path' => 'documents/photo/test.jpg',
    ], [
        '_meta_condensed_flow' => [
            'status' => 'meta',
        ],
    ]);

    Livewire::actingAs($user)
        ->test(UserRegistrationStatus::class, ['registration' => $registration])
        ->assertSee('2 dokumen review + 5 dokumen pendukung')
        ->assertSee('Dokumen Pendukung')
        ->assertSee('Dokumen pendukung tetap ditampilkan, tetapi tidak menunggu review admin.');
});

it('shows only general-user biodata fields on the registration status page for general users', function () {
    $user = createGeneralUser(
        ['nama' => 'Asa', 'email' => 'asa@gmail.com'],
        [
            'tempat_lahir' => 'asa',
            'tanggal_lahir' => '2026-04-03',
            'jenis_kelamin' => 'L',
            'alamat_rumah' => 'asa',
            'domisili_provinsi' => 'asa',
            'domisili_kota' => 'asa',
            'domisili_kecamatan' => 'asa',
            'no_wa' => '08112933',
            'fakultas' => 'Ilmu Komputer',
            'program_studi' => 'Sistem Informasi',
        ],
        [
            'no_ktp' => '3273056010900009',
            'pendidikan_terakhir' => 'asa',
            'nama_institusi' => 'Institut Contoh',
            'nama_pekerjaan' => 'asa',
            'nama_perusahaan' => null,
            'jabatan' => null,
            'alamat_perusahaan' => null,
            'kode_pos_perusahaan' => null,
            'no_telp_perusahaan' => null,
            'email_perusahaan' => null,
        ],
        true,
    );
    $scheme = createScheme();
    $registration = createRegistrationWithRelations($user, $scheme, [
        'status' => 'menunggu_verifikasi',
    ]);

    Livewire::actingAs($user)
        ->test(UserRegistrationStatus::class, ['registration' => $registration])
        ->call('setActiveTab', 'biodata')
        ->assertSee('NIK')
        ->assertSee('Nama Institusi')
        ->assertSee('Pekerjaan')
        ->assertSee('Nama Perusahaan')
        ->assertSee('Jabatan')
        ->assertSee('Telepon Perusahaan')
        ->assertDontSee('NIM')
        ->assertDontSee('Fakultas')
        ->assertDontSee('Program Studi')
        ->assertDontSee('Total SKS')
        ->assertDontSee('Status Semester');
});

it('shows only mahasiswa biodata fields on the registration status page for mahasiswa users', function () {
    $user = createMahasiswaUser();
    $scheme = createScheme();
    $registration = createRegistrationWithRelations($user, $scheme, [
        'status' => 'menunggu_verifikasi',
    ]);

    Livewire::actingAs($user)
        ->test(UserRegistrationStatus::class, ['registration' => $registration])
        ->call('setActiveTab', 'biodata')
        ->assertSee('NIM')
        ->assertSee('Fakultas')
        ->assertSee('Program Studi')
        ->assertSee('Total SKS')
        ->assertSee('Status Semester')
        ->assertDontSee('Nama Institusi')
        ->assertDontSee('Pekerjaan')
        ->assertDontSee('Nama Perusahaan')
        ->assertDontSee('Jabatan')
        ->assertDontSee('Email Perusahaan');
});

it('renders the certificates page with the active certificate and table', function () {
    $user = User::factory()->create();
    $scheme = createScheme(['nama' => 'Junior Web Developer']);

    createCertificateForUser($user, $scheme, [
        'level' => 'KKNI Level 6',
        'status' => 'active',
        'file_path' => 'certificates/jwd.pdf',
        'result_file_path' => 'exam-results/jwd-result.pdf',
        'expired_date' => now()->addMonths(9),
    ]);

    $this->actingAs($user)
        ->get(route('dashboard.certificates'))
        ->assertOk()
        ->assertSee('Sertifikat')
        ->assertSee('Junior Web Developer')
        ->assertSee('Semua Sertifikat')
        ->assertSee('Unduh Sertifikat')
        ->assertSee('Unduh Hasil Ujian');
});

it('filters only popular schemes on the user schemes page', function () {
    $user = User::factory()->create();

    $popularScheme = createScheme([
        'nama' => 'Skema Populer Dashboard',
        'is_active' => true,
        'is_popular' => true,
    ]);

    $regularScheme = createScheme([
        'nama' => 'Skema Biasa Dashboard',
        'is_active' => true,
        'is_popular' => false,
    ]);

    $this->actingAs($user);

    Livewire::test(UserSchemesPage::class)
        ->set('filterPopularity', 'populer')
        ->assertSee($popularScheme->name)
        ->assertDontSee($regularScheme->name)
        ->assertSee('Populer');
});
