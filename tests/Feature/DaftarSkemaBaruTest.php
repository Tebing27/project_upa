<?php

use App\Livewire\DaftarSkemaBaru;
use App\Models\Certificate;
use App\Models\Registration;
use App\Models\Scheme;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

beforeEach(function () {
    Storage::fake('public');

    $this->user = User::factory()->create([
        'nim' => '2210511042',
        'fakultas' => 'Fakultas Ilmu Komputer',
        'program_studi' => 'Informatika',
    ]);

    $this->scheme = Scheme::factory()->create([
        'name' => 'Junior Web Developer',
        'faculty' => 'Fakultas Ilmu Komputer',
        'study_program' => 'Informatika',
        'is_active' => true,
    ]);

    $this->secondScheme = Scheme::factory()->create([
        'name' => 'Junior Mobile Developer',
        'faculty' => 'Fakultas Ilmu Komputer',
        'study_program' => 'Informatika',
        'is_active' => true,
    ]);
});

function fakeDocuments(): array
{
    return [
        'frApl01' => UploadedFile::fake()->create('fr_apl_01.pdf', 100, 'application/pdf'),
        'frApl02' => UploadedFile::fake()->create('fr_apl_02.pdf', 100, 'application/pdf'),
        'ktm' => UploadedFile::fake()->create('ktm.pdf', 100, 'application/pdf'),
        'khs' => UploadedFile::fake()->create('khs.pdf', 100, 'application/pdf'),
        'ktp' => UploadedFile::fake()->create('ktp.pdf', 100, 'application/pdf'),
        'passportPhoto' => UploadedFile::fake()->image('photo.jpg'),
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

    Livewire::actingAs($this->user)
        ->test(DaftarSkemaBaru::class)
        ->assertSet('currentStep', 1)
        // Step 1: select type and scheme
        ->set('registrationType', 'baru')
        ->set('schemeId', $this->scheme->id)
        ->call('nextStep')
        ->assertHasNoErrors()
        ->assertSet('currentStep', 2)
        // Step 2: upload documents
        ->set('frApl01', $docs['frApl01'])
        ->set('frApl02', $docs['frApl02'])
        ->set('ktm', $docs['ktm'])
        ->set('khs', $docs['khs'])
        ->set('ktp', $docs['ktp'])
        ->set('passportPhoto', $docs['passportPhoto'])
        ->call('nextStep')
        ->assertHasNoErrors()
        ->assertSet('currentStep', 3)
        // Step 3: review & submit
        ->call('submit')
        ->assertHasNoErrors()
        ->assertSet('currentStep', 4);

    $this->assertDatabaseHas('registrations', [
        'user_id' => $this->user->id,
        'scheme_id' => $this->scheme->id,
        'type' => 'baru',
        'status' => 'pending_payment',
        'payment_reference' => '98'.$this->user->nim,
    ]);
});

it('completes the full perpanjangan registration flow', function () {
    Certificate::factory()->create([
        'user_id' => $this->user->id,
        'scheme_id' => $this->scheme->id,
        'scheme_name' => 'Junior Web Developer',
        'status' => 'inactive',
        'expired_date' => now()->subMonths(2),
    ]);

    $docs = fakeDocuments();

    Livewire::actingAs($this->user)
        ->test(DaftarSkemaBaru::class)
        ->set('registrationType', 'perpanjangan')
        ->set('schemeId', $this->scheme->id)
        ->call('nextStep')
        ->assertHasNoErrors()
        ->assertSet('currentStep', 2)
        ->set('frApl01', $docs['frApl01'])
        ->set('frApl02', $docs['frApl02'])
        ->set('ktm', $docs['ktm'])
        ->set('khs', $docs['khs'])
        ->set('ktp', $docs['ktp'])
        ->set('passportPhoto', $docs['passportPhoto'])
        ->call('nextStep')
        ->assertHasNoErrors()
        ->assertSet('currentStep', 3)
        ->call('submit')
        ->assertHasNoErrors()
        ->assertSet('currentStep', 4);

    $this->assertDatabaseHas('registrations', [
        'user_id' => $this->user->id,
        'scheme_id' => $this->scheme->id,
        'type' => 'perpanjangan',
        'status' => 'pending_payment',
    ]);
});

it('blocks perpanjangan when user has no expired or inactive certificate for that scheme', function () {
    // Has an ACTIVE certificate, shouldn't be allowed to renew
    Certificate::factory()->create([
        'user_id' => $this->user->id,
        'scheme_id' => $this->scheme->id,
        'scheme_name' => 'Junior Web Developer',
        'status' => 'active',
        'expired_date' => now()->addMonths(6),
    ]);

    Livewire::actingAs($this->user)
        ->test(DaftarSkemaBaru::class)
        ->set('registrationType', 'perpanjangan')
        ->set('schemeId', $this->scheme->id)
        ->call('nextStep')
        ->assertHasErrors(['schemeId'])
        ->assertSet('currentStep', 1);
});

it('blocks baru registration when user has active certificate for that scheme', function () {
    Certificate::factory()->create([
        'user_id' => $this->user->id,
        'scheme_id' => $this->scheme->id,
        'scheme_name' => 'Junior Web Developer',
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

it('blocks perpanjangan when user has no active certificate for that scheme', function () {
    Livewire::actingAs($this->user)
        ->test(DaftarSkemaBaru::class)
        ->set('registrationType', 'perpanjangan')
        ->set('schemeId', $this->scheme->id)
        ->call('nextStep')
        ->assertHasErrors(['schemeId'])
        ->assertSet('currentStep', 1);
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
        ->assertHasErrors(['registrationType', 'schemeId'])
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
        ->assertHasErrors(['frApl01', 'frApl02', 'ktm', 'khs', 'ktp', 'passportPhoto'])
        ->assertSet('currentStep', 2);
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
