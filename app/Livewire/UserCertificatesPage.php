<?php

namespace App\Livewire;

use App\Models\Scheme;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UserCertificatesPage extends Component
{
    public string $filterStatus = '';

    public string $filterFaculty = '';

    public function downloadCertificateAsPdf(int $certificateId): StreamedResponse
    {
        $certificate = auth()->user()->certificates()->findOrFail($certificateId);

        if (! $certificate->file_path) {
            abort(404, 'Sertifikat belum tersedia.');
        }

        $filePath = storage_path('app/public/'.$certificate->file_path);

        if (! file_exists($filePath)) {
            abort(404, 'File sertifikat tidak ditemukan.');
        }

        return response()->streamDownload(function () use ($filePath): void {
            echo file_get_contents($filePath);
        }, 'Sertifikat-'.str_replace(' ', '-', $certificate->scheme_name).'.pdf', [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function getFacultiesProperty(): Collection
    {
        $schemeIds = auth()->user()->certificates()->pluck('scheme_id')->filter()->unique();

        if ($schemeIds->isEmpty()) {
            return collect();
        }

        return Scheme::query()
            ->whereIn('id', $schemeIds)
            ->whereNotNull('faculty')
            ->pluck('faculty')
            ->unique()
            ->sort()
            ->values();
    }

    public function render(): View
    {
        $user = auth()->user();

        $allCertificates = $user->certificates()->with('scheme')->latest()->get();

        // Get only the latest certificate for each scheme
        $allCertificates = $allCertificates->unique('scheme_id')->values();

        $certificates = $allCertificates;

        if ($this->filterStatus === 'active') {
            $certificates = $certificates->filter(fn ($cert): bool => $cert->is_active);
        } elseif ($this->filterStatus === 'inactive') {
            $certificates = $certificates->filter(fn ($cert): bool => ! $cert->is_active);
        }

        if ($this->filterFaculty !== '') {
            $certificates = $certificates->filter(fn ($cert): bool => $cert->scheme?->faculty === $this->filterFaculty);
        }

        return view('livewire.user-certificates-page', [
            'activeCertificate' => $allCertificates->first(fn ($cert): bool => $cert->is_active),
            'certificates' => $certificates->values(),
            'faculties' => $this->faculties,
        ]);
    }
}
