<?php

namespace App\Livewire;

use Livewire\Component;

class UserCertificatesPage extends Component
{
    public function render()
    {
        $user = auth()->user();

        return view('livewire.user-certificates-page', [
            'activeCertificate' => $user->certificates()
                ->where('status', 'active')
                ->latest('expired_date')
                ->first(),
            'certificates' => $user->certificates()
                ->latest()
                ->get(),
        ]);
    }
}
