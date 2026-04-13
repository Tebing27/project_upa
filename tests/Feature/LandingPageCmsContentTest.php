<?php

use App\Models\BlockType;
use App\Models\ContentBlock;
use App\Models\ImageContent;
use App\Models\MediaFile;
use App\Models\Page;
use App\Models\Section;
use App\Models\SectionType;
use App\Models\TextContent;
use App\Models\User;

function ensureCmsTypesExist(): void
{
    SectionType::query()->firstOrCreate(['name' => 'content'], ['description' => 'Content section']);
    SectionType::query()->firstOrCreate(['name' => 'gallery'], ['description' => 'Gallery section']);
    BlockType::query()->firstOrCreate(['name' => 'text'], ['schema_name' => 'text_content']);
    BlockType::query()->firstOrCreate(['name' => 'image'], ['schema_name' => 'image_content']);
}

function createCmsTextBlock(Section $section, int $sortOrder, string $value, string $format = 'plain'): void
{
    $block = ContentBlock::query()->create([
        'section_id' => $section->id,
        'block_type_id' => BlockType::query()->where('name', 'text')->value('id'),
        'sort_order' => $sortOrder,
    ]);

    TextContent::query()->create([
        'content_block_id' => $block->id,
        'value' => $value,
        'format' => $format,
    ]);
}

function createCmsImageBlock(Section $section, int $sortOrder, string $filePath, ?string $caption = null): void
{
    $block = ContentBlock::query()->create([
        'section_id' => $section->id,
        'block_type_id' => BlockType::query()->where('name', 'image')->value('id'),
        'sort_order' => $sortOrder,
    ]);

    $mediaFile = MediaFile::query()->create([
        'file_name' => basename($filePath),
        'file_path' => $filePath,
        'mime_type' => 'image/jpeg',
        'file_size' => 1024,
        'uploaded_by' => User::factory()->create(['role' => 'admin'])->id,
        'uploaded_at' => now(),
    ]);

    ImageContent::query()->create([
        'content_block_id' => $block->id,
        'media_file_id' => $mediaFile->id,
        'alt_text' => 'CMS image',
        'caption' => $caption,
    ]);
}

it('renders article and gallery content from cms tables on the landing page', function () {
    ensureCmsTypesExist();

    $admin = User::factory()->create(['role' => 'admin']);
    $articlePage = Page::factory()->create([
        'title' => 'Berita CMS Pertama',
        'slug' => 'berita-cms-pertama',
        'created_by' => $admin->id,
    ]);
    $articleSection = Section::query()->create([
        'page_id' => $articlePage->id,
        'section_type_id' => SectionType::query()->where('name', 'content')->value('id'),
        'sort_order' => 1,
        'is_visible' => true,
    ]);
    createCmsTextBlock($articleSection, 1, 'Ringkasan berita dari CMS');
    createCmsImageBlock($articleSection, 2, 'cms/articles/berita-1.jpg');

    $galleryPage = Page::factory()->create([
        'title' => 'Galeri CMS Pertama',
        'slug' => 'galeri-cms-pertama',
        'created_by' => $admin->id,
    ]);
    $gallerySection = Section::query()->create([
        'page_id' => $galleryPage->id,
        'section_type_id' => SectionType::query()->where('name', 'gallery')->value('id'),
        'sort_order' => 1,
        'is_visible' => true,
    ]);
    createCmsTextBlock($gallerySection, 1, 'Deskripsi galeri dari CMS');
    createCmsImageBlock($gallerySection, 2, 'cms/galleries/galeri-1.jpg');

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Berita CMS Pertama')
        ->assertSee('Ringkasan berita dari CMS')
        ->assertSee('Galeri CMS Pertama')
        ->assertSee('cms/galleries/galeri-1.jpg')
        ->assertSee(route('article.index'), false)
        ->assertSee(route('gallery.index'), false);
});

it('shows article detail content from cms pages', function () {
    ensureCmsTypesExist();

    $admin = User::factory()->create(['role' => 'admin', 'nama' => 'Admin CMS']);
    $articlePage = Page::factory()->create([
        'title' => 'Artikel Detail CMS',
        'slug' => 'artikel-detail-cms',
        'created_by' => $admin->id,
    ]);
    $section = Section::query()->create([
        'page_id' => $articlePage->id,
        'section_type_id' => SectionType::query()->where('name', 'content')->value('id'),
        'sort_order' => 1,
        'is_visible' => true,
    ]);
    createCmsTextBlock($section, 1, '<h2>Isi Artikel CMS</h2><p>Konten detail dari CMS.</p>', 'html');
    createCmsImageBlock($section, 2, 'cms/articles/detail.jpg');

    $this->get(route('article.show', $articlePage->slug))
        ->assertOk()
        ->assertSee('Artikel Detail CMS')
        ->assertSee('Admin CMS')
        ->assertSee('Isi Artikel CMS', false)
        ->assertSee('Konten detail dari CMS.', false);
});

it('lists gallery content from cms pages', function () {
    ensureCmsTypesExist();

    $galleryPage = Page::factory()->create([
        'title' => 'Galeri Dokumentasi CMS',
        'slug' => 'kegiatan-dokumentasi-cms',
    ]);
    $section = Section::query()->create([
        'page_id' => $galleryPage->id,
        'section_type_id' => SectionType::query()->where('name', 'gallery')->value('id'),
        'sort_order' => 1,
        'is_visible' => true,
    ]);
    createCmsTextBlock($section, 1, 'Dokumentasi kegiatan dari CMS');
    createCmsImageBlock($section, 2, 'cms/galleries/dokumentasi.jpg');

    $this->get(route('gallery.index'))
        ->assertOk()
        ->assertSee('Galeri Dokumentasi CMS')
        ->assertSee('Dokumentasi kegiatan dari CMS')
        ->assertSee('cms/galleries/dokumentasi.jpg');
});
