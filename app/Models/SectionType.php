<?php

namespace App\Models;

use Database\Factories\SectionTypeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SectionType extends Model
{
    /** @use HasFactory<SectionTypeFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }
}
