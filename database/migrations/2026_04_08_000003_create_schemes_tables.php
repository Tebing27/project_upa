<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schemes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faculty_id')->nullable()->constrained('faculties')->nullOnDelete();
            $table->foreignId('study_program_id')->nullable()->constrained('study_programs')->nullOnDelete();
            $table->string('nama');
            $table->string('kode_skema')->nullable();
            $table->string('jenis_skema')->nullable();
            $table->decimal('harga', 10, 2)->nullable();
            $table->string('izin_nirkertas')->nullable();
            $table->text('ringkasan_skema')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('dokumen_skema_path')->nullable();
            $table->string('gambar_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_popular')->default(false);
            $table->timestamps();
        });

        Schema::create('scheme_persyaratan_dasar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scheme_id')->constrained('schemes')->cascadeOnDelete();
            $table->text('deskripsi');
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('scheme_persyaratan_administrasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scheme_id')->constrained('schemes')->cascadeOnDelete();
            $table->text('deskripsi')->nullable(); // In previous code it was nama_dokumen, but ERD says deskripsi. I'll use deskripsi.
            $table->integer('order')->nullable();
            $table->timestamps();
        });

        Schema::create('scheme_unit_kompetensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scheme_id')->constrained('schemes')->cascadeOnDelete();
            $table->string('kode_unit');
            $table->string('nama_unit');
            $table->string('nama_unit_en')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scheme_unit_kompetensis');
        Schema::dropIfExists('scheme_persyaratan_administrasis');
        Schema::dropIfExists('scheme_persyaratan_dasar');
        Schema::dropIfExists('schemes');
    }
};
