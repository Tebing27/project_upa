<?php

namespace App\Models;

use Database\Factories\SchemeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Scheme extends Model
{
    /** @use HasFactory<SchemeFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'kode_skema',
        'jenis_skema',
        'izin_nirkertas',
        'harga',
        'dokumen_skema_path',
        'ringkasan_skema',
        'gambar_path',
        'faculty',
        'study_program',
        'description',
        'is_active',
        'is_popular',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_popular' => 'boolean',
            'harga' => 'decimal:2',
        ];
    }

    /**
     * Get the registrations for the scheme.
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    /**
     * Get the unit kompetensi for the scheme.
     */
    public function unitKompetensis(): HasMany
    {
        return $this->hasMany(SchemeUnitKompetensi::class)->orderBy('order');
    }

    /**
     * Get the persyaratan dasar for the scheme.
     */
    public function persyaratanDasars(): HasMany
    {
        return $this->hasMany(SchemePersyaratanDasar::class)->orderBy('order');
    }

    /**
     * Get the persyaratan administrasi for the scheme.
     */
    public function persyaratanAdministrasis(): HasMany
    {
        return $this->hasMany(SchemePersyaratanAdministrasi::class)->orderBy('order');
    }
}
