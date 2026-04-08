<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchemeUnitKompetensi extends Model
{
    protected $table = 'scheme_unit_kompetensis';

    protected $fillable = ['scheme_id', 'kode_unit', 'nama_unit', 'nama_unit_en', 'order'];

    public function scheme(): BelongsTo
    {
        return $this->belongsTo(Scheme::class);
    }
}
