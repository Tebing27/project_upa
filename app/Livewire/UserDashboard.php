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
            ->with(['scheme', 'documentStatuses'])
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

        if (! $registration->relationLoaded('documentStatuses')) {
            $registration->load('documentStatuses');
        }

        $documentStatuses = $registration->getRelation('documentStatuses');

        $labels = [
            ...Registration::documentLabels(),
            'payment_proof_path' => 'Bukti Pembayaran',
        ];

        return $documentStatuses
            ->filter(fn ($ds): bool => $ds->status === 'rejected')
            ->map(fn ($ds): array => [
                'key' => $ds->document_type,
                'label' => $labels[$ds->document_type] ?? Str::title(str_replace('_', ' ', $ds->document_type)),
                'note' => $ds->catatan,
            ])
            ->values()
            ->all();
    }
}
