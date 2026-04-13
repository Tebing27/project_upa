<?php

use App\Livewire\Admin\DetailDokumen;
use App\Livewire\Admin\DetailPembayaran;
use App\Livewire\Admin\JadwalUji;
use App\Livewire\Admin\UploadHasilUji;
use App\Livewire\Admin\VerifikasiDokumen;
use App\Livewire\Admin\VerifikasiPembayaran;
use App\Models\AppSetting;
use App\Models\Certificate;
use App\Models\Registration;
use App\Models\Scheme;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

it('opens the reject modal and stores the rejection note for a document', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $scheme = Scheme::factory()->create(['name' => 'Junior Web Developer']);
    $participant = User::factory()->create();
    $registration = Registration::factory()->create([
        'user_id' => $participant->id,
        'scheme_id' => $scheme->id,
        'status' => 'menunggu_verifikasi',
        'khs_path' => 'documents/khs/khs.pdf',
    ]);

    Livewire::actingAs($admin)
        ->test(DetailDokumen::class, ['registration' => $registration])
        ->call('bukaModalTolak', 'khs_path')
        ->assertSet('rejectDocType', 'khs_path')
        ->assertDispatched('open-modal')
        ->set('rejectNote', 'Dokumen buram dan perlu diunggah ulang.')
        ->call('tolakDokumen')
        ->assertDispatched('close-modal');

    $registration->refresh();

    expect($registration->status)->toBe('dokumen_ditolak')
        ->and($registration->document_statuses['khs_path']['status'])->toBe('rejected')
        ->and($registration->document_statuses['khs_path']['note'])->toBe('Dokumen buram dan perlu diunggah ulang.');
});

it('shows document-rejected registrations in the rejected verification tab', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $scheme = Scheme::factory()->create(['name' => 'Junior Web Developer']);
    $rejectedUser = User::factory()->create(['name' => 'Mahasiswa Ditolak']);
    $otherUser = User::factory()->create(['name' => 'Mahasiswa Lain']);

    Registration::factory()->create([
        'user_id' => $rejectedUser->id,
        'scheme_id' => $scheme->id,
        'status' => 'dokumen_ditolak',
    ]);

    Registration::factory()->create([
        'user_id' => $otherUser->id,
        'scheme_id' => $scheme->id,
        'status' => 'dokumen_ok',
    ]);

    Livewire::actingAs($admin)
        ->test(VerifikasiDokumen::class)
        ->set('tab', 'ditolak')
        ->assertSee('Mahasiswa Ditolak')
        ->assertDontSee('Mahasiswa Lain');
});

it('shows registrations with rejected document statuses in the rejected verification tab even when status is not synced', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $scheme = Scheme::factory()->create(['name' => 'Junior Web Developer']);
    $rejectedUser = User::factory()->create(['name' => 'Mahasiswa Status Belum Sinkron']);

    Registration::factory()->create([
        'user_id' => $rejectedUser->id,
        'scheme_id' => $scheme->id,
        'status' => 'menunggu_verifikasi',
        'document_statuses' => [
            'khs_path' => [
                'status' => 'rejected',
                'note' => 'KHS belum jelas.',
            ],
        ],
    ]);

    Livewire::actingAs($admin)
        ->test(VerifikasiDokumen::class)
        ->set('tab', 'ditolak')
        ->assertSee('Mahasiswa Status Belum Sinkron');
});

it('redirects document-approved participants to the payment verification page without changing their status', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $scheme = Scheme::factory()->create(['name' => 'Junior Web Developer']);
    $participant = User::factory()->create();
    $registration = Registration::factory()->create([
        'user_id' => $participant->id,
        'scheme_id' => $scheme->id,
        'status' => 'dokumen_ok',
    ]);

    Livewire::actingAs($admin)
        ->test(DetailDokumen::class, ['registration' => $registration])
        ->call('lanjutkanKeJadwal')
        ->assertRedirect(route('admin.payment', ['highlight' => $registration->id], absolute: false));

    $registration->refresh();

    expect($registration->status)->toBe('dokumen_ok');
});

it('can continue to the payment verification page after a rejected document is later verified', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $scheme = Scheme::factory()->create(['name' => 'Junior Web Developer']);
    $participant = User::factory()->create();
    $registration = Registration::factory()->create([
        'user_id' => $participant->id,
        'scheme_id' => $scheme->id,
        'status' => 'menunggu_verifikasi',
        'document_statuses' => [
            '_meta_condensed_flow' => ['status' => 'meta'],
            'fr_apl_01_path' => ['status' => 'rejected', 'note' => 'Perbaiki formulir.'],
            'fr_apl_02_path' => ['status' => 'verified'],
        ],
        'fr_apl_01_path' => 'documents/fr_apl_01/form-1.pdf',
        'fr_apl_02_path' => 'documents/fr_apl_02/form-2.pdf',
    ]);

    Livewire::actingAs($admin)
        ->test(DetailDokumen::class, ['registration' => $registration])
        ->call('verifikasiDokumen', 'fr_apl_01_path')
        ->assertSee('Lanjut ke Pembayaran')
        ->call('lanjutkanKeJadwal')
        ->assertRedirect(route('admin.payment', ['highlight' => $registration->id], absolute: false));

    expect($registration->refresh()->status)->toBe('dokumen_ok');
});

it('shows the Data Pribadi modal button and correct user data in DetailDokumen', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $scheme = Scheme::factory()->create(['name' => 'Junior Web Developer']);
    $participant = User::factory()->create([
        'name' => 'Data Pribadi Peserta',
        'nim' => '12345678',
    ]);
    $registration = Registration::factory()->create([
        'user_id' => $participant->id,
        'scheme_id' => $scheme->id,
        'status' => 'menunggu_verifikasi',
    ]);

    Livewire::actingAs($admin)
        ->test(DetailDokumen::class, ['registration' => $registration])
        ->assertSee('Data Pribadi')
        ->assertSee('Data Pribadi Peserta')
        ->assertSee('12345678');
});

it('shows nik instead of generated nim for general users across admin verification pages', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $scheme = Scheme::factory()->create(['name' => 'Skema Sertifikasi Programmer']);
    $participant = User::factory()->completedGeneralProfile()->create([
        'name' => 'Peserta Umum Admin',
        'nim' => 'NON-CKDRDMGC5S',
        'no_ktp' => '3273056010900009',
        'email' => 'umum@example.com',
        'program_studi' => 'Sistem Informasi',
    ]);

    $documentRegistration = Registration::factory()->create([
        'user_id' => $participant->id,
        'scheme_id' => $scheme->id,
        'status' => 'menunggu_verifikasi',
        'payment_reference' => '98109000009015043',
    ]);

    $paymentRegistration = Registration::factory()->create([
        'user_id' => $participant->id,
        'scheme_id' => $scheme->id,
        'status' => 'paid',
        'payment_reference' => '98109000009015044',
        'payment_submitted_at' => now(),
    ]);

    $scheduledRegistration = Registration::factory()->create([
        'user_id' => $participant->id,
        'scheme_id' => $scheme->id,
        'status' => 'terjadwal',
        'payment_reference' => '98109000009015045',
        'exam_date' => Carbon::parse('2026-04-10 09:00:00'),
        'exam_location' => 'Lab Sertifikasi Gedung A',
        'assessor_name' => 'Budi Santoso',
    ]);

    Livewire::actingAs($admin)
        ->test(DetailDokumen::class, ['registration' => $documentRegistration])
        ->assertSee('NIK / No. Pendaftaran')
        ->assertSee('3273056010900009')
        ->assertSee('Provinsi Domisili')
        ->assertSee('Kota Domisili')
        ->assertSee('Kecamatan Domisili')
        ->assertSee('Nama Perusahaan')
        ->assertSee('Email Perusahaan')
        ->assertDontSee('SKS / Semester')
        ->assertDontSee('NON-CKDRDMGC5S');

    Livewire::actingAs($admin)
        ->test(VerifikasiDokumen::class)
        ->assertSee('NIK / No. Pendaftaran')
        ->assertSee('3273056010900009')
        ->assertDontSee('NON-CKDRDMGC5S');

    Livewire::actingAs($admin)
        ->test(DetailPembayaran::class, ['registration' => $paymentRegistration])
        ->assertSee('NIK / Email')
        ->assertSee('3273056010900009 / umum@example.com')
        ->assertDontSee('NON-CKDRDMGC5S');

    Livewire::actingAs($admin)
        ->test(VerifikasiPembayaran::class)
        ->set('tab', 'terverifikasi')
        ->assertSee('3273056010900009')
        ->assertDontSee('NON-CKDRDMGC5S');

    Livewire::actingAs($admin)
        ->test(JadwalUji::class)
        ->assertSee('Peserta Umum Admin')
        ->assertSee('3273056010900009')
        ->assertDontSee('NON-CKDRDMGC5S');

    Livewire::actingAs($admin)
        ->test(UploadHasilUji::class)
        ->assertSee('Peserta Umum Admin')
        ->assertSee('3273056010900009')
        ->assertDontSee('NON-CKDRDMGC5S');
});

it('can create a schedule for a paid participant', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $scheme = Scheme::factory()->create(['name' => 'Junior Web Developer']);
    $participant = User::factory()->create();
    $registration = Registration::factory()->create([
        'user_id' => $participant->id,
        'scheme_id' => $scheme->id,
        'status' => 'paid',
    ]);

    Livewire::actingAs($admin)
        ->test(JadwalUji::class)
        ->call('openScheduleModal', $registration->id)
        ->set('examDate', '2026-04-10')
        ->set('examTime', '09:00')
        ->set('examLocation', 'Lab Sertifikasi Gedung A')
        ->set('assessorName', 'Budi Santoso')
        ->call('saveSchedule')
        ->assertDispatched('close-modal')
        ->assertDispatched('toast');

    $registration->refresh();

    expect($registration->status)->toBe('terjadwal')
        ->and($registration->exam_location)->toBe('Lab Sertifikasi Gedung A')
        ->and($registration->assessor_name)->toBe('Budi Santoso')
        ->and($registration->exam_date?->format('Y-m-d H:i:s'))->toBe(Carbon::parse('2026-04-10 09:00:00')->format('Y-m-d H:i:s'));
});

it('can update an existing schedule for a participant', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $scheme = Scheme::factory()->create(['name' => 'Junior Web Developer']);
    $participant = User::factory()->create();
    $registration = Registration::factory()->create([
        'user_id' => $participant->id,
        'scheme_id' => $scheme->id,
        'status' => 'terjadwal',
        'exam_date' => Carbon::parse('2026-04-10 09:00:00'),
        'exam_location' => 'Lab Lama',
        'assessor_name' => 'Asesor Lama',
    ]);

    AppSetting::put('whatsapp_channel_link', 'https://chat.whatsapp.com/lama');

    Livewire::actingAs($admin)
        ->test(JadwalUji::class)
        ->call('openScheduleModal', $registration->id)
        ->set('examDate', '2026-04-12')
        ->set('examTime', '13:30')
        ->set('examLocation', 'Lab Baru')
        ->set('assessorName', 'Asesor Baru')
        ->call('saveSchedule');

    $registration->refresh();

    expect($registration->status)->toBe('terjadwal')
        ->and($registration->exam_location)->toBe('Lab Baru')
        ->and($registration->assessor_name)->toBe('Asesor Baru')
        ->and($registration->exam_date?->format('Y-m-d H:i:s'))->toBe(Carbon::parse('2026-04-12 13:30:00')->format('Y-m-d H:i:s'));
});

it('can create update and delete the global whatsapp link from the separate crud section', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $component = Livewire::actingAs($admin)
        ->test(JadwalUji::class)
        ->call('openWhatsappLinkModal')
        ->assertDispatched('open-modal', id: 'modal-whatsapp-link')
        ->set('whatsappLink', 'https://chat.whatsapp.com/global-upa')
        ->call('saveWhatsappLink')
        ->assertDispatched('close-modal', id: 'modal-whatsapp-link')
        ->assertDispatched('toast')
        ->assertSee('Link WhatsApp Global')
        ->assertSee('Link WhatsApp Universal');

    $settingId = AppSetting::query()
        ->where('key', 'whatsapp_channel_link')
        ->value('id');

    expect(AppSetting::whatsappChannelLink())->toBe('https://chat.whatsapp.com/global-upa');

    $component
        ->call('openWhatsappLinkModal', $settingId)
        ->assertDispatched('open-modal', id: 'modal-whatsapp-link')
        ->assertSet('editingWhatsappSettingId', $settingId)
        ->assertSet('whatsappLink', 'https://chat.whatsapp.com/global-upa')
        ->set('whatsappLink', 'https://chat.whatsapp.com/global-baru')
        ->call('saveWhatsappLink')
        ->assertDispatched('close-modal', id: 'modal-whatsapp-link')
        ->assertDispatched('toast');

    expect(AppSetting::whatsappChannelLink())->toBe('https://chat.whatsapp.com/global-baru');

    $component
        ->call('deleteWhatsappLink', $settingId)
        ->assertDispatched('toast')
        ->assertDontSee('Link WhatsApp Universal');

    expect(AppSetting::whatsappChannelLink())->toBeNull();
});

it('can filter schedule participants by search keyword and exam date', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $scheme = Scheme::factory()->create(['name' => 'Junior Web Developer']);

    $readyUser = User::factory()->create(['name' => 'Bagas Siap Jadwal', 'nim' => '123450001']);
    $matchingScheduledUser = User::factory()->create(['name' => 'Bagas Terjadwal', 'nim' => '123450002']);
    $otherScheduledUser = User::factory()->create(['name' => 'Sinta Terjadwal', 'nim' => '123450003']);

    Registration::factory()->create([
        'user_id' => $readyUser->id,
        'scheme_id' => $scheme->id,
        'status' => 'paid',
    ]);

    Registration::factory()->create([
        'user_id' => $matchingScheduledUser->id,
        'scheme_id' => $scheme->id,
        'status' => 'terjadwal',
        'exam_date' => Carbon::parse('2026-04-10 09:00:00'),
        'exam_location' => 'Lab Bagas',
        'assessor_name' => 'Asesor Bagas',
    ]);

    Registration::factory()->create([
        'user_id' => $otherScheduledUser->id,
        'scheme_id' => $scheme->id,
        'status' => 'terjadwal',
        'exam_date' => Carbon::parse('2026-04-11 09:00:00'),
        'exam_location' => 'Lab Sinta',
        'assessor_name' => 'Asesor Sinta',
    ]);

    Livewire::actingAs($admin)
        ->test(JadwalUji::class)
        ->set('search', 'Bagas')
        ->set('filterDate', '2026-04-10')
        ->assertSee('Bagas Siap Jadwal')
        ->assertSee('Bagas Terjadwal')
        ->assertDontSee('Sinta Terjadwal');
});

it('shows ready and scheduled participants in a single schedule table with contextual actions', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $scheme = Scheme::factory()->create(['name' => 'Junior Web Developer']);

    $readyUser = User::factory()->create(['name' => 'Raka Belum Dijadwalkan']);
    $scheduledUser = User::factory()->create(['name' => 'Dina Sudah Terjadwal']);

    Registration::factory()->create([
        'user_id' => $readyUser->id,
        'scheme_id' => $scheme->id,
        'status' => 'paid',
    ]);

    Registration::factory()->create([
        'user_id' => $scheduledUser->id,
        'scheme_id' => $scheme->id,
        'status' => 'terjadwal',
        'exam_date' => Carbon::parse('2026-04-10 09:00:00'),
        'exam_location' => 'Lab Sertifikasi',
        'assessor_name' => 'Asesor Uji',
    ]);

    AppSetting::put('whatsapp_channel_link', 'https://chat.whatsapp.com/asesor-uji');

    Livewire::actingAs($admin)
        ->test(JadwalUji::class)
        ->assertSee('Jadwal Uji')
        ->assertSee('Link WhatsApp Global')
        ->assertSee('Kelola satu link WhatsApp universal')
        ->assertDontSee('Peserta Siap Dijadwalkan')
        ->assertDontSee('Peserta Terjadwal')
        ->assertDontSee('Upload Sertifikat & Hasil')
        ->assertSee('Raka Belum Dijadwalkan')
        ->assertSee('Dina Sudah Terjadwal')
        ->assertSee('Jadwalkan')
        ->assertSee('Edit')
        ->assertSee('Hapus');
});

it('can delete a schedule and move the participant back to paid', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $scheme = Scheme::factory()->create(['name' => 'Junior Web Developer']);
    $participant = User::factory()->create();
    $registration = Registration::factory()->create([
        'user_id' => $participant->id,
        'scheme_id' => $scheme->id,
        'status' => 'terjadwal',
        'exam_date' => Carbon::parse('2026-04-10 09:00:00'),
        'exam_location' => 'Lab Sertifikasi Gedung A',
        'assessor_name' => 'Budi Santoso',
    ]);

    Livewire::actingAs($admin)
        ->test(JadwalUji::class)
        ->call('confirmDelete', $registration->id)
        ->call('deleteSchedule')
        ->assertDispatched('close-modal')
        ->assertDispatched('toast');

    $registration->refresh();

    expect($registration->status)->toBe('paid')
        ->and($registration->exam_date)->toBeNull()
        ->and($registration->exam_location)->toBeNull()
        ->and($registration->assessor_name)->toBeNull();
});

it('can upload certificate and exam result files for a scheduled participant', function () {
    Storage::fake('public');

    $admin = User::factory()->create(['role' => 'admin']);
    $scheme = Scheme::factory()->create(['name' => 'Junior Web Developer']);
    $participant = createMahasiswaUser(mahasiswaProfile: ['nim' => '2210511881']);
    $registration = Registration::factory()->create([
        'user_id' => $participant->id,
        'scheme_id' => $scheme->id,
        'status' => 'terjadwal',
        'exam_date' => Carbon::parse('2026-04-10 09:00:00'),
        'exam_location' => 'Lab Sertifikasi Gedung A',
        'assessor_name' => 'Budi Santoso',
    ]);

    AppSetting::put('whatsapp_channel_link', 'https://chat.whatsapp.com/upload-hasil');

    $certificateFile = UploadedFile::fake()->create('sertifikat.pdf', 200, 'application/pdf');
    $resultFile = UploadedFile::fake()->create('hasil-ujian.pdf', 200, 'application/pdf');

    Livewire::actingAs($admin)
        ->test(UploadHasilUji::class)
        ->call('openUploadModal', $registration->id)
        ->set('certificateFile', $certificateFile)
        ->set('resultFile', $resultFile)
        ->set('expiredDate', '2029-04-10')
        ->call('uploadParticipantFiles')
        ->assertDispatched('close-modal')
        ->assertDispatched('toast');

    $registration->refresh();

    $certificate = Certificate::query()
        ->where('user_id', $participant->id)
        ->where('scheme_id', $scheme->id)
        ->latest('id')
        ->first();

    expect($registration->status)->toBe('sertifikat_terbit')
        ->and($certificate)->not->toBeNull()
        ->and($certificate->certificate_number)->toBe('CERT-'.$participant->nim)
        ->and($certificate->status)->toBe('active')
        ->and($certificate->file_path)->not->toBeNull()
        ->and($certificate->result_file_path)->not->toBeNull()
        ->and($certificate->expired_date->format('Y-m-d'))->toBe('2029-04-10');

    Storage::disk('public')->assertExists($certificate->file_path);
    Storage::disk('public')->assertExists($certificate->result_file_path);
});

it('generates certificate numbers with nik and 12 random digits for general users', function () {
    Storage::fake('public');

    $admin = User::factory()->create(['role' => 'admin']);
    $scheme = Scheme::factory()->create(['name' => 'Junior Web Developer']);
    $participant = User::factory()->completedGeneralProfile()->create([
        'name' => 'Peserta Umum',
        'no_ktp' => '3174000000000001',
    ]);

    $registration = Registration::factory()->create([
        'user_id' => $participant->id,
        'scheme_id' => $scheme->id,
        'status' => 'terjadwal',
        'exam_date' => Carbon::parse('2026-04-10 09:00:00'),
        'exam_location' => 'Lab Sertifikasi Gedung A',
        'assessor_name' => 'Budi Santoso',
    ]);

    AppSetting::put('whatsapp_channel_link', 'https://chat.whatsapp.com/user-umum');

    $certificateFile = UploadedFile::fake()->create('sertifikat.pdf', 200, 'application/pdf');
    $resultFile = UploadedFile::fake()->create('hasil-ujian.pdf', 200, 'application/pdf');

    Livewire::actingAs($admin)
        ->test(UploadHasilUji::class)
        ->call('openUploadModal', $registration->id)
        ->set('certificateFile', $certificateFile)
        ->set('resultFile', $resultFile)
        ->set('expiredDate', '2029-04-10')
        ->call('uploadParticipantFiles');

    $certificate = Certificate::query()
        ->where('user_id', $participant->id)
        ->latest('id')
        ->first();

    expect($certificate)->not->toBeNull()
        ->and($certificate->certificate_number)->toBe('CERT-000000000001');
});

it('can edit only the expired date without re-uploading files', function () {
    Storage::fake('public');

    $admin = User::factory()->create(['role' => 'admin']);
    $scheme = Scheme::factory()->create(['name' => 'Junior Web Developer']);
    $participant = User::factory()->create();
    $registration = Registration::factory()->create([
        'user_id' => $participant->id,
        'scheme_id' => $scheme->id,
        'status' => 'sertifikat_terbit',
        'exam_date' => Carbon::parse('2026-04-10 09:00:00'),
        'exam_location' => 'Lab Sertifikasi Gedung A',
        'assessor_name' => 'Budi Santoso',
    ]);

    Storage::disk('public')->put('certificates/jwd.pdf', 'certificate-content');
    Storage::disk('public')->put('exam-results/jwd-result.pdf', 'result-content');

    $certificate = Certificate::factory()->create([
        'user_id' => $participant->id,
        'scheme_id' => $scheme->id,
        'scheme_name' => 'Junior Web Developer',
        'status' => 'active',
        'expired_date' => '2029-04-10',
        'file_path' => 'certificates/jwd.pdf',
        'result_file_path' => 'exam-results/jwd-result.pdf',
    ]);

    Livewire::actingAs($admin)
        ->test(UploadHasilUji::class)
        ->call('openUploadModal', $registration->id)
        ->assertSet('expiredDate', '2029-04-10')
        ->set('expiredDate', '2030-06-15')
        ->call('uploadParticipantFiles')
        ->assertDispatched('close-modal')
        ->assertDispatched('toast');

    $certificate->refresh();

    expect($certificate->expired_date->format('Y-m-d'))->toBe('2030-06-15')
        ->and($certificate->file_path)->toBe('certificates/jwd.pdf')
        ->and($certificate->result_file_path)->toBe('exam-results/jwd-result.pdf');

    Storage::disk('public')->assertExists('certificates/jwd.pdf');
    Storage::disk('public')->assertExists('exam-results/jwd-result.pdf');
});

it('keeps uploaded participants visible on the upload page for future updates', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $scheme = Scheme::factory()->create(['name' => 'Junior Web Developer']);
    $participant = User::factory()->create(['name' => 'Peserta Upload Tetap Tampil']);

    $registration = Registration::factory()->create([
        'user_id' => $participant->id,
        'scheme_id' => $scheme->id,
        'status' => 'sertifikat_terbit',
        'exam_date' => Carbon::parse('2026-04-10 09:00:00'),
        'exam_location' => 'Lab Sertifikasi Gedung A',
        'assessor_name' => 'Budi Santoso',
    ]);

    Certificate::factory()->create([
        'user_id' => $participant->id,
        'scheme_id' => $scheme->id,
        'scheme_name' => 'Junior Web Developer',
        'status' => 'active',
        'file_path' => 'certificates/jwd.pdf',
        'result_file_path' => 'exam-results/jwd-result.pdf',
    ]);

    Livewire::actingAs($admin)
        ->test(UploadHasilUji::class)
        ->assertSee('Peserta Upload Tetap Tampil')
        ->assertSee('Kompeten')
        ->assertSee('Sertifikat PDF')
        ->assertSee('Hasil Ujian PDF')
        ->assertSee('Edit')
        ->assertSee('Hapus')
        ->assertDontSee('Upload Ulang')
        ->call('openUploadModal', $registration->id)
        ->assertSet('uploadRegistrationId', $registration->id);
});

it('can delete uploaded certificate files and return participant to scheduled status', function () {
    Storage::fake('public');

    $admin = User::factory()->create(['role' => 'admin']);
    $scheme = Scheme::factory()->create(['name' => 'Junior Web Developer']);
    $participant = User::factory()->create();
    $registration = Registration::factory()->create([
        'user_id' => $participant->id,
        'scheme_id' => $scheme->id,
        'status' => 'sertifikat_terbit',
        'exam_date' => Carbon::parse('2026-04-10 09:00:00'),
        'exam_location' => 'Lab Sertifikasi Gedung A',
        'assessor_name' => 'Budi Santoso',
    ]);

    $certificate = Certificate::factory()->create([
        'user_id' => $participant->id,
        'scheme_id' => $scheme->id,
        'scheme_name' => 'Junior Web Developer',
        'status' => 'active',
        'file_path' => 'certificates/jwd.pdf',
        'result_file_path' => 'exam-results/jwd-result.pdf',
    ]);

    Storage::disk('public')->put('certificates/jwd.pdf', 'certificate');
    Storage::disk('public')->put('exam-results/jwd-result.pdf', 'result');

    Livewire::actingAs($admin)
        ->test(UploadHasilUji::class)
        ->call('confirmDelete', $registration->id)
        ->call('deleteUploadedFiles')
        ->assertDispatched('close-modal')
        ->assertDispatched('toast');

    $registration->refresh();

    expect($registration->status)->toBe('terjadwal')
        ->and(Certificate::query()->find($certificate->id))->toBeNull();

    Storage::disk('public')->assertMissing('certificates/jwd.pdf');
    Storage::disk('public')->assertMissing('exam-results/jwd-result.pdf');
});

it('shows only admin navigation on the admin dashboard', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Admin Panel')
        ->assertSee('Dashboard Admin')
        ->assertDontSee('Platform')
        ->assertDontSee('Status Pendaftaran')
        ->assertDontSee('Sertifikat Saya');
});

it('links recent admin requests to the document review page', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $registration = Registration::factory()->create();

    $this->actingAs($admin)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee(route('admin.payment.detail', $registration), false);
});

it('shows nik for general users in the recent admin requests dashboard card', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $participant = User::factory()->completedGeneralProfile()->create([
        'name' => 'Asa',
        'nim' => 'NON-CKDRDMGC5S',
        'no_ktp' => '3273056010900009',
    ]);
    $scheme = Scheme::factory()->create(['name' => 'Skema Sertifikasi Programmer']);

    Registration::factory()->create([
        'user_id' => $participant->id,
        'scheme_id' => $scheme->id,
        'status' => 'paid',
    ]);

    $this->actingAs($admin)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Asa')
        ->assertSee('3273056010900009')
        ->assertDontSee('NON-CKDRDMGC5S');
});
