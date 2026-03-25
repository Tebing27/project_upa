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
}
