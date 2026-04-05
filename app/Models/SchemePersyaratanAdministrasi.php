<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchemePersyaratanAdministrasi extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'scheme_id',
        'nama_dokumen',
        'order',
    ];

    /**
     * Get the scheme that owns this persyaratan administrasi.
     */
    public function scheme(): BelongsTo
    {
        return $this->belongsTo(Scheme::class);
    }
}
