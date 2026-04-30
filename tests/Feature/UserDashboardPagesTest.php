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
        'assessment_purpose' => 'sertifikasi',
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
        ->assertSee('Fotokopi Hasil Studi Semester 1 s/d Terbaru / Transkrip')
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

it('rejects payment proof files with unsupported extensions', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $scheme = createScheme();
    $registration = createRegistrationWithRelations($user, $scheme, [
        'status' => 'dokumen_ok',
        'payment_reference' => 'PAY-009',
    ]);

    $file = UploadedFile::fake()->create('payment-proof.txt', 200, 'application/pdf');

    Livewire::actingAs($user)
        ->test(UserRegistrationStatus::class, ['registration' => $registration])
        ->set('paymentProof', $file)
        ->call('uploadPaymentProof')
        ->assertHasErrors(['paymentProof']);

    expect($registration->refresh()->payment_proof_path)->toBeNull();
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
        'fr_apl_02_path' => [
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
        ->set('profile.kode_pos_rumah', '12950')
        ->set('profile.telp_rumah', '0211234567')
        ->set('profile.telp_kantor', '0217654321')
        ->set('profile.no_wa', '081234567890')
        ->set('profile.kualifikasi_pendidikan', 'S1')
        ->set('profile.nama_perusahaan', 'Universitas Contoh')
        ->set('profile.kode_pos_perusahaan', '12950')
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
        'fr_apl_02_path' => [
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

it('shows bagian 3 requirement documents on the registration status page', function () {
    $user = User::factory()->create();
    $scheme = createScheme();
    $registration = createRegistrationWithRelations($user, $scheme, [
        'status' => 'menunggu_verifikasi',
        'assessment_purpose' => 'sertifikasi',
    ], [
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
        ->assertSee('5 bukti kelengkapan pemohon')
        ->assertSee('Dokumen Pendaftaran')
        ->assertSee('Fotokopi Kartu Mahasiswa (KTM)')
        ->assertDontSee('FR APL 01')
        ->assertDontSee('Formulir APL-01');
});

it('shows the applicant signature on the tanda tangan tab before admin verification is complete', function () {
    $user = User::factory()->create();
    $scheme = createScheme();
    $registration = createRegistrationWithRelations($user, $scheme, [
        'status' => 'menunggu_verifikasi',
    ], [
        'applicant_signature_path' => 'documents/signatures/applicant-test.png',
    ]);

    Livewire::actingAs($user)
        ->test(UserRegistrationStatus::class, ['registration' => $registration])
        ->call('setActiveTab', 'tanda_tangan')
        ->assertSee('Tanda Tangan')
        ->assertSee('Pemohon')
        ->assertSee('Menunggu finalisasi admin')
        ->assertSee($user->name);
});

it('shows the admin signature on the tanda tangan tab after document verification is complete', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $scheme = createScheme();
    $registration = createRegistrationWithRelations($user, $scheme, [
        'status' => 'dokumen_ok',
        'admin_signatory_name' => 'Admin Sertifikasi',
    ], [
        'fr_apl_02_path' => 'documents/fr_apl_02/test.pdf',
        'applicant_signature_path' => 'documents/signatures/applicant-test.png',
        'admin_signature_path' => 'documents/signatures/admin-test.png',
    ], [
        '_meta_condensed_flow' => [
            'status' => 'meta',
        ],
        'fr_apl_02_path' => [
            'status' => 'verified',
        ],
    ]);

    $pixel = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO7Z0RcAAAAASUVORK5CYII=');

    Storage::disk('public')->put('documents/signatures/applicant-test.png', $pixel);
    Storage::disk('public')->put('documents/signatures/admin-test.png', $pixel);

    Livewire::actingAs($user)
        ->test(UserRegistrationStatus::class, ['registration' => $registration])
        ->call('setActiveTab', 'tanda_tangan')
        ->assertSee('Tanda tangan admin tersedia')
        ->assertSee('Tanda Tangan')
        ->assertSee('Verifikator')
        ->assertSee('Admin Sertifikasi')
        ->assertSee('Arsip Tanda')
        ->assertSee('Download PDF FR.APL.01');
});

it('allows users to download the verified FR.APL.01 pdf after admin signature is attached', function () {
    Storage::fake('public');

    $user = createGeneralUser([], [], [
        'kebangsaan' => 'Indonesia',
        'fax_perusahaan' => '021889977',
    ], true);

    $scheme = createScheme([
        'nama' => 'Customer Service',
        'kode_skema' => '08/SS/UN61/LSP-UPNVJ/2023',
    ]);

    $scheme->unitKompetensis()->createMany([
        [
            'kode_unit' => 'K.641266.005.01',
            'nama_unit' => 'Menangani Keluhan Nasabah',
            'order' => 1,
        ],
        [
            'kode_unit' => 'K.641266.006.01',
            'nama_unit' => 'Membuka Rekening',
            'order' => 2,
        ],
    ]);

    $scheme->persyaratanDasars()->createMany([
        ['deskripsi' => 'Fotocopy Kartu Tanda Mahasiswa Program Studi DIII Perbankan dan Keuangan', 'order' => 1],
        ['deskripsi' => 'Fotocopy Kartu Hasil Studi Semester 1 s/d semester 5', 'order' => 2],
        ['deskripsi' => 'Fotocopy Sertifikat Magang atau praktik kerja terkait jabatan Customer Service', 'order' => 3],
    ]);

    $scheme->persyaratanAdministrasis()->createMany([
        ['deskripsi' => 'Fotocopy KTP/KK sebanyak 2 lembar', 'order' => 1],
        ['deskripsi' => 'Pasfoto berwarna 3x4 background merah', 'order' => 2],
        ['deskripsi' => 'Dokumen FR.APL.01', 'order' => 3],
        ['deskripsi' => 'Dokumen FR.APL.02', 'order' => 4],
    ]);

    $registration = createRegistrationWithRelations($user, $scheme, [
        'status' => 'dokumen_ok',
        'assessment_purpose' => 'sertifikasi',
        'admin_signatory_name' => 'Admin Sertifikasi',
    ], [
        'fr_apl_02_path' => 'documents/fr_apl_02/test.pdf',
        'ktm_path' => 'documents/ktm/test.pdf',
        'khs_path' => 'documents/khs/test.pdf',
        'internship_certificate_path' => 'documents/internship/test.pdf',
        'ktp_path' => 'documents/ktp/test.pdf',
        'passport_photo_path' => 'documents/photo/test.jpg',
        'applicant_signature_path' => 'documents/signatures/applicant-test.png',
        'admin_signature_path' => 'documents/signatures/admin-test.png',
    ], [
        'fr_apl_02_path' => ['status' => 'verified', 'verified_at' => now()->subDay()],
        'ktm_path' => ['status' => 'verified', 'verified_at' => now()->subDay()],
        'khs_path' => ['status' => 'verified', 'verified_at' => now()->subDay()],
        'internship_certificate_path' => ['status' => 'verified', 'verified_at' => now()->subDay()],
        'ktp_path' => ['status' => 'verified', 'verified_at' => now()->subDay()],
        'passport_photo_path' => ['status' => 'verified', 'verified_at' => now()->subDay()],
    ]);

    $pixel = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO7Z0RcAAAAASUVORK5CYII=');

    Storage::disk('public')->put('documents/signatures/applicant-test.png', $pixel);
    Storage::disk('public')->put('documents/signatures/admin-test.png', $pixel);
    Storage::disk('public')->put('documents/photo/test.jpg', $pixel);

    $response = $this->actingAs($user)
        ->get(route('dashboard.status.apl01.download', $registration));

    $response
        ->assertOk()
        ->assertHeader('content-type', 'application/pdf')
        ->assertDownload('FR-APL-01-customer-service-'.$registration->id.'.pdf');
});

it('renders the FR.APL.01 blade with the plain four-page official form styling', function () {
    $user = createGeneralUser(['nama' => 'Asa'], completed: true);
    $scheme = createScheme(['nama' => 'Customer Service', 'kode_skema' => 'CS-001']);

    $registration = createRegistrationWithRelations($user, $scheme, [
        'status' => 'dokumen_ok',
        'assessment_purpose' => 'sertifikasi',
        'payment_reference' => 'REG-001',
    ]);

    $html = view('documents.apl-01-pdf', [
        'registration' => $registration->load(['user.profile', 'user.umumProfile', 'user.mahasiswaProfile', 'scheme.unitKompetensis']),
        'assessmentPurposeOptions' => [
            ['label' => 'Sertifikasi', 'checked' => true],
        ],
        'unitKompetensiRows' => collect(),
        'persyaratanDasarRows' => [
            ['label' => 'Persyaratan dasar', 'status' => 'verified'],
        ],
        'persyaratanAdministrasiRows' => [
            ['label' => 'Persyaratan administratif', 'status' => 'verified'],
        ],
        'applicantSignatureImage' => null,
        'adminSignatureImage' => null,
        'applicantSignedDate' => '21 April 2026',
        'adminSignedDate' => '21 April 2026',
    ])->render();

    expect($html)
        ->toContain('font-family: "Times New Roman", Times, serif;')
        ->toContain('font-size: 12px;')
        ->toContain('FR.APL.01. Permohonan Sertifikasi Kompetensi')
        ->toContain('Bagian 1 : Rincian Data Pemohon Sertifikasi')
        ->toContain('Bagian 2 : Data Sertifikasi')
        ->toContain('<td>Tujuan Asesmen</td>')
        ->toContain('Daftar Unit Kompetensi')
        ->toContain('3.2 Bukti Administratif')
        ->toContain('background: transparent;')
        ->toContain('border: 0;')
        ->not->toContain('background: #fffdf8;')
        ->not->toContain('rowspan="1">Tujuan Asesmen</td>')
        ->toContain('recommendation-table')
        ->not->toContain('document-header')
        ->not->toContain('No. Dokumen')
        ->not->toContain('No. Pendaftaran')
        ->not->toContain('class="passport-photo"');
});

it('prevents users from downloading the FR.APL.01 pdf before verification is complete', function () {
    $user = User::factory()->create();
    $scheme = createScheme();
    $registration = createRegistrationWithRelations($user, $scheme, [
        'status' => 'menunggu_verifikasi',
        'admin_signatory_name' => 'Admin Sertifikasi',
    ], [
        'applicant_signature_path' => 'documents/signatures/applicant-test.png',
        'admin_signature_path' => 'documents/signatures/admin-test.png',
    ], [
        'fr_apl_02_path' => ['status' => 'pending'],
    ]);

    $this->actingAs($user)
        ->get(route('dashboard.status.apl01.download', $registration))
        ->assertNotFound();
});

it('shows only general-user biodata fields on the registration status page for general users', function () {
    $user = createGeneralUser(
        ['nama' => 'Asa', 'email' => 'asa@gmail.com'],
        [
            'tempat_lahir' => 'asa',
            'tanggal_lahir' => '2026-04-03',
            'jenis_kelamin' => 'L',
            'alamat_rumah' => 'asa',
            'no_wa' => '08112933',
            'telp_rumah' => '0211234567',
            'telp_kantor' => '0217654321',
            'fakultas' => 'Ilmu Komputer',
            'program_studi' => 'Sistem Informasi',
        ],
        [
            'no_ktp' => '3273056010900009',
            'kualifikasi_pendidikan' => 'asa',
            'nama_perusahaan' => 'Institut Contoh',
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
        'assessment_purpose' => 'sertifikasi',
    ]);

    Livewire::actingAs($user)
        ->test(UserRegistrationStatus::class, ['registration' => $registration])
        ->call('setActiveTab', 'biodata')
        ->assertSee('Tujuan Asesmen')
        ->assertSee('Sertifikasi')
        ->assertSee('NIK')
        ->assertSee('Nama Institusi / Perusahaan')
        ->assertSee('Telepon Rumah')
        ->assertSee('Telepon Kantor')
        ->assertDontSee('Nama Perusahaan')
        ->assertSee('Jabatan')
        ->assertSee('Telepon Perusahaan')
        ->assertDontSee('NIM')
        ->assertDontSee('Fakultas')
        ->assertDontSee('Program Studi')
        ->assertDontSee('Total SKS')
        ->assertDontSee('Status Semester');
});

it('shows the shared general biodata fields on the registration status page for mahasiswa users', function () {
    $user = createMahasiswaUser();
    $scheme = createScheme();
    $registration = createRegistrationWithRelations($user, $scheme, [
        'status' => 'menunggu_verifikasi',
        'assessment_purpose' => 'rpl',
    ]);

    Livewire::actingAs($user)
        ->test(UserRegistrationStatus::class, ['registration' => $registration])
        ->call('setActiveTab', 'biodata')
        ->assertSee('Tujuan Asesmen')
        ->assertSee('Rekognisi Pembelajaran Lampau (RPL)')
        ->assertSee('NIK')
        ->assertSee('Nama Institusi / Perusahaan')
        ->assertSee('Telepon Rumah')
        ->assertSee('Telepon Kantor')
        ->assertSee('Jabatan')
        ->assertSee('Telepon Perusahaan')
        ->assertSee('NIM')
        ->assertDontSee('Fakultas')
        ->assertDontSee('Program Studi')
        ->assertDontSee('Total SKS')
        ->assertDontSee('Status Semester');
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
        ->assertSee('Unduh Surat Keterangan');
});

it('shows surat keterangan download while certificate copy is still waiting for admin upload', function () {
    Storage::fake('public');

    $user = User::factory()->create();
    $scheme = createScheme(['nama' => 'Junior Web Developer']);
    $registration = createRegistrationWithRelations($user, $scheme, [
        'status' => 'kompeten',
        'payment_reference' => 'PAY-LETTER-01',
    ], [], [], [
        'exam_result_path' => 'exam-results/kompeten.pdf',
    ]);

    Storage::disk('public')->put('documents/competency-letter/signature.png', 'signature');
    Storage::disk('public')->put('documents/competency-letter/stamp.png', 'stamp');

    AppSetting::put('competency_letter_signatory_name', 'Admin Sertifikasi');
    AppSetting::put('competency_letter_signature_path', 'documents/competency-letter/signature.png');
    AppSetting::put('competency_letter_stamp_path', 'documents/competency-letter/stamp.png');

    $this->actingAs($user)
        ->get(route('dashboard.certificates'))
        ->assertOk()
        ->assertSee('Status Kompeten Menunggu Sertifikat Copy')
        ->assertSee('Unduh Surat Keterangan')
        ->assertSee(Storage::url('exam-results/kompeten.pdf'), false)
        ->assertSee('Unduh Sertifikat')
        ->assertSee('Sertifikat belum tersedia');
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
