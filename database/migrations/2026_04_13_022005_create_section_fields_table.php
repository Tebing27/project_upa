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
        Schema::create('section_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_section_id')->constrained('page_sections')->cascadeOnDelete();
            $table->string('field_key');
            $table->string('label');
            $table->enum('type', ['text', 'textarea', 'rich_text', 'url', 'image']);
            $table->integer('sort_order')->default(1);
            $table->string('description')->nullable();
            $table->timestamps();

            $table->unique(['page_section_id', 'field_key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('section_fields');
    }
};
