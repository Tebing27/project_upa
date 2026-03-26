<?php

namespace App\Livewire;

use App\Models\Certificate;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.guest')]
class CekSertifikat extends Component
{
    public string $search = '';

    public bool $hasSearched = false;

    /** @var array<int, array<string, mixed>> */
    public array $results = [];

    public function cekSertifikat(): void
    {
        $this->validate([
            'search' => ['required', 'string', 'min:2'],
        ], [
            'search.required' => 'Masukkan nama atau nomor sertifikat.',
            'search.min' => 'Minimal 2 karakter.',
        ]);

        $keyword = trim($this->search);
        $this->hasSearched = true;

        $certificates = Certificate::query()
            ->with(['user', 'scheme'])
            ->where(function ($query) use ($keyword): void {
                $query->where('id', $this->extractCertificateId($keyword))
                    ->orWhereHas('user', function ($q) use ($keyword): void {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
            })
            ->latest()
            ->limit(20)
            ->get();

        $this->results = $certificates->map(fn (Certificate $cert): array => [
            'id' => $cert->id,
            'nomor' => 'CERT-'.str_pad((string) $cert->id, 5, '0', STR_PAD_LEFT),
            'nama_pemilik' => $cert->user?->name ?? '-',
            'skema' => $cert->scheme_name,
            'fakultas' => $cert->scheme?->faculty ?? 'Umum',
            'program_studi' => $cert->scheme?->study_program,
            'tanggal_terbit' => $cert->created_at?->translatedFormat('d F Y'),
            'masa_berlaku' => $cert->expired_date
                ? 's.d. '.$cert->expired_date->translatedFormat('d F Y')
                : 'Seumur Hidup',
            'is_active' => $cert->is_active,
            'status' => $cert->is_active ? 'Aktif' : 'Kedaluwarsa',
        ])->toArray();
    }

    /**
     * Extract certificate ID from formatted number like CERT-00001.
     */
    private function extractCertificateId(string $keyword): int
    {
        if (preg_match('/^CERT-?(\d+)$/i', $keyword, $matches)) {
            return (int) $matches[1];
        }

        if (is_numeric($keyword)) {
            return (int) $keyword;
        }

        return 0;
    }

    public function render()
    {
        return view('livewire.cek-sertifikat');
    }
}
