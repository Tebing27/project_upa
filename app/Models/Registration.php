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
        'payment_reference',
        'va_number',
        'status',
        'exam_result_path',
        'document_statuses',
        'exam_date',
        'exam_location',
        'assessor_id',
        'score',
    ];

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PENDING_VERIFICATION = 'pending_verification';
    public const STATUS_PENDING_PAYMENT = 'pending_payment';
    public const STATUS_PAID = 'paid';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CERTIFICATE_ISSUED = 'sertifikat_terbit';
    public const STATUS_INCOMPETENT = 'tidak_kompeten';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'document_statuses' => 'array',
            'exam_date' => 'datetime',
        ];
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

    /**
     * Get the assessor for the registration.
     */
    public function assessor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assessor_id');
    }
}
