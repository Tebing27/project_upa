<?php

namespace App\Livewire\Admin;

use App\Models\Registration;
use Illuminate\View\View;
use Livewire\Component;

class DetailPembayaran extends Component
{
    public Registration $registration;

    public string $rejectNote = '';

    public function mount(Registration $registration): void
    {
        $this->registration = $registration->load(['user', 'scheme', 'user.profile', 'user.umumProfile', 'user.mahasiswaProfile']);
    }

    public function verifikasiPembayaran(): void
    {
        if (! $this->registration->payment_proof_path) {
            return;
        }

        $this->registration->documentStatuses()->updateOrCreate(
            ['document_type' => 'payment_proof_path'],
            ['status' => 'verified', 'catatan' => null, 'verified_at' => now()],
        );

        $this->registration->update([
            'payment_verified_at' => now(),
            'status' => 'paid',
        ]);
    }

    public function tolakPembayaran(): void
    {
        $this->validate([
            'rejectNote' => 'required|string|max:255',
        ]);

        $this->registration->update([
            'payment_verified_at' => null,
            'status' => 'pending_payment',
        ]);

        $this->registration->documentStatuses()->updateOrCreate(
            ['document_type' => 'payment_proof_path'],
            ['status' => 'rejected', 'catatan' => $this->rejectNote],
        );

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
