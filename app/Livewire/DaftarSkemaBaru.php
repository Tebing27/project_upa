<?php

namespace App\Livewire;

use App\Models\Certificate;
use App\Models\Registration;
use App\Models\Scheme;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class DaftarSkemaBaru extends Component
{
    use WithFileUploads;

    public int $currentStep = 1;

    #[Url(as: 'type')]
    public string $registrationType = '';

    #[Url(as: 'scheme')]
    public string $schemeId = '';

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

    public function mount(): void
    {
        $user = Auth::user();

        if ($user->hasInProgressRegistration()) {
            $this->errorMessage = 'Anda masih memiliki pendaftaran yang sedang berjalan. Selesaikan pendaftaran tersebut terlebih dahulu.';
        }
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

            $user = Auth::user();
            $schemeId = (int) $this->schemeId;

            if ($this->registrationType === 'baru') {
                if ($user->hasAnyCertificateForScheme($schemeId)) {
                    $this->addError('schemeId', 'Anda sudah memiliki riwayat sertifikat untuk skema ini. Gunakan opsi perpanjangan.');

                    return;
                }
            }

            if ($this->registrationType === 'perpanjangan') {
                if (! $user->hasAnyCertificateForScheme($schemeId)) {
                    $this->addError('schemeId', 'Anda belum pernah memiliki sertifikat untuk skema ini. Pilih opsi skema baru.');

                    return;
                }

                if ($user->hasActiveCertificateForScheme($schemeId)) {
                    $this->addError('schemeId', 'Sertifikat Anda untuk skema ini masih aktif dan belum bisa diperpanjang.');

                    return;
                }
            }

            if ($user->hasInProgressRegistrationForScheme($schemeId)) {
                $this->addError('schemeId', 'Anda sudah memiliki pendaftaran yang sedang berjalan untuk skema ini.');

                return;
            }
        }

        if ($this->currentStep === 2) {
            $this->validate([
                'frApl01' => 'required|file|mimes:pdf|max:2048',
                'frApl02' => 'required|file|mimes:pdf|max:2048',
                'ktm' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'khs' => 'required|file|mimes:pdf|max:2048',
                'internshipCertificate' => 'nullable|file|mimes:pdf|max:2048',
                'ktp' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'passportPhoto' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            ]);
        }

        $this->currentStep++;
    }

    public function previousStep(): void
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function submit(): void
    {
        if ($this->errorMessage) {
            return;
        }

        $user = Auth::user();
        $schemeId = (int) $this->schemeId;

        if ($user->hasInProgressRegistrationForScheme($schemeId)) {
            $this->addError('schemeId', 'Anda sudah memiliki pendaftaran yang sedang berjalan untuk skema ini.');

            return;
        }

        if ($this->registrationType === 'baru' && $user->hasAnyCertificateForScheme($schemeId)) {
            $this->addError('schemeId', 'Anda sudah memiliki riwayat sertifikat untuk skema ini.');

            return;
        }

        if ($this->registrationType === 'perpanjangan') {
            if (! $user->hasAnyCertificateForScheme($schemeId)) {
                $this->addError('schemeId', 'Anda belum pernah memiliki sertifikat untuk skema ini.');

                return;
            }

            if ($user->hasActiveCertificateForScheme($schemeId)) {
                $this->addError('schemeId', 'Sertifikat Anda untuk skema ini masih aktif dan belum bisa diperpanjang.');

                return;
            }
        }

        $frApl01Path = $this->frApl01->store('documents/fr_apl_01', 'public');
        $frApl02Path = $this->frApl02->store('documents/fr_apl_02', 'public');
        $ktmPath = $this->ktm->store('documents/ktm', 'public');
        $khsPath = $this->khs->store('documents/khs', 'public');
        $internshipPath = $this->internshipCertificate?->store('documents/internship', 'public');
        $ktpPath = $this->ktp->store('documents/ktp', 'public');
        $passportPhotoPath = $this->passportPhoto->store('documents/photo', 'public');

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
            'payment_reference' => null,
            'va_number' => null,
            'status' => Registration::STATUS_PENDING_VERIFICATION,
        ]);

        $this->currentStep = 4;
    }

    /**
     * Get available schemes for "skema baru" registration type.
     *
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
            ->whereHas('studyPrograms', function ($q) use ($user) {
                $q->where('study_programs.id', $user->study_program_id);
            })
            ->where('is_active', true)
            ->whereNotIn('id', $excludeIds)
            ->get();
    }

    /**
     * Get available schemes for "perpanjangan" registration type.
     *
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
            ->whereHas('studyPrograms', function ($q) use ($user) {
                $q->where('study_programs.id', $user->study_program_id);
            })
            ->where('is_active', true)
            ->whereIn('id', $renewableSchemeIds)
            ->whereNotIn('id', $inProgressSchemeIds)
            ->get();
    }

    public function render()
    {
        $newSchemes = $this->getNewSchemes();
        $renewalSchemes = $this->getRenewalSchemes();
        $selectedScheme = $this->schemeId ? Scheme::find($this->schemeId) : null;

        return view('livewire.daftar-skema-baru', [
            'newSchemes' => $newSchemes,
            'renewalSchemes' => $renewalSchemes,
            'selectedScheme' => $selectedScheme,
        ]);
    }
}
