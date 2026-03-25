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
            ->with('scheme')
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
            'menunggu_verifikasi', 'dokumen_kurang', 'dokumen_ditolak', 'dokumen_ok', 'rejected' => 2,
            'terjadwal' => 3,
            'selesai_uji', 'kompeten', 'tidak_kompeten', 'sertifikat_terbit' => 4,
            default => 1,
        };
    }

    public function getStatusLabel(?string $status): string
    {
        return match ($status) {
            'pending_payment' => 'Menunggu Bayar',
            'menunggu_verifikasi' => 'Verifikasi Dokumen',
            'dokumen_kurang' => 'Dokumen Kurang',
            'dokumen_ditolak', 'rejected' => 'Dokumen Ditolak',
            'dokumen_ok' => 'Dokumen Terverifikasi',
            'terjadwal' => 'Jadwal Ujian Terbit',
            'selesai_uji' => 'Ujian Selesai',
            'kompeten' => 'Kompeten',
            'tidak_kompeten' => 'Belum Kompeten',
            'sertifikat_terbit' => 'Sertifikat Terbit',
            'draft' => 'Daftar',
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
