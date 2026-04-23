<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schemes', function (Blueprint $table): void {
            $table->string('apl_02_template_path')->nullable()->after('dokumen_skema_path');
        });
    }

    public function down(): void
    {
        Schema::table('schemes', function (Blueprint $table): void {
            $table->dropColumn('apl_02_template_path');
        });
    }
};
