<?php

namespace App\Livewire\Admin;

use App\Models\Registration;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class JadwalUji extends Component
{
    public ?int $scheduleRegistrationId = null;

    public ?int $deleteRegistrationId = null;

    public string $examDate = '';

    public string $examLocation = '';

    public string $assessorName = '';

    public string $search = '';

    public string $filterDate = '';

    public ?int $highlight = null;

    protected $queryString = ['search', 'filterDate', 'highlight'];

    public function openScheduleModal(int $registrationId): void
    {
        $registration = Registration::query()->findOrFail($registrationId);

        if (! in_array($registration->status, ['dokumen_ok', 'terjadwal'], true)) {
            return;
        }

        $this->scheduleRegistrationId = $registration->id;
        $this->highlight = $registration->id;
        $this->examDate = $registration->exam_date?->format('Y-m-d\TH:i') ?? '';
        $this->examLocation = $registration->exam_location ?? '';
        $this->assessorName = $registration->assessor_name ?? '';

        $this->dispatch('open-modal', id: 'modal-jadwal');
    }

    public function saveSchedule(): void
    {
        $validated = $this->validate([
            'scheduleRegistrationId' => 'required|integer|exists:registrations,id',
            'examDate' => 'required|date',
            'examLocation' => 'required|string|max:255',
            'assessorName' => 'required|string|max:255',
        ]);

        $registration = Registration::query()->findOrFail($validated['scheduleRegistrationId']);

        if (! in_array($registration->status, ['dokumen_ok', 'terjadwal'], true)) {
            return;
        }

        $registration->update([
            'exam_date' => $validated['examDate'],
            'exam_location' => $validated['examLocation'],
            'assessor_name' => $validated['assessorName'],
            'status' => 'terjadwal',
        ]);

        $this->resetScheduleForm();
        $this->dispatch('toast', ['message' => 'Jadwal uji berhasil disimpan.', 'type' => 'success']);
        $this->dispatch('close-modal', id: 'modal-jadwal');
    }

    public function confirmDelete(int $registrationId): void
    {
        $this->deleteRegistrationId = $registrationId;

        $this->dispatch('open-modal', id: 'modal-hapus-jadwal');
    }

    public function deleteSchedule(): void
    {
        if (! $this->deleteRegistrationId) {
            return;
        }

        $registration = Registration::query()->findOrFail($this->deleteRegistrationId);

        if ($registration->status !== 'terjadwal') {
            return;
        }

        $registration->update([
            'exam_date' => null,
            'exam_location' => null,
            'assessor_name' => null,
            'status' => 'dokumen_ok',
        ]);

        $this->deleteRegistrationId = null;
        $this->dispatch('toast', ['message' => 'Jadwal uji berhasil dihapus.', 'type' => 'success']);
        $this->dispatch('close-modal', id: 'modal-hapus-jadwal');
    }

    public function render()
    {
        return view('livewire.admin.jadwal-uji', [
            'eligibleRegistrations' => $this->eligibleRegistrations(),
            'scheduledRegistrations' => $this->scheduledRegistrations(),
            'selectedScheduleRegistration' => $this->scheduleRegistration(),
            'selectedDeleteRegistration' => $this->deleteRegistration(),
        ]);
    }

    /**
     * @return Collection<int, Registration>
     */
    private function eligibleRegistrations(): Collection
    {
        return $this->registrationsQuery()
            ->where('status', 'dokumen_ok')
            ->latest()
            ->get();
    }

    /**
     * @return Collection<int, Registration>
     */
    private function scheduledRegistrations(): Collection
    {
        return $this->registrationsQuery()
            ->where('status', 'terjadwal')
            ->when($this->filterDate !== '', function (Builder $query): void {
                $query->whereDate('exam_date', $this->filterDate);
            })
            ->orderBy('exam_date')
            ->get();
    }

    private function scheduleRegistration(): ?Registration
    {
        if (! $this->scheduleRegistrationId) {
            return null;
        }

        return Registration::query()
            ->with(['user', 'scheme'])
            ->find($this->scheduleRegistrationId);
    }

    private function deleteRegistration(): ?Registration
    {
        if (! $this->deleteRegistrationId) {
            return null;
        }

        return Registration::query()
            ->with(['user', 'scheme'])
            ->find($this->deleteRegistrationId);
    }

    private function resetScheduleForm(): void
    {
        $this->scheduleRegistrationId = null;
        $this->examDate = '';
        $this->examLocation = '';
        $this->assessorName = '';
    }

    private function registrationsQuery(): Builder
    {
        return Registration::query()
            ->with(['user', 'scheme'])
            ->when($this->search !== '', function (Builder $query): void {
                $query->whereHas('user', function (Builder $userQuery): void {
                    $userQuery
                        ->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('nim', 'like', '%'.$this->search.'%');
                });
            });
    }
}
