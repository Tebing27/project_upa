<?php

use App\Models\Article;
use App\Models\MediaFile;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\SectionField;
use App\Models\User;
use Database\Seeders\CmsContentSeeder;
use Database\Seeders\PageFieldsSeeder;

it('seeds dummy cms article and gallery pages', function () {
    $this->seed(CmsContentSeeder::class);

    expect(Article::query()->count())->toBe(3);
    expect(Page::query()->galleryEntries()->count())->toBe(6);

    $this->assertDatabaseHas('articles', [
        'slug' => 'berita-pembukaan-skema-sertifikasi-2026',
        'title' => 'Pembukaan Skema Sertifikasi 2026',
        'status' => 'published',
    ]);

    $this->assertDatabaseHas('pages', [
        'slug' => 'galeri-kegiatan-asesmen-lab-komputer',
        'title' => 'Pelatihan Asesor Internal',
        'is_published' => true,
    ]);

    expect(MediaFile::query()->count())->toBe(3);

    $this->assertDatabaseHas('media_files', [
        'file_path' => 'https://i.ibb.co.com/yBPy1KGh/kegiatan-1.jpg',
    ]);
});

it('seeds page field structure via PageFieldsSeeder', function () {
    // Buat page yang dibutuhkan seeder
    $admin = User::factory()->create(['role' => 'admin']);
    $slugsNeeded = ['home', 'profil', 'kontak', 'media', 'instagram', 'youtube', 'facebook', 'faq', 'skema', 'alur-sertifikasi', 'tempat-uji', 'jadwal', 'cek-sertifikat', 'artikel', 'galeri'];
    foreach ($slugsNeeded as $slug) {
        Page::factory()->create(['slug' => $slug, 'created_by' => $admin->id]);
    }

    $this->seed(PageFieldsSeeder::class);

    // Pastikan struktur home page ada
    $this->assertDatabaseHas('page_sections', ['section_key' => 'hero']);
    $this->assertDatabaseHas('page_sections', ['section_key' => 'about']);
    $this->assertDatabaseHas('page_sections', ['section_key' => 'video']);
    $this->assertDatabaseHas('page_sections', ['section_key' => 'whatsapp']);
    $this->assertDatabaseHas('page_sections', ['section_key' => 'profil_intro']);
    $this->assertDatabaseHas('page_sections', ['section_key' => 'profil_tabs']);
    $this->assertDatabaseHas('page_sections', ['section_key' => 'profil_leadership']);
    $this->assertDatabaseHas('page_sections', ['section_key' => 'profil_structure']);
    $this->assertDatabaseHas('page_sections', ['section_key' => 'faq_intro']);
    $this->assertDatabaseHas('page_sections', ['section_key' => 'faq_items']);
    $this->assertDatabaseHas('page_sections', ['section_key' => 'faq_help']);
    $this->assertDatabaseHas('page_sections', ['section_key' => 'steps']);
    $this->assertDatabaseHas('page_sections', ['section_key' => 'hero', 'label' => 'Header Skema']);
    $this->assertDatabaseHas('page_sections', ['section_key' => 'hero', 'label' => 'Header Validasi Sertifikat']);

    // Pastikan fields hero ada
    $this->assertDatabaseHas('section_fields', ['field_key' => 'hero_title', 'type' => 'text']);
    $this->assertDatabaseHas('section_fields', ['field_key' => 'hero_image', 'type' => 'image']);
    $this->assertDatabaseHas('section_fields', ['field_key' => 'youtube_url', 'type' => 'url']);
    $this->assertDatabaseHas('section_fields', ['field_key' => 'visi_text', 'type' => 'textarea']);
    $this->assertDatabaseHas('section_fields', ['field_key' => 'leader_image', 'type' => 'image']);
    $this->assertDatabaseHas('section_fields', ['field_key' => 'structure_image', 'type' => 'image']);
    $this->assertDatabaseHas('section_fields', ['field_key' => 'faq_title', 'type' => 'text']);
    $this->assertDatabaseHas('section_fields', ['field_key' => 'faq_fallback1_question', 'type' => 'text']);
    $this->assertDatabaseHas('section_fields', ['field_key' => 'faq_help_button_text', 'type' => 'text']);
    $this->assertDatabaseHas('section_fields', ['field_key' => 'contact_title', 'type' => 'text']);
    $this->assertDatabaseHas('section_fields', ['field_key' => 'scheme_title', 'type' => 'text']);
    $this->assertDatabaseHas('section_fields', ['field_key' => 'flow_step_1_title', 'type' => 'text']);
    $this->assertDatabaseHas('section_fields', ['field_key' => 'certificate_check_title', 'type' => 'text']);
    $this->assertDatabaseHas('section_fields', ['field_key' => 'gallery_index_title', 'type' => 'text']);

    expect(PageSection::query()->count())->toBeGreaterThanOrEqual(20);
    expect(SectionField::query()->count())->toBeGreaterThanOrEqual(70);
});
