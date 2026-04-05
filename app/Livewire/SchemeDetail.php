<?php

namespace App\Livewire;

use App\Models\Scheme;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.public')]
class SchemeDetail extends Component
{
    public Scheme $scheme;

    public string $activeTab = 'unit';

    public function mount(Scheme $scheme): void
    {
        $this->scheme = $scheme->load(['unitKompetensis', 'persyaratanDasars', 'persyaratanAdministrasis']);
    }

    public function numberToWords(int $number): string
    {
        $words = [
            0 => 'Nol', 1 => 'Satu', 2 => 'Dua', 3 => 'Tiga', 4 => 'Empat',
            5 => 'Lima', 6 => 'Enam', 7 => 'Tujuh', 8 => 'Delapan', 9 => 'Sembilan',
            10 => 'Sepuluh', 11 => 'Sebelas', 12 => 'Dua Belas', 13 => 'Tiga Belas',
            14 => 'Empat Belas', 15 => 'Lima Belas', 16 => 'Enam Belas',
            17 => 'Tujuh Belas', 18 => 'Delapan Belas', 19 => 'Sembilan Belas',
            20 => 'Dua Puluh',
        ];

        if (isset($words[$number])) {
            return $words[$number];
        }

        return (string) $number;
    }

    public function render(): View
    {
        return view('livewire.scheme-detail');
    }
}
