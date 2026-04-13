<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('image_contents');

        Schema::create('image_contents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('content_block_id')->unique();
            $table->unsignedBigInteger('media_file_id');
            $table->string('alt_text')->nullable();
            $table->string('caption')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('image_contents');
    }
};
