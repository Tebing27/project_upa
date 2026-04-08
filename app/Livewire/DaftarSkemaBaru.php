<?php

namespace App\Livewire;

use App\Concerns\ProfileValidationRules;
use App\Models\Certificate;
use App\Models\Faculty;
use App\Models\Registration;
use App\Models\RegistrationDocument;
use App\Models\Scheme;
use App\Models\StudyProgram;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class DaftarSkemaBaru extends Component
{
    use ProfileValidationRules;
    use WithFileUploads;

    public int $currentStep = 1;

    #[Url(as: 'type')]
    public string $registrationType = '';

    #[Url(as: 'scheme')]
    public string $schemeId = '';

    #[Url(as: 'source')]
    public string $source = '';

    public bool $shouldShowProfileStep = false;

    public bool $useCondensedDocumentFlow = false;

    public bool $requiresProfileCompletion = false;

    public string $faculty = '';

    public string $studyProgram = '';

    public string $name = '';

    public string $email = '';

    public ?string $nim = null;

    public ?string $no_ktp = null;

    public ?string $tempat_lahir = null;

    public ?string $tanggal_lahir = null;

    public ?string $jenis_kelamin = null;

    public ?string $alamat_rumah = null;

    public ?string $domisili_provinsi = null;

    public ?string $domisili_kota = null;

    public ?string $domisili_kecamatan = null;

    public ?string $no_wa = null;

    public ?string $pendidikan_terakhir = null;

    public ?string $nama_institusi = null;

    public ?int $total_sks = null;

    public ?string $status_semester = null;

    public ?string $fakultas = null;

    public ?string $program_studi = null;

    public ?string $pekerjaan = null;

    public ?string $nama_perusahaan = null;

    public ?string $jabatan = null;

    public ?string $alamat_perusahaan = null;

    public ?string $kode_pos_perusahaan = null;

    public ?string $no_telp_perusahaan = null;

    public ?string $email_perusahaan = null;

    /** @var TemporaryUploadedFile|null */
    public $frApl01;

    /** @var TemporaryUploadedFile|null */
    public $frApl02;

    /** @var TemporaryUploadedFile|null */
    public $ktm;

    /** @var TemporaryUploadedFile|null */
    public $khs;

    /** @var TemporaryUploadedFile|null */
    public $internshipCertificate;

    /** @var TemporaryUploadedFile|null */
    public $ktp;

    /** @var TemporaryUploadedFile|null */
    public $passportPhoto;

    public ?string $errorMessage = null;

    public ?Registration $submittedRegistration = null;

    public function mount(): void
    {
        $user = Auth::user();

        $this->fillProfileFromUser();
        $this->syncFlowConfiguration();

        if ($user->hasInProgressRegistration()) {
            $this->errorMessage = 'Anda masih memiliki pendaftaran yang sedang berjalan. Selesaikan pendaftaran tersebut terlebih dahulu.';

            return;
        }

        $selectedScheme = $this->schemeId ? Scheme::find($this->schemeId) : null;

        if (
            $this->source === 'dashboard-skema'
            && $selectedScheme
            && $this->registrationType === 'baru'
        ) {
            $this->faculty = (string) ($selectedScheme->faculty_id ?? '');
            $this->studyProgram = (string) ($selectedScheme->study_program_id ?? '');
        }

    }

    public function updatedFaculty(): void
    {
        $this->studyProgram = '';
        $this->schemeId = '';
    }

    public function updatedStudyProgram(): void
    {
        $this->schemeId = '';
    }

    public function updatedRegistrationType(): void
    {
        $this->schemeId = '';
        $this->syncFlowConfiguration();
    }

    public function nextStep(): void
    {
        if ($this->errorMessage) {
            return;
        }

        if ($this->currentStep === 1) {
            $this->validate([
                'registrationType' => 'required|in:baru,perpanjangan',
                'schemeId' => 'required|exists:schemes,id',
            ], [
                'registrationType.required' => 'Pilih tipe pendaftaran.',
                'schemeId.required' => 'Pilih skema sertifikasi.',
            ]);

            if (! $this->selectedSchemeIsAvailable()) {
                $this->addError('schemeId', 'Pilih skema sertifikasi yang tersedia untuk tipe pendaftaran ini.');

                return;
            }

            if (! $this->guardRegistrationRules()) {
                return;
            }
            $this->currentStep = $this->shouldShowProfileStep ? 2 : 3;

            return;
        }

        if ($this->currentStep === 2) {
            $this->validateProfileStep();
            $this->currentStep = 3;

            return;
        }

        if ($this->currentStep === 3) {
            $this->validate($this->documentRules());

            $this->currentStep = 4;
        }
    }

    public function previousStep(): void
    {
        if ($this->currentStep === 4) {
            $this->currentStep = 3;

            return;
        }

        if ($this->currentStep === 3) {
            $this->currentStep = $this->shouldShowProfileStep ? 2 : 1;

            return;
        }

        if ($this->currentStep === 2) {
            $this->currentStep = 1;
        }
    }

    public function submit(): void
    {
        if ($this->errorMessage) {
            return;
        }

        if (! $this->guardRegistrationRules()) {
            return;
        }

        $user = Auth::user();

        if ($this->shouldShowProfileStep && $this->currentStep >= 2) {
            $this->saveProfileStep();
        }

        $schemeId = (int) $this->schemeId;
        $supportingDocumentPaths = $this->useCondensedDocumentFlow
            ? $this->getSupportingDocumentPathsFromHistory()
            : [];

        $frApl01Path = $this->frApl01->store('documents/fr_apl_01', 'public');
        $frApl02Path = $this->frApl02->store('documents/fr_apl_02', 'public');
        $ktmPath = $this->useCondensedDocumentFlow
            ? $supportingDocumentPaths['ktm_path']
            : $this->ktm?->store('documents/ktm', 'public');
        $khsPath = $this->useCondensedDocumentFlow
            ? $supportingDocumentPaths['khs_path']
            : $this->khs?->store('documents/khs', 'public');
        $internshipPath = $this->useCondensedDocumentFlow
            ? $supportingDocumentPaths['internship_certificate_path']
            : $this->internshipCertificate?->store('documents/internship', 'public');
        $ktpPath = $this->useCondensedDocumentFlow
            ? $supportingDocumentPaths['ktp_path']
            : $this->ktp?->store('documents/ktp', 'public');
        $passportPhotoPath = $this->useCondensedDocumentFlow
            ? $supportingDocumentPaths['passport_photo_path']
            : $this->passportPhoto?->store('documents/photo', 'public');

        $paymentReference = $this->generatePaymentReference();

        $registration = $user->registrations()->create([
            'scheme_id' => $schemeId,
            'type' => $this->registrationType,
            'payment_reference' => $paymentReference,
            'va_numer' => null,
            'status' => 'menunggu_verifikasi',
        ]);

        $documents = [
            'fr_apl_01_path' => $frApl01Path,
            'fr_apl_02_path' => $frApl02Path,
            'ktm_path' => $ktmPath,
            'khs_path' => $khsPath,
            'internship_certificate_path' => $internshipPath,
            'ktp_path' => $ktpPath,
            'passport_photo_path' => $passportPhotoPath,
        ];

        foreach ($documents as $type => $path) {
            if ($path) {
                $registration->documents()->create([
                    'document_type' => $type,
                    'file_path' => $path,
                ]);
            }
        }

        if ($this->useCondensedDocumentFlow) {
            $registration->documentStatuses()->create([
                'document_type' => '_meta_condensed_flow',
                'status' => 'verified',
            ]);
        }

        $this->submittedRegistration = $registration->load('scheme');
        $this->currentStep = 5;
    }

    /**
     * @return array<int, string>
     */
    public function stepLabels(): array
    {
        $steps = [1 => 'Pilih Tipe & Skema'];

        if ($this->shouldShowProfileStep) {
            $steps[2] = 'Lengkapi Biodata';
        }

        $steps[3] = 'Upload Dokumen';
        $steps[4] = 'Review';

        return $steps;
    }

    /**
     * @return Collection<int, Scheme>
     */
    public function getNewSchemes(): Collection
    {
        $user = Auth::user();

        $existingSchemeIds = Certificate::query()
            ->where('user_id', $user->id)
            ->pluck('scheme_id')
            ->filter()
            ->all();

        $inProgressSchemeIds = Registration::query()
            ->where('user_id', $user->id)
            ->whereNotIn('status', ['sertifikat_terbit', 'tidak_kompeten'])
            ->pluck('scheme_id')
            ->all();

        $excludeIds = array_unique(array_merge($existingSchemeIds, $inProgressSchemeIds));

        return Scheme::query()
            ->where('is_active', true)
            ->when($this->faculty !== '', fn ($query) => $query->where('faculty_id', $this->faculty))
            ->when($this->studyProgram !== '', fn ($query) => $query->where('study_program_id', $this->studyProgram))
            ->whereNotIn('id', $excludeIds)
            ->get();
    }

    /**
     * @return Collection<int, Scheme>
     */
    public function getRenewalSchemes(): Collection
    {
        $user = Auth::user();

        $existingSchemeIds = Certificate::query()
            ->where('user_id', $user->id)
            ->pluck('scheme_id')
            ->filter()
            ->all();

        $activeSchemeIds = Certificate::query()
            ->where('user_id', $user->id)
            ->active()
            ->pluck('scheme_id')
            ->filter()
            ->all();

        $renewableSchemeIds = array_diff($existingSchemeIds, $activeSchemeIds);

        $inProgressSchemeIds = Registration::query()
            ->where('user_id', $user->id)
            ->whereNotIn('status', ['sertifikat_terbit', 'tidak_kompeten'])
            ->pluck('scheme_id')
            ->all();

        return Scheme::query()
            ->where('is_active', true)
            ->whereIn('id', $renewableSchemeIds)
            ->whereNotIn('id', $inProgressSchemeIds)
            ->get();
    }

    public function getFaculties(): \Illuminate\Support\Collection
    {
        return Faculty::query()->orderBy('name')->get();
    }

    public function getStudyPrograms(): \Illuminate\Support\Collection
    {
        return StudyProgram::query()
            ->when($this->faculty !== '', fn ($q) => $q->where('faculty_id', $this->faculty))
            ->orderBy('nama')
            ->get();
    }

    public function render(): View
    {
        return view('livewire.daftar-skema-baru', [
            'newSchemes' => $this->getNewSchemes(),
            'renewalSchemes' => $this->getRenewalSchemes(),
            'hasMatchingCertifiedSchemeForNewRegistration' => $this->hasMatchingCertifiedSchemeForNewRegistration(),
            'selectedScheme' => $this->schemeId ? Scheme::find($this->schemeId) : null,
            'faculties' => $this->getFaculties(),
            'studyPrograms' => $this->getStudyPrograms(),
            'steps' => $this->stepLabels(),
            'showFacultyFilters' => $this->registrationType === 'baru',
            'useCondensedDocumentFlow' => $this->useCondensedDocumentFlow,
        ]);
    }

    private function fillProfileFromUser(): void
    {
        $user = Auth::user();

        $this->name = $user->nama;
        $this->email = $user->email;
        $this->nim = $user->mahasiswaProfile?->nim;
        $this->no_ktp = $user->umumProfile?->no_ktp;
        $this->tempat_lahir = $user->profile?->tempat_lahir;
        $this->tanggal_lahir = $user->profile?->tanggal_lahir
            ? Carbon::parse($user->profile?->tanggal_lahir)->format('Y-m-d')
            : null;
        $this->jenis_kelamin = $user->profile?->jenis_kelamin;
        $this->alamat_rumah = $user->profile?->alamat_rumah;
        $this->domisili_provinsi = $user->profile?->domisili_provinsi;
        $this->domisili_kota = $user->profile?->domisili_kota;
        $this->domisili_kecamatan = $user->profile?->domisili_kecamatan;
        $this->no_wa = $user->profile?->no_wa;
        $this->pendidikan_terakhir = $user->umumProfile?->pendidikan_terakhir;
        $this->nama_institusi = $user->umumProfile?->nama_perusahaan;
        $this->total_sks = $user->mahasiswaProfile?->total_sks;
        $this->status_semester = $user->mahasiswaProfile?->status_semester;
        $this->fakultas = $user->profile?->fakultas;
        $this->program_studi = $user->profile?->program_studi;
        $this->faculty = $this->resolveFacultyFilterValue($user->profile?->fakultas);
        $this->studyProgram = $this->resolveStudyProgramFilterValue(
            $user->profile?->program_studi,
            $this->faculty,
        );
        $this->pekerjaan = $user->umumProfile?->nama_pekerjaan;
        $this->nama_perusahaan = $user->umumProfile?->nama_perusahaan;
        $this->jabatan = $user->umumProfile?->jabatan;
        $this->alamat_perusahaan = $user->umumProfile?->alamat_perusahaan;
        $this->kode_pos_perusahaan = $user->umumProfile?->kode_pos_perusahaan;
        $this->no_telp_perusahaan = $user->umumProfile?->no_telp_perusahaan;
        $this->email_perusahaan = $user->umumProfile?->email_perusahaan;
    }

    private function validateProfileStep(): array
    {
        $user = Auth::user();
        $rules = $this->profileRules($user->id, $user->role);

        $rules['name'] = $rules['nama'];
        unset($rules['nama']);

        foreach ([
            'no_ktp', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'alamat_rumah',
            'domisili_provinsi', 'domisili_kota', 'domisili_kecamatan', 'no_wa',
            'pendidikan_terakhir', 'nama_institusi', 'fakultas', 'program_studi', 'pekerjaan',
            'nama_perusahaan', 'jabatan', 'alamat_perusahaan', 'kode_pos_perusahaan',
            'no_telp_perusahaan', 'email_perusahaan',
        ] as $field) {
            $this->{$field} = filled($this->{$field}) ? trim((string) $this->{$field}) : null;
        }

        return $this->validate($rules);
    }

    private function saveProfileStep(): void
    {
        $user = Auth::user();

        $validated = $this->validateProfileStep();

        $user->update([
            'nama' => $validated['name'] ?? $user->nama,
            'email' => $validated['email'] ?? $user->email,
        ]);

        $user->profile()->updateOrCreate([], [
            'tempat_lahir' => $validated['tempat_lahir'] ?? null,
            'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
            'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
            'alamat_rumah' => $validated['alamat_rumah'] ?? null,
            'domisili_provinsi' => $validated['domisili_provinsi'] ?? null,
            'domisili_kota' => $validated['domisili_kota'] ?? null,
            'domisili_kecamatan' => $validated['domisili_kecamatan'] ?? null,
            'no_wa' => $validated['no_wa'] ?? null,
            'fakultas' => $validated['fakultas'] ?? null,
            'program_studi' => $validated['program_studi'] ?? null,
        ]);

        $user->umumProfile()->updateOrCreate([], [
            'no_ktp' => $validated['no_ktp'] ?? null,
            'pendidikan_terakhir' => $validated['pendidikan_terakhir'] ?? null,
            'nama_pekerjaan' => $validated['pekerjaan'] ?? null,
            'nama_perusahaan' => $validated['nama_perusahaan'] ?? null,
            'jabatan' => $validated['jabatan'] ?? null,
            'alamat_perusahaan' => $validated['alamat_perusahaan'] ?? null,
            'kode_pos_perusahaan' => $validated['kode_pos_perusahaan'] ?? null,
            'no_telp_perusahaan' => $validated['no_telp_perusahaan'] ?? null,
            'email_perusahaan' => $validated['email_perusahaan'] ?? null,
        ]);

        $user->syncProfileCompletionStatus();
        $user->save();

        $this->faculty = $this->resolveFacultyFilterValue($user->profile?->fakultas);
        $this->studyProgram = $this->resolveStudyProgramFilterValue(
            $user->profile?->program_studi,
            $this->faculty,
        );
    }

    private function guardRegistrationRules(): bool
    {
        $user = Auth::user();
        $schemeId = (int) $this->schemeId;

        if ($user->hasInProgressRegistrationForScheme($schemeId)) {
            $this->addError('schemeId', 'Anda sudah memiliki pendaftaran yang sedang berjalan untuk skema ini.');

            return false;
        }

        if ($this->registrationType === 'baru' && $user->hasAnyCertificateForScheme($schemeId)) {
            $this->addError('schemeId', 'Anda sudah memiliki riwayat sertifikat untuk skema ini. Gunakan opsi perpanjangan.');

            return false;
        }

        if ($this->registrationType === 'perpanjangan' && ! $user->hasAnyCertificateForScheme($schemeId)) {
            $this->addError('schemeId', 'Anda belum pernah memiliki sertifikat untuk skema ini. Pilih opsi skema baru.');

            return false;
        }

        if ($this->registrationType === 'perpanjangan' && $user->hasActiveCertificateForScheme($schemeId)) {
            $this->addError('schemeId', 'Sertifikat Anda untuk skema ini masih aktif dan belum bisa diperpanjang.');

            return false;
        }

        return true;
    }

    private function selectedSchemeIsAvailable(): bool
    {
        if ($this->schemeId === '') {
            return false;
        }

        $schemeIds = ($this->registrationType === 'perpanjangan'
            ? $this->getRenewalSchemes()
            : $this->getNewSchemes())
            ->pluck('id')
            ->all();

        return in_array((int) $this->schemeId, $schemeIds, true);
    }

    private function generatePaymentReference(): string
    {
        $user = Auth::user();

        if ($user->isGeneralUser()) {
            $nik = preg_replace('/\D+/', '', (string) $user->umumProfile?->no_ktp);

            return '98'.substr(str_pad($nik, 8, '0', STR_PAD_LEFT), -8).now()->format('His');
        }

        $existingCount = $user->registrations()->count();
        $nim = preg_replace('/\D+/', '', (string) $user->mahasiswaProfile?->nim);

        return '98'.$nim.($existingCount > 0 ? '-'.($existingCount + 1) : '');
    }

    /**
     * @return array<string, string>
     */
    private function documentRules(): array
    {
        $rules = [
            'frApl01' => 'required|file|mimes:pdf|max:2048',
            'frApl02' => 'required|file|mimes:pdf|max:2048',
        ];

        if ($this->useCondensedDocumentFlow) {
            return $rules;
        }

        return array_merge($rules, [
            'ktm' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'khs' => 'required|file|mimes:pdf|max:2048',
            'internshipCertificate' => 'nullable|file|mimes:pdf|max:2048',
            'ktp' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'passportPhoto' => 'required|file|mimes:jpg,jpeg,png|max:2048',
        ]);
    }

    private function syncFlowConfiguration(): void
    {
        $user = Auth::user();

        $this->useCondensedDocumentFlow = $user->hasIssuedCertificate();
        $this->shouldShowProfileStep = $user->isGeneralUser() && ! $this->useCondensedDocumentFlow;
        $this->requiresProfileCompletion = $this->shouldShowProfileStep && ! $user->hasCompletedProfile();
    }

    private function hasMatchingCertifiedSchemeForNewRegistration(): bool
    {
        if ($this->registrationType !== 'baru') {
            return false;
        }

        $user = Auth::user();

        return Certificate::query()
            ->where('user_id', $user->id)
            ->whereHas('scheme', function ($query): void {
                $query->where('is_active', true)
                    ->when($this->faculty !== '', fn ($schemeQuery) => $schemeQuery->where('faculty_id', $this->faculty))
                    ->when($this->studyProgram !== '', fn ($schemeQuery) => $schemeQuery->where('study_program_id', $this->studyProgram));
            })
            ->exists();
    }

    /**
     * @return array{ktm_path: string|null, khs_path: string|null, internship_certificate_path: string|null, ktp_path: string|null, passport_photo_path: string|null}
     */
    private function getSupportingDocumentPathsFromHistory(): array
    {
        $user = Auth::user();
        $documentFields = [
            'ktm_path',
            'khs_path',
            'internship_certificate_path',
            'ktp_path',
            'passport_photo_path',
        ];

        $paths = array_fill_keys($documentFields, null);

        // Fetch all documents from past registrations
        $pastDocuments = RegistrationDocument::query()
            ->whereHas('registration', fn ($q) => $q->where('user_id', $user->id))
            ->whereIn('document_type', $documentFields)
            ->latest('id')
            ->get();

        foreach ($documentFields as $field) {
            $paths[$field] = $pastDocuments->firstWhere('document_type', $field)?->file_path;
        }

        return $paths;
    }

    private function resolveFacultyFilterValue(?string $facultyName): string
    {
        if (blank($facultyName)) {
            return '';
        }

        return (string) (Faculty::query()
            ->where('name', $facultyName)
            ->value('id') ?? '');
    }

    private function resolveStudyProgramFilterValue(?string $studyProgramName, string $facultyId = ''): string
    {
        if (blank($studyProgramName)) {
            return '';
        }

        return (string) (StudyProgram::query()
            ->when($facultyId !== '', fn ($query) => $query->where('faculty_id', $facultyId))
            ->where('nama', $studyProgramName)
            ->value('id') ?? '');
    }
}
