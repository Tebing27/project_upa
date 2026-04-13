<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_files', function (Blueprint $table) {
            $table->id();
            $table->string('file_name');
            $table->string('file_path');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('uploaded_at')->nullable();
        });

        Schema::table('image_contents', function (Blueprint $table) {
            $table->foreign('media_file_id')->references('id')->on('media_files')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('image_contents', function (Blueprint $table) {
            $table->dropForeign(['media_file_id']);
        });

        Schema::dropIfExists('media_files');
    }
};
