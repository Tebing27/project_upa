<?php

namespace App\Livewire;

use App\Models\Certificate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('components.layouts.public')]
class CekSertifikat extends Component
{
    #[Url(as: 'nama', except: '')]
    public string $name = '';

    #[Url(as: 'nomor', except: '')]
    public string $search = '';

    public bool $hasSearched = false;

    /** @var array<int, array<string, mixed>> */
    public array $results = [];

    public function mount(): void
    {
        if (trim($this->name) !== '' || trim($this->search) !== '') {
            $this->cekSertifikat();
        }
    }

    public function cekSertifikat(): void
    {
        $key = 'req-cek-sertifikat:'.request()->ip();

        if (RateLimiter::tooManyAttempts($key, 10)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'search' => "Sistem mendeteksi indikasi bot. Silakan coba lagi dalam {$seconds} detik.",
            ]);
        }

        RateLimiter::hit($key, 60);

        $this->validate([
            'name' => ['required', 'string'],
            'search' => ['required', 'string', 'min:2'],
        ], [
            'name.required' => 'Masukkan nama lengkap.',
            'search.required' => 'Masukkan nomor sertifikat / registrasi.',
            'search.min' => 'Minimal 2 karakter.',
        ]);

        $name = trim($this->name);
        $keyword = trim($this->search);
        $identifier = $this->extractIdentifierFromCertNumber($keyword);
        $this->hasSearched = true;

        $certificates = Certificate::query()
            ->with(['user.mahasiswaProfile', 'user.umumProfile', 'scheme.faculty', 'scheme.studyProgram'])
            ->where(function ($query) use ($keyword, $identifier): void {
                $query->where('certificate_number', $keyword)
                    ->orWhereHas('user', function ($userQuery) use ($identifier): void {
                        $userQuery
                            ->whereHas('mahasiswaProfile', fn ($q) => $q->where('nim', $identifier))
                            ->orWhereHas('umumProfile', fn ($q) => $q->where('no_ktp', $identifier));
                    });
            })
            ->whereHas('user', function ($query) use ($name): void {
                $query->where('nama', 'like', '%'.$name.'%');
            })
            ->latest()
            ->limit(20)
            ->get();

        $this->results = $certificates->map(fn (Certificate $cert): array => [
            'id' => $cert->id,
            'nomor' => $cert->displayNumber(),
            'nama_pemilik' => $cert->user?->nama ?? '-',
            'skema' => $cert->scheme?->nama,
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
     * Extract the owner identifier from a certificate number.
     */
    private function extractIdentifierFromCertNumber(string $keyword): string
    {
        if (preg_match('/^CERT-(.+?)(?:-\d{12})?$/i', $keyword, $matches)) {
            return $matches[1];
        }

        return $keyword;
    }

    public function render(): View
    {
        return view('livewire.cek-sertifikat');
    }
}
