<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users_mahasiswa_profiles', function (Blueprint $table) {
            $table->string('fakultas')->nullable()->after('status_semester');
            $table->string('program_studi')->nullable()->after('fakultas');
        });

        DB::table('users_profiles')
            ->join('users_mahasiswa_profiles', 'users_profiles.user_id', '=', 'users_mahasiswa_profiles.user_id')
            ->join('users', 'users.id', '=', 'users_profiles.user_id')
            ->where('users.role', 'mahasiswa')
            ->select('users_profiles.user_id', 'users_profiles.fakultas', 'users_profiles.program_studi')
            ->orderBy('users_profiles.user_id')
            ->get()
            ->each(function (object $profile): void {
                DB::table('users_mahasiswa_profiles')
                    ->where('user_id', $profile->user_id)
                    ->update([
                        'fakultas' => $profile->fakultas,
                        'program_studi' => $profile->program_studi,
                    ]);
            });

        Schema::table('users_profiles', function (Blueprint $table) {
            $table->dropColumn(['fakultas', 'program_studi']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users_profiles', function (Blueprint $table) {
            $table->string('fakultas')->nullable()->after('user_id');
            $table->string('program_studi')->nullable()->after('fakultas');
        });

        DB::table('users_mahasiswa_profiles')
            ->join('users_profiles', 'users_mahasiswa_profiles.user_id', '=', 'users_profiles.user_id')
            ->join('users', 'users.id', '=', 'users_mahasiswa_profiles.user_id')
            ->where('users.role', 'mahasiswa')
            ->select('users_mahasiswa_profiles.user_id', 'users_mahasiswa_profiles.fakultas', 'users_mahasiswa_profiles.program_studi')
            ->orderBy('users_mahasiswa_profiles.user_id')
            ->get()
            ->each(function (object $profile): void {
                DB::table('users_profiles')
                    ->where('user_id', $profile->user_id)
                    ->update([
                        'fakultas' => $profile->fakultas,
                        'program_studi' => $profile->program_studi,
                    ]);
            });

        Schema::table('users_mahasiswa_profiles', function (Blueprint $table) {
            $table->dropColumn(['fakultas', 'program_studi']);
        });
    }
};
