<?php

namespace App\Livewire\Admin;

use App\Models\Registration;
use App\Models\Scheme;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class VerifikasiDokumen extends Component
{
    use WithPagination;

    public string $tab = 'perlu_review';

    public string $search = '';

    public string $filterScheme = '';

    protected $queryString = ['tab', 'search', 'filterScheme'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingTab(): void
    {
        $this->resetPage();
    }

    public function updatingFilterScheme(): void
    {
        $this->resetPage();
    }

    public function setTab(string $tab): void
    {
        $this->tab = $tab;
        $this->resetPage();
    }

    public function lanjutkanKeJadwal(int $registrationId): void
    {
        Registration::query()
            ->whereKey($registrationId)
            ->where('status', Registration::STATUS_DOCUMENT_APPROVED)
            ->firstOrFail();

        $this->redirectRoute('admin.jadwal', ['highlight' => $registrationId], navigate: true);
    }

    public function render()
    {
        $query = Registration::with(['user.studyProgram', 'scheme'])
            ->where('status', '!=', Registration::STATUS_DRAFT);

        if ($this->search) {
            $query->whereHas('user', function (Builder $userQuery): void {
                $userQuery
                    ->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('nim', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->filterScheme) {
            $query->where('scheme_id', $this->filterScheme);
        }

        if ($this->tab === 'perlu_review') {
            $query->whereIn('status', [Registration::STATUS_PENDING_VERIFICATION]);
        } elseif ($this->tab === 'dokumen_ok') {
            $query->whereIn('status', [
                Registration::STATUS_DOCUMENT_APPROVED,
                Registration::STATUS_PENDING_PAYMENT,
                Registration::STATUS_PAID,
                Registration::STATUS_SCHEDULED,
                Registration::STATUS_COMPETENT,
                Registration::STATUS_INCOMPETENT,
                Registration::STATUS_CERTIFICATE_ISSUED,
            ]);
        } elseif ($this->tab === 'ditolak') {
            $query->where(function (Builder $rejectedQuery): void {
                $rejectedQuery->whereIn('status', [Registration::STATUS_DOCUMENT_REJECTED]);

                foreach ($this->rejectedDocumentFields() as $field) {
                    $rejectedQuery->orWhere("document_statuses->{$field}->status", 'rejected');
                }
            });
        }

        $registrations = $query->latest()->paginate(10);
        $schemes = Scheme::query()->where('is_active', true)->get();

        return view('livewire.admin.verifikasi-dokumen', [
            'registrations' => $registrations,
            'schemes' => $schemes,
        ]);
    }

    /**
     * @return list<string>
     */
    private function rejectedDocumentFields(): array
    {
        return [
            'fr_apl_01_path',
            'fr_apl_02_path',
            'ktm_path',
            'khs_path',
            'internship_certificate_path',
            'ktp_path',
            'passport_photo_path',
            'payment_reference',
        ];
    }
}
