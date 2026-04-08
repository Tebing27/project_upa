<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('scheme_id')->constrained('schemes')->cascadeOnDelete();
            $table->string('type')->default('baru'); // baru, perpanjangan
            $table->string('status')->default('draft');
            $table->string('payment_reference')->nullable()->unique();
            $table->string('va_numer')->nullable(); // based on ERD
            $table->string('payment_proof_path')->nullable();
            $table->timestamp('payment_submitted_at')->nullable();
            $table->timestamp('payment_verified_at')->nullable();
            $table->timestamps();
        });

        Schema::create('registration_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained('registrations')->cascadeOnDelete();
            $table->string('document_type'); // e.g. fr_apl_01_path
            $table->string('file_path');
            $table->timestamps();
        });

        Schema::create('registration_document_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained('registrations')->cascadeOnDelete();
            $table->string('document_type');
            $table->string('status')->default('pending'); // pending, verified, rejected
            $table->text('catatan')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registration_document_statuses');
        Schema::dropIfExists('registration_documents');
        Schema::dropIfExists('registrations');
    }
};
