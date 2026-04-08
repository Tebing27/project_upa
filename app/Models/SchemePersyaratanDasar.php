<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchemePersyaratanDasar extends Model
{
    protected $table = 'scheme_persyaratan_dasar';

    protected $fillable = ['scheme_id', 'deskripsi', 'order'];

    public function scheme(): BelongsTo
    {
        return $this->belongsTo(Scheme::class);
    }
}
