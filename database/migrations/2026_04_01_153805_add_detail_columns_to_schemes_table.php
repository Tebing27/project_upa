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
        Schema::table('schemes', function (Blueprint $table) {
            $table->string('kode_skema')->nullable()->after('name');
            $table->string('jenis_skema')->nullable()->after('kode_skema');
            $table->string('izin_nirkertas')->nullable()->after('jenis_skema');
            $table->decimal('harga', 12, 2)->nullable()->after('izin_nirkertas');
            $table->string('dokumen_skema_path')->nullable()->after('harga');
            $table->text('ringkasan_skema')->nullable()->after('dokumen_skema_path');
            $table->string('gambar_path')->nullable()->after('ringkasan_skema');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schemes', function (Blueprint $table) {
            $table->dropColumn([
                'kode_skema',
                'jenis_skema',
                'izin_nirkertas',
                'harga',
                'dokumen_skema_path',
                'ringkasan_skema',
                'gambar_path',
            ]);
        });
    }
};
