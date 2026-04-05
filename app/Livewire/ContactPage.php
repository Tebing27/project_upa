<?php

namespace App\Livewire;

use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.public')]
class ContactPage extends Component
{
    public function render(): View
    {
        return view('livewire.contact-page');
    }
}
