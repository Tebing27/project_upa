<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfile extends Model
{
    protected $table = 'users_profiles';

    protected $fillable = [
        'user_id', 'fakultas', 'program_studi', 'tempat_lahir',
        'tanggal_lahir', 'jenis_kelamin', 'domisili_provinsi',
        'domisili_kota', 'domisili_kecamatan', 'domisili_kelurahan',
        'alamat_rumah', 'no_wa',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
