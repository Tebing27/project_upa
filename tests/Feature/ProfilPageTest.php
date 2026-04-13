<?php

use App\Models\FieldValue;
use App\Models\MediaFile;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\SectionField;
use App\Models\User;

it('renders the profil page', function () {
    $this->get(route('profil'))
        ->assertOk()
        ->assertSee('UPA LUK')
        ->assertSee('Profil')
        ->assertSee('Bagan Susunan Pengurus')
        ->assertSee('images/struktur-organisasi-luk.jpeg', false);
});

it('renders dynamic profil content from cms fields', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $page = Page::factory()->create([
        'slug' => 'profil',
        'title' => 'Profil',
        'created_by' => $admin->id,
    ]);

    $introSection = PageSection::query()->create([
        'page_id' => $page->id,
        'section_key' => 'profil_intro',
        'label' => 'Profil Lembaga',
        'sort_order' => 1,
        'is_visible' => true,
    ]);

    $tabsSection = PageSection::query()->create([
        'page_id' => $page->id,
        'section_key' => 'profil_tabs',
        'label' => 'Tabs',
        'sort_order' => 2,
        'is_visible' => true,
    ]);

    $leadershipSection = PageSection::query()->create([
        'page_id' => $page->id,
        'section_key' => 'profil_leadership',
        'label' => 'Pimpinan',
        'sort_order' => 3,
        'is_visible' => true,
    ]);

    $structureSection = PageSection::query()->create([
        'page_id' => $page->id,
        'section_key' => 'profil_structure',
        'label' => 'Struktur',
        'sort_order' => 4,
        'is_visible' => true,
    ]);

    $profilHeading = SectionField::query()->create([
        'page_section_id' => $introSection->id,
        'field_key' => 'profil_heading',
        'label' => 'Judul Profil',
        'type' => 'text',
        'sort_order' => 1,
    ]);
    FieldValue::query()->create([
        'section_field_id' => $profilHeading->id,
        'value' => 'Profil Dinamis UPA LUK',
    ]);

    $profilText = SectionField::query()->create([
        'page_section_id' => $introSection->id,
        'field_key' => 'profil_text',
        'label' => 'Teks Profil',
        'type' => 'rich_text',
        'sort_order' => 2,
    ]);
    FieldValue::query()->create([
        'section_field_id' => $profilText->id,
        'value' => '<p>Konten profil dari CMS.</p>',
    ]);

    $visiText = SectionField::query()->create([
        'page_section_id' => $tabsSection->id,
        'field_key' => 'visi_text',
        'label' => 'Isi Visi',
        'type' => 'textarea',
        'sort_order' => 1,
    ]);
    FieldValue::query()->create([
        'section_field_id' => $visiText->id,
        'value' => 'Visi dinamis dari CMS.',
    ]);

    $misiItems = SectionField::query()->create([
        'page_section_id' => $tabsSection->id,
        'field_key' => 'misi_items',
        'label' => 'Daftar Misi',
        'type' => 'textarea',
        'sort_order' => 2,
    ]);
    FieldValue::query()->create([
        'section_field_id' => $misiItems->id,
        'value' => "Misi pertama\nMisi kedua",
    ]);

    $leaderName = SectionField::query()->create([
        'page_section_id' => $leadershipSection->id,
        'field_key' => 'leader_name',
        'label' => 'Nama Pimpinan',
        'type' => 'text',
        'sort_order' => 1,
    ]);
    FieldValue::query()->create([
        'section_field_id' => $leaderName->id,
        'value' => 'Prof. Dinamis',
    ]);

    $leaderTitle = SectionField::query()->create([
        'page_section_id' => $leadershipSection->id,
        'field_key' => 'leader_title',
        'label' => 'Jabatan Pimpinan',
        'type' => 'text',
        'sort_order' => 2,
    ]);
    FieldValue::query()->create([
        'section_field_id' => $leaderTitle->id,
        'value' => 'Ketua UPA LUK',
    ]);

    $mediaFile = MediaFile::factory()->create([
        'file_path' => 'cms/images/struktur-profil.png',
        'uploaded_by' => $admin->id,
    ]);

    $structureImage = SectionField::query()->create([
        'page_section_id' => $structureSection->id,
        'field_key' => 'structure_image',
        'label' => 'Gambar Struktur',
        'type' => 'image',
        'sort_order' => 1,
    ]);
    FieldValue::query()->create([
        'section_field_id' => $structureImage->id,
        'media_file_id' => $mediaFile->id,
    ]);

    $this->get(route('profil'))
        ->assertOk()
        ->assertSee('Profil Dinamis UPA LUK')
        ->assertSee('Konten profil dari CMS.', false)
        ->assertSee('Visi dinamis dari CMS.')
        ->assertSee('Misi pertama')
        ->assertSee('Prof. Dinamis')
        ->assertSee('/storage/cms/images/struktur-profil.png', false);
});
