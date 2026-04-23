<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users_umum_profiles', function (Blueprint $table): void {
            $table->renameColumn('pendidikan_terakhir', 'kualifikasi_pendidikan');
        });

        DB::table('users_umum_profiles')
            ->whereNull('nama_perusahaan')
            ->whereNotNull('nama_institusi')
            ->update([
                'nama_perusahaan' => DB::raw('nama_institusi'),
            ]);

        Schema::table('users_umum_profiles', function (Blueprint $table): void {
            $table->dropColumn('nama_institusi');
        });
    }

    public function down(): void
    {
        Schema::table('users_umum_profiles', function (Blueprint $table): void {
            $table->string('nama_institusi')->nullable()->after('kualifikasi_pendidikan');
        });

        DB::table('users_umum_profiles')
            ->whereNull('nama_institusi')
            ->whereNotNull('nama_perusahaan')
            ->update([
                'nama_institusi' => DB::raw('nama_perusahaan'),
            ]);

        Schema::table('users_umum_profiles', function (Blueprint $table): void {
            $table->renameColumn('kualifikasi_pendidikan', 'pendidikan_terakhir');
        });
    }
};
