<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Registration extends Model
{
    use HasFactory;

    /** @var array<string, string|null> */
    protected array $legacyDocuments = [];

    /** @var array<string, array<string, mixed>> */
    protected array $legacyDocumentStatuses = [];

    /** @var array<string, mixed> */
    protected array $legacyExamAttributes = [];

    protected $fillable = [
        'user_id', 'scheme_id', 'type', 'assessment_purpose', 'admin_signatory_name', 'status',
        'payment_reference', 'va_numer', 'payment_proof_path',
        'payment_submitted_at', 'payment_verified_at',
    ];

    protected $casts = [
        'payment_submitted_at' => 'datetime',
        'payment_verified_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::saved(function (self $registration): void {
            $registration->syncLegacyRelations();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scheme(): BelongsTo
    {
        return $this->belongsTo(Scheme::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(RegistrationDocument::class);
    }

    public function documentStatuses(): HasMany
    {
        return $this->hasMany(RegistrationDocumentStatus::class);
    }

    public function exam(): HasOne
    {
        return $this->hasOne(Exam::class);
    }

    // Helper methods ported from old Registration
    public static function documentLabels(): array
    {
        return [
            'fr_apl_01_path' => 'FR APL 01',
            'fr_apl_02_path' => 'FR APL 02',
            ...static::apl01RequirementLabels(),
        ];
    }

    public static function apl01RequirementLabels(): array
    {
        return [
            'ktm_path' => 'Fotokopi Kartu Mahasiswa (KTM)',
            'khs_path' => 'Fotokopi Hasil Studi Semester 1 s/d Terbaru / Transkrip',
            'internship_certificate_path' => 'Fotokopi Sertifikat Magang (Opsional)',
            'ktp_path' => 'Fotokopi KTP/KK',
            'passport_photo_path' => 'Pasfoto berwarna 3x4 background merah',
        ];
    }

    public static function assessmentPurposeLabels(): array
    {
        return [
            'sertifikasi' => 'Sertifikasi',
            'paling_lambat_pkt' => 'Pengakuan Kompetensi Terkini (PKT)',
            'rpl' => 'Rekognisi Pembelajaran Lampau (RPL)',
            'lainnya' => 'Lainnya',
        ];
    }

    public static function allDocumentFields(): array
    {
        return array_keys(static::documentLabels());
    }

    public function requiredDocumentFields(): array
    {
        // For simplicity, returning the required ones.
        // Condensed flow logic can be implemented if needed.
        return [
            'fr_apl_01_path',
            'fr_apl_02_path',
            'ktm_path',
            'khs_path',
            'ktp_path',
            'passport_photo_path',
        ];
    }

    public function optionalDocumentFields(): array
    {
        return [
            'internship_certificate_path',
        ];
    }

    public function reviewableDocumentFields(): array
    {
        if ($this->usesSimplifiedDocumentFlow()) {
            return [
                'fr_apl_01_path',
                'fr_apl_02_path',
            ];
        }

        return array_values(array_merge(
            $this->requiredDocumentFields(),
            $this->optionalDocumentFields(),
        ));
    }

    public function visibleDocumentFields(): array
    {
        return static::allDocumentFields();
    }

    public function apl01RequirementDocumentFields(): array
    {
        return array_keys(static::apl01RequirementLabels());
    }

    public function assessmentPurposeLabel(): string
    {
        return static::assessmentPurposeLabels()[$this->assessment_purpose] ?? '-';
    }

    public function progressStep(): int
    {
        return match ($this->status) {
            'menunggu_verifikasi', 'dokumen_kurang', 'dokumen_ditolak', 'rejected' => 2,
            'dokumen_ok', 'pending_payment', 'paid' => 3,
            'terjadwal' => 4,
            'selesai_uji', 'kompeten', 'tidak_kompeten', 'sertifikat_terbit' => 5,
            default => 1,
        };
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'draft' => 'Daftar',
            'menunggu_verifikasi' => 'Verifikasi Data & Dokumen',
            'dokumen_kurang' => 'Dokumen Kurang',
            'dokumen_ditolak', 'rejected' => 'Dokumen Ditolak',
            'dokumen_ok' => 'Siap Pembayaran',
            'pending_payment' => $this->payment_proof_path ? 'Bukti Pembayaran Direview' : 'Menunggu Pembayaran',
            'paid' => 'Pembayaran Tervalidasi',
            'terjadwal' => 'Jadwal Ujian Terbit',
            'selesai_uji' => 'Ujian Selesai',
            'kompeten' => 'Kompeten',
            'tidak_kompeten' => 'Belum Kompeten',
            'sertifikat_terbit' => 'Sertifikat Terbit',
            default => str($this->status)->replace('_', ' ')->title()->toString(),
        };
    }

    public function hasPublishedExamSchedule(): bool
    {
        if (! $this->relationLoaded('exam') || ! $this->exam) {
            $this->load('exam');
        }

        return $this->exam
            && $this->exam->exam_date !== null
            && filled($this->exam->exam_location)
            && filled($this->exam->assessor_id);
    }

    /**
     * Get exam_date via the exam relation (backward-compat accessor).
     */
    public function getExamDateAttribute(): mixed
    {
        if (! $this->relationLoaded('exam')) {
            $this->load('exam');
        }

        return $this->exam?->exam_date;
    }

    /**
     * Get exam_location via the exam relation (backward-compat accessor).
     */
    public function getExamLocationAttribute(): ?string
    {
        if (! $this->relationLoaded('exam')) {
            $this->load('exam');
        }

        return $this->exam?->exam_location;
    }

    /**
     * Get assessor_name via the exam->assessor relation (backward-compat accessor).
     */
    public function getAssessorNameAttribute(): ?string
    {
        if (! $this->relationLoaded('exam')) {
            $this->load('exam');
        }

        if (! $this->exam || ! $this->exam->relationLoaded('assessor')) {
            $this->exam?->load('assessor');
        }

        return $this->exam?->assessor?->nama;
    }

    /**
     * Get exam_result_path via the exam relation (backward-compat accessor).
     */
    public function getExamResultPathAttribute(): ?string
    {
        if (! $this->relationLoaded('exam')) {
            $this->load('exam');
        }

        return $this->exam?->exam_result_path;
    }

    /**
     * Get the status of the payment proof document.
     * Returns: 'verified', 'rejected', 'pending', or null.
     */
    public function paymentProofStatus(): ?string
    {
        if (! $this->payment_proof_path) {
            return null;
        }

        if (in_array($this->status, ['paid', 'terjadwal', 'sertifikat_terbit', 'tidak_kompeten'], true)) {
            return 'verified';
        }

        if (! $this->relationLoaded('documentStatuses')) {
            $this->load('documentStatuses');
        }

        $docStatus = $this->getRelation('documentStatuses')
            ->firstWhere('document_type', 'payment_proof_path');

        if ($docStatus) {
            return $docStatus->status;
        }

        return 'pending';
    }

    /**
     * Get a specific document path from the documents relation.
     */
    public function getDocumentPath(string $documentType): ?string
    {
        if (! $this->relationLoaded('documents')) {
            $this->load('documents');
        }

        return $this->documents->firstWhere('document_type', $documentType)?->file_path;
    }

    public function getDocumentStatusesAttribute(): array
    {
        $statuses = $this->relationLoaded('documentStatuses')
            ? $this->getRelation('documentStatuses')
            : $this->documentStatuses()->get();

        return $statuses
            ->mapWithKeys(function (RegistrationDocumentStatus $status): array {
                return [
                    $status->document_type => [
                        'status' => $status->status,
                        'note' => $status->catatan,
                        'verified_by' => $status->verified_by,
                        'verified_at' => $status->verified_at,
                    ],
                ];
            })
            ->all();
    }

    public function getFrApl01PathAttribute(): ?string
    {
        return $this->getDocumentPath('fr_apl_01_path');
    }

    public function setFrApl01PathAttribute(?string $value): void
    {
        $this->legacyDocuments['fr_apl_01_path'] = $value;
    }

    public function getFrApl02PathAttribute(): ?string
    {
        return $this->getDocumentPath('fr_apl_02_path');
    }

    public function setFrApl02PathAttribute(?string $value): void
    {
        $this->legacyDocuments['fr_apl_02_path'] = $value;
    }

    public function getKtmPathAttribute(): ?string
    {
        return $this->getDocumentPath('ktm_path');
    }

    public function setKtmPathAttribute(?string $value): void
    {
        $this->legacyDocuments['ktm_path'] = $value;
    }

    public function getKhsPathAttribute(): ?string
    {
        return $this->getDocumentPath('khs_path');
    }

    public function setKhsPathAttribute(?string $value): void
    {
        $this->legacyDocuments['khs_path'] = $value;
    }

    public function getInternshipCertificatePathAttribute(): ?string
    {
        return $this->getDocumentPath('internship_certificate_path');
    }

    public function setInternshipCertificatePathAttribute(?string $value): void
    {
        $this->legacyDocuments['internship_certificate_path'] = $value;
    }

    public function getKtpPathAttribute(): ?string
    {
        return $this->getDocumentPath('ktp_path');
    }

    public function setKtpPathAttribute(?string $value): void
    {
        $this->legacyDocuments['ktp_path'] = $value;
    }

    public function getPassportPhotoPathAttribute(): ?string
    {
        return $this->getDocumentPath('passport_photo_path');
    }

    public function setPassportPhotoPathAttribute(?string $value): void
    {
        $this->legacyDocuments['passport_photo_path'] = $value;
    }

    public function getApplicantSignaturePathAttribute(): ?string
    {
        return $this->getDocumentPath('applicant_signature_path');
    }

    public function setApplicantSignaturePathAttribute(?string $value): void
    {
        $this->legacyDocuments['applicant_signature_path'] = $value;
    }

    public function getAdminSignaturePathAttribute(): ?string
    {
        return $this->getDocumentPath('admin_signature_path');
    }

    public function setAdminSignaturePathAttribute(?string $value): void
    {
        $this->legacyDocuments['admin_signature_path'] = $value;
    }

    public function getScoreAttribute(): ?int
    {
        if (! $this->relationLoaded('exam')) {
            $this->load('exam');
        }

        return $this->exam?->score;
    }

    public function usesSimplifiedDocumentFlow(): bool
    {
        if (! $this->relationLoaded('documentStatuses')) {
            $this->load('documentStatuses');
        }

        return $this->getRelation('documentStatuses')
            ->contains(fn (RegistrationDocumentStatus $status): bool => $status->document_type === '_meta_condensed_flow');
    }

    public function hasAdminSignatureApproval(): bool
    {
        return filled($this->admin_signatory_name) && filled($this->admin_signature_path);
    }

    public function hasCompletedDocumentVerification(): bool
    {
        if (! $this->relationLoaded('documents') || ! $this->relationLoaded('documentStatuses')) {
            $this->load('documents', 'documentStatuses');
        }

        $optionalDocs = $this->optionalDocumentFields();
        $docMap = $this->documents->keyBy('document_type');

        foreach ($this->reviewableDocumentFields() as $field) {
            if (! $docMap->has($field)) {
                if (! in_array($field, $optionalDocs, true)) {
                    return false;
                }

                continue;
            }

            if (($this->document_statuses[$field]['status'] ?? 'pending') !== 'verified') {
                return false;
            }
        }

        return true;
    }

    public function isApl01PdfDownloadReady(): bool
    {
        return $this->hasCompletedDocumentVerification() && $this->hasAdminSignatureApproval();
    }

    public function latestDocumentVerificationDate(): ?CarbonInterface
    {
        if (! $this->relationLoaded('documentStatuses')) {
            $this->load('documentStatuses');
        }

        return $this->getRelation('documentStatuses')
            ->where('status', 'verified')
            ->whereIn('document_type', $this->reviewableDocumentFields())
            ->pluck('verified_at')
            ->filter()
            ->sort()
            ->last();
    }

    public function setDocumentStatusesAttribute(array $value): void
    {
        foreach ($value as $documentType => $status) {
            if ($documentType === '_meta' && ($status['condensed_flow'] ?? false)) {
                $this->legacyDocumentStatuses['_meta_condensed_flow'] = ['status' => 'verified'];

                continue;
            }

            if (is_array($status)) {
                $this->legacyDocumentStatuses[$documentType] = [
                    'status' => $status['status'] ?? 'pending',
                    'catatan' => $status['note'] ?? $status['catatan'] ?? null,
                    'verified_by' => $status['verified_by'] ?? null,
                    'verified_at' => $status['verified_at'] ?? null,
                ];
            }
        }
    }

    public function setExamDateAttribute(mixed $value): void
    {
        $this->legacyExamAttributes['exam_date'] = $value;
    }

    public function setExamLocationAttribute(?string $value): void
    {
        $this->legacyExamAttributes['exam_location'] = $value;
    }

    public function setAssessorNameAttribute(?string $value): void
    {
        $this->legacyExamAttributes['assessor_name'] = $value;
    }

    public function setExamResultPathAttribute(?string $value): void
    {
        $this->legacyExamAttributes['exam_result_path'] = $value;
    }

    public function setScoreAttribute(?int $value): void
    {
        $this->legacyExamAttributes['score'] = $value;
    }

    private function syncLegacyRelations(): void
    {
        foreach ($this->legacyDocuments as $documentType => $filePath) {
            if ($filePath === null) {
                continue;
            }

            $this->documents()->updateOrCreate(
                ['document_type' => $documentType],
                ['file_path' => $filePath],
            );
        }

        $this->legacyDocuments = [];

        foreach ($this->legacyDocumentStatuses as $documentType => $status) {
            $this->documentStatuses()->updateOrCreate(
                ['document_type' => $documentType],
                [
                    'status' => $status['status'] ?? 'pending',
                    'catatan' => $status['catatan'] ?? null,
                    'verified_by' => $status['verified_by'] ?? null,
                    'verified_at' => $status['verified_at'] ?? null,
                ],
            );
        }

        $this->legacyDocumentStatuses = [];

        if ($this->legacyExamAttributes !== []) {
            $payload = [
                'exam_date' => $this->legacyExamAttributes['exam_date'] ?? null,
                'exam_location' => $this->legacyExamAttributes['exam_location'] ?? null,
                'score' => $this->legacyExamAttributes['score'] ?? null,
                'exam_result_path' => $this->legacyExamAttributes['exam_result_path'] ?? null,
            ];

            if (filled($this->legacyExamAttributes['assessor_name'] ?? null)) {
                $payload['assessor_id'] = Assessor::query()->firstOrCreate([
                    'nama' => $this->legacyExamAttributes['assessor_name'],
                ])->id;
            }

            $this->exam()->updateOrCreate([], $payload);
            $this->legacyExamAttributes = [];
        }
    }
}
