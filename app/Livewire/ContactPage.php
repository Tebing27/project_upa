<?php

namespace App\Livewire;

use App\Models\Page;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.public')]
class ContactPage extends Component
{
    /**
     * @var array<string, string|null>
     */
    public array $contactContent = [];

    public function mount(): void
    {
        $this->contactContent = $this->loadContactContent();
    }

    public function render(): View
    {
        return view('livewire.contact-page', [
            'pageTitle' => $this->contactContent['contact_title'] ?? 'Kontak',
            'pageSubtitle' => $this->contactContent['contact_subtitle'] ?? 'Hubungi kami untuk informasi layanan, pendaftaran, dan dukungan terkait sertifikasi.',
            'addressLabel' => $this->contactContent['address_label'] ?? 'Address',
            'address' => $this->contactContent['alamat'] ?? 'Jalan RS. Fatmawati Raya',
            'emailLabel' => $this->contactContent['email_label'] ?? 'Email Address',
            'email' => $this->contactContent['email'] ?? 'lsp@upnvj.ac.id',
            'phoneLabel' => $this->contactContent['phone_label'] ?? 'Phone Number',
            'phone' => $this->contactContent['telepon'] ?? '+62 812-8028-0908',
            'mapsEmbed' => $this->contactContent['maps_embed'] ?? 'https://www.google.com/maps?q=Universitas%20Pembangunan%20Nasional%20Veteran%20Jakarta&z=15&output=embed',
        ]);
    }

    /**
     * @return array<string, string|null>
     */
    private function loadContactContent(): array
    {
        $page = Page::query()
            ->where('slug', 'kontak')
            ->with(['pageSections.fields.value'])
            ->first();

        if (! $page) {
            return [];
        }

        $content = [];

        foreach ($page->pageSections as $section) {
            foreach ($section->fields as $field) {
                $content[$field->field_key] = $field->value?->value;
            }
        }

        return $content;
    }
}
