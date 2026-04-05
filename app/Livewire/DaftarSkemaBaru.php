<?php

namespace App\Livewire;

use App\Concerns\ProfileValidationRules;
use App\Models\Certificate;
use App\Models\Registration;
use App\Models\Scheme;
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

        $this->faculty = $user->fakultas ?? '';
        $this->studyProgram = $user->program_studi ?? '';

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
            $this->faculty = $selectedScheme->faculty ?? '';
            $this->studyProgram = $selectedScheme->study_program ?? '';
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

        $user->registrations()->create([
            'scheme_id' => $schemeId,
            'type' => $this->registrationType,
            'fr_apl_01_path' => $frApl01Path,
            'fr_apl_02_path' => $frApl02Path,
            'ktm_path' => $ktmPath,
            'khs_path' => $khsPath,
            'internship_certificate_path' => $internshipPath,
            'ktp_path' => $ktpPath,
            'passport_photo_path' => $passportPhotoPath,
            'payment_reference' => $paymentReference,
            'va_number' => null,
            'document_statuses' => $this->useCondensedDocumentFlow ? ['_meta' => ['condensed_flow' => true]] : null,
            'status' => 'menunggu_verifikasi',
        ]);

        $this->submittedRegistration = $user->registrations()->latest()->with('scheme')->first();
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
            ->when($this->faculty !== '', fn ($query) => $query->where('faculty', $this->faculty))
            ->when($this->studyProgram !== '', fn ($query) => $query->where('study_program', $this->studyProgram))
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
        return Scheme::query()
            ->where('is_active', true)
            ->whereNotNull('faculty')
            ->distinct()
            ->pluck('faculty')
            ->sort()
            ->values();
    }

    public function getStudyPrograms(): \Illuminate\Support\Collection
    {
        return Scheme::query()
            ->where('is_active', true)
            ->where('faculty', $this->faculty)
            ->whereNotNull('study_program')
            ->distinct()
            ->pluck('study_program')
            ->sort()
            ->values();
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

        $this->name = $user->name;
        $this->email = $user->email;
        $this->nim = $user->nim;
        $this->no_ktp = $user->no_ktp;
        $this->tempat_lahir = $user->tempat_lahir;
        $this->tanggal_lahir = $user->tanggal_lahir
            ? Carbon::parse($user->tanggal_lahir)->format('Y-m-d')
            : null;
        $this->jenis_kelamin = $user->jenis_kelamin;
        $this->alamat_rumah = $user->alamat_rumah;
        $this->domisili_provinsi = $user->domisili_provinsi;
        $this->domisili_kota = $user->domisili_kota;
        $this->domisili_kecamatan = $user->domisili_kecamatan;
        $this->no_wa = $user->no_wa;
        $this->pendidikan_terakhir = $user->pendidikan_terakhir;
        $this->nama_institusi = $user->nama_institusi;
        $this->total_sks = $user->total_sks;
        $this->status_semester = $user->status_semester;
        $this->fakultas = $user->fakultas;
        $this->program_studi = $user->program_studi;
        $this->faculty = $user->fakultas ?? '';
        $this->studyProgram = $user->program_studi ?? '';
        $this->pekerjaan = $user->pekerjaan;
        $this->nama_perusahaan = $user->nama_perusahaan;
        $this->jabatan = $user->jabatan;
        $this->alamat_perusahaan = $user->alamat_perusahaan;
        $this->kode_pos_perusahaan = $user->kode_pos_perusahaan;
        $this->no_telp_perusahaan = $user->no_telp_perusahaan;
        $this->email_perusahaan = $user->email_perusahaan;
    }

    private function validateProfileStep(): array
    {
        $user = Auth::user();

        foreach ([
            'no_ktp', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'alamat_rumah',
            'domisili_provinsi', 'domisili_kota', 'domisili_kecamatan', 'no_wa',
            'pendidikan_terakhir', 'nama_institusi', 'fakultas', 'program_studi', 'pekerjaan',
            'nama_perusahaan', 'jabatan', 'alamat_perusahaan', 'kode_pos_perusahaan',
            'no_telp_perusahaan', 'email_perusahaan',
        ] as $field) {
            $this->{$field} = filled($this->{$field}) ? trim((string) $this->{$field}) : null;
        }

        return $this->validate($this->profileRules($user->id, $user->user_type));
    }

    private function saveProfileStep(): void
    {
        $user = Auth::user();

        $validated = $this->validateProfileStep();

        $user->fill($validated);
        $user->syncProfileCompletionStatus();
        $user->save();

        $this->faculty = $user->fakultas ?? '';
        $this->studyProgram = $user->program_studi ?? '';
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
            $nik = preg_replace('/\D+/', '', (string) $user->no_ktp);

            return '98'.substr(str_pad($nik, 8, '0', STR_PAD_LEFT), -8).now()->format('His');
        }

        $existingCount = $user->registrations()->count();
        $nim = preg_replace('/\D+/', '', (string) $user->nim);

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
                    ->when($this->faculty !== '', fn ($schemeQuery) => $schemeQuery->where('faculty', $this->faculty))
                    ->when($this->studyProgram !== '', fn ($schemeQuery) => $schemeQuery->where('study_program', $this->studyProgram));
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

        $registrations = $user->registrations()
            ->latest('id')
            ->get($documentFields);

        foreach ($documentFields as $field) {
            $paths[$field] = $registrations->first(fn (Registration $registration): bool => filled($registration->{$field}))?->{$field};
        }

        return $paths;
    }
}
