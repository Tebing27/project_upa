<?php

namespace App\Models;

use Database\Factories\RegistrationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Registration extends Model
{
    /** @use HasFactory<RegistrationFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'scheme_id',
        'type',
        'fr_apl_01_path',
        'fr_apl_02_path',
        'ktm_path',
        'khs_path',
        'internship_certificate_path',
        'ktp_path',
        'passport_photo_path',
        'payment_proof_path',
        'payment_reference',
        'va_number',
        'status',
        'exam_result_path',
        'document_statuses',
        'payment_submitted_at',
        'payment_verified_at',
        'exam_date',
        'exam_location',
        'assessor_name',
        'score',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'document_statuses' => 'array',
            'payment_submitted_at' => 'datetime',
            'payment_verified_at' => 'datetime',
            'exam_date' => 'datetime',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function documentLabels(): array
    {
        return [
            'fr_apl_01_path' => 'FR APL 01',
            'fr_apl_02_path' => 'FR APL 02',
            'ktm_path' => 'KTM',
            'khs_path' => 'KHS',
            'internship_certificate_path' => 'Sertifikat Magang',
            'ktp_path' => 'KTP / Scan Foto',
            'passport_photo_path' => 'Pas Foto 3x4',
        ];
    }

    /**
     * @return list<string>
     */
    public static function allDocumentFields(): array
    {
        return array_keys(static::documentLabels());
    }

    public function usesSimplifiedDocumentFlow(): bool
    {
        return (bool) data_get($this->document_statuses, '_meta.condensed_flow', false);
    }

    /**
     * @return list<string>
     */
    public function requiredDocumentFields(): array
    {
        if ($this->usesSimplifiedDocumentFlow()) {
            return [
                'fr_apl_01_path',
                'fr_apl_02_path',
            ];
        }

        return [
            'fr_apl_01_path',
            'fr_apl_02_path',
            'ktm_path',
            'khs_path',
            'ktp_path',
            'passport_photo_path',
        ];
    }

    /**
     * @return list<string>
     */
    public function optionalDocumentFields(): array
    {
        if ($this->usesSimplifiedDocumentFlow()) {
            return [];
        }

        return [
            'internship_certificate_path',
        ];
    }

    /**
     * @return list<string>
     */
    public function reviewableDocumentFields(): array
    {
        return array_values(array_merge(
            $this->requiredDocumentFields(),
            $this->optionalDocumentFields(),
        ));
    }

    /**
     * @return list<string>
     */
    public function visibleDocumentFields(): array
    {
        return static::allDocumentFields();
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

    public function paymentProofStatus(): string
    {
        return $this->document_statuses['payment_proof_path']['status'] ?? ($this->payment_proof_path ? 'pending' : 'missing');
    }

    public function hasPublishedExamSchedule(): bool
    {
        return $this->exam_date !== null
            && filled($this->exam_location)
            && filled($this->assessor_name)
            && filled(AppSetting::whatsappChannelLink());
    }

    /**
     * Get the user that owns the registration.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the scheme that the registration belongs to.
     */
    public function scheme(): BelongsTo
    {
        return $this->belongsTo(Scheme::class);
    }
}
