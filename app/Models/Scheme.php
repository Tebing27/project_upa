<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Scheme extends Model
{
    use HasFactory;

    protected ?string $legacyFacultyName = null;

    protected ?string $legacyStudyProgramName = null;

    protected $fillable = [
        'faculty_id', 'study_program_id', 'nama', 'kode_skema',
        'jenis_skema', 'harga', 'izin_nirkertas', 'ringkasan_skema',
        'deskripsi', 'dokumen_skema_path', 'gambar_path',
        'is_active', 'is_popular',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_popular' => 'boolean',
        'harga' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $scheme): void {
            $scheme->resolveLegacyRelations();
        });
    }

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function studyProgram(): BelongsTo
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function persyaratanDasars(): HasMany
    {
        return $this->hasMany(SchemePersyaratanDasar::class)->orderBy('order');
    }

    public function persyaratanAdministrasis(): HasMany
    {
        return $this->hasMany(SchemePersyaratanAdministrasi::class)->orderBy('order');
    }

    public function unitKompetensis(): HasMany
    {
        return $this->hasMany(SchemeUnitKompetensi::class)->orderBy('order');
    }

    /**
     * Backward-compat accessor: $scheme->name → $scheme->nama
     */
    public function getNameAttribute(): ?string
    {
        return $this->nama;
    }

    public function setNameAttribute(?string $value): void
    {
        $this->attributes['nama'] = $value;
    }

    /**
     * Backward-compat accessor: $scheme->description → $scheme->deskripsi
     */
    public function getDescriptionAttribute(): ?string
    {
        return $this->deskripsi;
    }

    public function setDescriptionAttribute(?string $value): void
    {
        $this->attributes['deskripsi'] = $value;
    }

    public function getFacultyAttribute(): ?string
    {
        if (! $this->relationLoaded('faculty')) {
            $this->load('faculty');
        }

        return $this->getRelation('faculty')?->name;
    }

    public function setFacultyAttribute(?string $value): void
    {
        $this->legacyFacultyName = filled($value) ? $value : null;
    }

    public function getStudyProgramAttribute(): ?string
    {
        if (! $this->relationLoaded('studyProgram')) {
            $this->load('studyProgram');
        }

        return $this->getRelation('studyProgram')?->nama;
    }

    public function setStudyProgramAttribute(?string $value): void
    {
        $this->legacyStudyProgramName = filled($value) ? $value : null;
    }

    private function resolveLegacyRelations(): void
    {
        if ($this->legacyFacultyName !== null) {
            $faculty = Faculty::query()->firstOrCreate([
                'name' => $this->legacyFacultyName,
            ]);

            $this->attributes['faculty_id'] = $faculty->id;
        }

        if ($this->legacyStudyProgramName !== null) {
            $facultyId = $this->attributes['faculty_id'] ?? Faculty::query()->firstOrCreate([
                'name' => 'Umum',
            ])->id;

            $studyProgram = StudyProgram::query()
                ->where('faculty_id', $facultyId)
                ->where('nama', $this->legacyStudyProgramName)
                ->first();

            if (! $studyProgram) {
                $studyProgram = StudyProgram::query()->create([
                    'faculty_id' => $facultyId,
                    'nama' => $this->legacyStudyProgramName,
                ]);
            }

            $this->attributes['study_program_id'] = $studyProgram->id;
        }
    }
}
