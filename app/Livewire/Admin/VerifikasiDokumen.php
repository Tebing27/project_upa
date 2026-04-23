<?php

namespace App\Livewire\Admin;

use App\Models\AppSetting;
use App\Models\Registration;
use App\Models\Scheme;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class VerifikasiDokumen extends Component
{
    use WithFileUploads;
    use WithPagination;

    public string $tab = 'perlu_review';

    public string $search = '';

    public string $filterScheme = '';

    public string $adminSignatureName = '';

    public $adminSignatureFile;

    protected $queryString = ['tab', 'search', 'filterScheme'];

    public function mount(): void
    {
        $this->adminSignatureName = AppSetting::adminSignatureName() ?? '';
    }

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

    public function simpanTandaTanganAdmin(): void
    {
        $rules = [
            'adminSignatureName' => 'required|string|max:255',
        ];

        if (! AppSetting::adminSignaturePath()) {
            $rules['adminSignatureFile'] = 'required|file|mimes:png,jpg,jpeg|max:2048';
        } elseif ($this->adminSignatureFile) {
            $rules['adminSignatureFile'] = 'file|mimes:png,jpg,jpeg|max:2048';
        }

        $this->validate($rules);

        $storedPath = AppSetting::adminSignaturePath();

        if ($this->adminSignatureFile) {
            $storedPath = $this->adminSignatureFile->store('documents/signatures/admin', 'public');
            AppSetting::put('admin_signature_path', $storedPath);
        }

        AppSetting::put('admin_signature_name', $this->adminSignatureName);
        $this->reset('adminSignatureFile');
    }

    public function editTandaTanganAdmin(): void
    {
        $this->adminSignatureName = AppSetting::adminSignatureName() ?? '';
    }

    public function hapusTandaTanganAdmin(): void
    {
        $existingPath = AppSetting::adminSignaturePath();

        if ($existingPath) {
            Storage::disk('public')->delete($existingPath);
        }

        AppSetting::query()->whereIn('key', ['admin_signature_name', 'admin_signature_path'])->delete();

        $this->adminSignatureName = '';
        $this->reset('adminSignatureFile');
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
            'adminSignaturePath' => AppSetting::adminSignaturePath(),
            'adminSignatureName' => AppSetting::adminSignatureName(),
        ]);
    }
}
