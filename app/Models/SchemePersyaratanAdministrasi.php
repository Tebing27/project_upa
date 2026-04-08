<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchemePersyaratanAdministrasi extends Model
{
    protected $fillable = ['scheme_id', 'deskripsi', 'order'];

    public function scheme(): BelongsTo
    {
        return $this->belongsTo(Scheme::class);
    }
}
