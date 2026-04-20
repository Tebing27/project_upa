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

    public function isGeneralUser(): bool
    {
        return (bool) $this->registration?->user?->isGeneralUser();
    }

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

        $this->registration = $registration->load('scheme', 'user', 'documents', 'documentStatuses', 'exam.assessor');
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
        $this->registration->load('scheme', 'user', 'documents', 'documentStatuses', 'exam.assessor');

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

        foreach (array_keys($this->profileRules($user->id, $user->role)) as $field) {
            $value = $this->profile[$field] ?? null;
            $this->profile[$field] = filled($value) ? trim((string) $value) : null;
        }

        $validated = $this->validate($this->prefixedProfileRules($user->id, $user->role));

        $profileData = $validated['profile'];

        $user->update([
            'nama' => $profileData['nama'] ?? $user->nama,
            'email' => $profileData['email'] ?? $user->email,
        ]);

        $user->profile()->updateOrCreate([], [
            'tempat_lahir' => $profileData['tempat_lahir'] ?? null,
            'tanggal_lahir' => $profileData['tanggal_lahir'] ?? null,
            'jenis_kelamin' => $profileData['jenis_kelamin'] ?? null,
            'alamat_rumah' => $profileData['alamat_rumah'] ?? null,
            'kode_pos_rumah' => $profileData['kode_pos_rumah'] ?? null,
            'telp_rumah' => $profileData['telp_rumah'] ?? null,
            'telp_kantor' => $profileData['telp_kantor'] ?? null,
            'no_wa' => $profileData['no_wa'] ?? null,
            'fakultas' => $user->isUpnvjUser() ? ($profileData['fakultas'] ?? null) : ($user->profile?->fakultas ?? null),
            'program_studi' => $user->isUpnvjUser() ? ($profileData['program_studi'] ?? null) : ($user->profile?->program_studi ?? null),
        ]);

        $user->mahasiswaProfile()->updateOrCreate([], [
            'nim' => $user->isUpnvjUser() ? ($profileData['nim'] ?? null) : ($user->mahasiswaProfile?->nim ?? null),
            'total_sks' => $user->isUpnvjUser() ? ($profileData['total_sks'] ?? null) : ($user->mahasiswaProfile?->total_sks ?? null),
            'status_semester' => $user->isUpnvjUser() ? ($profileData['status_semester'] ?? null) : ($user->mahasiswaProfile?->status_semester ?? null),
        ]);

        $user->umumProfile()->updateOrCreate([], [
            'no_ktp' => $user->isGeneralUser() ? ($profileData['no_ktp'] ?? null) : ($user->umumProfile?->no_ktp ?? null),
            'kualifikasi_pendidikan' => $user->isGeneralUser() ? ($profileData['kualifikasi_pendidikan'] ?? null) : ($user->umumProfile?->kualifikasi_pendidikan ?? null),
            'nama_perusahaan' => $user->isGeneralUser() ? ($profileData['nama_perusahaan'] ?? null) : ($user->umumProfile?->nama_perusahaan ?? null),
            'jabatan' => $user->isGeneralUser() ? ($profileData['jabatan'] ?? null) : ($user->umumProfile?->jabatan ?? null),
            'alamat_perusahaan' => $user->isGeneralUser() ? ($profileData['alamat_perusahaan'] ?? null) : ($user->umumProfile?->alamat_perusahaan ?? null),
            'kode_pos_perusahaan' => $user->isGeneralUser() ? ($profileData['kode_pos_perusahaan'] ?? null) : ($user->umumProfile?->kode_pos_perusahaan ?? null),
            'no_telp_perusahaan' => $user->isGeneralUser() ? ($profileData['no_telp_perusahaan'] ?? null) : ($user->umumProfile?->no_telp_perusahaan ?? null),
            'email_perusahaan' => $user->isGeneralUser() ? ($profileData['email_perusahaan'] ?? null) : ($user->umumProfile?->email_perusahaan ?? null),
        ]);

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

        // Update or create document record
        $registration->documents()->updateOrCreate(
            ['document_type' => $documentField],
            ['file_path' => $storedPath]
        );

        // Reset the document status to pending so admin re-reviews
        $registration->documentStatuses()->updateOrCreate(
            ['document_type' => $documentField],
            ['status' => 'pending', 'catatan' => null, 'verified_by' => null, 'verified_at' => null]
        );

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

        $this->registration->update([
            'payment_proof_path' => $storedPath,
            'payment_submitted_at' => now(),
            'payment_verified_at' => null,
            'status' => 'pending_payment',
        ]);

        $this->registration->documentStatuses()->updateOrCreate(
            ['document_type' => 'payment_proof_path'],
            ['status' => 'pending', 'catatan' => null, 'verified_by' => null, 'verified_at' => null]
        );

        $this->reset('paymentProof');
        $this->activeTab = 'pembayaran';
        $this->successMessage = 'Bukti pembayaran berhasil diupload dan sedang menunggu validasi admin.';
    }

    public function setActiveTab(string $tab): void
    {
        if (! in_array($tab, ['biodata', 'dokumen', 'tanda_tangan', 'pembayaran', 'jadwal'], true)) {
            return;
        }

        if ($tab === 'jadwal' && ! $this->hasPublishedExamSchedule()) {
            return;
        }

        $this->activeTab = $tab;
    }

    public function shouldDisplayAdminSignature(): bool
    {
        if (! $this->registration) {
            return false;
        }

        return $this->registration->isApl01PdfDownloadReady();
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

        $documents = Registration::apl01RequirementLabels();
        $docMap = $registration->documents->keyBy('document_type');
        $statusMap = $registration->getRelation('documentStatuses')->keyBy('document_type');

        return collect($registration->apl01RequirementDocumentFields())
            ->mapWithKeys(fn (string $field): array => [$field => $documents[$field]])
            ->map(function (string $label, string $field) use ($docMap, $statusMap): array {
                $docRecord = $docMap->get($field);
                $statusRecord = $statusMap->get($field);
                $status = $statusRecord?->status ?? ($docRecord ? 'pending' : 'missing');

                return [
                    'field' => $field,
                    'label' => $label,
                    'status' => $status,
                    'note' => $statusRecord?->catatan,
                    'has_file' => (bool) $docRecord,
                    'can_reupload' => $status === 'rejected',
                    'can_upload_optional' => $field === 'internship_certificate_path' && $status === 'missing',
                    'file_url' => $docRecord?->file_path ? Storage::url($docRecord->file_path) : null,
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

        $rejectedStatuses = $registration->getRelation('documentStatuses')->where('status', 'rejected');

        if ($rejectedStatuses->isNotEmpty()) {
            $history[] = [
                'title' => 'Dokumen perlu diperbaiki',
                'description' => $rejectedStatuses->count().' dokumen ditolak dan menunggu upload ulang.',
                'date' => $rejectedStatuses->pluck('verified_at')->filter()->sort()->last()?->translatedFormat('d M Y'),
                'color' => 'red',
            ];
        } elseif (in_array($registration->status, ['menunggu_verifikasi', 'dokumen_ok', 'pending_payment', 'paid', 'terjadwal', 'kompeten', 'sertifikat_terbit'], true)) {
            $verifiedStatuses = $registration->getRelation('documentStatuses');

            $verifiedCount = $verifiedStatuses
                ->whereIn('document_type', $registration->reviewableDocumentFields())
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

        $exam = $registration->exam;
        if ($exam?->exam_date) {
            $scheduleDescription = $exam->exam_location ?: 'Lokasi ujian akan diinformasikan oleh admin.';

            if (AppSetting::whatsappChannelLink()) {
                $scheduleDescription .= ' Link WhatsApp sudah tersedia pada detail jadwal ujian.';
            }

            $history[] = [
                'title' => 'Jadwal ujian diterbitkan',
                'description' => $scheduleDescription,
                'date' => $exam->exam_date->translatedFormat('d M Y H:i'),
                'color' => 'indigo',
            ];
        }

        if (in_array($registration->status, ['kompeten', 'tidak_kompeten', 'sertifikat_terbit'], true)) {
            $history[] = [
                'title' => $registration->status === 'tidak_kompeten' ? 'Hasil ujian belum kompeten' : 'Hasil ujian kompeten',
                'description' => $registration->status === 'tidak_kompeten'
                    ? 'Silahkan download file hasil ujian dan lakukan pendaftaran ulang.'
                    : ($exam?->score !== null
                        ? 'Nilai akhir: '.$exam->score
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

        if (! $this->registration->relationLoaded('documentStatuses')) {
            $this->registration->load('documentStatuses');
        }

        return $this->registration->getRelation('documentStatuses')
            ->where('status', 'rejected')
            ->isNotEmpty();
    }

    private function hasPublishedExamSchedule(): bool
    {
        if (! $this->registration?->exam?->exam_date) {
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

        $user->load('profile', 'mahasiswaProfile', 'umumProfile');

        $this->profile = [
            'nama' => $user->nama,
            'email' => $user->email,
            'nim' => $user->mahasiswaProfile?->nim,
            'no_ktp' => $user->umumProfile?->no_ktp,
            'tempat_lahir' => $user->profile?->tempat_lahir,
            'tanggal_lahir' => $user->profile?->tanggal_lahir ? Carbon::parse($user->profile->tanggal_lahir)->format('Y-m-d') : null,
            'jenis_kelamin' => $user->profile?->jenis_kelamin,
            'alamat_rumah' => $user->profile?->alamat_rumah,
            'no_wa' => $user->profile?->no_wa,
            'kode_pos_rumah' => $user->profile?->kode_pos_rumah,
            'telp_rumah' => $user->profile?->telp_rumah,
            'telp_kantor' => $user->profile?->telp_kantor,
            'kualifikasi_pendidikan' => $user->umumProfile?->kualifikasi_pendidikan,
            'total_sks' => $user->mahasiswaProfile?->total_sks,
            'status_semester' => $user->mahasiswaProfile?->status_semester,
            'fakultas' => $user->profile?->fakultas,
            'program_studi' => $user->profile?->program_studi,
            'nama_perusahaan' => $user->umumProfile?->nama_perusahaan,
            'jabatan' => $user->umumProfile?->jabatan,
            'alamat_perusahaan' => $user->umumProfile?->alamat_perusahaan,
            'kode_pos_perusahaan' => $user->umumProfile?->kode_pos_perusahaan,
            'no_telp_perusahaan' => $user->umumProfile?->no_telp_perusahaan,
            'email_perusahaan' => $user->umumProfile?->email_perusahaan,
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
