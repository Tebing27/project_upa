<?php

use App\Livewire\Admin\DetailDokumen;
use App\Livewire\Admin\JadwalUji;
use App\Livewire\Admin\UploadHasilUji;
use App\Livewire\Admin\VerifikasiDokumen;
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

it('redirects document-approved participants to the schedule page without changing their status', function () {
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
        ->assertRedirect(route('admin.jadwal', ['highlight' => $registration->id], absolute: false));

    $registration->refresh();

    expect($registration->status)->toBe('dokumen_ok');
});

it('can create a schedule for a document-approved participant', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $scheme = Scheme::factory()->create(['name' => 'Junior Web Developer']);
    $participant = User::factory()->create();
    $registration = Registration::factory()->create([
        'user_id' => $participant->id,
        'scheme_id' => $scheme->id,
        'status' => 'dokumen_ok',
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

it('can filter schedule participants by search keyword and exam date', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $scheme = Scheme::factory()->create(['name' => 'Junior Web Developer']);

    $readyUser = User::factory()->create(['name' => 'Bagas Siap Jadwal', 'nim' => '123450001']);
    $matchingScheduledUser = User::factory()->create(['name' => 'Bagas Terjadwal', 'nim' => '123450002']);
    $otherScheduledUser = User::factory()->create(['name' => 'Sinta Terjadwal', 'nim' => '123450003']);

    Registration::factory()->create([
        'user_id' => $readyUser->id,
        'scheme_id' => $scheme->id,
        'status' => 'dokumen_ok',
    ]);

    Registration::factory()->create([
        'user_id' => $matchingScheduledUser->id,
        'scheme_id' => $scheme->id,
        'status' => 'terjadwal',
        'exam_date' => Carbon::parse('2026-04-10 09:00:00'),
    ]);

    Registration::factory()->create([
        'user_id' => $otherScheduledUser->id,
        'scheme_id' => $scheme->id,
        'status' => 'terjadwal',
        'exam_date' => Carbon::parse('2026-04-11 09:00:00'),
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
        'status' => 'dokumen_ok',
    ]);

    Registration::factory()->create([
        'user_id' => $scheduledUser->id,
        'scheme_id' => $scheme->id,
        'status' => 'terjadwal',
        'exam_date' => Carbon::parse('2026-04-10 09:00:00'),
        'exam_location' => 'Lab Sertifikasi',
        'assessor_name' => 'Asesor Uji',
    ]);

    Livewire::actingAs($admin)
        ->test(JadwalUji::class)
        ->assertSee('Peserta Jadwal Uji')
        ->assertDontSee('Peserta Siap Dijadwalkan')
        ->assertDontSee('Peserta Terjadwal')
        ->assertDontSee('Upload Sertifikat & Hasil')
        ->assertSee('Raka Belum Dijadwalkan')
        ->assertSee('Dina Sudah Terjadwal')
        ->assertSee('Jadwalkan')
        ->assertSee('Edit')
        ->assertSee('Hapus');
});

it('can delete a schedule and move the participant back to document approved', function () {
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

    expect($registration->status)->toBe('dokumen_ok')
        ->and($registration->exam_date)->toBeNull()
        ->and($registration->exam_location)->toBeNull()
        ->and($registration->assessor_name)->toBeNull();
});

it('can upload certificate and exam result files for a scheduled participant', function () {
    Storage::fake('public');

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

    $oldCertificate = Certificate::factory()->create([
        'user_id' => $participant->id,
        'scheme_name' => 'Junior Web Developer',
        'status' => 'active',
        'file_path' => 'certificates/old-jwd.pdf',
    ]);

    $certificateFile = UploadedFile::fake()->create('sertifikat.pdf', 200, 'application/pdf');
    $resultFile = UploadedFile::fake()->create('hasil-ujian.pdf', 200, 'application/pdf');

    Livewire::actingAs($admin)
        ->test(UploadHasilUji::class)
        ->call('openUploadModal', $registration->id)
        ->set('certificateFile', $certificateFile)
        ->set('resultFile', $resultFile)
        ->call('uploadParticipantFiles')
        ->assertDispatched('close-modal')
        ->assertDispatched('toast');

    $registration->refresh();
    $oldCertificate->refresh();

    $newCertificate = Certificate::query()
        ->where('user_id', $participant->id)
        ->where('scheme_name', 'Junior Web Developer')
        ->latest('id')
        ->first();

    expect($registration->status)->toBe('sertifikat_terbit')
        ->and($registration->score)->toBeNull()
        ->and($oldCertificate->status)->toBe('inactive')
        ->and($newCertificate)->not->toBeNull()
        ->and($newCertificate->status)->toBe('active')
        ->and($newCertificate->file_path)->not->toBeNull()
        ->and($newCertificate->result_file_path)->not->toBeNull();

    Storage::disk('public')->assertExists($newCertificate->file_path);
    Storage::disk('public')->assertExists($newCertificate->result_file_path);
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
        'scheme_name' => 'Junior Web Developer',
        'status' => 'active',
        'file_path' => 'certificates/jwd.pdf',
        'result_file_path' => 'exam-results/jwd-result.pdf',
    ]);

    Livewire::actingAs($admin)
        ->test(UploadHasilUji::class)
        ->assertSee('Peserta Upload Tetap Tampil')
        ->assertSee('Sudah upload')
        ->assertSee('Lihat Sertifikat')
        ->assertSee('Lihat Hasil Ujian')
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
        ->assertSee(route('admin.verifikasi.detail', $registration), false);
});
