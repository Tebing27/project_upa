<?php

namespace App\Livewire\Admin;

use App\Models\Registration;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
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
        if ($this->registration->status === 'dokumen_ok') {
            $this->redirectRoute('admin.payment', ['highlight' => $this->registration->id], navigate: true);
        }
    }

    private function cekStatusKeseluruhan(): void
    {
        $requiredDocs = $this->registration->requiredDocumentFields();
        $optionalDocs = $this->registration->optionalDocumentFields();
        $allDocs = $this->registration->reviewableDocumentFields();
        $statuses = $this->registration->document_statuses ?? [];

        $allVerified = true;
        $anyRejected = false;

        foreach ($allDocs as $doc) {
            $isOptional = in_array($doc, $optionalDocs, true);

            if (empty($this->registration->$doc)) {
                if (! $isOptional) {
                    $allVerified = false;
                }

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
            $this->registration->status = 'dokumen_ok';
        } elseif ($anyRejected) {
            $this->registration->status = 'dokumen_ditolak';
        } else {
            $this->registration->status = 'menunggu_verifikasi';
        }

        $this->registration->save();
    }

    public function render(): View
    {
        return view('livewire.admin.detail-dokumen');
    }
}
