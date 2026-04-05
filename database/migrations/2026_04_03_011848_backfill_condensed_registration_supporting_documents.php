<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('registrations')) {
            return;
        }

        DB::table('registrations')
            ->orderBy('id')
            ->get()
            ->each(function (object $registration): void {
                if (
                    blank($registration->fr_apl_01_path)
                    || blank($registration->fr_apl_02_path)
                    || ! blank($registration->ktm_path)
                    || ! blank($registration->khs_path)
                    || ! blank($registration->ktp_path)
                    || ! blank($registration->passport_photo_path)
                ) {
                    return;
                }

                $previousRegistrations = DB::table('registrations')
                    ->where('user_id', $registration->user_id)
                    ->where('id', '<', $registration->id)
                    ->orderByDesc('id')
                    ->get([
                        'ktm_path',
                        'khs_path',
                        'internship_certificate_path',
                        'ktp_path',
                        'passport_photo_path',
                    ]);

                $supportingDocumentPaths = [
                    'ktm_path' => $previousRegistrations->first(fn (object $row): bool => filled($row->ktm_path))?->ktm_path,
                    'khs_path' => $previousRegistrations->first(fn (object $row): bool => filled($row->khs_path))?->khs_path,
                    'internship_certificate_path' => $previousRegistrations->first(fn (object $row): bool => filled($row->internship_certificate_path))?->internship_certificate_path,
                    'ktp_path' => $previousRegistrations->first(fn (object $row): bool => filled($row->ktp_path))?->ktp_path,
                    'passport_photo_path' => $previousRegistrations->first(fn (object $row): bool => filled($row->passport_photo_path))?->passport_photo_path,
                ];

                $documentStatuses = json_decode($registration->document_statuses ?? '[]', true);

                if (! is_array($documentStatuses)) {
                    $documentStatuses = [];
                }

                $documentStatuses['_meta']['condensed_flow'] = true;

                DB::table('registrations')
                    ->where('id', $registration->id)
                    ->update([
                        ...$supportingDocumentPaths,
                        'document_statuses' => json_encode($documentStatuses, JSON_UNESCAPED_UNICODE),
                        'updated_at' => now(),
                    ]);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
