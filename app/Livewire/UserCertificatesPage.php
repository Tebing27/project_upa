<?php

namespace App\Livewire;

use App\Models\AppSetting;
use App\Models\Registration;
use App\Models\Scheme;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UserCertificatesPage extends Component
{
    public string $filterStatus = '';

    public string $filterFaculty = '';

    public function downloadCompetencyLetter(int $registrationId): StreamedResponse
    {
        $registration = auth()->user()->registrations()
            ->with(['scheme', 'user.mahasiswaProfile', 'user.umumProfile', 'exam'])
            ->where('status', 'kompeten')
            ->findOrFail($registrationId);

        if ($registration->exam?->exam_result_path) {
            $filePath = $registration->exam->exam_result_path;

            abort_unless(Storage::disk('public')->exists($filePath), 404, 'File surat keterangan tidak ditemukan.');

            return response()->streamDownload(function () use ($filePath): void {
                echo Storage::disk('public')->get($filePath);
            }, 'surat-keterangan-kompeten-'.Str::slug($registration->scheme?->nama ?? 'sertifikasi').'.pdf', [
                'Content-Type' => 'application/pdf',
            ]);
        }

        abort_unless(AppSetting::hasCompetencyLetterAssets(), 404, 'Surat keterangan belum disiapkan admin.');

        $html = view('documents.competency-letter', [
            'registration' => $registration,
            'signatoryName' => AppSetting::competencyLetterSignatoryName(),
            'signatureImage' => $this->dataUrlFromPublicPath(AppSetting::competencyLetterSignaturePath()),
            'stampImage' => $this->dataUrlFromPublicPath(AppSetting::competencyLetterStampPath()),
        ])->render();

        $fileName = 'surat-keterangan-kompeten-'.Str::slug($registration->scheme?->nama ?? 'sertifikasi').'.doc';

        return response()->streamDownload(function () use ($html): void {
            echo $html;
        }, $fileName, [
            'Content-Type' => 'application/msword',
        ]);
    }

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
            ->with('faculty')
            ->whereIn('id', $schemeIds)
            ->get()
            ->pluck('faculty.name')
            ->filter()
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
            $certificates = $certificates->filter(fn ($cert): bool => $cert->scheme?->faculty?->name === $this->filterFaculty);
        }

        $pendingCompetencyRegistrations = $user->registrations()
            ->with(['scheme', 'exam'])
            ->where('status', 'kompeten')
            ->latest()
            ->get()
            ->unique('scheme_id')
            ->filter(function (Registration $registration) use ($allCertificates): bool {
                return ! $allCertificates->contains(
                    fn ($certificate): bool => $certificate->scheme_id === $registration->scheme_id && $certificate->is_active
                );
            })
            ->values();

        return view('livewire.user-certificates-page', [
            'activeCertificate' => $allCertificates->first(fn ($cert): bool => $cert->is_active),
            'certificates' => $certificates->values(),
            'faculties' => $this->faculties,
            'pendingCompetencyRegistrations' => $pendingCompetencyRegistrations,
            'hasCompetencyLetterAssets' => AppSetting::hasCompetencyLetterAssets(),
        ]);
    }

    private function dataUrlFromPublicPath(?string $path): ?string
    {
        if (! $path || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        $contents = Storage::disk('public')->get($path);
        $mimeType = Storage::disk('public')->mimeType($path) ?: 'image/png';

        return 'data:'.$mimeType.';base64,'.base64_encode($contents);
    }
}
