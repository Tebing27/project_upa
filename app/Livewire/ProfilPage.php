<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

class ProfilPage extends Component
{
    public $activeTab = 'visi';

    /** @var array<int, array{name: string, title: string, prefix: string, image: string}> */
    public $staff = [
        [
            'name' => 'Dr. Kusumajanti, S.Sos., M.M., M.Si.',
            'title' => 'Kepala UPA LUK',
            'prefix' => '',
            'image' => '/assets/Dr.Kusumajanti.webp',
        ],
        [
            'name' => 'Furqaan Fathin Waliyuddin, SH.',
            'title' => 'Staf UPA LUK',
            'prefix' => 'Prefix',
            'image' => '/assets/FurqaanFathinWaliyuddin.webp',
        ],
        [
            'name' => 'Yami',
            'title' => 'Staf UPA LUK',
            'prefix' => 'Prefix',
            'image' => '/assets/yami.webp',
        ],
    ];

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    #[Layout('components.layouts.public')]
    public function render()
    {
        return view('livewire.profil-page');
    }
}
