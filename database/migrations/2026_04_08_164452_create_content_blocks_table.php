<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('content_blocks');

        Schema::create('content_blocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('section_id');
            $table->foreignId('block_type_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('sort_order')->default(1);
            $table->timestamps();
        });

        Schema::table('image_contents', function (Blueprint $table) {
            $table->foreign('content_block_id')->references('id')->on('content_blocks')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('image_contents', function (Blueprint $table) {
            $table->dropForeign(['content_block_id']);
        });

        Schema::dropIfExists('content_blocks');
    }
};
