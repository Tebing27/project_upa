<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    use HasFactory;

    protected ?string $legacySchemeName = null;

    protected $fillable = [
        'user_id', 'scheme_id', 'certificate_number',
        'level', 'status', 'expired_date',
        'file_path', 'result_file_path',
    ];

    protected $casts = [
        'expired_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $certificate): void {
            $certificate->resolveLegacyScheme();
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

    public function getIsExpiredAttribute(): bool
    {
        if (! $this->expired_date) {
            return false;
        }

        return $this->expired_date->isPast();
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->status === 'active' && ! $this->is_expired;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('expired_date')
                    ->orWhere('expired_date', '>=', now()->startOfDay());
            });
    }

    /**
     * Generate a display certificate number.
     */
    public function displayNumber(): string
    {
        if ($this->certificate_number) {
            return $this->certificate_number;
        }

        $identifier = $this->relationLoaded('user') && $this->user
            ? ($this->user->mahasiswaProfile?->nim ?? $this->user->umumProfile?->no_ktp ?? $this->user_id)
            : $this->user_id;

        return 'CERT-'.$identifier.'-'.str_pad($this->id, 12, '0', STR_PAD_LEFT);
    }

    /**
     * Get the scheme name via the scheme relation (replaces old scheme_name column).
     */
    public function getSchemNameAttribute(): ?string
    {
        return $this->scheme?->nama;
    }

    /**
     * Alias accessor for scheme name (used in older blade templates).
     */
    public function getSchemeNameAttribute(): ?string
    {
        return $this->scheme?->nama;
    }

    public function setSchemeNameAttribute(?string $value): void
    {
        $this->legacySchemeName = filled($value) ? $value : null;
    }

    private function resolveLegacyScheme(): void
    {
        if ($this->legacySchemeName === null || filled($this->scheme_id)) {
            return;
        }

        $this->scheme_id = Scheme::query()->firstOrCreate([
            'nama' => $this->legacySchemeName,
        ])->id;
    }
}
