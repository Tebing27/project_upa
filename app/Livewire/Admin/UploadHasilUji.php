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

    protected $queryString = ['search', 'filterDate', 'highlight'];

    public function openUploadModal(int $registrationId): void
    {
        $registration = $this->uploadableRegistrationQuery()->findOrFail($registrationId);

        if (! in_array($registration->status, ['terjadwal', 'sertifikat_terbit'], true)) {
            return;
        }

        $this->uploadRegistrationId = $registration->id;
        $this->highlight = $registration->id;
        $this->certificateFile = null;
        $this->resultFile = null;

        $this->dispatch('open-modal', id: 'modal-upload-sertifikat');
    }

    public function confirmDelete(int $registrationId): void
    {
        $registration = $this->uploadableRegistrationQuery()->findOrFail($registrationId);
        $activeCertificate = $this->activeCertificateForRegistration($registration);

        if (! $activeCertificate) {
            return;
        }

        $this->deleteRegistrationId = $registration->id;
        $this->highlight = $registration->id;

        $this->dispatch('open-modal', id: 'modal-hapus-upload');
    }

    public function uploadParticipantFiles(): void
    {
        $validated = $this->validate([
            'uploadRegistrationId' => 'required|integer|exists:registrations,id',
            'certificateFile' => 'required|file|mimes:pdf|max:4096',
            'resultFile' => 'required|file|mimes:pdf|max:4096',
        ]);

        $registration = Registration::query()
            ->with(['scheme'])
            ->findOrFail($validated['uploadRegistrationId']);

        if (! in_array($registration->status, ['terjadwal', 'sertifikat_terbit'], true)) {
            return;
        }

        $schemeName = $registration->scheme?->name ?? 'Sertifikat Kompetensi';

        Certificate::query()
            ->where('user_id', $registration->user_id)
            ->where('scheme_name', $schemeName)
            ->where('status', 'active')
            ->update(['status' => 'inactive']);

        $certificatePath = $this->certificateFile->store('certificates', 'public');
        $resultPath = $this->resultFile->store('exam-results', 'public');

        Certificate::query()->create([
            'user_id' => $registration->user_id,
            'scheme_name' => $schemeName,
            'level' => null,
            'status' => 'active',
            'expired_date' => null,
            'file_path' => $certificatePath,
            'result_file_path' => $resultPath,
        ]);

        $registration->update([
            'status' => 'sertifikat_terbit',
        ]);

        $this->resetUploadForm();
        $this->dispatch('toast', ['message' => 'Sertifikat dan hasil ujian berhasil diunggah.', 'type' => 'success']);
        $this->dispatch('close-modal', id: 'modal-upload-sertifikat');
    }

    public function deleteUploadedFiles(): void
    {
        if (! $this->deleteRegistrationId) {
            return;
        }

        $registration = $this->uploadableRegistrationQuery()->findOrFail($this->deleteRegistrationId);
        $certificate = $this->activeCertificateForRegistration($registration);

        if (! $certificate) {
            return;
        }

        if ($certificate->file_path) {
            Storage::disk('public')->delete($certificate->file_path);
        }

        if ($certificate->result_file_path) {
            Storage::disk('public')->delete($certificate->result_file_path);
        }

        $certificate->delete();

        $registration->update([
            'status' => 'terjadwal',
        ]);

        $this->deleteRegistrationId = null;
        $this->dispatch('toast', ['message' => 'Hasil upload berhasil dihapus.', 'type' => 'success']);
        $this->dispatch('close-modal', id: 'modal-hapus-upload');
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
            ->keyBy(fn (Certificate $certificate): string => $certificate->user_id.'|'.$certificate->scheme_name);

        return $registrations->map(function (Registration $registration) use ($activeCertificates): Registration {
            $certificate = $activeCertificates->get($registration->user_id.'|'.($registration->scheme?->name ?? ''));

            $registration->active_certificate_id = $certificate?->id;
            $registration->certificate_file_url = $certificate?->file_path ? Storage::url($certificate->file_path) : null;
            $registration->result_file_url = $certificate?->result_file_path ? Storage::url($certificate->result_file_path) : null;

            return $registration;
        });
    }

    private function uploadableRegistrationQuery(): Builder
    {
        return Registration::query()
            ->with(['user', 'scheme'])
            ->whereIn('status', ['terjadwal', 'sertifikat_terbit'])
            ->when($this->search !== '', function (Builder $query): void {
                $query->whereHas('user', function (Builder $userQuery): void {
                    $userQuery
                        ->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('nim', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->filterDate !== '', function (Builder $query): void {
                $query->whereDate('exam_date', $this->filterDate);
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
        $this->certificateFile = null;
        $this->resultFile = null;
    }

    private function activeCertificateForRegistration(Registration $registration): ?Certificate
    {
        return Certificate::query()
            ->where('user_id', $registration->user_id)
            ->where('scheme_name', $registration->scheme?->name ?? '')
            ->where('status', 'active')
            ->latest('id')
            ->first();
    }
}
