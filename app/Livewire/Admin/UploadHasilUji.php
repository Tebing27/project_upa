<?php

namespace App\Livewire\Admin;

use App\Models\Certificate;
use App\Models\Registration;
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

    protected $queryString = ['search', 'filterDate', 'highlight', 'filterStatus'];

    public function openUploadModal(int $registrationId): void
    {
        $registration = $this->uploadableRegistrationQuery()->findOrFail($registrationId);

        if (! in_array($registration->status, ['terjadwal', 'sertifikat_terbit', 'tidak_kompeten'], true)) {
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

        if ($registration->status !== 'tidak_kompeten') {
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
            ->with(['scheme'])
            ->findOrFail($this->uploadRegistrationId);

        if (! in_array($registration->status, ['terjadwal', 'sertifikat_terbit', 'tidak_kompeten'], true)) {
            return;
        }

        if ($registration->status === 'terjadwal' && ! $registration->hasPublishedExamSchedule()) {
            $this->addError('uploadRegistrationId', 'Peserta harus dijadwalkan lengkap terlebih dahulu sebelum upload hasil uji.');

            return;
        }

        $activeCertificate = $this->activeCertificateForRegistration($registration);
        $isEditingCertificate = $activeCertificate !== null;
        $isEditingResultOnly = $registration->exam_result_path !== null;

        $rules = [
            'uploadRegistrationId' => 'required|integer|exists:registrations,id',
            'examResult' => 'required|in:kompeten,belum_kompeten',
        ];

        if ($this->examResult === 'kompeten') {
            $rules['expiredDate'] = 'required|date|after:today';
            $rules['certificateFile'] = ($isEditingCertificate ? 'nullable' : 'required').'|file|mimes:pdf|max:4096';
            $rules['resultFile'] = ($isEditingCertificate ? 'nullable' : 'required').'|file|mimes:pdf|max:4096';
        } else {
            $rules['resultFile'] = ($isEditingResultOnly ? 'nullable' : 'required').'|file|mimes:pdf|max:4096';
        }

        $validated = $this->validate($rules);

        $schemeName = $registration->scheme?->name ?? 'Sertifikat Kompetensi';

        if ($this->examResult === 'kompeten') {
            if ($isEditingCertificate) {
                $updateData = ['expired_date' => $validated['expiredDate']];

                if ($this->certificateFile) {
                    if ($activeCertificate->file_path) {
                        Storage::disk('public')->delete($activeCertificate->file_path);
                    }
                    $updateData['file_path'] = $this->certificateFile->store('certificates', 'public');
                }

                if ($this->resultFile) {
                    if ($activeCertificate->result_file_path) {
                        Storage::disk('public')->delete($activeCertificate->result_file_path);
                    }
                    $updateData['result_file_path'] = $this->resultFile->store('exam-results', 'public');
                }

                $activeCertificate->update($updateData);
            } else {
                $certificatePath = $this->certificateFile->store('certificates', 'public');
                $resultPath = $this->resultFile->store('exam-results', 'public');

                Certificate::query()->create([
                    'user_id' => $registration->user_id,
                    'scheme_id' => $registration->scheme_id,
                    'scheme_name' => $schemeName,
                    'level' => null,
                    'status' => 'active',
                    'expired_date' => $validated['expiredDate'],
                    'file_path' => $certificatePath,
                    'result_file_path' => $resultPath,
                ]);
            }

            $registration->update([
                'status' => 'sertifikat_terbit',
                'exam_result_path' => null,
            ]);
        } else {
            $resultPath = $registration->exam_result_path;

            if ($this->resultFile) {
                if ($resultPath && $resultPath !== $activeCertificate?->result_file_path) {
                    Storage::disk('public')->delete($resultPath);
                }
                $resultPath = $this->resultFile->store('exam-results', 'public');
            }

            if ($isEditingCertificate) {
                $activeCertificate->update(['status' => 'inactive']);
            }

            $registration->update([
                'status' => 'tidak_kompeten',
                'exam_result_path' => $resultPath,
            ]);
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

        if ($registration->status === 'tidak_kompeten') {
            if ($registration->exam_result_path) {
                Storage::disk('public')->delete($registration->exam_result_path);
            }
            $registration->update([
                'status' => 'terjadwal',
                'exam_result_path' => null,
            ]);
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

            $registration->update([
                'status' => 'terjadwal',
            ]);
        }

        $this->deleteRegistrationId = null;
        $this->dispatch('toast', ['message' => 'Hasil upload berhasil dihapus.', 'type' => 'success']);
        $this->dispatch('close-modal', id: 'modal-hapus-upload');
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
        ]);
    }

    /**
     * @return Collection<int, Registration>
     */
    private function uploadableRegistrations(): Collection
    {
        $registrations = $this->uploadableRegistrationQuery()
            ->orderByRaw("case when status = 'terjadwal' then 0 else 1 end")
            ->orderBy('exam_date')
            ->get();

        $activeCertificates = Certificate::query()
            ->where('status', 'active')
            ->whereIn('user_id', $registrations->pluck('user_id')->unique()->all())
            ->get()
            ->keyBy(fn (Certificate $certificate): string => $certificate->user_id.'|'.$certificate->scheme_id);

        return $registrations->map(function (Registration $registration) use ($activeCertificates): Registration {
            $certificate = $activeCertificates->get($registration->user_id.'|'.$registration->scheme_id);

            $registration->active_certificate_id = $certificate?->id;
            $registration->certificate_file_url = $certificate?->file_path ? Storage::url($certificate->file_path) : null;
            $registration->result_file_url = $certificate?->result_file_path ? Storage::url($certificate->result_file_path) : ($registration->exam_result_path ? Storage::url($registration->exam_result_path) : null);

            return $registration;
        });
    }

    private function uploadableRegistrationQuery(): Builder
    {
        return Registration::query()
            ->with(['user', 'scheme'])
            ->whereIn('status', ['terjadwal', 'sertifikat_terbit', 'tidak_kompeten'])
            ->where(function (Builder $query): void {
                $query->where('status', '!=', 'sertifikat_terbit')
                    ->orWhereNotExists(function (\Illuminate\Database\Query\Builder $sub): void {
                        $sub->selectRaw(1)
                            ->from('registrations as r2')
                            ->whereColumn('r2.user_id', 'registrations.user_id')
                            ->whereColumn('r2.scheme_id', 'registrations.scheme_id')
                            ->whereColumn('r2.id', '>', 'registrations.id')
                            ->where('r2.type', 'perpanjangan')
                            ->whereIn('r2.status', ['terjadwal', 'sertifikat_terbit', 'tidak_kompeten']);
                    });
            })
            ->when($this->search !== '', function (Builder $query): void {
                $query->whereHas('user', function (Builder $userQuery): void {
                    $userQuery
                        ->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('nim', 'like', '%'.$this->search.'%')
                        ->orWhere('no_ktp', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->filterDate !== '', function (Builder $query): void {
                $query->whereDate('exam_date', $this->filterDate);
            })
            ->when($this->filterStatus !== '', function (Builder $query): void {
                if ($this->filterStatus === 'kompeten') {
                    $query->where('status', 'sertifikat_terbit');
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
}
