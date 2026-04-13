<?php

namespace App\Livewire\Admin;

use App\Models\Registration;
use App\Models\Scheme;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
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
            ->whereIn('status', [
                'dokumen_ok',
                'pending_payment',
                'paid',
                'terjadwal',
                'kompeten',
                'tidak_kompeten',
                'sertifikat_terbit',
            ])
            ->firstOrFail();

        $this->redirectRoute('admin.payment', ['highlight' => $registrationId], navigate: true);
    }

    public function render(): View
    {
        $query = Registration::with(['user.mahasiswaProfile', 'user.umumProfile', 'scheme'])
            ->where('status', '!=', 'draft');

        if ($this->search) {
            $query->whereHas('user', function (Builder $userQuery): void {
                $userQuery->where('nama', 'like', '%'.$this->search.'%')
                    ->orWhereHas('mahasiswaProfile', fn (Builder $q) => $q->where('nim', 'like', '%'.$this->search.'%'))
                    ->orWhereHas('umumProfile', fn (Builder $q) => $q->where('no_ktp', 'like', '%'.$this->search.'%'));
            });
        }

        if ($this->filterScheme) {
            $query->where('scheme_id', $this->filterScheme);
        }

        if ($this->tab === 'perlu_review') {
            $query->whereIn('status', ['menunggu_verifikasi', 'dokumen_ditolak']);
        } elseif ($this->tab === 'dokumen_ok') {
            $query->whereIn('status', ['dokumen_ok', 'pending_payment', 'paid', 'terjadwal', 'kompeten', 'tidak_kompeten']);
        } elseif ($this->tab === 'ditolak') {
            $query->where(function (Builder $rejectedQuery): void {
                $rejectedQuery->whereIn('status', ['dokumen_ditolak', 'rejected'])
                    ->orWhereHas('documentStatuses', function (Builder $dsQuery): void {
                        $dsQuery->where('status', 'rejected')
                            ->whereIn('document_type', Registration::allDocumentFields());
                    });
            });
        }

        $registrations = $query->latest()->paginate(10);
        $schemes = Scheme::query()->where('is_active', true)->get();

        return view('livewire.admin.verifikasi-dokumen', [
            'registrations' => $registrations,
            'schemes' => $schemes,
        ]);
    }
}
