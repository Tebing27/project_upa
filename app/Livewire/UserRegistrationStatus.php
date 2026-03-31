<?php

namespace App\Livewire;

use App\Models\Registration;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class UserRegistrationStatus extends Component
{
    use WithFileUploads;

    public Registration $registration;

    public array $reuploadFiles = [];

    public ?string $successMessage = null;

    public function mount(?Registration $registration = null): void
    {
        if (! $registration || ! $registration->exists) {
            $registration = Registration::query()
                ->where('user_id', auth()->id())
                ->latest()
                ->first();

            if (! $registration) {
                abort(404);
            }
        }

        if ($registration->user_id !== auth()->id()) {
            abort(403);
        }

        $this->registration = $registration->load(['scheme', 'user.studyProgram.faculty']);
    }

    public function render()
    {
        $this->registration->refresh();
        $this->registration->load(['scheme', 'user.studyProgram.faculty']);

        return view('livewire.user-registration-status', [
            'registration' => $this->registration,
            'currentStep' => $this->getStepProgress($this->registration->status),
            'statusLabel' => $this->getStatusLabel($this->registration->status),
            'documentCards' => $this->getDocumentCards($this->registration),
            'statusHistory' => $this->getStatusHistory($this->registration),
        ]);
    }

    public function reuploadDocument(string $documentField): void
    {
        $registration = $this->registration;

        if ($registration->user_id !== auth()->id()) {
            abort(403);
        }

        $fileRules = $this->documentUploadRules();

        if (! array_key_exists($documentField, $fileRules)) {
            return;
        }

        $this->validate([
            "reuploadFiles.$documentField" => $fileRules[$documentField],
        ]);

        $storedPath = $this->reuploadFiles[$documentField]->store($this->documentStoragePath($documentField), 'public');

        $registration->{$documentField} = $storedPath;

        $documentStatuses = $registration->document_statuses ?? [];
        $documentStatuses[$documentField] = [
            'status' => 'pending',
            'note' => null,
            'reuploaded_at' => now()->toDateTimeString(),
        ];

        $registration->document_statuses = $documentStatuses;
        $registration->status = Registration::STATUS_PENDING_VERIFICATION;
        $registration->save();

        unset($this->reuploadFiles[$documentField]);

        $this->successMessage = 'Dokumen berhasil diupload ulang dan sedang menunggu verifikasi admin.';
    }

    public function getStepProgress(?string $status): int
    {
        return match ($status) {
            Registration::STATUS_PENDING_VERIFICATION, Registration::STATUS_DOCUMENT_REJECTED => 2,
            Registration::STATUS_DOCUMENT_APPROVED, Registration::STATUS_PENDING_PAYMENT => 3,
            Registration::STATUS_PAID, Registration::STATUS_SCHEDULED => 4,
            Registration::STATUS_COMPLETED, Registration::STATUS_COMPETENT, Registration::STATUS_INCOMPETENT, Registration::STATUS_CERTIFICATE_ISSUED => 5,
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
            default => 'Belum ada pendaftaran',
        };
    }

    /**
     * @return array<int, array{field: string, label: string, status: string, note: string|null, has_file: bool, can_reupload: bool, file_url: string|null}>
     */
    public function getDocumentCards(?Registration $registration): array
    {
        if (! $registration) {
            return [];
        }

        $documents = [
            'fr_apl_01_path' => 'FR APL 01',
            'fr_apl_02_path' => 'FR APL 02',
            'ktm_path' => 'KTM',
            'khs_path' => 'KHS',
            'internship_certificate_path' => 'Sertifikat Magang',
            'ktp_path' => 'KTP / Scan Foto',
            'passport_photo_path' => 'Pas Foto 3x4',
        ];

        return collect($documents)
            ->map(function (string $label, string $field) use ($registration): array {
                $documentStatus = $registration->document_statuses[$field] ?? [];
                $status = $documentStatus['status'] ?? ($registration->{$field} ? 'pending' : 'missing');

                return [
                    'field' => $field,
                    'label' => $label,
                    'status' => $status,
                    'note' => $documentStatus['note'] ?? null,
                    'has_file' => (bool) $registration->{$field},
                    'can_reupload' => $status === 'rejected',
                    'file_url' => $registration->{$field} ? Storage::url($registration->{$field}) : null,
                ];
            })
            ->values()
            ->all();
    }

    /**
     * @return array<int, array{title: string, description: string, date: string|null, color: string}>
     */
    public function getStatusHistory(?Registration $registration): array
    {
        if (! $registration) {
            return [];
        }

        $history = [
            [
                'title' => 'Pendaftaran dikirim',
                'description' => 'Data pendaftaran berhasil masuk ke sistem.',
                'date' => $registration->created_at?->translatedFormat('d M Y'),
                'color' => 'blue',
            ],
        ];

        $rejectedDocuments = collect($registration->document_statuses ?? [])
            ->filter(fn (array $documentStatus): bool => ($documentStatus['status'] ?? null) === 'rejected');

        if ($rejectedDocuments->isNotEmpty()) {
            $history[] = [
                'title' => 'Dokumen perlu diperbaiki',
                'description' => $rejectedDocuments->count().' dokumen ditolak dan menunggu upload ulang.',
                'date' => $rejectedDocuments->pluck('verified_at')->filter()->sort()->last(),
                'color' => 'red',
            ];
        } elseif (in_array($registration->status, [Registration::STATUS_PENDING_VERIFICATION, Registration::STATUS_DOCUMENT_APPROVED, Registration::STATUS_PENDING_PAYMENT, Registration::STATUS_PAID, Registration::STATUS_SCHEDULED, Registration::STATUS_COMPLETED, Registration::STATUS_COMPETENT, Registration::STATUS_CERTIFICATE_ISSUED], true)) {
            $verifiedCount = collect($registration->document_statuses ?? [])
                ->where('status', 'verified')
                ->count();

            $history[] = [
                'title' => 'Dokumen diverifikasi',
                'description' => $verifiedCount > 0
                    ? $verifiedCount.' dokumen telah diverifikasi admin.'
                    : 'Dokumen sedang diperiksa oleh admin.',
                'date' => $registration->updated_at?->translatedFormat('d M Y'),
                'color' => 'amber',
            ];
        }

        if ($registration->exam_date) {
            $history[] = [
                'title' => 'Jadwal ujian diterbitkan',
                'description' => $registration->exam_location ?: 'Lokasi ujian akan diinformasikan oleh admin.',
                'date' => $registration->exam_date->translatedFormat('d M Y H:i'),
                'color' => 'indigo',
            ];
        }

        if (in_array($registration->status, [Registration::STATUS_COMPETENT, Registration::STATUS_INCOMPETENT, Registration::STATUS_CERTIFICATE_ISSUED], true)) {
            $history[] = [
                'title' => $registration->status === Registration::STATUS_INCOMPETENT ? 'Hasil ujian belum kompeten' : 'Hasil ujian kompeten',
                'description' => $registration->status === Registration::STATUS_INCOMPETENT
                    ? 'Silahkan download file hasil ujian dan lakukan pendaftaran ulang.'
                    : ($registration->score !== null
                        ? 'Nilai akhir: '.$registration->score
                        : 'Hasil ujian telah diproses.'),
                'date' => $registration->updated_at?->translatedFormat('d M Y'),
                'color' => $registration->status === Registration::STATUS_INCOMPETENT ? 'red' : 'emerald',
            ];
        }

        if ($registration->status === Registration::STATUS_CERTIFICATE_ISSUED) {
            $history[] = [
                'title' => 'Sertifikat terbit',
                'description' => 'Sertifikat aktif sudah tersedia untuk diunduh.',
                'date' => $registration->updated_at?->translatedFormat('d M Y'),
                'color' => 'emerald',
            ];
        }

        return $history;
    }

    /**
     * @return array<string, string>
     */
    private function documentUploadRules(): array
    {
        return [
            'fr_apl_01_path' => 'required|file|mimes:pdf|max:2048',
            'fr_apl_02_path' => 'required|file|mimes:pdf|max:2048',
            'ktm_path' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'khs_path' => 'required|file|mimes:pdf|max:2048',
            'internship_certificate_path' => 'required|file|mimes:pdf|max:2048',
            'ktp_path' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'passport_photo_path' => 'required|file|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    private function documentStoragePath(string $documentField): string
    {
        return match ($documentField) {
            'fr_apl_01_path' => 'documents/fr_apl_01',
            'fr_apl_02_path' => 'documents/fr_apl_02',
            'ktm_path' => 'documents/ktm',
            'khs_path' => 'documents/khs',
            'internship_certificate_path' => 'documents/internship',
            'ktp_path' => 'documents/ktp',
            'passport_photo_path' => 'documents/photo',
            default => 'documents/reupload',
        };
    }
}
