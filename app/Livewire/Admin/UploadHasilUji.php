<?php

namespace App\Livewire\Admin;

use App\Models\AppSetting;
use App\Models\Certificate;
use App\Models\Exam;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class UploadHasilUji extends Component
{
    use WithFileUploads;

    public ?int $uploadRegistrationId = null;

    public string $search = '';

    public string $filterDate = '';

    public ?int $highlight = null;

    public ?int $deleteRegistrationId = null;

    public ?TemporaryUploadedFile $certificateFile = null;

    public ?TemporaryUploadedFile $resultFile = null;

    public ?string $expiredDate = null;

    public string $examResult = 'kompeten';

    public string $filterStatus = '';

    public string $competencyLetterSignatoryName = '';

    public ?TemporaryUploadedFile $competencyLetterSignatureFile = null;

    public ?TemporaryUploadedFile $competencyLetterStampFile = null;

    protected $queryString = ['search', 'filterDate', 'highlight', 'filterStatus'];

    public function mount(): void
    {
        $this->competencyLetterSignatoryName = AppSetting::competencyLetterSignatoryName() ?? '';
    }

    public function openUploadModal(int $registrationId): void
    {
        $registration = $this->uploadableRegistrationQuery()->findOrFail($registrationId);

        if (! in_array($registration->status, ['terjadwal', 'kompeten', 'sertifikat_terbit', 'tidak_kompeten'], true)) {
            return;
        }

        if ($registration->status === 'terjadwal' && ! $registration->hasPublishedExamSchedule()) {
            $this->dispatch('toast', ['message' => 'Peserta harus dijadwalkan lengkap terlebih dahulu sebelum upload hasil uji.', 'type' => 'error']);

            return;
        }

        $this->uploadRegistrationId = $registration->id;
        $this->highlight = $registration->id;
        $this->certificateFile = null;
        $this->resultFile = null;

        if ($registration->status === 'tidak_kompeten') {
            $this->examResult = 'belum_kompeten';
            $this->expiredDate = null;
        } else {
            $this->examResult = 'kompeten';
            $activeCertificate = $this->activeCertificateForRegistration($registration);
            $this->expiredDate = $activeCertificate?->expired_date?->format('Y-m-d');
        }

        $this->dispatch('open-modal', id: 'modal-upload-sertifikat');
    }

    public function confirmDelete(int $registrationId): void
    {
        $registration = $this->uploadableRegistrationQuery()->findOrFail($registrationId);

        if (! in_array($registration->status, ['kompeten', 'tidak_kompeten'], true)) {
            $activeCertificate = $this->activeCertificateForRegistration($registration);
            if (! $activeCertificate) {
                return;
            }
        }

        $this->deleteRegistrationId = $registration->id;
        $this->highlight = $registration->id;

        $this->dispatch('open-modal', id: 'modal-hapus-upload');
    }

    public function uploadParticipantFiles(): void
    {
        $registration = Registration::query()
            ->with(['scheme', 'exam', 'user'])
            ->findOrFail($this->uploadRegistrationId);

        if (! in_array($registration->status, ['terjadwal', 'kompeten', 'sertifikat_terbit', 'tidak_kompeten'], true)) {
            return;
        }

        if ($registration->status === 'terjadwal' && ! $registration->hasPublishedExamSchedule()) {
            $this->addError('uploadRegistrationId', 'Peserta harus dijadwalkan lengkap terlebih dahulu sebelum upload hasil uji.');

            return;
        }

        $activeCertificate = $this->activeCertificateForRegistration($registration);
        $isEditingCertificate = $activeCertificate !== null;
        $currentExamResultPath = $registration->exam?->exam_result_path;
        $hasExistingResultFile = filled($currentExamResultPath) || filled($activeCertificate?->result_file_path);

        $rules = [
            'uploadRegistrationId' => 'required|integer|exists:registrations,id',
            'examResult' => 'required|in:kompeten,belum_kompeten',
        ];

        if ($this->examResult === 'kompeten') {
            $rules['certificateFile'] = 'nullable|file|mimes:pdf|max:4096';
            $rules['resultFile'] = ($hasExistingResultFile ? 'nullable' : 'required').'|file|mimes:pdf|max:4096';
            $rules['expiredDate'] = ($isEditingCertificate || $this->certificateFile ? 'required' : 'nullable').'|date|after:today';
        } else {
            $rules['resultFile'] = ($currentExamResultPath !== null ? 'nullable' : 'required').'|file|mimes:pdf|max:4096';
        }

        $validated = $this->validate($rules);

        if ($this->examResult === 'kompeten') {
            $resultPath = $activeCertificate?->result_file_path ?? $currentExamResultPath;

            if ($this->resultFile) {
                if ($activeCertificate?->result_file_path) {
                    Storage::disk('public')->delete($activeCertificate->result_file_path);
                } elseif ($currentExamResultPath) {
                    Storage::disk('public')->delete($currentExamResultPath);
                }

                $resultPath = $this->resultFile->store('exam-results', 'public');
            }

            if ($isEditingCertificate) {
                $updateData = [
                    'expired_date' => $validated['expiredDate'],
                    'result_file_path' => $resultPath,
                ];

                if ($this->certificateFile) {
                    if ($activeCertificate->file_path) {
                        Storage::disk('public')->delete($activeCertificate->file_path);
                    }

                    $updateData['file_path'] = $this->certificateFile->store('certificates', 'public');
                }

                $activeCertificate->update($updateData);

                if ($registration->exam) {
                    $registration->exam->update(['exam_result_path' => null]);
                }

                $registration->update(['status' => 'sertifikat_terbit']);
            } elseif ($this->certificateFile) {
                $certificatePath = $this->certificateFile->store('certificates', 'public');

                Certificate::query()->create([
                    'user_id' => $registration->user_id,
                    'scheme_id' => $registration->scheme_id,
                    'certificate_number' => $this->generateCertificateNumber($registration->user),
                    'level' => null,
                    'status' => 'active',
                    'expired_date' => $validated['expiredDate'],
                    'file_path' => $certificatePath,
                    'result_file_path' => $resultPath,
                ]);

                if ($registration->exam) {
                    $registration->exam->update(['exam_result_path' => null]);
                }

                $registration->update(['status' => 'sertifikat_terbit']);
            } else {
                if ($registration->exam) {
                    $registration->exam->update(['exam_result_path' => $resultPath]);
                } else {
                    Exam::query()->create([
                        'registration_id' => $registration->id,
                        'exam_result_path' => $resultPath,
                    ]);
                }

                $registration->update(['status' => 'kompeten']);
            }
        } else {
            $resultPath = $currentExamResultPath;

            if ($this->resultFile) {
                if ($resultPath && $resultPath !== $activeCertificate?->result_file_path) {
                    Storage::disk('public')->delete($resultPath);
                }

                $resultPath = $this->resultFile->store('exam-results', 'public');
            }

            if ($isEditingCertificate) {
                $activeCertificate->update(['status' => 'inactive']);
            }

            if ($registration->exam) {
                $registration->exam->update(['exam_result_path' => $resultPath]);
            } else {
                Exam::query()->create([
                    'registration_id' => $registration->id,
                    'exam_result_path' => $resultPath,
                ]);
            }

            $registration->update(['status' => 'tidak_kompeten']);
        }

        $this->resetUploadForm();
        $this->dispatch('toast', ['message' => 'Hasil ujian berhasil disimpan.', 'type' => 'success']);
        $this->dispatch('close-modal', id: 'modal-upload-sertifikat');
    }

    public function deleteUploadedFiles(): void
    {
        if (! $this->deleteRegistrationId) {
            return;
        }

        $registration = $this->uploadableRegistrationQuery()->findOrFail($this->deleteRegistrationId);

        if (in_array($registration->status, ['kompeten', 'tidak_kompeten'], true)) {
            $examResultPath = $registration->exam?->exam_result_path;
            if ($examResultPath) {
                Storage::disk('public')->delete($examResultPath);
            }

            if ($registration->exam) {
                $registration->exam->update(['exam_result_path' => null]);
            }

            $registration->update(['status' => 'terjadwal']);
        } else {
            $certificate = $this->activeCertificateForRegistration($registration);

            if ($certificate) {
                if ($certificate->file_path) {
                    Storage::disk('public')->delete($certificate->file_path);
                }

                if ($certificate->result_file_path) {
                    Storage::disk('public')->delete($certificate->result_file_path);
                }

                $certificate->delete();
            }

            $registration->update(['status' => 'terjadwal']);
        }

        $this->deleteRegistrationId = null;
        $this->dispatch('toast', ['message' => 'Hasil upload berhasil dihapus.', 'type' => 'success']);
        $this->dispatch('close-modal', id: 'modal-hapus-upload');
    }

    public function saveCompetencyLetterSettings(): void
    {
        $rules = [
            'competencyLetterSignatoryName' => 'required|string|max:255',
        ];

        if (! AppSetting::competencyLetterSignaturePath()) {
            $rules['competencyLetterSignatureFile'] = 'required|file|mimes:png,jpg,jpeg|max:2048';
        } elseif ($this->competencyLetterSignatureFile) {
            $rules['competencyLetterSignatureFile'] = 'file|mimes:png,jpg,jpeg|max:2048';
        }

        if (! AppSetting::competencyLetterStampPath()) {
            $rules['competencyLetterStampFile'] = 'required|file|mimes:png,jpg,jpeg|max:2048';
        } elseif ($this->competencyLetterStampFile) {
            $rules['competencyLetterStampFile'] = 'file|mimes:png,jpg,jpeg|max:2048';
        }

        $this->validate($rules);

        $signaturePath = AppSetting::competencyLetterSignaturePath();
        $stampPath = AppSetting::competencyLetterStampPath();

        if ($this->competencyLetterSignatureFile) {
            if ($signaturePath) {
                Storage::disk('public')->delete($signaturePath);
            }

            $signaturePath = $this->competencyLetterSignatureFile->store('documents/competency-letter/signatures', 'public');
            AppSetting::put('competency_letter_signature_path', $signaturePath);
        }

        if ($this->competencyLetterStampFile) {
            if ($stampPath) {
                Storage::disk('public')->delete($stampPath);
            }

            $stampPath = $this->competencyLetterStampFile->store('documents/competency-letter/stamps', 'public');
            AppSetting::put('competency_letter_stamp_path', $stampPath);
        }

        AppSetting::put('competency_letter_signatory_name', $this->competencyLetterSignatoryName);

        $this->reset('competencyLetterSignatureFile', 'competencyLetterStampFile');

        $this->dispatch('toast', ['message' => 'Pengaturan surat keterangan berhasil disimpan.', 'type' => 'success']);
    }

    public function deleteCompetencyLetterSettings(): void
    {
        $signaturePath = AppSetting::competencyLetterSignaturePath();
        $stampPath = AppSetting::competencyLetterStampPath();

        if ($signaturePath) {
            Storage::disk('public')->delete($signaturePath);
        }

        if ($stampPath) {
            Storage::disk('public')->delete($stampPath);
        }

        AppSetting::query()->whereIn('key', [
            'competency_letter_signatory_name',
            'competency_letter_signature_path',
            'competency_letter_stamp_path',
        ])->delete();

        $this->competencyLetterSignatoryName = '';
        $this->reset('competencyLetterSignatureFile', 'competencyLetterStampFile');

        $this->dispatch('toast', ['message' => 'Pengaturan surat keterangan berhasil dihapus.', 'type' => 'success']);
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'filterStatus', 'filterDate']);
    }

    public function render(): View
    {
        $uploadableRegistrations = $this->uploadableRegistrations();

        return view('livewire.admin.upload-hasil-uji', [
            'uploadableRegistrations' => $uploadableRegistrations,
            'selectedUploadRegistration' => $this->uploadRegistration(),
            'selectedDeleteRegistration' => $uploadableRegistrations->firstWhere('id', $this->deleteRegistrationId),
            'competencyLetterSignaturePath' => AppSetting::competencyLetterSignaturePath(),
            'competencyLetterStampPath' => AppSetting::competencyLetterStampPath(),
            'competencyLetterSignatoryName' => AppSetting::competencyLetterSignatoryName(),
        ]);
    }

    /**
     * @return Collection<int, Registration>
     */
    private function uploadableRegistrations(): Collection
    {
        $registrations = $this->uploadableRegistrationQuery()
            ->orderByRaw("case when status = 'terjadwal' then 0 else 1 end")
            ->orderBy(
                Exam::query()->select('exam_date')
                    ->whereColumn('registration_id', 'registrations.id')
                    ->limit(1)
            )
            ->get();

        $activeCertificates = Certificate::query()
            ->where('status', 'active')
            ->whereIn('user_id', $registrations->pluck('user_id')->unique()->all())
            ->get()
            ->keyBy(fn (Certificate $certificate): string => $certificate->user_id.'|'.$certificate->scheme_id);

        return $registrations->map(function (Registration $registration) use ($activeCertificates): Registration {
            $certificate = $activeCertificates->get($registration->user_id.'|'.$registration->scheme_id);
            $examResultPath = $registration->exam?->exam_result_path;

            $registration->active_certificate_id = $certificate?->id;
            $registration->certificate_file_url = $certificate?->file_path ? Storage::url($certificate->file_path) : null;
            $registration->result_file_url = $certificate?->result_file_path
                ? Storage::url($certificate->result_file_path)
                : ($examResultPath ? Storage::url($examResultPath) : null);

            return $registration;
        });
    }

    private function uploadableRegistrationQuery(): Builder
    {
        return Registration::query()
            ->with(['user.mahasiswaProfile', 'user.umumProfile', 'scheme', 'exam'])
            ->whereIn('status', ['terjadwal', 'kompeten', 'sertifikat_terbit', 'tidak_kompeten'])
            ->where(function (Builder $query): void {
                $query->where('status', '!=', 'sertifikat_terbit')
                    ->orWhereNotExists(function (\Illuminate\Database\Query\Builder $sub): void {
                        $sub->selectRaw(1)
                            ->from('registrations as r2')
                            ->whereColumn('r2.user_id', 'registrations.user_id')
                            ->whereColumn('r2.scheme_id', 'registrations.scheme_id')
                            ->whereColumn('r2.id', '>', 'registrations.id')
                            ->where('r2.type', 'perpanjangan')
                            ->whereIn('r2.status', ['terjadwal', 'kompeten', 'sertifikat_terbit', 'tidak_kompeten']);
                    });
            })
            ->when($this->search !== '', function (Builder $query): void {
                $query->whereHas('user', function (Builder $userQuery): void {
                    $userQuery->where('nama', 'like', '%'.$this->search.'%')
                        ->orWhereHas('mahasiswaProfile', fn (Builder $q) => $q->where('nim', 'like', '%'.$this->search.'%'))
                        ->orWhereHas('umumProfile', fn (Builder $q) => $q->where('no_ktp', 'like', '%'.$this->search.'%'));
                });
            })
            ->when($this->filterDate !== '', function (Builder $query): void {
                $query->whereHas('exam', fn (Builder $q) => $q->whereDate('exam_date', $this->filterDate));
            })
            ->when($this->filterStatus !== '', function (Builder $query): void {
                if ($this->filterStatus === 'kompeten') {
                    $query->whereIn('status', ['kompeten', 'sertifikat_terbit']);
                } elseif ($this->filterStatus === 'belum_kompeten') {
                    $query->where('status', 'tidak_kompeten');
                } elseif ($this->filterStatus === 'belum_upload') {
                    $query->where('status', 'terjadwal');
                }
            });
    }

    private function uploadRegistration(): ?Registration
    {
        if (! $this->uploadRegistrationId) {
            return null;
        }

        return $this->uploadableRegistrations()->firstWhere('id', $this->uploadRegistrationId);
    }

    private function resetUploadForm(): void
    {
        $this->uploadRegistrationId = null;
        $this->deleteRegistrationId = null;
        $this->expiredDate = null;
        $this->certificateFile = null;
        $this->resultFile = null;
        $this->examResult = 'kompeten';
    }

    private function activeCertificateForRegistration(Registration $registration): ?Certificate
    {
        return Certificate::query()
            ->where('user_id', $registration->user_id)
            ->where('scheme_id', $registration->scheme_id)
            ->where('status', 'active')
            ->latest('id')
            ->first();
    }

    private function generateCertificateNumber(User $user): string
    {
        if (filled($user->nim)) {
            return 'CERT-'.$user->nim;
        }

        $nik = preg_replace('/\D+/', '', (string) $user->no_ktp);

        return 'CERT-'.substr(str_pad($nik, 12, '0', STR_PAD_LEFT), -12);
    }
}
