<?php

namespace App\Livewire;

use App\Models\AppSetting;
use App\Models\Registration;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Component;

class UserDashboard extends Component
{
    public function render(): View
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
            'globalWhatsappLink' => AppSetting::whatsappChannelLink(),
        ]);
    }

    public function getStepProgress(?string $status): int
    {
        if (! $status) {
            return 1;
        }

        return new Registration(['status' => $status])->progressStep();
    }

    public function getStatusLabel(?string $status): string
    {
        if (! $status) {
            return 'Daftar';
        }

        return new Registration(['status' => $status])->statusLabel();
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
            ...Registration::documentLabels(),
            'payment_proof_path' => 'Bukti Pembayaran',
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
