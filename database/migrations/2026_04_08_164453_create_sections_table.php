<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('section_type_id');
            $table->unsignedInteger('sort_order')->default(1);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });

        Schema::table('content_blocks', function (Blueprint $table) {
            $table->foreign('section_id')->references('id')->on('sections')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('content_blocks', function (Blueprint $table) {
            $table->dropForeign(['section_id']);
        });

        Schema::dropIfExists('sections');
    }
};
