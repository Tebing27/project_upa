<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('user_type')->default('upnvj')->after('role');
            $table->timestamp('profile_completed_at')->nullable()->after('user_type');
            $table->string('domisili_provinsi')->nullable()->after('alamat_rumah');
            $table->string('domisili_kota')->nullable()->after('domisili_provinsi');
            $table->string('domisili_kecamatan')->nullable()->after('domisili_kota');
            $table->string('nama_institusi')->nullable()->after('pendidikan_terakhir');
            $table->string('pekerjaan')->nullable()->after('program_studi');
            $table->string('nama_perusahaan')->nullable()->after('pekerjaan');
            $table->string('jabatan')->nullable()->after('nama_perusahaan');
            $table->text('alamat_perusahaan')->nullable()->after('jabatan');
            $table->string('kode_pos_perusahaan')->nullable()->after('alamat_perusahaan');
            $table->string('no_telp_perusahaan')->nullable()->after('kode_pos_perusahaan');
            $table->string('email_perusahaan')->nullable()->after('no_telp_perusahaan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'user_type',
                'profile_completed_at',
                'domisili_provinsi',
                'domisili_kota',
                'domisili_kecamatan',
                'nama_institusi',
                'pekerjaan',
                'nama_perusahaan',
                'jabatan',
                'alamat_perusahaan',
                'kode_pos_perusahaan',
                'no_telp_perusahaan',
                'email_perusahaan',
            ]);
        });
    }
};
