<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class DownloadVerifiedApl01PdfController extends Controller
{
    public function __invoke(Registration $registration): SymfonyResponse
    {
        ini_set('memory_limit', '256M');

        abort_unless($registration->user_id === auth()->id(), Response::HTTP_FORBIDDEN);

        $registration->load([
            'documents',
            'documentStatuses',
            'scheme.persyaratanDasars',
            'scheme.persyaratanAdministrasis',
            'scheme.unitKompetensis',
            'user.profile',
            'user.mahasiswaProfile',
            'user.umumProfile',
        ]);

        abort_unless(
            $registration->isApl01PdfDownloadReady(),
            Response::HTTP_NOT_FOUND,
            'Dokumen FR.APL.01 belum siap diunduh.',
        );

        $pdf = Pdf::loadView('documents.apl-01-pdf', [
            'registration' => $registration,
            'assessmentPurposeOptions' => $this->assessmentPurposeOptions($registration),
            'unitKompetensiRows' => $registration->scheme?->unitKompetensis ?? collect(),
            'persyaratanDasarRows' => $this->buildRequirementRows(
                $registration,
                $registration->scheme?->persyaratanDasars?->pluck('deskripsi')->all() ?? [],
                [
                    'ktm_path',
                    'khs_path',
                    'internship_certificate_path',
                ],
                Registration::apl01RequirementLabels(),
            ),
            'persyaratanAdministrasiRows' => $this->buildRequirementRows(
                $registration,
                $registration->scheme?->persyaratanAdministrasis?->pluck('deskripsi')->all() ?? [],
                [
                    'ktp_path',
                    'passport_photo_path',
                    'fr_apl_01_path',
                    'fr_apl_02_path',
                ],
                [
                    'ktp_path' => 'Fotokopi KTP/KK',
                    'passport_photo_path' => 'Pasfoto berwarna 3x4 background merah',
                    'fr_apl_01_path' => 'Dokumen FR.APL.01',
                    'fr_apl_02_path' => 'Dokumen FR.APL.02',
                ],
            ),
            'applicantSignatureImage' => $this->optimizedImageDataUrlFromPublicPath($registration->applicant_signature_path, 420, 120),
            'adminSignatureImage' => $this->optimizedImageDataUrlFromPublicPath($registration->admin_signature_path, 420, 120),
            'passportPhotoImage' => $this->optimizedImageDataUrlFromPublicPath($registration->passport_photo_path, 180, 240, true),
            'applicantSignedDate' => $registration->created_at?->translatedFormat('d F Y'),
            'adminSignedDate' => $registration->latestDocumentVerificationDate()?->translatedFormat('d F Y'),
        ])
            ->setPaper('a4')
            ->setWarnings(false)
            ->setOption([
                'defaultFont' => 'DejaVu Sans',
                'dpi' => 72,
                'isRemoteEnabled' => false,
                'isPhpEnabled' => false,
                'chroot' => base_path(),
            ]);

        return $pdf->download(
            'FR-APL-01-'.Str::slug($registration->scheme?->nama ?? 'sertifikasi').'-'.$registration->id.'.pdf'
        );
    }

    /**
     * @return array<int, array{label: string, checked: bool}>
     */
    private function assessmentPurposeOptions(Registration $registration): array
    {
        return collect(Registration::assessmentPurposeLabels())
            ->map(
                fn (string $label, string $value): array => [
                    'label' => $label,
                    'checked' => $registration->assessment_purpose === $value,
                ]
            )
            ->values()
            ->all();
    }

    /**
     * @param  array<int, string>  $customDescriptions
     * @param  array<int, string>  $documentTypes
     * @param  array<string, string>  $fallbackLabels
     * @return array<int, array{label: string, status: string}>
     */
    private function buildRequirementRows(
        Registration $registration,
        array $customDescriptions,
        array $documentTypes,
        array $fallbackLabels,
    ): array {
        $rows = [];
        $statusMap = $registration->document_statuses;

        foreach ($documentTypes as $index => $documentType) {
            $label = $customDescriptions[$index] ?? $fallbackLabels[$documentType] ?? Str::headline($documentType);
            $status = $statusMap[$documentType]['status'] ?? ($registration->getDocumentPath($documentType) ? 'pending' : 'missing');

            $rows[] = [
                'label' => $label,
                'status' => $status,
            ];
        }

        foreach (array_slice($customDescriptions, count($documentTypes)) as $extraDescription) {
            $rows[] = [
                'label' => $extraDescription,
                'status' => 'missing',
            ];
        }

        return $rows;
    }

    private function optimizedImageDataUrlFromPublicPath(
        ?string $path,
        int $maxWidth,
        int $maxHeight,
        bool $preferJpeg = false,
    ): ?string {
        if (! $path || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        $contents = Storage::disk('public')->get($path);
        $mimeType = Storage::disk('public')->mimeType($path) ?: 'image/png';

        if (! str_starts_with($mimeType, 'image/')) {
            return null;
        }

        if (! function_exists('imagecreatefromstring')) {
            return $this->inlineImageData($contents, $mimeType);
        }

        $image = @imagecreatefromstring($contents);

        if (! $image) {
            return $this->inlineImageData($contents, $mimeType);
        }

        $width = imagesx($image);
        $height = imagesy($image);

        if ($width <= 0 || $height <= 0) {
            imagedestroy($image);

            return null;
        }

        $ratio = min($maxWidth / $width, $maxHeight / $height, 1);
        $targetWidth = max(1, (int) round($width * $ratio));
        $targetHeight = max(1, (int) round($height * $ratio));

        $resized = $image;

        if ($ratio < 1) {
            $canvas = imagecreatetruecolor($targetWidth, $targetHeight);

            imagealphablending($canvas, false);
            imagesavealpha($canvas, true);
            $transparent = imagecolorallocatealpha($canvas, 255, 255, 255, 127);
            imagefill($canvas, 0, 0, $transparent);

            imagecopyresampled($canvas, $image, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);

            $resized = $canvas;
            imagedestroy($image);
        }

        ob_start();

        if ($preferJpeg) {
            imagejpeg($resized, null, 82);
            $outputMime = 'image/jpeg';
        } else {
            imagepng($resized, null, 6);
            $outputMime = 'image/png';
        }

        $binary = (string) ob_get_clean();
        imagedestroy($resized);

        return $this->inlineImageData($binary, $outputMime);
    }

    private function inlineImageData(string $contents, string $mimeType): string
    {
        return 'data:'.$mimeType.';base64,'.base64_encode($contents);
    }
}
