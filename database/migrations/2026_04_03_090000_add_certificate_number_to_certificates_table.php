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
        Schema::table('certificates', function (Blueprint $table) {
            $table->string('certificate_number')->nullable()->after('scheme_name');
        });

        $usedCertificateNumbers = [];

        $certificates = DB::table('certificates')
            ->join('users', 'users.id', '=', 'certificates.user_id')
            ->select(
                'certificates.id',
                'users.user_type',
                'users.nim',
                'users.no_ktp',
            )
            ->orderBy('certificates.id')
            ->get();

        foreach ($certificates as $certificate) {
            $certificateNumber = null;

            if ($certificate->user_type === 'umum') {
                $nik = preg_replace('/\D+/', '', (string) $certificate->no_ktp);

                do {
                    $randomSuffix = str_pad((string) random_int(0, 999999999999), 12, '0', STR_PAD_LEFT);
                    $certificateNumber = 'CERT-'.($nik !== '' ? $nik : 'UMUM').'-'.$randomSuffix;
                } while (isset($usedCertificateNumbers[$certificateNumber]));
            } else {
                $nim = trim((string) $certificate->nim);
                $certificateNumber = $nim !== ''
                    ? 'CERT-'.$nim
                    : 'CERT-'.str_pad((string) random_int(0, 999999999999), 12, '0', STR_PAD_LEFT);
            }

            $usedCertificateNumbers[$certificateNumber] = true;

            DB::table('certificates')
                ->where('id', $certificate->id)
                ->update(['certificate_number' => $certificateNumber]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropColumn('certificate_number');
        });
    }
};
