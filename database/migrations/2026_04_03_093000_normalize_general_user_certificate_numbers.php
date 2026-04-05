<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $certificates = DB::table('certificates')
            ->join('users', 'users.id', '=', 'certificates.user_id')
            ->where('users.user_type', 'umum')
            ->select('certificates.id', 'users.no_ktp')
            ->orderBy('certificates.id')
            ->get();

        foreach ($certificates as $certificate) {
            $nik = preg_replace('/\D+/', '', (string) $certificate->no_ktp);

            DB::table('certificates')
                ->where('id', $certificate->id)
                ->update([
                    'certificate_number' => 'CERT-'.substr(str_pad($nik, 12, '0', STR_PAD_LEFT), -12),
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {}
};
