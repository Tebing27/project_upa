<?php

use App\Models\FieldValue;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\SectionField;
use App\Models\User;

it('renders the faq page', function () {
    $this->get(route('faq'))
        ->assertOk()
        ->assertSee('FAQ')
        ->assertSee('Hubungi Kami')
        ->assertSee(route('kontak'), false);
});

it('renders dynamic faq content from cms fields', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $page = Page::factory()->create([
        'slug' => 'faq',
        'title' => 'FAQ',
        'created_by' => $admin->id,
    ]);

    $introSection = PageSection::query()->create([
        'page_id' => $page->id,
        'section_key' => 'faq_intro',
        'label' => 'Header FAQ',
        'sort_order' => 1,
        'is_visible' => true,
    ]);

    $categorySection = PageSection::query()->create([
        'page_id' => $page->id,
        'section_key' => 'faq_categories',
        'label' => 'Kategori FAQ',
        'sort_order' => 2,
        'is_visible' => true,
    ]);

    $itemsSection = PageSection::query()->create([
        'page_id' => $page->id,
        'section_key' => 'faq_items',
        'label' => 'Daftar FAQ',
        'sort_order' => 3,
        'is_visible' => true,
    ]);

    $helpSection = PageSection::query()->create([
        'page_id' => $page->id,
        'section_key' => 'faq_help',
        'label' => 'Bantuan',
        'sort_order' => 4,
        'is_visible' => true,
    ]);

    $faqTitle = SectionField::query()->create([
        'page_section_id' => $introSection->id,
        'field_key' => 'faq_title',
        'label' => 'Judul FAQ',
        'type' => 'text',
        'sort_order' => 1,
    ]);
    FieldValue::query()->create([
        'section_field_id' => $faqTitle->id,
        'value' => 'Pusat Bantuan CMS',
    ]);

    $faqSubtitle = SectionField::query()->create([
        'page_section_id' => $introSection->id,
        'field_key' => 'faq_subtitle',
        'label' => 'Subjudul FAQ',
        'type' => 'textarea',
        'sort_order' => 2,
    ]);
    FieldValue::query()->create([
        'section_field_id' => $faqSubtitle->id,
        'value' => 'Subjudul FAQ dari CMS.',
    ]);

    foreach (['Pendaftaran', 'Asesmen', 'Sertifikat', 'Lainnya'] as $index => $category) {
        $field = SectionField::query()->create([
            'page_section_id' => $categorySection->id,
            'field_key' => 'faq_category_'.($index + 1),
            'label' => 'Kategori '.($index + 1),
            'type' => 'text',
            'sort_order' => $index + 1,
        ]);

        FieldValue::query()->create([
            'section_field_id' => $field->id,
            'value' => $category,
        ]);
    }

    $faqQuestion = SectionField::query()->create([
        'page_section_id' => $itemsSection->id,
        'field_key' => 'faq_entry_question',
        'label' => 'Pertanyaan',
        'type' => 'text',
        'sort_order' => 1,
    ]);
    FieldValue::query()->create([
        'section_field_id' => $faqQuestion->id,
        'value' => 'Bagaimana cara daftar?',
    ]);

    $faqAnswer = SectionField::query()->create([
        'page_section_id' => $itemsSection->id,
        'field_key' => 'faq_entry_answer',
        'label' => 'Jawaban',
        'type' => 'textarea',
        'sort_order' => 2,
    ]);
    FieldValue::query()->create([
        'section_field_id' => $faqAnswer->id,
        'value' => 'Daftar melalui portal resmi.',
    ]);

    $faqCategory = SectionField::query()->create([
        'page_section_id' => $itemsSection->id,
        'field_key' => 'faq_entry_category',
        'label' => 'Kategori',
        'type' => 'text',
        'sort_order' => 3,
    ]);
    FieldValue::query()->create([
        'section_field_id' => $faqCategory->id,
        'value' => 'Pendaftaran',
    ]);

    $helpTitle = SectionField::query()->create([
        'page_section_id' => $helpSection->id,
        'field_key' => 'faq_help_title',
        'label' => 'Judul Bantuan',
        'type' => 'text',
        'sort_order' => 1,
    ]);
    FieldValue::query()->create([
        'section_field_id' => $helpTitle->id,
        'value' => 'Butuh bantuan lanjutan?',
    ]);

    $helpText = SectionField::query()->create([
        'page_section_id' => $helpSection->id,
        'field_key' => 'faq_help_text',
        'label' => 'Deskripsi Bantuan',
        'type' => 'textarea',
        'sort_order' => 2,
    ]);
    FieldValue::query()->create([
        'section_field_id' => $helpText->id,
        'value' => 'Tim admin siap membantu.',
    ]);

    $helpButton = SectionField::query()->create([
        'page_section_id' => $helpSection->id,
        'field_key' => 'faq_help_button_text',
        'label' => 'Teks Tombol',
        'type' => 'text',
        'sort_order' => 3,
    ]);
    FieldValue::query()->create([
        'section_field_id' => $helpButton->id,
        'value' => 'Hubungi Admin',
    ]);

    $this->get(route('faq'))
        ->assertOk()
        ->assertSee('Pusat Bantuan CMS')
        ->assertSee('Subjudul FAQ dari CMS.')
        ->assertSee('Bagaimana cara daftar?')
        ->assertSee('Daftar melalui portal resmi.')
        ->assertSee('Butuh bantuan lanjutan?')
        ->assertSee('Hubungi Admin');
});
