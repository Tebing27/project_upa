<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Scheme extends Model
{
    /** @use HasFactory<\Database\Factories\SchemeFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'faculty',
        'study_program',
        'description',
        'is_active',
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
        ];
    }

    /**
     * Get the registrations for the scheme.
     */
    public function registrations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Registration::class);
    }
}
