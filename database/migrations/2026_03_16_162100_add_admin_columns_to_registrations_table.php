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
        Schema::table('registrations', function (Blueprint $table) {
            $table->json('document_statuses')->nullable();
            $table->dateTime('exam_date')->nullable();
            $table->string('exam_location')->nullable();
            $table->string('assessor_name')->nullable();
            $table->integer('score')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn([
                'document_statuses',
                'exam_date',
                'exam_location',
                'assessor_name',
                'score',
            ]);
        });
    }
};
