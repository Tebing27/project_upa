<?php

namespace App\Livewire\Admin;

use App\Models\Certificate;
use App\Models\Registration;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
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

    public string $score = '';

    public ?TemporaryUploadedFile $certificateFile = null;

    public ?TemporaryUploadedFile $resultFile = null;

    protected $queryString = ['search', 'filterDate', 'highlight'];

    public function openUploadModal(int $registrationId): void
    {
        $registration = Registration::query()->with(['user', 'scheme'])->findOrFail($registrationId);

        if ($registration->status !== 'terjadwal') {
            return;
        }

        $this->uploadRegistrationId = $registration->id;
        $this->highlight = $registration->id;
        $this->score = $registration->score !== null ? (string) $registration->score : '';
        $this->certificateFile = null;
        $this->resultFile = null;

        $this->dispatch('open-modal', id: 'modal-upload-sertifikat');
    }

    public function uploadParticipantFiles(): void
    {
        $validated = $this->validate([
            'uploadRegistrationId' => 'required|integer|exists:registrations,id',
            'score' => 'required|integer|min:0|max:100',
            'certificateFile' => 'required|file|mimes:pdf|max:4096',
            'resultFile' => 'required|file|mimes:pdf|max:4096',
        ]);

        $registration = Registration::query()
            ->with(['scheme'])
            ->findOrFail($validated['uploadRegistrationId']);

        if ($registration->status !== 'terjadwal') {
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
            'score' => (int) $validated['score'],
            'status' => 'sertifikat_terbit',
        ]);

        $this->resetUploadForm();
        $this->dispatch('toast', ['message' => 'Sertifikat dan hasil ujian berhasil diunggah.', 'type' => 'success']);
        $this->dispatch('close-modal', id: 'modal-upload-sertifikat');
    }

    public function render()
    {
        return view('livewire.admin.upload-hasil-uji', [
            'scheduledRegistrations' => $this->scheduledRegistrations(),
            'selectedUploadRegistration' => $this->uploadRegistration(),
        ]);
    }

    /**
     * @return Collection<int, Registration>
     */
    private function scheduledRegistrations(): Collection
    {
        return Registration::query()
            ->with(['user', 'scheme'])
            ->where('status', 'terjadwal')
            ->when($this->search !== '', function (Builder $query): void {
                $query->whereHas('user', function (Builder $userQuery): void {
                    $userQuery
                        ->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('nim', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->filterDate !== '', function (Builder $query): void {
                $query->whereDate('exam_date', $this->filterDate);
            })
            ->orderBy('exam_date')
            ->get();
    }

    private function uploadRegistration(): ?Registration
    {
        if (! $this->uploadRegistrationId) {
            return null;
        }

        return Registration::query()
            ->with(['user', 'scheme'])
            ->find($this->uploadRegistrationId);
    }

    private function resetUploadForm(): void
    {
        $this->uploadRegistrationId = null;
        $this->score = '';
        $this->certificateFile = null;
        $this->resultFile = null;
    }
}
