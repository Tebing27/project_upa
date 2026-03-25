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
            ->where('status', 'dokumen_ok')
            ->firstOrFail();

        $this->redirectRoute('admin.jadwal', ['highlight' => $registrationId], navigate: true);
    }

    public function render()
    {
        $query = Registration::with(['user', 'scheme'])
            ->where('status', '!=', 'draft');

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
            $query->whereIn('status', ['menunggu_verifikasi', 'pending_payment', 'paid']);
        } elseif ($this->tab === 'dokumen_ok') {
            $query->whereIn('status', ['dokumen_ok', 'terjadwal', 'kompeten', 'tidak_kompeten']);
        } elseif ($this->tab === 'ditolak') {
            $query->where(function (Builder $rejectedQuery): void {
                $rejectedQuery->whereIn('status', ['dokumen_ditolak', 'rejected']);

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
