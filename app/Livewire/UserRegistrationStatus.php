<?php

namespace App\Livewire;

use App\Concerns\ProfileValidationRules;
use App\Models\AppSetting;
use App\Models\Registration;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;

class UserRegistrationStatus extends Component
{
    use ProfileValidationRules;
    use WithFileUploads;

    public ?Registration $registration = null;

    public string $activeTab = 'dokumen';

    public array $reuploadFiles = [];

    public array $profile = [];

    public bool $isEditingBiodata = false;

    public ?string $successMessage = null;

    public $paymentProof;

    public function mount(?Registration $registration = null): void
    {
        if (! $registration || ! $registration->exists) {
            $registration = Registration::query()
                ->where('user_id', auth()->id())
                ->latest()
                ->first();

            if (! $registration) {
                return;
            }
        }

        if ($registration->user_id !== auth()->id()) {
            abort(403);
        }

        $this->registration = $registration->load('scheme', 'user');
        $this->fillProfileForm();
        $this->activeTab = $this->resolveActiveTab($this->registration->status);
    }

    public function render(): View
    {
        if (! $this->registration) {
            return view('livewire.user-registration-status', [
                'registration' => null,
                'currentStep' => 1,
                'statusLabel' => 'Belum Ada Pendaftaran',
                'documentCards' => [],
                'statusHistory' => [],
            ]);
        }

        $this->registration->refresh();
        $this->registration->load('scheme', 'user');

        return view('livewire.user-registration-status', [
            'registration' => $this->registration,
            'currentStep' => $this->getStepProgress($this->registration->status),
            'statusLabel' => $this->getStatusLabel($this->registration->status),
            'documentCards' => $this->getDocumentCards($this->registration),
            'statusHistory' => $this->getStatusHistory($this->registration),
            'canEditBiodata' => $this->canEditBiodata(),
            'globalWhatsappLink' => AppSetting::whatsappChannelLink(),
        ]);
    }

    public function startEditingBiodata(): void
    {
        if (! $this->canEditBiodata()) {
            return;
        }

        $this->fillProfileForm();
        $this->isEditingBiodata = true;
    }

    public function cancelEditingBiodata(): void
    {
        $this->fillProfileForm();
        $this->isEditingBiodata = false;
    }

    public function saveBiodata(): void
    {
        if (! $this->canEditBiodata()) {
            return;
        }

        $user = $this->registration->user;

        foreach (array_keys($this->profileRules($user->id, $user->user_type)) as $field) {
            $value = $this->profile[$field] ?? null;
            $this->profile[$field] = filled($value) ? trim((string) $value) : null;
        }

        $validated = $this->validate($this->prefixedProfileRules($user->id, $user->user_type));

        $user->fill($validated['profile']);
        $user->syncProfileCompletionStatus();
        $user->save();

        $this->registration->load('user');
        $this->fillProfileForm();
        $this->isEditingBiodata = false;
        $this->successMessage = 'Biodata berhasil diperbarui. Silakan lanjutkan perbaikan dokumen yang ditolak.';
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
        $registration->status = 'menunggu_verifikasi';
        $registration->save();

        unset($this->reuploadFiles[$documentField]);

        $this->activeTab = 'dokumen';
        $this->successMessage = 'Dokumen berhasil diupload ulang dan sedang menunggu verifikasi admin.';
    }

    public function uploadPaymentProof(): void
    {
        if ($this->registration->user_id !== auth()->id()) {
            abort(403);
        }

        if (! in_array($this->registration->status, ['dokumen_ok', 'pending_payment'], true)) {
            return;
        }

        $this->validate([
            'paymentProof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:4096',
        ]);

        $storedPath = $this->paymentProof->store('payments/proofs', 'public');
        $statuses = $this->registration->document_statuses ?? [];
        $statuses['payment_proof_path'] = [
            'status' => 'pending',
            'note' => null,
            'verified_at' => null,
        ];

        $this->registration->update([
            'payment_proof_path' => $storedPath,
            'payment_submitted_at' => now(),
            'payment_verified_at' => null,
            'document_statuses' => $statuses,
            'status' => 'pending_payment',
        ]);

        $this->reset('paymentProof');
        $this->activeTab = 'pembayaran';
        $this->successMessage = 'Bukti pembayaran berhasil diupload dan sedang menunggu validasi admin.';
    }

    public function setActiveTab(string $tab): void
    {
        if (! in_array($tab, ['biodata', 'dokumen', 'pembayaran', 'jadwal'], true)) {
            return;
        }

        if ($tab === 'jadwal' && ! $this->hasPublishedExamSchedule()) {
            return;
        }

        $this->activeTab = $tab;
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

    /**
     * @return array<int, array{field: string, label: string, status: string, note: string|null, has_file: bool, can_reupload: bool, can_upload_optional: bool, file_url: string|null}>
     */
    public function getDocumentCards(?Registration $registration): array
    {
        if (! $registration) {
            return [];
        }

        $documents = Registration::documentLabels();
        $reviewableFields = $registration->reviewableDocumentFields();

        return collect($registration->visibleDocumentFields())
            ->mapWithKeys(fn (string $field): array => [$field => $documents[$field]])
            ->map(function (string $label, string $field) use ($registration, $reviewableFields): array {
                $isSupportingDocument = $registration->usesSimplifiedDocumentFlow()
                    && ! in_array($field, $registration->reviewableDocumentFields(), true);
                $documentStatus = $registration->document_statuses[$field] ?? [];
                $status = $isSupportingDocument
                    ? ($registration->{$field} ? 'supporting' : 'missing')
                    : ($documentStatus['status'] ?? ($registration->{$field} ? 'pending' : 'missing'));

                return [
                    'field' => $field,
                    'label' => $label,
                    'status' => $status,
                    'note' => $documentStatus['note'] ?? null,
                    'has_file' => (bool) $registration->{$field},
                    'can_reupload' => $status === 'rejected' && in_array($field, $reviewableFields, true),
                    'can_upload_optional' => $field === 'internship_certificate_path' && $status === 'missing',
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
        } elseif (in_array($registration->status, ['menunggu_verifikasi', 'dokumen_ok', 'pending_payment', 'paid', 'terjadwal', 'kompeten', 'sertifikat_terbit'], true)) {
            $verifiedCount = collect($registration->document_statuses ?? [])
                ->filter(fn (array $documentStatus, string $field): bool => in_array($field, $registration->reviewableDocumentFields(), true))
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

        if (in_array($registration->status, ['dokumen_ok', 'pending_payment', 'paid', 'terjadwal', 'kompeten', 'sertifikat_terbit', 'tidak_kompeten'], true)) {
            $history[] = [
                'title' => 'Tahap pembayaran',
                'description' => $registration->status === 'paid'
                    ? 'Pembayaran telah tervalidasi dan peserta siap dijadwalkan ujian.'
                    : ($registration->payment_proof_path
                        ? 'Bukti pembayaran sudah diupload dan sedang diperiksa admin.'
                        : 'Peserta menunggu untuk melakukan pembayaran dan upload bukti pembayaran.'),
                'date' => $registration->payment_submitted_at?->translatedFormat('d M Y H:i') ?? $registration->updated_at?->translatedFormat('d M Y'),
                'color' => 'blue',
            ];
        }

        if ($registration->exam_date) {
            $scheduleDescription = $registration->exam_location ?: 'Lokasi ujian akan diinformasikan oleh admin.';

            if (AppSetting::whatsappChannelLink()) {
                $scheduleDescription .= ' Link WhatsApp sudah tersedia pada detail jadwal ujian.';
            }

            $history[] = [
                'title' => 'Jadwal ujian diterbitkan',
                'description' => $scheduleDescription,
                'date' => $registration->exam_date->translatedFormat('d M Y H:i'),
                'color' => 'indigo',
            ];
        }

        if (in_array($registration->status, ['kompeten', 'tidak_kompeten', 'sertifikat_terbit'], true)) {
            $history[] = [
                'title' => $registration->status === 'tidak_kompeten' ? 'Hasil ujian belum kompeten' : 'Hasil ujian kompeten',
                'description' => $registration->status === 'tidak_kompeten'
                    ? 'Silahkan download file hasil ujian dan lakukan pendaftaran ulang.'
                    : ($registration->score !== null
                        ? 'Nilai akhir: '.$registration->score
                        : 'Hasil ujian telah diproses.'),
                'date' => $registration->updated_at?->translatedFormat('d M Y'),
                'color' => $registration->status === 'tidak_kompeten' ? 'red' : 'emerald',
            ];
        }

        if ($registration->status === 'sertifikat_terbit') {
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
            'internship_certificate_path' => 'sometimes|required|file|mimes:pdf|max:2048',
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

    private function resolveActiveTab(?string $status): string
    {
        return match ($status) {
            'terjadwal', 'sertifikat_terbit', 'tidak_kompeten' => $this->hasPublishedExamSchedule() ? 'jadwal' : 'dokumen',
            'dokumen_ok', 'pending_payment', 'paid' => 'pembayaran',
            default => 'dokumen',
        };
    }

    private function canEditBiodata(): bool
    {
        if (! $this->registration) {
            return false;
        }

        return collect($this->registration->document_statuses ?? [])
            ->contains(fn (array $documentStatus): bool => ($documentStatus['status'] ?? null) === 'rejected');
    }

    private function hasPublishedExamSchedule(): bool
    {
        if (! $this->registration?->exam_date) {
            return false;
        }

        return in_array($this->registration->status, ['terjadwal', 'sertifikat_terbit', 'tidak_kompeten'], true);
    }

    private function fillProfileForm(): void
    {
        $user = $this->registration?->user;

        if (! $user) {
            $this->profile = [];

            return;
        }

        $this->profile = [
            'name' => $user->name,
            'email' => $user->email,
            'nim' => $user->nim,
            'no_ktp' => $user->no_ktp,
            'tempat_lahir' => $user->tempat_lahir,
            'tanggal_lahir' => $user->tanggal_lahir ? Carbon::parse($user->tanggal_lahir)->format('Y-m-d') : null,
            'jenis_kelamin' => $user->jenis_kelamin,
            'alamat_rumah' => $user->alamat_rumah,
            'domisili_provinsi' => $user->domisili_provinsi,
            'domisili_kota' => $user->domisili_kota,
            'domisili_kecamatan' => $user->domisili_kecamatan,
            'no_wa' => $user->no_wa,
            'pendidikan_terakhir' => $user->pendidikan_terakhir,
            'nama_institusi' => $user->nama_institusi,
            'total_sks' => $user->total_sks,
            'status_semester' => $user->status_semester,
            'fakultas' => $user->fakultas,
            'program_studi' => $user->program_studi,
            'pekerjaan' => $user->pekerjaan,
            'nama_perusahaan' => $user->nama_perusahaan,
            'jabatan' => $user->jabatan,
            'alamat_perusahaan' => $user->alamat_perusahaan,
            'kode_pos_perusahaan' => $user->kode_pos_perusahaan,
            'no_telp_perusahaan' => $user->no_telp_perusahaan,
            'email_perusahaan' => $user->email_perusahaan,
        ];
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    private function prefixedProfileRules(?int $userId, string $userType): array
    {
        return collect($this->profileRules($userId, $userType))
            ->mapWithKeys(fn (array $rules, string $field): array => ["profile.$field" => $rules])
            ->all();
    }
}
