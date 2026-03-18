<?php

use App\Models\Scheme;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

test('a user can log in with nim', function () {
    $user = User::factory()->create([
        'nim' => '123456789',
        'password' => Hash::make('password'),
    ]);

    $response = $this->post('/login', [
        'nim' => '123456789',
        'password' => 'password',
    ]);

    $this->assertAuthenticatedAs($user);
    $response->assertRedirect('/dashboard');
});

test('registration stepper functional flow', function () {
    Storage::fake('public');

    $scheme = Scheme::create([
        'name' => 'Skema Sertifikasi Programmer',
        'faculty' => 'Fakultas Ilmu Komputer',
        'study_program' => 'Informatika',
        'description' => 'Sertifikasi kompetensi programmer level junior.',
        'is_active' => true,
    ]);

    $frApl01 = UploadedFile::fake()->create('fr_apl_01.pdf', 100, 'application/pdf');
    $frApl02 = UploadedFile::fake()->create('fr_apl_02.pdf', 100, 'application/pdf');
    $ktm = UploadedFile::fake()->create('ktm.pdf', 100, 'application/pdf');
    $khs = UploadedFile::fake()->create('khs.pdf', 100, 'application/pdf');
    $ktp = UploadedFile::fake()->create('ktp.pdf', 100, 'application/pdf');
    $passportPhoto = UploadedFile::fake()->image('photo.jpg');

    Livewire::test('auth.registration-stepper')
        // STEP 1
        ->set('email', 'johndoe@example.com')
        ->set('name', 'John Doe')
        ->set('nim', '987654321')
        ->set('no_ktp', '123123123')
        ->set('tempat_lahir', 'Jakarta')
        ->set('tanggal_lahir', '2000-01-01')
        ->set('jenis_kelamin', 'L')
        ->set('alamat_rumah', '123 Main St')
        ->set('no_wa', '08123456789')
        ->set('pendidikan_terakhir', 'SMA')
        ->set('total_sks', 100)
        ->set('status_semester', 'Aktif')
        ->set('fakultas', 'Fakultas Ilmu Komputer')
        ->set('program_studi', 'Informatika')
        ->call('nextStep')
        ->assertSet('currentStep', 2)

        // STEP 2
        ->set('scheme_id', $scheme->id)
        ->call('nextStep')
        ->assertSet('currentStep', 3)

        // STEP 3
        ->set('fr_apl_01', $frApl01)
        ->set('fr_apl_02', $frApl02)
        ->set('ktm', $ktm)
        ->set('khs', $khs)
        ->set('ktp', $ktp)
        ->set('passport_photo', $passportPhoto)
        ->call('nextStep')
        ->assertSet('currentStep', 4)

        // STEP 4 - Submit
        ->call('submit')
        ->assertSet('currentStep', 5);

    $this->assertDatabaseHas('users', [
        'email' => 'johndoe@example.com',
        'nim' => '987654321',
    ]);

    $this->assertDatabaseHas('registrations', [
        'scheme_id' => $scheme->id,
        'payment_reference' => '98987654321',
        'status' => 'pending_payment'
    ]);
});
