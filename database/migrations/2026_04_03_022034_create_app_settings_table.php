<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        $legacyWhatsappLink = DB::table('registrations')
            ->whereNotNull('whatsapp_link')
            ->orderBy('id')
            ->value('whatsapp_link');

        if ($legacyWhatsappLink) {
            DB::table('app_settings')->insert([
                'key' => 'whatsapp_channel_link',
                'value' => $legacyWhatsappLink,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_settings');
    }
};
