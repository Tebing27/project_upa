<?php

namespace App\Livewire\Admin;

use App\Models\Registration;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Component;

class JadwalUji extends Component
{
    public ?int $scheduleRegistrationId = null;

    public ?int $deleteRegistrationId = null;

    public string $examDate = '';

    public string $examTime = '';

    public string $examLocation = '';

    public ?int $assessorId = null;

    public string $search = '';

    public string $filterDate = '';

    public ?int $highlight = null;

    protected $queryString = ['search', 'filterDate', 'highlight'];

    public function openScheduleModal(int $registrationId): void
    {
        $registration = Registration::query()->findOrFail($registrationId);

        if (! in_array($registration->status, [Registration::STATUS_PAID, Registration::STATUS_SCHEDULED], true)) {
            return;
        }

        $this->scheduleRegistrationId = $registration->id;
        $this->highlight = $registration->id;
        $this->examDate = $registration->exam_date?->format('Y-m-d') ?? '';
        $this->examTime = $registration->exam_date?->format('H:i') ?? '';
        $this->examLocation = $registration->exam_location ?? '';
        $this->assessorId = $registration->assessor_id ?? null;

        $this->dispatch('open-modal', id: 'modal-jadwal');
    }

    public function saveSchedule(): void
    {
        $validated = $this->validate([
            'scheduleRegistrationId' => 'required|integer|exists:registrations,id',
            'examDate' => 'required|date',
            'examTime' => 'required|date_format:H:i',
            'examLocation' => 'required|string|max:255',
            'assessorId' => 'required|exists:users,id',
        ]);

        $registration = Registration::query()->findOrFail($validated['scheduleRegistrationId']);

        if (! in_array($registration->status, [Registration::STATUS_PAID, Registration::STATUS_SCHEDULED], true)) {
            return;
        }

        $examDateTime = Carbon::createFromFormat('Y-m-d H:i', $validated['examDate'].' '.$validated['examTime']);

        $registration->update([
            'exam_date' => $examDateTime,
            'exam_location' => $validated['examLocation'],
            'assessor_id' => $validated['assessorId'],
            'status' => Registration::STATUS_SCHEDULED,
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

        if ($registration->status !== Registration::STATUS_SCHEDULED) {
            return;
        }

        $registration->update([
            'exam_date' => null,
            'exam_location' => null,
            'assessor_id' => null,
            'status' => Registration::STATUS_PAID,
        ]);

        $this->deleteRegistrationId = null;
        $this->dispatch('toast', ['message' => 'Jadwal uji berhasil dihapus.', 'type' => 'success']);
        $this->dispatch('close-modal', id: 'modal-hapus-jadwal');
    }

    public function getAssessorsProperty()
    {
        return User::where('role', 'asesor')->get();
    }

    public function render(): View
    {
        $registrations = $this->scheduleRegistrations();

        return view('livewire.admin.jadwal-uji', [
            'registrations' => $registrations,
            'readyRegistrationsCount' => $registrations->where('status', Registration::STATUS_PAID)->count(),
            'scheduledRegistrationsCount' => $registrations->where('status', Registration::STATUS_SCHEDULED)->count(),
            'selectedScheduleRegistration' => $this->scheduleRegistration(),
            'selectedDeleteRegistration' => $this->deleteRegistration(),
        ]);
    }

    /**
     * @return Collection<int, Registration>
     */
    private function scheduleRegistrations(): Collection
    {
        return $this->registrationsQuery()
            ->where(function (Builder $query): void {
                $query
                    ->where('status', Registration::STATUS_PAID)
                    ->orWhere(function (Builder $scheduledQuery): void {
                        $scheduledQuery
                            ->where('status', Registration::STATUS_SCHEDULED)
                            ->when($this->filterDate !== '', function (Builder $query): void {
                                $query->whereDate('exam_date', $this->filterDate);
                            });
                    });
            })
            ->orderByRaw("case when status = '".Registration::STATUS_PAID."' then 0 else 1 end")
            ->orderBy('exam_date')
            ->latest('id')
            ->get();
    }

    private function scheduleRegistration(): ?Registration
    {
        if (! $this->scheduleRegistrationId) {
            return null;
        }

        return Registration::query()
            ->with(['user.studyProgram', 'scheme', 'assessor'])
            ->find($this->scheduleRegistrationId);
    }

    private function deleteRegistration(): ?Registration
    {
        if (! $this->deleteRegistrationId) {
            return null;
        }

        return Registration::query()
            ->with(['user.studyProgram', 'scheme', 'assessor'])
            ->find($this->deleteRegistrationId);
    }

    private function resetScheduleForm(): void
    {
        $this->scheduleRegistrationId = null;
        $this->examDate = '';
        $this->examTime = '';
        $this->examLocation = '';
        $this->assessorId = null;
    }

    private function registrationsQuery(): Builder
    {
        return Registration::query()
            ->with(['user.studyProgram', 'scheme', 'assessor'])
            ->when($this->search !== '', function (Builder $query): void {
                $query->whereHas('user', function (Builder $userQuery): void {
                    $userQuery
                        ->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('nim', 'like', '%'.$this->search.'%');
                });
            });
    }
}
