<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchemeUnitKompetensi extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'scheme_id',
        'kode_unit',
        'nama_unit',
        'nama_unit_en',
        'order',
    ];

    /**
     * Get the scheme that owns this unit kompetensi.
     */
    public function scheme(): BelongsTo
    {
        return $this->belongsTo(Scheme::class);
    }
}
