<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessors', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->timestamps();
        });

        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained('registrations')->cascadeOnDelete();
            $table->foreignId('assessor_id')->nullable()->constrained('assessors')->nullOnDelete();
            $table->dateTime('exam_date')->nullable();
            $table->string('exam_location')->nullable();
            $table->integer('score')->nullable();
            $table->string('exam_result_path')->nullable();
            $table->timestamps();
        });

        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('scheme_id')->constrained('schemes')->cascadeOnDelete();
            $table->string('certificate_number')->nullable();
            $table->string('level')->nullable();
            $table->string('status')->default('active'); // active, inactive
            $table->date('expired_date')->nullable();
            $table->string('file_path')->nullable();
            $table->string('result_file_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
        Schema::dropIfExists('exams');
        Schema::dropIfExists('assessors');
    }
};
