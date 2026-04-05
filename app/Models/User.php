<?php

namespace App\Models;

use App\Notifications\VerifyEmailNotification;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable implements MustVerifyEmail
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
        'user_type',
        'profile_completed_at',
        'no_ktp',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat_rumah',
        'domisili_provinsi',
        'domisili_kota',
        'domisili_kecamatan',
        'no_wa',
        'pendidikan_terakhir',
        'nama_institusi',
        'total_sks',
        'status_semester',
        'fakultas',
        'program_studi',
        'pekerjaan',
        'nama_perusahaan',
        'jabatan',
        'alamat_perusahaan',
        'kode_pos_perusahaan',
        'no_telp_perusahaan',
        'email_perusahaan',
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
            'profile_completed_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Determine if the user is an UPNVJ SSO user.
     */
    public function isUpnvjUser(): bool
    {
        return $this->user_type === 'upnvj';
    }

    /**
     * Determine if the user is a general user.
     */
    public function isGeneralUser(): bool
    {
        return $this->user_type === 'umum';
    }

    /**
     * Determine whether the user's profile is complete.
     */
    public function hasCompletedProfile(): bool
    {
        if ($this->isUpnvjUser()) {
            return true;
        }

        return collect([
            'name' => $this->name,
            'email' => $this->email,
            'no_ktp' => $this->no_ktp,
            'jenis_kelamin' => $this->jenis_kelamin,
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir,
            'alamat_rumah' => $this->alamat_rumah,
            'domisili_provinsi' => $this->domisili_provinsi,
            'domisili_kota' => $this->domisili_kota,
            'domisili_kecamatan' => $this->domisili_kecamatan,
            'no_wa' => $this->no_wa,
            'pendidikan_terakhir' => $this->pendidikan_terakhir,
            'nama_institusi' => $this->nama_institusi,
            'program_studi' => $this->program_studi,
            'pekerjaan' => $this->pekerjaan,
        ])->every(static fn (mixed $value): bool => filled($value));
    }

    /**
     * Sync the cached profile completion timestamp.
     */
    public function syncProfileCompletionStatus(): void
    {
        $this->profile_completed_at = $this->hasCompletedProfile() ? now() : null;
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
     * Send the email verification notification.
     */
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmailNotification);
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
     * Determine if the user has ever reached the certificate issuance stage.
     */
    public function hasIssuedCertificate(): bool
    {
        return $this->certificates()->exists()
            || $this->registrations()
                ->where('status', 'sertifikat_terbit')
                ->exists();
    }

    /**
     * Determine if the user has a registration in progress (not yet completed/failed).
     */
    public function hasInProgressRegistration(): bool
    {
        return $this->registrations()
            ->whereNotIn('status', ['sertifikat_terbit', 'tidak_kompeten'])
            ->exists();
    }

    /**
     * Determine if the user has an in-progress registration for a specific scheme.
     */
    public function hasInProgressRegistrationForScheme(int $schemeId): bool
    {
        return $this->registrations()
            ->where('scheme_id', $schemeId)
            ->whereNotIn('status', ['sertifikat_terbit', 'tidak_kompeten'])
            ->exists();
    }

    /**
     * Determine if the user failed their most recent registration for a specific scheme.
     */
    public function hasFailedLatestRegistrationForScheme(int $schemeId): bool
    {
        /** @var Registration|null $latestRegistration */
        $latestRegistration = $this->registrations()
            ->where('scheme_id', $schemeId)
            ->latest('id')
            ->first();

        return $latestRegistration !== null && $latestRegistration->status === 'tidak_kompeten';
    }
}
