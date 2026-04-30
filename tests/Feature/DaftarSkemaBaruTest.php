<?php

use App\Livewire\DaftarSkemaBaru;
use App\Models\Certificate;
use App\Models\Faculty;
use App\Models\Registration;
use App\Models\Scheme;
use App\Models\StudyProgram;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

beforeEach(function () {
    Storage::fake('public');

    $faculty = Faculty::factory()->create([
        'name' => 'Fakultas Ilmu Komputer',
    ]);
    $studyProgram = StudyProgram::factory()->create([
        'faculty_id' => $faculty->id,
        'nama' => 'Informatika',
    ]);

    $this->user = createMahasiswaUser(
        profile: [
            'fakultas' => 'Fakultas Ilmu Komputer',
            'program_studi' => 'Informatika',
        ],
        mahasiswaProfile: [
            'nim' => '2210511042',
        ],
    );

    $this->scheme = createScheme([
        'nama' => 'Junior Web Developer',
        'faculty_id' => $faculty->id,
        'study_program_id' => $studyProgram->id,
        'is_active' => true,
    ]);

    $this->secondScheme = createScheme([
        'nama' => 'Junior Mobile Developer',
        'faculty_id' => $faculty->id,
        'study_program_id' => $studyProgram->id,
        'is_active' => true,
    ]);
});

function fakeDocuments(): array
{
    return [
        'frApl02' => UploadedFile::fake()->create('fr_apl_02.pdf', 100, 'application/pdf'),
        'ktm' => UploadedFile::fake()->image('ktm.jpg'),
        'khs' => UploadedFile::fake()->create('khs.pdf', 100, 'application/pdf'),
        'ktp' => UploadedFile::fake()->image('ktp.png'),
        'passportPhoto' => UploadedFile::fake()->image('photo.jpg'),
    ];
}

function fakeCondensedDocuments(): array
{
    return [
        'frApl02' => UploadedFile::fake()->create('fr_apl_02.pdf', 100, 'application/pdf'),
    ];
}

it('redirects guests to login', function () {
    $this->get(route('dashboard.daftar-skema'))
        ->assertRedirect('/login');
});

it('renders the page for authenticated users', function () {
    $this->actingAs($this->user)
        ->get(route('dashboard.daftar-skema'))
        ->assertOk()
        ->assertSeeLivewire(DaftarSkemaBaru::class);
});

it('composes the daftar skema page from blade fragments', function () {
    $view = file_get_contents(resource_path('views/livewire/daftar-skema-baru.blade.php'));

    expect($view)->toContain("@include('livewire.daftar-skema-baru._page-header')")
        ->toContain("@include('livewire.daftar-skema-baru._progress-stepper')")
        ->toContain("@include('livewire.daftar-skema-baru._step-1')")
        ->toContain("@include('livewire.daftar-skema-baru._step-2-apl-01')")
        ->toContain("@include('livewire.daftar-skema-baru._step-3-apl-02')")
        ->toContain("@include('livewire.daftar-skema-baru._step-4-review')")
        ->toContain("@include('livewire.daftar-skema-baru._step-5-success')");
});

it('prefills type and scheme from dashboard skema source without auto advancing', function () {
    $user = createGeneralUser();
    $faculty = Faculty::factory()->create(['name' => 'Fakultas Ekonomi']);
    $studyProgram = StudyProgram::factory()->create([
        'faculty_id' => $faculty->id,
        'nama' => 'Manajemen',
    ]);
    $otherScheme = createScheme([
        'nama' => 'Data Analyst',
        'faculty_id' => $faculty->id,
        'study_program_id' => $studyProgram->id,
        'is_active' => true,
    ]);

    Livewire::actingAs($user)
        ->withQueryParams([
            'type' => 'baru',
            'scheme' => (string) $otherScheme->id,
            'source' => 'dashboard-skema',
        ])
        ->test(DaftarSkemaBaru::class)
        ->assertSet('registrationType', 'baru')
        ->assertSet('schemeId', (string) $otherScheme->id)
        ->assertSet('faculty', (string) $otherScheme->faculty_id)
        ->assertSet('studyProgram', (string) $otherScheme->study_program_id)
        ->assertSet('currentStep', 1);
});

it('normalizes old perpanjangan shortcuts to the new registration flow', function () {
    createCertificateForUser($this->user, $this->scheme, [
        'status' => 'inactive',
        'expired_date' => now()->subMonths(2),
    ]);

    Livewire::actingAs($this->user)
        ->withQueryParams([
            'type' => 'perpanjangan',
            'scheme' => (string) $this->scheme->id,
            'source' => 'dashboard-skema',
        ])
        ->test(DaftarSkemaBaru::class)
        ->assertSet('registrationType', 'baru')
        ->assertSet('schemeId', (string) $this->scheme->id)
        ->assertSet('currentStep', 1);
});

it('stays on tahap 1 when scheme query is present from another source', function () {
    Livewire::actingAs($this->user)
        ->withQueryParams([
            'type' => 'baru',
            'scheme' => (string) $this->scheme->id,
        ])
        ->test(DaftarSkemaBaru::class)
        ->assertSet('currentStep', 1);
});

it('shows APL 01 step for upnvj users', function () {
    Livewire::actingAs($this->user)
        ->test(DaftarSkemaBaru::class)
        ->set('registrationType', 'baru')
        ->set('schemeId', $this->scheme->id)
        ->call('nextStep')
        ->assertSet('shouldShowProfileStep', true)
        ->assertSet('requiresProfileCompletion', false)
        ->assertSet('currentStep', 2)
        ->assertSet('apl01SubStep', 1);
});

it('shows an error when user has an in-progress registration', function () {
    Registration::factory()->create([
        'user_id' => $this->user->id,
        'scheme_id' => $this->scheme->id,
        'status' => 'menunggu_verifikasi',
    ]);

    Livewire::actingAs($this->user)
        ->test(DaftarSkemaBaru::class)
        ->assertSet('errorMessage', 'Anda masih memiliki pendaftaran yang sedang berjalan. Selesaikan pendaftaran tersebut terlebih dahulu.');
});

it('completes the full baru registration flow', function () {
    $docs = fakeDocuments();
    $fakeSignature = 'data:image/png;base64,iVBORw0KGgo=';

    Livewire::actingAs($this->user)
        ->test(DaftarSkemaBaru::class)
        ->assertSet('currentStep', 1)
        // Step 1: select type and scheme
        ->set('registrationType', 'baru')
        ->set('schemeId', $this->scheme->id)
        ->call('nextStep')
        ->assertHasNoErrors()
        ->assertSet('currentStep', 2)
        ->assertSet('apl01SubStep', 1)
        // Step 2.1 -> 2.2
        ->call('nextStep')
        ->assertSet('apl01SubStep', 2)
        // Step 2.2 -> 2.3
        ->set('assessmentPurpose', 'sertifikasi')
        ->call('nextStep')
        ->assertSet('apl01SubStep', 3)
        // Step 2.3: docs -> confirm -> 2.4
        ->set('ktm', $docs['ktm'])
        ->set('khs', $docs['khs'])
        ->set('ktp', $docs['ktp'])
        ->set('passportPhoto', $docs['passportPhoto'])
        ->call('confirmApl01')
        ->assertHasNoErrors()
        ->assertSet('apl01SubStep', 4)
        // Step 2.4: signature -> advance to Step 3
        ->set('applicantSignature', $fakeSignature)
        ->call('nextStep')
        ->assertHasNoErrors()
        ->assertSet('currentStep', 3)
        // Step 3 visible: APL 02
        ->set('frApl02', $docs['frApl02'])
        ->call('nextStep')
        ->assertHasNoErrors()
        ->assertSet('currentStep', 4)
        // Step 4 visible: review & submit
        ->call('submit')
        ->assertHasNoErrors()
        ->assertSet('currentStep', 5);

    $this->assertDatabaseHas('registrations', [
        'user_id' => $this->user->id,
        'scheme_id' => $this->scheme->id,
        'type' => 'baru',
        'assessment_purpose' => 'sertifikasi',
        'status' => 'menunggu_verifikasi',
        'payment_reference' => '98'.$this->user->nim,
    ]);
});

it('keeps the stepper on the last visible step after registration is submitted', function () {
    $docs = fakeDocuments();
    $fakeSignature = 'data:image/png;base64,iVBORw0KGgo=';

    Livewire::actingAs($this->user)
        ->test(DaftarSkemaBaru::class)
        ->set('registrationType', 'baru')
        ->set('schemeId', $this->scheme->id)
        ->call('nextStep')
        ->call('nextStep')
        ->set('assessmentPurpose', 'sertifikasi')
        ->call('nextStep')
        ->set('ktm', $docs['ktm'])
        ->set('khs', $docs['khs'])
        ->set('ktp', $docs['ktp'])
        ->set('passportPhoto', $docs['passportPhoto'])
        ->call('confirmApl01')
        ->set('applicantSignature', $fakeSignature)
        ->call('nextStep')
        ->set('frApl02', $docs['frApl02'])
        ->call('nextStep')
        ->call('submit')
        ->assertSet('currentStep', 5)
        ->assertSeeHtml('style="width: 100%;"');
});

it('allows registration again when the previous certificate is expired', function () {
    createCertificateForUser($this->user, $this->scheme, [
        'status' => 'inactive',
        'expired_date' => now()->subMonths(2),
    ]);

    Livewire::actingAs($this->user)
        ->test(DaftarSkemaBaru::class)
        ->set('registrationType', 'baru')
        ->set('schemeId', $this->scheme->id)
        ->call('nextStep')
        ->assertHasNoErrors()
        ->assertSet('currentStep', 2);
});

it('generates unique payment_reference when user has existing registrations', function () {
    Registration::factory()->create([
        'user_id' => $this->user->id,
        'scheme_id' => $this->scheme->id,
        'status' => 'sertifikat_terbit',
        'payment_reference' => '98'.$this->user->nim,
    ]);

    $docs = fakeDocuments();

    Livewire::actingAs($this->user)
        ->test(DaftarSkemaBaru::class)
        ->set('registrationType', 'baru')
        ->set('schemeId', $this->secondScheme->id)
        ->call('nextStep')
        ->assertHasNoErrors()
        ->assertSet('currentStep', 2)
        ->call('nextStep')
        ->set('assessmentPurpose', 'sertifikasi')
        ->call('nextStep')
        ->set('ktm', $docs['ktm'])
        ->set('khs', $docs['khs'])
        ->set('ktp', $docs['ktp'])
        ->set('passportPhoto', $docs['passportPhoto'])
        ->call('confirmApl01')
        ->set('applicantSignature', 'data:image/png;base64,iVBORw0KGgo=')
        ->call('nextStep')
        ->assertHasNoErrors()
        ->assertSet('currentStep', 3)
        ->set('frApl02', $docs['frApl02'])
        ->call('nextStep')
        ->assertHasNoErrors()
        ->assertSet('currentStep', 4)
        ->call('submit')
        ->assertHasNoErrors()
        ->assertSet('currentStep', 5);

    $this->assertDatabaseHas('registrations', [
        'user_id' => $this->user->id,
        'scheme_id' => $this->secondScheme->id,
        'type' => 'baru',
        'status' => 'menunggu_verifikasi',
        'payment_reference' => '98'.$this->user->nim.'-2',
    ]);
});

it('generates 16 digit payment_reference for general users from nik and current timestamp', function () {
    Carbon::setTestNow('2026-04-03 10:11:12');

    $user = createGeneralUser(
        user: ['email_verified_at' => now()],
        profile: [
            'fakultas' => 'Fakultas Ilmu Komputer',
            'program_studi' => 'Informatika',
        ],
        umumProfile: ['no_ktp' => '3174000000000001'],
        completed: true,
    );

    $docs = fakeDocuments();
    $fakeSignature = 'data:image/png;base64,iVBORw0KGgo=';

    Livewire::actingAs($user)
        ->test(DaftarSkemaBaru::class)
        ->set('registrationType', 'baru')
        ->set('schemeId', $this->scheme->id)
        ->call('nextStep')
        ->assertSet('currentStep', 2)
        ->call('nextStep')
        ->set('assessmentPurpose', 'sertifikasi')
        ->call('nextStep')
        ->set('ktm', $docs['ktm'])
        ->set('khs', $docs['khs'])
        ->set('ktp', $docs['ktp'])
        ->set('passportPhoto', $docs['passportPhoto'])
        ->call('confirmApl01')
        ->assertSet('apl01SubStep', 4)
        ->set('applicantSignature', $fakeSignature)
        ->call('nextStep')
        ->assertSet('currentStep', 3)
        ->set('frApl02', $docs['frApl02'])
        ->call('nextStep')
        ->assertSet('currentStep', 4)
        ->call('submit')
        ->assertHasNoErrors()
        ->assertSet('currentStep', 5);

    $this->assertDatabaseHas('registrations', [
        'user_id' => $user->id,
        'scheme_id' => $this->scheme->id,
        'assessment_purpose' => 'sertifikasi',
        'payment_reference' => '9800000001101112',
    ]);

    expect(Registration::query()->where('user_id', $user->id)->latest()->value('payment_reference'))
        ->toHaveLength(16);

    Carbon::setTestNow();
});

it('blocks new registration when user has an active certificate for that scheme', function () {
    // Has an ACTIVE certificate, shouldn't be allowed to renew
    createCertificateForUser($this->user, $this->scheme, [
        'status' => 'active',
        'expired_date' => now()->addMonths(6),
    ]);

    Livewire::actingAs($this->user)
        ->test(DaftarSkemaBaru::class)
        ->set('registrationType', 'baru')
        ->set('schemeId', $this->scheme->id)
        ->call('nextStep')
        ->assertHasErrors(['schemeId'])
        ->assertSet('currentStep', 1);
});

it('requires APL 01 again for upnvj users who already have an issued certificate', function () {
    createRegistrationWithRelations(
        $this->user,
        $this->scheme,
        ['status' => 'sertifikat_terbit'],
        [
            'fr_apl_01_path' => 'documents/fr_apl_01/old.pdf',
            'fr_apl_02_path' => 'documents/fr_apl_02/old.pdf',
            'ktm_path' => 'documents/ktm/old.pdf',
            'khs_path' => 'documents/khs/old.pdf',
            'ktp_path' => 'documents/ktp/old.pdf',
            'passport_photo_path' => 'documents/photo/old.jpg',
        ],
    );

    createCertificateForUser($this->user, $this->scheme, [
        'status' => 'active',
        'expired_date' => now()->addMonths(6),
    ]);

    $this->user->profile()->updateOrCreate([], [
        'tempat_lahir' => 'Jakarta',
        'tanggal_lahir' => '2001-02-03',
        'jenis_kelamin' => 'L',
        'alamat_rumah' => 'Jl. Lama No. 1',
        'no_wa' => '081234567890',
        'kode_pos_rumah' => '12950',
    ]);
    $this->user->unsetRelation('profile');

    $docs = fakeDocuments();
    $fakeSignature = 'data:image/png;base64,iVBORw0KGgo=';

    Livewire::actingAs($this->user)
        ->test(DaftarSkemaBaru::class)
        ->assertSet('useCondensedDocumentFlow', false)
        ->assertSet('name', $this->user->nama)
        ->assertSet('email', $this->user->email)
        ->assertSet('tempat_lahir', 'Jakarta')
        ->assertSet('tanggal_lahir', '2001-02-03')
        ->assertSet('alamat_rumah', 'Jl. Lama No. 1')
        ->set('registrationType', 'baru')
        ->set('schemeId', $this->secondScheme->id)
        ->call('nextStep')
        ->assertSet('currentStep', 2)
        ->assertSet('apl01SubStep', 1)
        ->call('nextStep')
        ->assertSet('apl01SubStep', 2)
        ->set('assessmentPurpose', 'sertifikasi')
        ->call('nextStep')
        ->assertSet('apl01SubStep', 3)
        ->call('confirmApl01')
        ->assertHasErrors(['ktm', 'khs', 'ktp', 'passportPhoto'])
        ->set('ktm', $docs['ktm'])
        ->set('khs', $docs['khs'])
        ->set('ktp', $docs['ktp'])
        ->set('passportPhoto', $docs['passportPhoto'])
        ->call('confirmApl01')
        ->assertHasNoErrors(['ktm', 'khs', 'ktp', 'passportPhoto'])
        ->assertSet('apl01SubStep', 4)
        ->set('applicantSignature', $fakeSignature)
        ->call('nextStep')
        ->assertSet('currentStep', 3)
        ->set('frApl02', $docs['frApl02'])
        ->call('nextStep')
        ->assertSet('currentStep', 4)
        ->call('submit')
        ->assertSet('currentStep', 5);

    $registration = Registration::query()
        ->where('user_id', $this->user->id)
        ->where('scheme_id', $this->secondScheme->id)
        ->latest('id')
        ->first();

    expect($registration)->not->toBeNull()
        ->and($registration->fr_apl_01_path)->toBeNull()
        ->and($registration->fr_apl_02_path)->not->toBeNull()
        ->and($registration->ktm_path)->not->toBe('documents/ktm/old.pdf')
        ->and($registration->khs_path)->not->toBe('documents/khs/old.pdf')
        ->and($registration->ktp_path)->not->toBe('documents/ktp/old.pdf')
        ->and($registration->passport_photo_path)->not->toBe('documents/photo/old.jpg')
        ->and($registration->usesSimplifiedDocumentFlow())->toBeFalse();
});

it('prefills but does not skip biodata for general users who already have an issued certificate', function () {
    $user = createGeneralUser(
        profile: [
            'fakultas' => 'Fakultas Ilmu Komputer',
            'program_studi' => 'Informatika',
        ],
        completed: true,
    );

    createCertificateForUser($user, $this->scheme, [
        'status' => 'active',
        'expired_date' => now()->addMonths(6),
    ]);

    Livewire::actingAs($user)
        ->test(DaftarSkemaBaru::class)
        ->assertSet('useCondensedDocumentFlow', false)
        ->assertSet('shouldShowProfileStep', true)
        ->assertSet('name', $user->nama)
        ->assertSet('email', $user->email)
        ->set('registrationType', 'baru')
        ->set('schemeId', $this->secondScheme->id)
        ->call('nextStep')
        ->assertSet('currentStep', 2)
        ->assertSet('apl01SubStep', 1);
});

it('blocks baru registration when user has active certificate for that scheme', function () {
    createCertificateForUser($this->user, $this->scheme, [
        'status' => 'active',
    ]);

    Livewire::actingAs($this->user)
        ->test(DaftarSkemaBaru::class)
        ->set('registrationType', 'baru')
        ->set('schemeId', $this->scheme->id)
        ->call('nextStep')
        ->assertHasErrors(['schemeId'])
        ->assertSet('currentStep', 1);
});

it('shows renewal guidance when no new schemes are available because the user already has the scheme certificate', function () {
    $this->secondScheme->update(['is_active' => false]);

    createCertificateForUser($this->user, $this->scheme, [
        'status' => 'active',
    ]);

    Livewire::actingAs($this->user)
        ->withQueryParams([
            'type' => 'baru',
        ])
        ->test(DaftarSkemaBaru::class)
        ->assertSet('registrationType', 'baru')
        ->assertSee('Tidak ada skema baru yang tersedia untuk pilihan Anda saat ini.')
        ->assertSee('Anda sudah memiliki riwayat sertifikasi untuk skema yang cocok.');
});

it('shows expired certificate schemes as selectable without renewal warning', function () {
    $this->secondScheme->update(['is_active' => false]);

    createCertificateForUser($this->user, $this->scheme, [
        'status' => 'inactive',
        'expired_date' => now()->subDay(),
    ]);

    Livewire::actingAs($this->user)
        ->test(DaftarSkemaBaru::class)
        ->assertSet('registrationType', 'baru')
        ->assertSee('Pilih Skema Sertifikasi')
        ->assertDontSee('Pilih Tipe Pendaftaran')
        ->assertSee($this->scheme->name)
        ->assertDontSee('Anda sudah memiliki riwayat sertifikasi untuk skema yang cocok.');
});

it('keeps old perpanjangan selection normalized when no certificate exists', function () {
    Livewire::actingAs($this->user)
        ->test(DaftarSkemaBaru::class)
        ->set('registrationType', 'perpanjangan')
        ->set('schemeId', $this->scheme->id)
        ->call('nextStep')
        ->assertHasNoErrors()
        ->assertSet('registrationType', 'baru')
        ->assertSet('currentStep', 2);
});

it('blocks registration for a scheme that already has an in-progress registration', function () {
    Registration::factory()->create([
        'user_id' => $this->user->id,
        'scheme_id' => $this->scheme->id,
        'status' => 'menunggu_verifikasi',
    ]);

    Livewire::actingAs($this->user)
        ->test(DaftarSkemaBaru::class)
        ->assertSet('errorMessage', 'Anda masih memiliki pendaftaran yang sedang berjalan. Selesaikan pendaftaran tersebut terlebih dahulu.');
});

it('allows new registration after previous one is completed', function () {
    Registration::factory()->create([
        'user_id' => $this->user->id,
        'scheme_id' => $this->scheme->id,
        'status' => 'sertifikat_terbit',
    ]);

    Livewire::actingAs($this->user)
        ->test(DaftarSkemaBaru::class)
        ->assertSet('errorMessage', null)
        ->assertSet('currentStep', 1);
});

it('allows new registration after previous one resulted in tidak_kompeten', function () {
    Registration::factory()->create([
        'user_id' => $this->user->id,
        'scheme_id' => $this->scheme->id,
        'status' => 'tidak_kompeten',
    ]);

    Livewire::actingAs($this->user)
        ->test(DaftarSkemaBaru::class)
        ->assertSet('errorMessage', null)
        ->assertSet('currentStep', 1);
});

it('validates required fields on step 1', function () {
    Livewire::actingAs($this->user)
        ->test(DaftarSkemaBaru::class)
        ->call('nextStep')
        ->assertHasErrors(['schemeId'])
        ->assertHasNoErrors(['registrationType'])
        ->assertSet('currentStep', 1);
});

it('does not continue to tahap 2 when registration type is chosen but scheme is empty', function () {
    Livewire::actingAs($this->user)
        ->test(DaftarSkemaBaru::class)
        ->set('registrationType', 'baru')
        ->set('schemeId', '')
        ->call('nextStep')
        ->assertHasErrors(['schemeId'])
        ->assertSet('currentStep', 1);
});

it('validates required documents on step 2', function () {
    Livewire::actingAs($this->user)
        ->test(DaftarSkemaBaru::class)
        ->set('registrationType', 'baru')
        ->set('schemeId', $this->scheme->id)
        ->call('nextStep')
        ->assertSet('currentStep', 2)
        ->call('nextStep')
        ->assertSet('apl01SubStep', 2)
        ->set('assessmentPurpose', 'sertifikasi')
        ->call('nextStep')
        ->assertSet('apl01SubStep', 3)
        ->call('confirmApl01')
        ->assertHasErrors(['ktm', 'khs', 'ktp', 'passportPhoto'])
        ->assertSet('currentStep', 2);
});

it('validates apl 01 supporting document file types', function () {
    Livewire::actingAs($this->user)
        ->test(DaftarSkemaBaru::class)
        ->set('registrationType', 'baru')
        ->set('schemeId', $this->scheme->id)
        ->call('nextStep')
        ->call('nextStep')
        ->set('assessmentPurpose', 'sertifikasi')
        ->call('nextStep')
        ->set('ktm', UploadedFile::fake()->create('ktm.pdf', 100, 'application/pdf'))
        ->set('khs', UploadedFile::fake()->image('khs.jpg'))
        ->set('internshipCertificate', UploadedFile::fake()->image('magang.png'))
        ->set('ktp', UploadedFile::fake()->create('ktp.pdf', 100, 'application/pdf'))
        ->set('passportPhoto', UploadedFile::fake()->image('photo.jpg'))
        ->call('nextStep')
        ->assertHasErrors(['ktm', 'khs', 'ktp'])
        ->assertHasNoErrors(['internshipCertificate', 'passportPhoto'])
        ->assertSet('apl01SubStep', 3);
});

it('renders upload previews and 3x4 passport photo preview on apl 01 section 3', function () {
    $docs = fakeDocuments();

    Livewire::actingAs($this->user)
        ->test(DaftarSkemaBaru::class)
        ->set('registrationType', 'baru')
        ->set('schemeId', $this->scheme->id)
        ->call('nextStep')
        ->call('nextStep')
        ->set('assessmentPurpose', 'sertifikasi')
        ->call('nextStep')
        ->set('ktm', $docs['ktm'])
        ->set('khs', $docs['khs'])
        ->set('ktp', $docs['ktp'])
        ->set('passportPhoto', $docs['passportPhoto'])
        ->assertSeeHtml('Preview pasfoto')
        ->assertSeeHtml('aspect-[3/4]')
        ->assertSee('Sesuaikan Pas Foto')
        ->assertSee('Atur Ulang Crop')
        ->assertSee('PDF')
        ->assertSee('JPG, JPEG, PNG maksimal 2MB.');
});

it('stores the cropped 3x4 passport photo when provided', function () {
    $docs = fakeDocuments();
    $fakeSignature = 'data:image/png;base64,iVBORw0KGgo=';
    $croppedPhoto = 'data:image/jpeg;base64,'.base64_encode('cropped-passport-photo');

    Livewire::actingAs($this->user)
        ->test(DaftarSkemaBaru::class)
        ->set('registrationType', 'baru')
        ->set('schemeId', $this->scheme->id)
        ->call('nextStep')
        ->call('nextStep')
        ->set('assessmentPurpose', 'sertifikasi')
        ->call('nextStep')
        ->set('ktm', $docs['ktm'])
        ->set('khs', $docs['khs'])
        ->set('ktp', $docs['ktp'])
        ->set('passportPhoto', $docs['passportPhoto'])
        ->set('passportPhotoBase64', $croppedPhoto)
        ->call('nextStep')
        ->assertSet('apl01SubStep', 4)
        ->set('applicantSignature', $fakeSignature)
        ->call('nextStep')
        ->set('frApl02', $docs['frApl02'])
        ->call('nextStep')
        ->call('submit')
        ->assertSet('currentStep', 5);

    $registration = Registration::query()
        ->where('user_id', $this->user->id)
        ->latest('id')
        ->firstOrFail();

    expect($registration->passport_photo_path)->toStartWith('documents/photo/passport_')
        ->and($registration->passport_photo_path)->toEndWith('.jpg');

    Storage::disk('public')->assertExists($registration->passport_photo_path);
});

it('can navigate back with previousStep', function () {
    Livewire::actingAs($this->user)
        ->test(DaftarSkemaBaru::class)
        ->set('registrationType', 'baru')
        ->set('schemeId', $this->scheme->id)
        ->call('nextStep')
        ->assertSet('currentStep', 2)
        ->call('previousStep')
        ->assertSet('currentStep', 1);
});

it('requires incomplete general users to fill biodata and documents on APL 01', function () {
    $user = createGeneralUser();
    $docs = fakeDocuments();
    $fakeSignature = 'data:image/png;base64,iVBORw0KGgo=';

    Livewire::actingAs($user)
        ->test(DaftarSkemaBaru::class)
        ->set('registrationType', 'baru')
        ->set('schemeId', $this->scheme->id)
        ->call('nextStep')
        ->assertSet('currentStep', 2)
        ->set('name', 'Peserta Umum')
        ->set('email', 'umum@example.com')
        ->set('no_ktp', '3174000000000001')
        ->set('jenis_kelamin', 'L')
        ->set('tempat_lahir', 'Jakarta')
        ->set('tanggal_lahir', '1998-04-10')
        ->set('alamat_rumah', 'Jl. Contoh No. 1')
        ->set('kode_pos_rumah', '12950')
        ->set('telp_rumah', '0211234567')
        ->set('telp_kantor', '0217654321')
        ->set('no_wa', '081234567890')
        ->set('kualifikasi_pendidikan', 'S1')
        ->set('fakultas', 'Fakultas Ilmu Komputer')
        ->set('nama_perusahaan', 'Universitas Contoh')
        ->set('kode_pos_perusahaan', '12950')
        ->set('program_studi', 'Teknik Informatika')
        ->call('nextStep')
        ->assertHasNoErrors()
        ->assertSet('apl01SubStep', 2)
        ->set('assessmentPurpose', 'sertifikasi')
        ->call('nextStep')
        ->assertSet('apl01SubStep', 3)
        ->set('ktm', $docs['ktm'])
        ->set('khs', $docs['khs'])
        ->set('ktp', $docs['ktp'])
        ->set('passportPhoto', $docs['passportPhoto'])
        ->call('confirmApl01')
        ->assertHasNoErrors()
        ->assertSet('apl01SubStep', 4)
        ->set('applicantSignature', $fakeSignature)
        ->call('nextStep')
        ->assertSet('currentStep', 3);

    expect($user->refresh()->hasCompletedProfile())->toBeFalse();

    Livewire::actingAs($user)
        ->test(DaftarSkemaBaru::class)
        ->set('registrationType', 'baru')
        ->set('schemeId', $this->scheme->id)
        ->call('nextStep')
        ->set('name', 'Peserta Umum')
        ->set('email', 'umum@example.com')
        ->set('no_ktp', '3174000000000001')
        ->set('jenis_kelamin', 'L')
        ->set('tempat_lahir', 'Jakarta')
        ->set('tanggal_lahir', '1998-04-10')
        ->set('alamat_rumah', 'Jl. Contoh No. 1')
        ->set('kode_pos_rumah', '12950')
        ->set('telp_rumah', '0211234567')
        ->set('telp_kantor', '0217654321')
        ->set('no_wa', '081234567890')
        ->set('kualifikasi_pendidikan', 'S1')
        ->set('fakultas', 'Fakultas Ilmu Komputer')
        ->set('nama_perusahaan', 'Universitas Contoh')
        ->set('kode_pos_perusahaan', '12950')
        ->set('program_studi', 'Teknik Informatika')
        ->call('nextStep')
        ->assertHasNoErrors()
        ->set('assessmentPurpose', 'sertifikasi')
        ->call('nextStep')
        ->set('ktm', $docs['ktm'])
        ->set('khs', $docs['khs'])
        ->set('ktp', $docs['ktp'])
        ->set('passportPhoto', $docs['passportPhoto'])
        ->call('confirmApl01')
        ->assertHasNoErrors()
        ->assertSet('apl01SubStep', 4)
        ->set('applicantSignature', $fakeSignature)
        ->call('nextStep')
        ->assertSet('currentStep', 3)
        ->assertSet('shouldShowProfileStep', true)
        ->set('frApl02', $docs['frApl02'])
        ->call('nextStep')
        ->assertSet('currentStep', 4)
        ->call('submit')
        ->assertSet('currentStep', 5);

    expect($user->refresh()->hasCompletedProfile())->toBeTrue();
});

it('shows all required biodata inputs for general users on apl 01 section 1', function () {
    $user = createGeneralUser();

    Livewire::actingAs($user)
        ->test(DaftarSkemaBaru::class)
        ->set('registrationType', 'baru')
        ->set('schemeId', $this->scheme->id)
        ->call('nextStep')
        ->assertSet('currentStep', 2)
        ->assertSet('apl01SubStep', 1)
        ->assertDontSee('Instansi Pendidikan')
        ->assertDontSee('Pekerjaan')
        ->assertDontSee('Domisili')
        ->assertSeeHtml('wire:model="telp_rumah"')
        ->assertSeeHtml('wire:model="telp_kantor"')
        ->assertSeeHtml('wire:model="nama_perusahaan"')
        ->assertDontSeeHtml('wire:model="pekerjaan"');
});

it('shows applicant name and pending admin verification block on apl 01 section 4', function () {
    $docs = fakeDocuments();

    Livewire::actingAs($this->user)
        ->test(DaftarSkemaBaru::class)
        ->set('registrationType', 'baru')
        ->set('schemeId', $this->scheme->id)
        ->call('nextStep')
        ->set('name', 'Budi Santoso')
        ->call('nextStep')
        ->set('assessmentPurpose', 'sertifikasi')
        ->call('nextStep')
        ->set('ktm', $docs['ktm'])
        ->set('khs', $docs['khs'])
        ->set('ktp', $docs['ktp'])
        ->set('passportPhoto', $docs['passportPhoto'])
        ->call('confirmApl01')
        ->assertSet('apl01SubStep', 4)
        ->assertSee('Budi Santoso')
        ->assertSee('Menunggu verifikasi admin LSP');
});

it('shows the admin apl 02 template download action on step 3 when available', function () {
    Storage::disk('public')->put('schemes/apl-02-templates/jwd-template.docx', 'template-docx');
    $this->scheme->update([
        'apl_02_template_path' => 'schemes/apl-02-templates/jwd-template.docx',
    ]);

    $docs = fakeDocuments();
    $fakeSignature = 'data:image/png;base64,iVBORw0KGgo=';

    Livewire::actingAs($this->user)
        ->test(DaftarSkemaBaru::class)
        ->set('registrationType', 'baru')
        ->set('schemeId', $this->scheme->id)
        ->call('nextStep')
        ->call('nextStep')
        ->set('assessmentPurpose', 'sertifikasi')
        ->call('nextStep')
        ->set('ktm', $docs['ktm'])
        ->set('khs', $docs['khs'])
        ->set('ktp', $docs['ktp'])
        ->set('passportPhoto', $docs['passportPhoto'])
        ->call('confirmApl01')
        ->set('applicantSignature', $fakeSignature)
        ->call('nextStep')
        ->assertSet('currentStep', 3)
        ->assertSee('Template APL 02 dari admin tersedia')
        ->assertSee('Download APL 02');
});

it('shows entered biodata on the review step before general user submits the registration', function () {
    $user = createGeneralUser();
    $docs = fakeDocuments();
    $fakeSignature = 'data:image/png;base64,iVBORw0KGgo=';

    Livewire::actingAs($user)
        ->test(DaftarSkemaBaru::class)
        ->set('registrationType', 'baru')
        ->set('schemeId', $this->scheme->id)
        ->call('nextStep')
        ->assertSet('currentStep', 2)
        ->set('name', 'Bing')
        ->set('email', 'bing@example.com')
        ->set('no_ktp', '3174000000000001')
        ->set('jenis_kelamin', 'L')
        ->set('tempat_lahir', 'Jakarta')
        ->set('tanggal_lahir', '1998-04-10')
        ->set('alamat_rumah', 'Jl. Contoh No. 1')
        ->set('kode_pos_rumah', '12950')
        ->set('telp_rumah', '0211234567')
        ->set('telp_kantor', '0217654321')
        ->set('no_wa', '081234567890')
        ->set('kualifikasi_pendidikan', 'S1')
        ->set('fakultas', 'Fakultas Ilmu Komputer')
        ->set('nama_perusahaan', 'Universitas Contoh')
        ->set('kode_pos_perusahaan', '12950')
        ->set('program_studi', 'Teknik Informatika')
        ->call('nextStep')
        ->set('assessmentPurpose', 'sertifikasi')
        ->call('nextStep')
        ->set('ktm', $docs['ktm'])
        ->set('khs', $docs['khs'])
        ->set('ktp', $docs['ktp'])
        ->set('passportPhoto', $docs['passportPhoto'])
        ->call('confirmApl01')
        ->assertSet('apl01SubStep', 4)
        ->set('applicantSignature', $fakeSignature)
        ->call('nextStep')
        ->assertSet('currentStep', 3)
        ->set('frApl02', $docs['frApl02'])
        ->call('nextStep')
        ->assertSet('currentStep', 4)
        ->assertSee('Bing')
        ->assertSee('3174000000000001')
        ->assertSee('No. WhatsApp')
        ->assertSee('081234567890')
        ->assertDontSee('Telepon Kantor')
        ->assertSee('Universitas Contoh');
});

it('does not prefill academic filters for completed general users', function () {
    $user = createGeneralUser(
        completed: true,
    );

    Livewire::actingAs($user)
        ->test(DaftarSkemaBaru::class)
        ->assertSet('shouldShowProfileStep', true)
        ->assertSet('requiresProfileCompletion', false)
        ->assertSet('faculty', '')
        ->assertSet('studyProgram', '')
        ->assertSee('APL 01')
        ->set('registrationType', 'baru')
        ->assertSee($this->scheme->name);
});

it('backfills supporting documents for old condensed registrations via migration', function () {
    $previousRegistration = createRegistrationWithRelations(
        $this->user,
        $this->scheme,
        ['status' => 'sertifikat_terbit'],
        [
            'fr_apl_01_path' => 'documents/fr_apl_01/old.pdf',
            'fr_apl_02_path' => 'documents/fr_apl_02/old.pdf',
            'ktm_path' => 'documents/ktm/old.pdf',
            'khs_path' => 'documents/khs/old.pdf',
            'internship_certificate_path' => 'documents/internship/old.pdf',
            'ktp_path' => 'documents/ktp/old.pdf',
            'passport_photo_path' => 'documents/photo/old.jpg',
        ],
    );

    $currentRegistration = createRegistrationWithRelations(
        $this->user,
        $this->secondScheme,
        ['status' => 'menunggu_verifikasi'],
        [
            'fr_apl_01_path' => 'documents/fr_apl_01/new.pdf',
            'fr_apl_02_path' => 'documents/fr_apl_02/new.pdf',
            'ktm_path' => $previousRegistration->ktm_path,
            'khs_path' => $previousRegistration->khs_path,
            'internship_certificate_path' => $previousRegistration->internship_certificate_path,
            'ktp_path' => $previousRegistration->ktp_path,
            'passport_photo_path' => $previousRegistration->passport_photo_path,
        ],
        [
            'fr_apl_01_path' => ['status' => 'verified'],
            'fr_apl_02_path' => ['status' => 'verified'],
            '_meta_condensed_flow' => ['status' => 'verified'],
        ],
    );

    expect($currentRegistration->ktm_path)->toBe($previousRegistration->ktm_path)
        ->and($currentRegistration->khs_path)->toBe($previousRegistration->khs_path)
        ->and($currentRegistration->internship_certificate_path)->toBe($previousRegistration->internship_certificate_path)
        ->and($currentRegistration->ktp_path)->toBe($previousRegistration->ktp_path)
        ->and($currentRegistration->passport_photo_path)->toBe($previousRegistration->passport_photo_path)
        ->and($currentRegistration->usesSimplifiedDocumentFlow())->toBeTrue();
});
