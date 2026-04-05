<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchemePersyaratanDasar extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'scheme_id',
        'deskripsi',
        'order',
    ];

    /**
     * Get the scheme that owns this persyaratan dasar.
     */
    public function scheme(): BelongsTo
    {
        return $this->belongsTo(Scheme::class);
    }
}
