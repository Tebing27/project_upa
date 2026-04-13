<?php

namespace App\Models;

use App\Notifications\VerifyEmailNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /** @var array<string, mixed> */
    protected array $legacyProfileAttributes = [];

    /** @var array<string, mixed> */
    protected array $legacyMahasiswaProfileAttributes = [];

    /** @var array<string, mixed> */
    protected array $legacyUmumProfileAttributes = [];

    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'profile_completed_at',
    ];

    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'profile_completed_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function booted(): void
    {
        static::saved(function (self $user): void {
            $user->syncLegacyAttributesToRelations();
        });
    }

    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    public function mahasiswaProfile(): HasOne
    {
        return $this->hasOne(UserMahasiswaProfile::class);
    }

    public function umumProfile(): HasOne
    {
        return $this->hasOne(UserUmumProfile::class);
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public function isGeneralUser(): bool
    {
        return $this->role === 'umum';
    }

    public function isUpnvjUser(): bool
    {
        return $this->role === 'mahasiswa';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmailNotification);
    }

    public function hasActiveCertificateForScheme(int $schemeId): bool
    {
        return $this->certificates()
            ->where('scheme_id', $schemeId)
            ->active()
            ->exists();
    }

    public function hasAnyCertificateForScheme(int $schemeId): bool
    {
        return $this->certificates()
            ->where('scheme_id', $schemeId)
            ->exists();
    }

    public function hasIssuedCertificate(): bool
    {
        return $this->certificates()->exists()
            || $this->registrations()
                ->where('status', 'sertifikat_terbit')
                ->exists();
    }

    public function hasInProgressRegistration(): bool
    {
        return $this->registrations()
            ->whereNotIn('status', ['sertifikat_terbit', 'tidak_kompeten'])
            ->exists();
    }

    public function hasInProgressRegistrationForScheme(int $schemeId): bool
    {
        return $this->registrations()
            ->where('scheme_id', $schemeId)
            ->whereNotIn('status', ['sertifikat_terbit', 'tidak_kompeten'])
            ->exists();
    }

    public function hasCompletedProfile(): bool
    {
        if ($this->isUpnvjUser()) {
            return true;
        }

        if (! $this->relationLoaded('umumProfile') || ! $this->umumProfile) {
            $this->load('umumProfile');
        }

        if (! $this->relationLoaded('profile') || ! $this->profile) {
            $this->load('profile');
        }

        if (! $this->umumProfile || ! $this->profile) {
            return false;
        }

        return collect([
            'nama' => $this->nama,
            'email' => $this->email,
            'no_ktp' => $this->umumProfile->no_ktp,
            'jenis_kelamin' => $this->profile->jenis_kelamin,
            'tempat_lahir' => $this->profile->tempat_lahir,
            'tanggal_lahir' => $this->profile->tanggal_lahir,
            'alamat_rumah' => $this->profile->alamat_rumah,
            'domisili_provinsi' => $this->profile->domisili_provinsi,
            'domisili_kota' => $this->profile->domisili_kota,
            'domisili_kecamatan' => $this->profile->domisili_kecamatan,
            'no_wa' => $this->profile->no_wa,
            'pendidikan_terakhir' => $this->umumProfile->pendidikan_terakhir,
            'nama_institusi' => $this->umumProfile->nama_institusi,
            'pekerjaan' => $this->umumProfile->nama_pekerjaan,
        ])->every(static fn (mixed $value): bool => filled($value));
    }

    public function syncProfileCompletionStatus(): void
    {
        $this->profile_completed_at = $this->hasCompletedProfile() ? now() : null;
    }

    public function initials(): string
    {
        return str($this->nama ?? '')
            ->trim()
            ->explode(' ')
            ->filter()
            ->take(2)
            ->map(static fn (string $part): string => str($part)->substr(0, 1)->upper()->toString())
            ->implode('');
    }

    /**
     * Backward-compat accessor: $user->name → $user->nama
     */
    public function getNameAttribute(): ?string
    {
        return $this->nama;
    }

    public function getUserTypeAttribute(): string
    {
        return $this->role;
    }

    public function setNameAttribute(?string $value): void
    {
        $this->attributes['nama'] = $value;
    }

    public function setRoleAttribute(?string $value): void
    {
        $this->attributes['role'] = $value === 'user' ? 'umum' : $value;
    }

    public function setNimAttribute(?string $value): void
    {
        $this->legacyMahasiswaProfileAttributes['nim'] = $value;
    }

    public function setNoKtpAttribute(?string $value): void
    {
        $this->legacyUmumProfileAttributes['no_ktp'] = $value;
    }

    public function setFakultasAttribute(?string $value): void
    {
        $this->legacyProfileAttributes['fakultas'] = $value;
    }

    public function setProgramStudiAttribute(?string $value): void
    {
        $this->legacyProfileAttributes['program_studi'] = $value;
    }

    public function setTempatLahirAttribute(?string $value): void
    {
        $this->legacyProfileAttributes['tempat_lahir'] = $value;
    }

    public function setTanggalLahirAttribute(mixed $value): void
    {
        $this->legacyProfileAttributes['tanggal_lahir'] = $value;
    }

    public function setJenisKelaminAttribute(?string $value): void
    {
        $this->legacyProfileAttributes['jenis_kelamin'] = $value;
    }

    public function setAlamatRumahAttribute(?string $value): void
    {
        $this->legacyProfileAttributes['alamat_rumah'] = $value;
    }

    public function setDomisiliProvinsiAttribute(?string $value): void
    {
        $this->legacyProfileAttributes['domisili_provinsi'] = $value;
    }

    public function setDomisiliKotaAttribute(?string $value): void
    {
        $this->legacyProfileAttributes['domisili_kota'] = $value;
    }

    public function setDomisiliKecamatanAttribute(?string $value): void
    {
        $this->legacyProfileAttributes['domisili_kecamatan'] = $value;
    }

    public function setNoWaAttribute(?string $value): void
    {
        $this->legacyProfileAttributes['no_wa'] = $value;
    }

    public function setPendidikanTerakhirAttribute(?string $value): void
    {
        $this->legacyUmumProfileAttributes['pendidikan_terakhir'] = $value;
    }

    public function setPekerjaanAttribute(?string $value): void
    {
        $this->legacyUmumProfileAttributes['nama_pekerjaan'] = $value;
    }

    public function setNamaInstitusiAttribute(?string $value): void
    {
        $this->legacyUmumProfileAttributes['nama_institusi'] = $value;
    }

    /**
     * Backward-compat accessor: $user->nim → $user->mahasiswaProfile->nim
     */
    public function getNimAttribute(): ?string
    {
        if (! $this->relationLoaded('mahasiswaProfile')) {
            $this->load('mahasiswaProfile');
        }

        return $this->mahasiswaProfile?->nim;
    }

    /**
     * Backward-compat accessor: $user->no_ktp → $user->umumProfile->no_ktp
     */
    public function getNoKtpAttribute(): ?string
    {
        if (! $this->relationLoaded('umumProfile')) {
            $this->load('umumProfile');
        }

        return $this->umumProfile?->no_ktp;
    }

    /**
     * Backward-compat accessor: $user->program_studi → $user->profile->program_studi
     */
    public function getProgramStudiAttribute(): ?string
    {
        if (! $this->relationLoaded('profile')) {
            $this->load('profile');
        }

        return $this->profile?->program_studi;
    }

    public function getFakultasAttribute(): ?string
    {
        if (! $this->relationLoaded('profile')) {
            $this->load('profile');
        }

        return $this->profile?->fakultas;
    }

    public function getTempatLahirAttribute(): ?string
    {
        if (! $this->relationLoaded('profile')) {
            $this->load('profile');
        }

        return $this->profile?->tempat_lahir;
    }

    public function getTanggalLahirAttribute(): mixed
    {
        if (! $this->relationLoaded('profile')) {
            $this->load('profile');
        }

        return $this->profile?->tanggal_lahir;
    }

    public function getJenisKelaminAttribute(): ?string
    {
        if (! $this->relationLoaded('profile')) {
            $this->load('profile');
        }

        return $this->profile?->jenis_kelamin;
    }

    public function getAlamatRumahAttribute(): ?string
    {
        if (! $this->relationLoaded('profile')) {
            $this->load('profile');
        }

        return $this->profile?->alamat_rumah;
    }

    public function getDomisiliProvinsiAttribute(): ?string
    {
        if (! $this->relationLoaded('profile')) {
            $this->load('profile');
        }

        return $this->profile?->domisili_provinsi;
    }

    public function getDomisiliKotaAttribute(): ?string
    {
        if (! $this->relationLoaded('profile')) {
            $this->load('profile');
        }

        return $this->profile?->domisili_kota;
    }

    public function getDomisiliKecamatanAttribute(): ?string
    {
        if (! $this->relationLoaded('profile')) {
            $this->load('profile');
        }

        return $this->profile?->domisili_kecamatan;
    }

    public function getNoWaAttribute(): ?string
    {
        if (! $this->relationLoaded('profile')) {
            $this->load('profile');
        }

        return $this->profile?->no_wa;
    }

    public function getPendidikanTerakhirAttribute(): ?string
    {
        if (! $this->relationLoaded('umumProfile')) {
            $this->load('umumProfile');
        }

        return $this->umumProfile?->pendidikan_terakhir;
    }

    public function getPekerjaanAttribute(): ?string
    {
        if (! $this->relationLoaded('umumProfile')) {
            $this->load('umumProfile');
        }

        return $this->umumProfile?->nama_pekerjaan;
    }

    public function getNamaInstitusiAttribute(): ?string
    {
        if (! $this->relationLoaded('umumProfile')) {
            $this->load('umumProfile');
        }

        return $this->umumProfile?->nama_institusi;
    }

    public function getNamaPerusahaanAttribute(): ?string
    {
        if (! $this->relationLoaded('umumProfile')) {
            $this->load('umumProfile');
        }

        return $this->umumProfile?->nama_perusahaan;
    }

    public function getJabatanAttribute(): ?string
    {
        if (! $this->relationLoaded('umumProfile')) {
            $this->load('umumProfile');
        }

        return $this->umumProfile?->jabatan;
    }

    public function getAlamatPerusahaanAttribute(): ?string
    {
        if (! $this->relationLoaded('umumProfile')) {
            $this->load('umumProfile');
        }

        return $this->umumProfile?->alamat_perusahaan;
    }

    public function getKodePosPerusahaanAttribute(): ?string
    {
        if (! $this->relationLoaded('umumProfile')) {
            $this->load('umumProfile');
        }

        return $this->umumProfile?->kode_pos_perusahaan;
    }

    public function getNoTelpPerusahaanAttribute(): ?string
    {
        if (! $this->relationLoaded('umumProfile')) {
            $this->load('umumProfile');
        }

        return $this->umumProfile?->no_telp_perusahaan;
    }

    public function getEmailPerusahaanAttribute(): ?string
    {
        if (! $this->relationLoaded('umumProfile')) {
            $this->load('umumProfile');
        }

        return $this->umumProfile?->email_perusahaan;
    }

    public function getTotalSksAttribute(): ?int
    {
        if (! $this->relationLoaded('mahasiswaProfile')) {
            $this->load('mahasiswaProfile');
        }

        return $this->mahasiswaProfile?->total_sks;
    }

    public function getStatusSemesterAttribute(): ?string
    {
        if (! $this->relationLoaded('mahasiswaProfile')) {
            $this->load('mahasiswaProfile');
        }

        return $this->mahasiswaProfile?->status_semester;
    }

    /**
     * Check if the user's latest registration for a scheme has failed (tidak_kompeten).
     */
    public function hasFailedLatestRegistrationForScheme(int $schemeId): bool
    {
        return $this->registrations()
            ->where('scheme_id', $schemeId)
            ->where('status', 'tidak_kompeten')
            ->exists();
    }

    private function syncLegacyAttributesToRelations(): void
    {
        if ($this->legacyProfileAttributes !== []) {
            $this->profile()->updateOrCreate([], $this->legacyProfileAttributes);
            $this->legacyProfileAttributes = [];
        }

        if ($this->legacyMahasiswaProfileAttributes !== []) {
            $this->mahasiswaProfile()->updateOrCreate([], $this->legacyMahasiswaProfileAttributes);
            $this->legacyMahasiswaProfileAttributes = [];
        }

        if ($this->legacyUmumProfileAttributes !== []) {
            $this->umumProfile()->updateOrCreate([], $this->legacyUmumProfileAttributes);
            $this->legacyUmumProfileAttributes = [];
        }
    }
}
