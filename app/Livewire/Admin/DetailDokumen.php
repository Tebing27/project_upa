<?php

namespace App\Livewire\Admin;

use App\Models\Registration;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DetailDokumen extends Component
{
    public Registration $registration;

    public string $rejectDocType = '';

    public string $rejectNote = '';

    public function mount(Registration $registration): void
    {
        $this->registration = $registration;
    }

    public function verifikasiDokumen(string $docType): void
    {
        $statuses = $this->registration->document_statuses ?? [];

        $statuses[$docType] = [
            'status' => 'verified',
            'note' => null,
            'verified_at' => now()->toDateTimeString(),
            'verified_by' => Auth::id(),
        ];

        $this->registration->document_statuses = $statuses;
        $this->registration->save();

        $this->cekStatusKeseluruhan();
    }

    public function bukaModalTolak(string $docType): void
    {
        $this->rejectDocType = $docType;
        $this->rejectNote = '';

        $this->dispatch('open-modal', id: 'modal-tolak');
    }

    public function tolakDokumen(): void
    {
        $this->validate([
            'rejectNote' => 'required|string|max:255',
        ]);

        $statuses = $this->registration->document_statuses ?? [];

        $statuses[$this->rejectDocType] = [
            'status' => 'rejected',
            'note' => $this->rejectNote,
            'verified_at' => now()->toDateTimeString(),
            'verified_by' => Auth::id(),
        ];

        $this->registration->document_statuses = $statuses;
        $this->registration->save();

        $this->cekStatusKeseluruhan();

        $this->dispatch('close-modal', id: 'modal-tolak');
        $this->rejectDocType = '';
        $this->rejectNote = '';
    }

    public function lanjutkanKeJadwal(): void
    {
        if ($this->registration->status === Registration::STATUS_PAID) {
            $this->redirectRoute('admin.jadwal', ['highlight' => $this->registration->id], navigate: true);
        }
    }

    public function simulasiPembayaranLunas(): void
    {
        // Pastikan hanya bisa dieksekusi di local environment
        if (! app()->isLocal()) {
            abort(403, 'Unauthorized action.');
        }

        if ($this->registration->status === Registration::STATUS_PENDING_PAYMENT) {
            $this->registration->status = Registration::STATUS_PAID;
            $this->registration->save();
        }
    }

    private function cekStatusKeseluruhan(): void
    {
        $docs = [
            'fr_apl_01_path',
            'fr_apl_02_path',
            'ktm_path',
            'khs_path',
            'internship_certificate_path',
            'ktp_path',
            'passport_photo_path',
        ];

        $statuses = $this->registration->document_statuses ?? [];

        $allVerified = true;
        $anyRejected = false;

        foreach ($docs as $doc) {
            if (empty($this->registration->$doc)) {
                $allVerified = false;

                continue;
            }

            $status = $statuses[$doc]['status'] ?? 'pending';

            if ($status === 'rejected') {
                $anyRejected = true;
            }

            if ($status !== 'verified') {
                $allVerified = false;
            }
        }

        if ($allVerified) {
            $this->registration->status = Registration::STATUS_PENDING_PAYMENT;
            // Generate VA otomatis saat dokumen valid
            if (empty($this->registration->payment_reference)) {
                $this->registration->payment_reference = '98'.$this->registration->user->nim;
            }
        } elseif ($anyRejected) {
            $this->registration->status = Registration::STATUS_DOCUMENT_REJECTED;
        } else {
            $this->registration->status = Registration::STATUS_PENDING_VERIFICATION;
        }

        $this->registration->save();
    }

    public function render()
    {
        return view('livewire.admin.detail-dokumen');
    }
}
