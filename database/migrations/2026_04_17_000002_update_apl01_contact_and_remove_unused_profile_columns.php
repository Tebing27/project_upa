<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users_profiles', function (Blueprint $table): void {
            $table->string('telp_kantor', 20)->nullable()->after('telp_rumah');
        });

        DB::table('users_profiles')
            ->join('users_umum_profiles', 'users_profiles.user_id', '=', 'users_umum_profiles.user_id')
            ->whereNull('users_profiles.telp_kantor')
            ->whereNotNull('users_umum_profiles.no_telp_perusahaan')
            ->select('users_profiles.user_id', 'users_umum_profiles.no_telp_perusahaan')
            ->orderBy('users_profiles.user_id')
            ->get()
            ->each(function (object $profile): void {
                DB::table('users_profiles')
                    ->where('user_id', $profile->user_id)
                    ->update([
                        'telp_kantor' => $profile->no_telp_perusahaan,
                    ]);
            });

        Schema::table('users_profiles', function (Blueprint $table): void {
            $table->dropColumn([
                'domisili_provinsi',
                'domisili_kota',
                'domisili_kecamatan',
                'domisili_kelurahan',
            ]);
        });

        Schema::table('users_umum_profiles', function (Blueprint $table): void {
            $table->dropColumn('nama_pekerjaan');
        });
    }

    public function down(): void
    {
        Schema::table('users_profiles', function (Blueprint $table): void {
            $table->string('domisili_provinsi')->nullable()->after('jenis_kelamin');
            $table->string('domisili_kota')->nullable()->after('domisili_provinsi');
            $table->string('domisili_kecamatan')->nullable()->after('domisili_kota');
            $table->string('domisili_kelurahan')->nullable()->after('domisili_kecamatan');
        });

        Schema::table('users_umum_profiles', function (Blueprint $table): void {
            $table->string('nama_pekerjaan')->nullable()->after('kualifikasi_pendidikan');
        });

        Schema::table('users_profiles', function (Blueprint $table): void {
            $table->dropColumn('telp_kantor');
        });
    }
};
