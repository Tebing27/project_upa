<?php

namespace App\Livewire\Admin;

use App\Models\AppSetting;
use App\Models\Registration;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Component;

class JadwalUji extends Component
{
    private const WHATSAPP_CHANNEL_SETTING_KEY = 'whatsapp_channel_link';

    public ?int $scheduleRegistrationId = null;

    public ?int $deleteRegistrationId = null;

    public string $examDate = '';

    public string $examTime = '';

    public string $examLocation = '';

    public string $assessorName = '';

    public string $whatsappLink = '';

    public ?int $editingWhatsappSettingId = null;

    public string $search = '';

    public string $filterDate = '';

    public ?int $highlight = null;

    protected $queryString = ['search', 'filterDate', 'highlight'];

    public function openScheduleModal(int $registrationId): void
    {
        $registration = Registration::query()->findOrFail($registrationId);

        if (! in_array($registration->status, ['paid', 'terjadwal'], true)) {
            return;
        }

        $this->scheduleRegistrationId = $registration->id;
        $this->highlight = $registration->id;
        $this->examDate = $registration->exam_date?->format('Y-m-d') ?? '';
        $this->examTime = $registration->exam_date?->format('H:i') ?? '';
        $this->examLocation = $registration->exam_location ?? '';
        $this->assessorName = $registration->assessor_name ?? '';

        $this->dispatch('open-modal', id: 'modal-jadwal');
    }

    public function saveWhatsappLink(): void
    {
        $validated = $this->validate([
            'whatsappLink' => 'required|url|max:255',
        ]);

        AppSetting::query()->updateOrCreate(
            ['key' => self::WHATSAPP_CHANNEL_SETTING_KEY],
            ['value' => $validated['whatsappLink']],
        );

        $this->editingWhatsappSettingId = AppSetting::query()
            ->where('key', self::WHATSAPP_CHANNEL_SETTING_KEY)
            ->value('id');

        $this->dispatch('toast', ['message' => 'Link WhatsApp berhasil disimpan.', 'type' => 'success']);
        $this->dispatch('close-modal', id: 'modal-whatsapp-link');
    }

    public function openWhatsappLinkModal(?int $settingId = null): void
    {
        if ($settingId === null) {
            $this->resetWhatsappLinkForm();
            $this->dispatch('open-modal', id: 'modal-whatsapp-link');

            return;
        }

        $setting = AppSetting::query()->findOrFail($settingId);

        if ($setting->key !== self::WHATSAPP_CHANNEL_SETTING_KEY) {
            return;
        }

        $this->editingWhatsappSettingId = $setting->id;
        $this->whatsappLink = (string) $setting->value;
        $this->dispatch('open-modal', id: 'modal-whatsapp-link');
    }

    public function deleteWhatsappLink(int $settingId): void
    {
        $setting = AppSetting::query()->findOrFail($settingId);

        if ($setting->key !== self::WHATSAPP_CHANNEL_SETTING_KEY) {
            return;
        }

        $setting->delete();

        $this->editingWhatsappSettingId = null;
        $this->whatsappLink = '';
        $this->dispatch('toast', ['message' => 'Link WhatsApp berhasil dihapus.', 'type' => 'success']);
    }

    public function resetWhatsappLinkForm(): void
    {
        $this->editingWhatsappSettingId = null;
        $this->whatsappLink = '';
        $this->resetErrorBag('whatsappLink');
    }

    public function saveSchedule(): void
    {
        $validated = $this->validate([
            'scheduleRegistrationId' => 'required|integer|exists:registrations,id',
            'examDate' => 'required|date',
            'examTime' => 'required|date_format:H:i',
            'examLocation' => 'required|string|max:255',
            'assessorName' => 'required|string|max:255',
        ]);

        $registration = Registration::query()->findOrFail($validated['scheduleRegistrationId']);

        if (! in_array($registration->status, ['paid', 'terjadwal'], true)) {
            return;
        }

        $examDateTime = Carbon::createFromFormat('Y-m-d H:i', $validated['examDate'].' '.$validated['examTime']);

        $registration->update([
            'exam_date' => $examDateTime,
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
            'status' => 'paid',
        ]);

        $this->deleteRegistrationId = null;
        $this->dispatch('toast', ['message' => 'Jadwal uji berhasil dihapus.', 'type' => 'success']);
        $this->dispatch('close-modal', id: 'modal-hapus-jadwal');
    }

    public function render(): View
    {
        $registrations = $this->scheduleRegistrations();

        return view('livewire.admin.jadwal-uji', [
            'registrations' => $registrations,
            'readyRegistrationsCount' => $registrations->where('status', 'paid')->count(),
            'scheduledRegistrationsCount' => $registrations->where('status', 'terjadwal')->count(),
            'selectedScheduleRegistration' => $this->scheduleRegistration(),
            'selectedDeleteRegistration' => $this->deleteRegistration(),
            'globalWhatsappLink' => AppSetting::whatsappChannelLink(),
            'whatsappSettings' => AppSetting::query()
                ->where('key', self::WHATSAPP_CHANNEL_SETTING_KEY)
                ->latest('id')
                ->get(),
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
                    ->where('status', 'paid')
                    ->orWhere(function (Builder $scheduledQuery): void {
                        $scheduledQuery
                            ->where('status', 'terjadwal')
                            ->when($this->filterDate !== '', function (Builder $query): void {
                                $query->whereDate('exam_date', $this->filterDate);
                            });
                    });
            })
            ->orderByRaw("case when status = 'paid' then 0 else 1 end")
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
        $this->examTime = '';
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
                        ->orWhere('nim', 'like', '%'.$this->search.'%')
                        ->orWhere('no_ktp', 'like', '%'.$this->search.'%');
                });
            });
    }
}
