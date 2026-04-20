<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserUmumProfile extends Model
{
    protected $table = 'users_umum_profiles';

    protected $fillable = [
        'user_id', 'no_ktp', 'kualifikasi_pendidikan', 'nama_perusahaan',
        'jabatan', 'alamat_perusahaan',
        'kode_pos_perusahaan', 'no_telp_perusahaan', 'email_perusahaan',
        'fax_perusahaan', 'kebangsaan',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
