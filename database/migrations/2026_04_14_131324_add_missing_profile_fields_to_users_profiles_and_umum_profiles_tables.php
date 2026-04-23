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
        Schema::table('users_profiles', function (Blueprint $table) {
            $table->string('kode_pos_rumah', 20)->nullable()->after('alamat_rumah');
            $table->string('telp_rumah', 20)->nullable()->after('kode_pos_rumah');
        });

        Schema::table('users_umum_profiles', function (Blueprint $table) {
            $table->string('fax_perusahaan', 20)->nullable()->after('email_perusahaan');
            $table->string('kebangsaan', 100)->nullable()->after('no_ktp');
        });
    }

    public function down(): void
    {
        Schema::table('users_profiles', function (Blueprint $table) {
            $table->dropColumn(['kode_pos_rumah', 'telp_rumah']);
        });

        Schema::table('users_umum_profiles', function (Blueprint $table) {
            $table->dropColumn(['fax_perusahaan', 'kebangsaan']);
        });
    }
};
