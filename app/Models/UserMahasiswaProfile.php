<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserMahasiswaProfile extends Model
{
    protected $table = 'users_mahasiswa_profiles';

    protected $fillable = [
        'user_id', 'nim', 'total_sks', 'status_semester',
        'fakultas', 'program_studi',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
