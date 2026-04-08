<?php

use App\Models\Assessor;
use App\Models\Certificate;
use App\Models\Faculty;
use App\Models\Registration;
use App\Models\RegistrationDocument;
use App\Models\RegistrationDocumentStatus;
use App\Models\Scheme;
use App\Models\StudyProgram;
use App\Models\User;
use App\Models\UserMahasiswaProfile;
use App\Models\UserProfile;
use App\Models\UserUmumProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function something()
{
    // ..
}

function createMahasiswaUser(array $user = [], array $profile = [], array $mahasiswaProfile = []): User
{
    $user = User::factory()->create(array_merge([
        'role' => 'mahasiswa',
    ], $user));

    UserProfile::query()->updateOrCreate(
        ['user_id' => $user->id],
        array_merge([
            'fakultas' => 'Ilmu Komputer',
            'program_studi' => 'Teknik Informatika',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2000-01-01',
            'jenis_kelamin' => 'L',
            'domisili_provinsi' => 'DKI Jakarta',
            'domisili_kota' => 'Jakarta Selatan',
            'domisili_kecamatan' => 'Setiabudi',
            'alamat_rumah' => 'Jl. Contoh No. 1',
            'no_wa' => '081234567890',
        ], $profile)
    );

    UserMahasiswaProfile::query()->updateOrCreate(
        ['user_id' => $user->id],
        array_merge([
            'nim' => '2210511042',
            'total_sks' => 144,
            'status_semester' => 'Lulus',
        ], $mahasiswaProfile)
    );

    return $user->fresh(['profile', 'mahasiswaProfile', 'umumProfile']);
}

function createGeneralUser(array $user = [], array $profile = [], array $umumProfile = [], bool $completed = false): User
{
    $user = User::factory()->general()->create(array_merge([
        'role' => 'umum',
        'profile_completed_at' => $completed ? now() : null,
    ], $user));

    if ($completed || $profile !== [] || $umumProfile !== []) {
        UserProfile::query()->updateOrCreate(
            ['user_id' => $user->id],
            array_merge([
                'fakultas' => 'Umum',
                'program_studi' => 'Manajemen Informatika',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1998-04-10',
                'jenis_kelamin' => 'L',
                'domisili_provinsi' => 'DKI Jakarta',
                'domisili_kota' => 'Jakarta Selatan',
                'domisili_kecamatan' => 'Setiabudi',
                'alamat_rumah' => 'Jl. Contoh No. 1',
                'no_wa' => '081234567890',
            ], $profile)
        );

        UserUmumProfile::query()->updateOrCreate(
            ['user_id' => $user->id],
            array_merge([
                'no_ktp' => '3174000000000001',
                'pendidikan_terakhir' => 'S1',
                'nama_pekerjaan' => 'Karyawan Swasta',
                'nama_perusahaan' => 'Perusahaan Contoh',
                'jabatan' => 'Staf',
                'alamat_perusahaan' => 'Jl. Kantor No. 2',
                'kode_pos_perusahaan' => '12950',
                'no_telp_perusahaan' => '0215551234',
                'email_perusahaan' => 'kantor@example.com',
            ], $umumProfile)
        );

        $user->syncProfileCompletionStatus();
        $user->save();
    }

    return $user->fresh(['profile', 'mahasiswaProfile', 'umumProfile']);
}

function createScheme(array $attributes = []): Scheme
{
    $faculty = Faculty::factory()->create();
    $studyProgram = StudyProgram::factory()->create([
        'faculty_id' => $faculty->id,
    ]);

    return Scheme::factory()->create(array_merge([
        'faculty_id' => $faculty->id,
        'study_program_id' => $studyProgram->id,
    ], $attributes));
}

function createRegistrationWithRelations(
    User $user,
    Scheme $scheme,
    array $registration = [],
    array $documents = [],
    array $statuses = [],
    ?array $exam = null,
): Registration {
    $registrationModel = Registration::factory()->create(array_merge([
        'user_id' => $user->id,
        'scheme_id' => $scheme->id,
    ], $registration));

    foreach ($documents as $documentType => $filePath) {
        RegistrationDocument::query()->create([
            'registration_id' => $registrationModel->id,
            'document_type' => $documentType,
            'file_path' => $filePath,
        ]);
    }

    foreach ($statuses as $documentType => $status) {
        RegistrationDocumentStatus::query()->create([
            'registration_id' => $registrationModel->id,
            'document_type' => $documentType,
            'status' => $status['status'] ?? 'pending',
            'catatan' => $status['note'] ?? $status['catatan'] ?? null,
            'verified_by' => $status['verified_by'] ?? null,
            'verified_at' => $status['verified_at'] ?? null,
        ]);
    }

    if ($exam !== null) {
        $assessorId = $exam['assessor_id'] ?? null;

        if ($assessorId === null && filled($exam['assessor_name'] ?? null)) {
            $assessorId = Assessor::query()->firstOrCreate([
                'nama' => $exam['assessor_name'],
            ])->id;
        }

        $registrationModel->exam()->create([
            'assessor_id' => $assessorId,
            'exam_date' => $exam['exam_date'] ?? null,
            'exam_location' => $exam['exam_location'] ?? null,
            'score' => $exam['score'] ?? null,
            'exam_result_path' => $exam['exam_result_path'] ?? null,
        ]);
    }

    return $registrationModel->fresh(['documents', 'documentStatuses', 'exam.assessor', 'scheme', 'user']);
}

function createCertificateForUser(User $user, Scheme $scheme, array $attributes = []): Certificate
{
    return Certificate::factory()->create(array_merge([
        'user_id' => $user->id,
        'scheme_id' => $scheme->id,
    ], $attributes));
}
