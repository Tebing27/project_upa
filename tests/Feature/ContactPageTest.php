<?php

use App\Models\FieldValue;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\SectionField;

it('renders the contact page with cms-configured information', function () {
    $page = Page::factory()->create(['slug' => 'kontak', 'title' => 'Kontak']);

    $heroSection = PageSection::query()->create([
        'page_id' => $page->id,
        'section_key' => 'hero',
        'label' => 'Hero Kontak',
        'sort_order' => 1,
        'is_visible' => true,
    ]);

    $contentSection = PageSection::query()->create([
        'page_id' => $page->id,
        'section_key' => 'content',
        'label' => 'Informasi Kontak',
        'sort_order' => 2,
        'is_visible' => true,
    ]);

    $fields = [
        ['section' => $heroSection, 'key' => 'contact_title', 'value' => 'Hubungi Tim UPA LUK'],
        ['section' => $heroSection, 'key' => 'contact_subtitle', 'value' => 'Layanan informasi sertifikasi tersedia pada jam kerja.'],
        ['section' => $contentSection, 'key' => 'address_label', 'value' => 'Alamat Kantor'],
        ['section' => $contentSection, 'key' => 'alamat', 'value' => 'Jl. RS. Fatmawati Raya, Pondok Labu'],
        ['section' => $contentSection, 'key' => 'email_label', 'value' => 'Email Resmi'],
        ['section' => $contentSection, 'key' => 'email', 'value' => 'cms-kontak@upnvj.ac.id'],
        ['section' => $contentSection, 'key' => 'phone_label', 'value' => 'Nomor WhatsApp'],
        ['section' => $contentSection, 'key' => 'telepon', 'value' => '+62 811-1111-2222'],
        ['section' => $contentSection, 'key' => 'maps_embed', 'value' => 'https://maps.example.test/embed'],
    ];

    foreach ($fields as $index => $fieldData) {
        $field = SectionField::query()->create([
            'page_section_id' => $fieldData['section']->id,
            'field_key' => $fieldData['key'],
            'label' => $fieldData['key'],
            'type' => 'text',
            'sort_order' => $index + 1,
        ]);

        FieldValue::query()->create([
            'section_field_id' => $field->id,
            'value' => $fieldData['value'],
        ]);
    }

    $this->get(route('kontak'))
        ->assertOk()
        ->assertSee('Welcome to UPA LUK')
        ->assertSee('Hubungi Tim UPA LUK')
        ->assertSee('Layanan informasi sertifikasi tersedia pada jam kerja.')
        ->assertSee('Alamat Kantor')
        ->assertSee('Email Resmi')
        ->assertSee('Nomor WhatsApp')
        ->assertSee('Jl. RS. Fatmawati Raya, Pondok Labu')
        ->assertSee('cms-kontak@upnvj.ac.id')
        ->assertSee('+62 811-1111-2222')
        ->assertSee('https://maps.example.test/embed', false);
});
