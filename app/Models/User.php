<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'nim',
        'email',
        'password',
        'no_ktp',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat_rumah',
        'no_wa',
        'pendidikan_terakhir',
        'total_sks',
        'status_semester',
        'study_program_id',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class, 'study_program_id');
    }

    /**
     * Get the registrations for the user.
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    /**
     * Get the certificates for the user.
     */
    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    /**
     * Determine if the user has an active certificate for a given scheme.
     */
    public function hasActiveCertificateForScheme(int $schemeId): bool
    {
        return $this->certificates()
            ->where('scheme_id', $schemeId)
            ->active()
            ->exists();
    }

    /**
     * Determine if the user has ANY certificate (active or inactive) for a given scheme.
     */
    public function hasAnyCertificateForScheme(int $schemeId): bool
    {
        return $this->certificates()
            ->where('scheme_id', $schemeId)
            ->exists();
    }

    /**
     * Determine if the user has a registration in progress (not yet completed/failed).
     */
    public function hasInProgressRegistration(): bool
    {
        return $this->registrations()
            ->whereNotIn('status', [Registration::STATUS_CERTIFICATE_ISSUED, Registration::STATUS_INCOMPETENT])
            ->exists();
    }

    /**
     * Determine if the user has an in-progress registration for a specific scheme.
     */
    public function hasInProgressRegistrationForScheme(int $schemeId): bool
    {
        return $this->registrations()
            ->where('scheme_id', $schemeId)
            ->whereNotIn('status', [Registration::STATUS_CERTIFICATE_ISSUED, Registration::STATUS_INCOMPETENT])
            ->exists();
    }
}
