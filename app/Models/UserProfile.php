<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfile extends Model
{
    protected $table = 'users_profiles';

    protected $fillable = [
        'user_id', 'fakultas', 'program_studi', 'tempat_lahir',
        'tanggal_lahir', 'jenis_kelamin', 'alamat_rumah',
        'no_wa', 'kode_pos_rumah', 'telp_rumah', 'telp_kantor',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
