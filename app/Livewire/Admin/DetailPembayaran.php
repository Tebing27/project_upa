<?php

namespace App\Livewire\Admin;

use App\Models\Registration;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

class DetailPembayaran extends Component
{
    public Registration $registration;

    public string $rejectNote = '';

    public function mount(Registration $registration): void
    {
        $this->registration = $registration->load(['user', 'scheme']);
    }

    public function verifikasiPembayaran(): void
    {
        if (! $this->registration->payment_proof_path) {
            return;
        }

        $statuses = $this->registration->document_statuses ?? [];
        $statuses['payment_proof_path'] = [
            'status' => 'verified',
            'note' => null,
            'verified_at' => now()->toDateTimeString(),
            'verified_by' => Auth::id(),
        ];

        $this->registration->update([
            'document_statuses' => $statuses,
            'payment_verified_at' => now(),
            'status' => 'paid',
        ]);
    }

    public function tolakPembayaran(): void
    {
        $this->validate([
            'rejectNote' => 'required|string|max:255',
        ]);

        $statuses = $this->registration->document_statuses ?? [];
        $statuses['payment_proof_path'] = [
            'status' => 'rejected',
            'note' => $this->rejectNote,
            'verified_at' => now()->toDateTimeString(),
            'verified_by' => Auth::id(),
        ];

        $this->registration->update([
            'document_statuses' => $statuses,
            'payment_verified_at' => null,
            'status' => 'pending_payment',
        ]);

        $this->rejectNote = '';
    }

    public function lanjutkanKeJadwal(): void
    {
        if ($this->registration->status !== 'paid') {
            return;
        }

        $this->redirectRoute('admin.jadwal', ['highlight' => $this->registration->id], navigate: true);
    }

    public function render(): View
    {
        $this->registration->refresh();

        return view('livewire.admin.detail-pembayaran');
    }
}
