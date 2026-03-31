<?php

namespace App\Livewire;

use App\Models\Registration;
use Illuminate\Support\Str;
use Livewire\Component;

class UserDashboard extends Component
{
    public function render()
    {
        $user = auth()->user();
        $allRegistrations = $user->registrations()
            ->with(['scheme', 'assessor'])
            ->latest()
            ->get();
        $latestRegistration = $allRegistrations->first();
        $allCertificates = $user->certificates()->latest()->get();
        $activeCertificate = $allCertificates->first(fn ($cert): bool => $cert->is_active);
        $hasInProgressRegistration = $user->hasInProgressRegistration();

        return view('livewire.user-dashboard', [
            'activeCertificatesCount' => $allCertificates->filter(fn ($cert): bool => $cert->is_active)->count(),
            'latestRegistration' => $latestRegistration,
            'allRegistrations' => $allRegistrations,
            'activeCertificate' => $activeCertificate,
            'rejectedDocuments' => $this->getRejectedDocuments($latestRegistration),
            'hasInProgressRegistration' => $hasInProgressRegistration,
        ]);
    }

    public function getStepProgress(?string $status): int
    {
        return match ($status) {
            Registration::STATUS_PENDING_VERIFICATION, Registration::STATUS_DOCUMENT_REJECTED => 2,
            Registration::STATUS_DOCUMENT_APPROVED, Registration::STATUS_PENDING_PAYMENT, Registration::STATUS_PAID => 3,
            Registration::STATUS_SCHEDULED, Registration::STATUS_COMPLETED => 4,
            Registration::STATUS_COMPETENT, Registration::STATUS_INCOMPETENT, Registration::STATUS_CERTIFICATE_ISSUED => 5,
            default => 1,
        };
    }

    public function getStatusLabel(?string $status): string
    {
        return match ($status) {
            Registration::STATUS_PENDING_PAYMENT => 'Menunggu Pembayaran',
            Registration::STATUS_PENDING_VERIFICATION => 'Verifikasi Dokumen',
            Registration::STATUS_DOCUMENT_REJECTED => 'Dokumen Ditolak',
            Registration::STATUS_DOCUMENT_APPROVED => 'Dokumen Terverifikasi',
            Registration::STATUS_PAID => 'Pembayaran Lunas',
            Registration::STATUS_SCHEDULED => 'Jadwal Ujian Terbit',
            Registration::STATUS_COMPLETED => 'Ujian Selesai',
            Registration::STATUS_COMPETENT => 'Kompeten',
            Registration::STATUS_INCOMPETENT => 'Belum Kompeten',
            Registration::STATUS_CERTIFICATE_ISSUED => 'Sertifikat Terbit',
            Registration::STATUS_DRAFT => 'Daftar',
            default => Str::title(str_replace('_', ' ', (string) $status)),
        };
    }

    public function getTypeLabel(?string $type): string
    {
        return match ($type) {
            'perpanjangan' => 'Perpanjangan',
            default => 'Baru',
        };
    }

    /**
     * @return array<int, array{key: string, label: string, note: string|null}>
     */
    public function getRejectedDocuments(?Registration $registration): array
    {
        if (! $registration) {
            return [];
        }

        $labels = [
            'fr_apl_01_path' => 'FR APL 01',
            'fr_apl_02_path' => 'FR APL 02',
            'ktm_path' => 'KTM',
            'khs_path' => 'KHS',
            'internship_certificate_path' => 'Sertifikat Magang',
            'ktp_path' => 'KTP / Scan Foto',
            'passport_photo_path' => 'Pas Foto 3x4',
            'payment_reference' => 'Bukti UKT / Pembayaran',
        ];

        return collect($registration->document_statuses ?? [])
            ->filter(fn (array $documentStatus): bool => ($documentStatus['status'] ?? null) === 'rejected')
            ->map(function (array $documentStatus, string $key) use ($labels): array {
                return [
                    'key' => $key,
                    'label' => $labels[$key] ?? Str::title(str_replace('_', ' ', $key)),
                    'note' => $documentStatus['note'] ?? null,
                ];
            })
            ->values()
            ->all();
    }
}
