<?php

namespace App\Models;

use Database\Factories\CertificateFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    /** @use HasFactory<CertificateFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'scheme_id',
        'scheme_name',
        'certificate_number',
        'level',
        'status',
        'expired_date',
        'file_path',
        'result_file_path',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var list<string>
     */
    protected $appends = ['is_expired'];

    protected static function booted(): void
    {
        static::creating(function (self $certificate): void {
            if (blank($certificate->certificate_number)) {
                $certificate->certificate_number = $certificate->generateCertificateNumber();
            }
        });
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'expired_date' => 'date',
        ];
    }

    /**
     * Determine if the certificate has expired based on expired_date.
     */
    public function getIsExpiredAttribute(): bool
    {
        if (! $this->expired_date) {
            return false;
        }

        return $this->expired_date->isPast();
    }

    /**
     * Determine if the certificate is currently active (not manually deactivated and not expired).
     */
    public function getIsActiveAttribute(): bool
    {
        return $this->status === 'active' && ! $this->is_expired;
    }

    /**
     * Get the display number for the certificate.
     */
    public function displayNumber(): string
    {
        if (filled($this->certificate_number)) {
            return $this->certificate_number;
        }

        if ($this->user?->isGeneralUser()) {
            $nik = preg_replace('/\D+/', '', (string) $this->user->no_ktp);

            return 'CERT-'.substr(str_pad($nik, 12, '0', STR_PAD_LEFT), -12);
        }

        $identifier = trim((string) ($this->user?->nim ?? ''));

        return $identifier !== '' ? 'CERT-'.$identifier : '-';
    }

    /**
     * Scope a query to only include active certificates (status active and not expired).
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active')
            ->where(function (Builder $q): void {
                $q->whereNull('expired_date')
                    ->orWhere('expired_date', '>=', now()->startOfDay());
            });
    }

    /**
     * Get the user that owns the certificate.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the scheme that the certificate belongs to.
     */
    public function scheme(): BelongsTo
    {
        return $this->belongsTo(Scheme::class);
    }

    /**
     * Generate a certificate number based on the owner type.
     */
    private function generateCertificateNumber(): string
    {
        $user = $this->relationLoaded('user') ? $this->user : $this->user()->first();

        if ($user?->isGeneralUser()) {
            $nik = preg_replace('/\D+/', '', (string) $user->no_ktp);

            return 'CERT-'.substr(str_pad($nik, 12, '0', STR_PAD_LEFT), -12);
        }

        $nim = trim((string) ($user?->nim ?? ''));

        if ($nim !== '') {
            return 'CERT-'.$nim;
        }

        return 'CERT-'.$this->generateRandomSuffix();
    }

    /**
     * Generate a 12-digit random suffix for certificate numbers.
     */
    private function generateRandomSuffix(): string
    {
        return str_pad((string) random_int(0, 999999999999), 12, '0', STR_PAD_LEFT);
    }
}
