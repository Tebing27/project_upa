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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('scheme_id')->constrained()->cascadeOnDelete();
            $table->string('fr_apl_01_path')->nullable();
            $table->string('fr_apl_02_path')->nullable();
            $table->string('ktm_path')->nullable();
            $table->string('khs_path')->nullable();
            $table->string('internship_certificate_path')->nullable();
            $table->string('ktp_path')->nullable();
            $table->string('passport_photo_path')->nullable();
            $table->string('payment_reference')->unique();
            $table->string('va_number')->nullable();
            $table->string('status')->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
