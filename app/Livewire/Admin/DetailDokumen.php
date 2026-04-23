<?php

namespace App\Livewire\Admin;

use App\Models\AppSetting;
use App\Models\Registration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Livewire\Component;

class DetailDokumen extends Component
{
    public Registration $registration;

    public string $rejectDocType = '';

    public string $rejectNote = '';

    public function canProceedToPayment(): bool
    {
        return in_array($this->registration->status, [
            'dokumen_ok',
            'pending_payment',
            'paid',
            'terjadwal',
            'kompeten',
            'tidak_kompeten',
            'sertifikat_terbit',
        ], true);
    }

    public function mount(Registration $registration): void
    {
        $this->registration = $registration->load('documents', 'documentStatuses');
    }

    public function verifikasiDokumen(string $docType): void
    {
        $this->sinkronkanTandaTanganAdmin();

        $this->registration->documentStatuses()->updateOrCreate(
            ['document_type' => $docType],
            [
                'status' => 'verified',
                'catatan' => null,
                'verified_at' => now(),
                'verified_by' => Auth::id(),
            ]
        );

        $this->registration->load('documentStatuses');
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

        $this->registration->documentStatuses()->updateOrCreate(
            ['document_type' => $this->rejectDocType],
            [
                'status' => 'rejected',
                'catatan' => $this->rejectNote,
                'verified_at' => now(),
                'verified_by' => Auth::id(),
            ]
        );

        $this->registration->load('documentStatuses');
        $this->cekStatusKeseluruhan();

        $this->dispatch('close-modal', id: 'modal-tolak');
        $this->rejectDocType = '';
        $this->rejectNote = '';
    }

    public function lanjutkanKeJadwal(): void
    {
        if ($this->canProceedToPayment()) {
            $this->redirectRoute('admin.payment', ['highlight' => $this->registration->id], navigate: true);
        }
    }

    private function cekStatusKeseluruhan(): void
    {
        $optionalDocs = $this->registration->optionalDocumentFields();
        $allDocs = $this->registration->reviewableDocumentFields();

        $docMap = $this->registration->documents->keyBy('document_type');
        $statusMap = $this->registration->getRelation('documentStatuses')->keyBy('document_type');

        $allVerified = true;
        $anyRejected = false;

        foreach ($allDocs as $doc) {
            $isOptional = in_array($doc, $optionalDocs, true);

            if (! $docMap->has($doc)) {
                if (! $isOptional) {
                    $allVerified = false;
                }

                continue;
            }

            $status = $statusMap->get($doc)?->status ?? 'pending';

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
        $this->registration->load('documents', 'documentStatuses');
    }

    private function sinkronkanTandaTanganAdmin(): void
    {
        $signatureName = AppSetting::adminSignatureName();
        $signaturePath = AppSetting::adminSignaturePath();

        if (! filled($signatureName) || ! filled($signaturePath)) {
            return;
        }

        $registrationSignaturePath = $this->registration->admin_signature_path;

        if (! $registrationSignaturePath && Storage::disk('public')->exists($signaturePath)) {
            $extension = pathinfo($signaturePath, PATHINFO_EXTENSION) ?: 'png';
            $registrationSignaturePath = 'documents/signatures/admin/registration_'.$this->registration->id.'.'.$extension;

            Storage::disk('public')->copy($signaturePath, $registrationSignaturePath);
        }

        $this->registration->update([
            'admin_signatory_name' => $signatureName,
        ]);

        $this->registration->documents()->updateOrCreate(
            ['document_type' => 'admin_signature_path'],
            ['file_path' => $registrationSignaturePath ?? $signaturePath]
        );
    }

    public function render(): View
    {
        return view('livewire.admin.detail-dokumen');
    }
}
