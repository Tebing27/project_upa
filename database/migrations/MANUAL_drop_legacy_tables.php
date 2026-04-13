<?php

// ============================================================
// MANUAL MIGRATION — JANGAN DIJALANKAN OTOMATIS
// Jalankan hanya setelah cms:migrate-legacy berhasil diverifikasi.
// Perintah: php artisan migrate --path=database/migrations/MANUAL_drop_legacy_tables.php
// ============================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Drop semua tabel CMS legacy.
     * JALANKAN HANYA setelah cms:migrate-legacy berhasil diverifikasi.
     */
    public function up(): void
    {
        Schema::dropIfExists('image_contents');
        Schema::dropIfExists('text_contents');
        Schema::dropIfExists('content_blocks');
        Schema::dropIfExists('block_types');
        Schema::dropIfExists('sections');
        Schema::dropIfExists('section_types');
    }

    /**
     * Reverse tidak tersedia — data legacy sudah dipindah ke skema baru.
     */
    public function down(): void
    {
        // Tidak ada rollback untuk drop manual ini.
    }
};
