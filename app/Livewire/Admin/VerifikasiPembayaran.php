<?php

namespace App\Livewire\Admin;

use App\Models\Registration;
use App\Models\Scheme;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class VerifikasiPembayaran extends Component
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
            ->where('status', 'paid')
            ->firstOrFail();

        $this->redirectRoute('admin.jadwal', ['highlight' => $registrationId], navigate: true);
    }

    public function render(): View
    {
        $query = Registration::query()
            ->with(['user.mahasiswaProfile', 'user.umumProfile', 'scheme', 'documentStatuses'])
            ->whereIn('status', ['dokumen_ok', 'pending_payment', 'paid', 'terjadwal', 'sertifikat_terbit', 'tidak_kompeten']);

        if ($this->search !== '') {
            $query->whereHas('user', function (Builder $userQuery): void {
                $userQuery
                    ->where('nama', 'like', '%'.$this->search.'%')
                    ->orWhereHas('mahasiswaProfile', fn (Builder $q) => $q->where('nim', 'like', '%'.$this->search.'%'))
                    ->orWhereHas('umumProfile', fn (Builder $q) => $q->where('no_ktp', 'like', '%'.$this->search.'%'))
                    ->orWhere('email', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->filterScheme !== '') {
            $query->where('scheme_id', $this->filterScheme);
        }

        if ($this->tab === 'perlu_review') {
            $query->where(function (Builder $paymentQuery): void {
                $paymentQuery
                    ->where('status', 'dokumen_ok')
                    ->orWhere(function (Builder $pendingQuery): void {
                        $pendingQuery
                            ->where('status', 'pending_payment')
                            ->where(function (Builder $proofQuery): void {
                                $proofQuery
                                    ->whereNull('payment_proof_path')
                                    ->orWhereHas('documentStatuses', function (Builder $dsQuery): void {
                                        $dsQuery->where('document_type', 'payment_proof_path')
                                            ->where('status', 'pending');
                                    })
                                    ->orWhereDoesntHave('documentStatuses', function (Builder $dsQuery): void {
                                        $dsQuery->where('document_type', 'payment_proof_path');
                                    });
                            });
                    });
            });
        } elseif ($this->tab === 'terverifikasi') {
            $query->whereIn('status', ['paid', 'terjadwal', 'sertifikat_terbit', 'tidak_kompeten']);
        } elseif ($this->tab === 'ditolak') {
            $query->whereHas('documentStatuses', function (Builder $dsQuery): void {
                $dsQuery->where('document_type', 'payment_proof_path')
                    ->where('status', 'rejected');
            });
        }

        return view('livewire.admin.verifikasi-pembayaran', [
            'registrations' => $query->latest()->paginate(10),
            'schemes' => Scheme::query()->where('is_active', true)->get(),
        ]);
    }
}
