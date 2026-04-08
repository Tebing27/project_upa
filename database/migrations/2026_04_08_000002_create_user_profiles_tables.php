<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('fakultas')->nullable();
            $table->string('program_studi')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->string('domisili_provinsi')->nullable();
            $table->string('domisili_kota')->nullable();
            $table->string('domisili_kecamatan')->nullable();
            $table->string('domisili_kelurahan')->nullable();
            $table->text('alamat_rumah')->nullable();
            $table->string('no_wa')->nullable();
            $table->timestamps();
        });

        Schema::create('users_mahasiswa_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('nim')->nullable()->unique();
            $table->integer('total_sks')->nullable();
            $table->string('status_semester')->nullable();
            $table->timestamps();
        });

        Schema::create('users_umum_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('no_ktp')->nullable();
            $table->string('pendidikan_terakhir')->nullable();
            $table->string('nama_pekerjaan')->nullable();
            $table->string('nama_perusahaan')->nullable();
            $table->string('jabatan')->nullable();
            $table->text('alamat_perusahaan')->nullable();
            $table->string('kode_pos_perusahaan')->nullable();
            $table->string('no_telp_perusahaan')->nullable();
            $table->string('email_perusahaan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users_umum_profiles');
        Schema::dropIfExists('users_mahasiswa_profiles');
        Schema::dropIfExists('users_profiles');
    }
};
