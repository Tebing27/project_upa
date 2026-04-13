<?php

use App\Livewire\Admin\CmsManager;
use App\Models\Article;
use App\Models\FieldValue;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\SectionField;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

it('renders the cms manager page for admin', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get(route('admin.cms'))
        ->assertSuccessful()
        ->assertSeeLivewire(CmsManager::class)
        ->assertSee('data-toast-root', false);
});

it('forbids non admin users from accessing cms manager', function () {
    $user = User::factory()->create(['role' => 'umum']);

    $this->actingAs($user)
        ->get(route('admin.cms'))
        ->assertForbidden();
});

it('creates page tabs from the cms manager', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    Livewire::actingAs($admin)
        ->test(CmsManager::class)
        ->call('startCreatingPage')
        ->set('pageForm.title', 'Layanan')
        ->set('pageForm.slug', 'layanan')
        ->set('pageForm.is_published', true)
        ->call('savePage')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('pages', [
        'slug' => 'layanan',
        'title' => 'Layanan',
        'is_published' => true,
    ]);
});

it('stores article metadata from cms manager', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    Livewire::actingAs($admin)
        ->test(CmsManager::class)
        ->call('switchCmsTab', 'artikel')
        ->call('startCreatingPage')
        ->set('pageForm.title', 'Pengumuman Ujian')
        ->set('pageForm.editor_name', 'Tim Admin CMS')
        ->set('pageForm.tags', 'Pengumuman, Ujian, Akademik')
        ->set('pageForm.published_at', '2026-04-11')
        ->set('pageForm.is_published', true)
        ->call('savePage')
        ->assertHasNoErrors()
        ->assertDispatched('toast');

    $article = Article::query()->where('title', 'Pengumuman Ujian')->firstOrFail();

    expect($article->author_name)->toBe('Tim Admin CMS')
        ->and($article->status)->toBe('published')
        ->and($article->published_at?->format('Y-m-d'))->toBe('2026-04-11');

    expect($article->tags()->pluck('name')->all())->toBe(['Pengumuman', 'Ujian', 'Akademik']);
});

it('shows dedicated article editor for article tabs', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    Livewire::actingAs($admin)
        ->test(CmsManager::class)
        ->call('switchCmsTab', 'artikel')
        ->call('startCreatingPage')
        ->set('pageForm.title', 'Artikel Testing')
        ->call('savePage')
        ->assertSee('Metadata')
        ->assertSee('Isi Artikel')
        ->assertSee('Baca Juga')
        ->assertDontSee('Trix')
        ->assertDontSee('Section Manager');
});

it('stores dedicated blog content for article tabs', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $recommendedArticle = Article::factory()->create([
        'title' => 'Artikel Rekomendasi',
        'author_name' => 'Tim Rekomendasi',
        'status' => 'published',
    ]);

    $component = Livewire::actingAs($admin)
        ->test(CmsManager::class)
        ->call('switchCmsTab', 'artikel')
        ->call('startCreatingPage')
        ->set('pageForm.title', 'Blog Sertifikasi')
        ->set('pageForm.editor_name', 'Editor Blog')
        ->set('pageForm.tags', 'Blog, Sertifikasi')
        ->set('pageForm.is_published', true)
        ->call('savePage')
        ->assertHasNoErrors();

    $article = Article::query()->where('title', 'Blog Sertifikasi')->firstOrFail();

    $component
        ->set('articleForm.excerpt', 'Ringkasan artikel blog sertifikasi.')
        ->set('articleForm.body_format', 'html')
        ->set('articleForm.body', '<p>Isi panjang artikel blog.</p><p>Paragraf kedua.</p>')
        ->set('articleForm.related_article_ids', [$recommendedArticle->id])
        ->call('saveArticleContent')
        ->assertHasNoErrors()
        ->assertDispatched('toast');

    $article->refresh();

    expect($article->excerpt)->toBe('Isi panjang artikel blog.Paragraf kedua.')
        ->and($article->body)->toBe('<p>Isi panjang artikel blog.</p><p>Paragraf kedua.</p>')
        ->and($article->related_article_ids)->toBe([$recommendedArticle->id]);
});

it('seeds missing cms tabs for media menu items', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    Livewire::actingAs($admin)
        ->test(CmsManager::class);

    expect(Page::query()->where('slug', 'media')->exists())->toBeTrue()
        ->and(Page::query()->where('slug', 'instagram')->exists())->toBeTrue()
        ->and(Page::query()->where('slug', 'youtube')->exists())->toBeTrue()
        ->and(Page::query()->where('slug', 'facebook')->exists())->toBeTrue()
        ->and(Page::query()->where('slug', 'artikel')->exists())->toBeTrue();
});

it('saves field values for a page section', function () {
    Storage::fake('public');

    $admin = User::factory()->create(['role' => 'admin']);

    // Buat page & section structure
    $page = Page::factory()->create(['slug' => 'home-test', 'title' => 'Home Test']);
    $pageSection = PageSection::query()->create([
        'page_id' => $page->id,
        'section_key' => 'hero',
        'label' => 'Hero Section',
        'sort_order' => 1,
        'is_visible' => true,
    ]);
    $field = SectionField::query()->create([
        'page_section_id' => $pageSection->id,
        'field_key' => 'hero_title',
        'label' => 'Hero Title',
        'type' => 'text',
        'sort_order' => 1,
    ]);

    $component = Livewire::actingAs($admin)
        ->test(CmsManager::class)
        ->set('activePageSlug', 'home-test');

    $component
        ->set("fieldValues.{$field->id}", 'Selamat Datang di UPA')
        ->call('saveFieldValues', $pageSection->id)
        ->assertHasNoErrors()
        ->assertDispatched('toast');

    $this->assertDatabaseHas('field_values', [
        'section_field_id' => $field->id,
        'value' => 'Selamat Datang di UPA',
    ]);
});

it('uploads image for image type field', function () {
    Storage::fake('public');

    $admin = User::factory()->create(['role' => 'admin']);

    $page = Page::factory()->create(['slug' => 'home-img-test', 'title' => 'Home Img Test']);
    $pageSection = PageSection::query()->create([
        'page_id' => $page->id,
        'section_key' => 'hero',
        'label' => 'Hero Section',
        'sort_order' => 1,
        'is_visible' => true,
    ]);
    $imageField = SectionField::query()->create([
        'page_section_id' => $pageSection->id,
        'field_key' => 'hero_image',
        'label' => 'Hero Image',
        'type' => 'image',
        'sort_order' => 1,
    ]);

    Livewire::actingAs($admin)
        ->test(CmsManager::class)
        ->set('activePageSlug', 'home-img-test')
        ->set("fieldImages.{$imageField->id}", UploadedFile::fake()->image('hero.png'))
        ->call('saveFieldValues', $pageSection->id)
        ->assertHasNoErrors()
        ->assertDispatched('toast');

    $fieldValue = FieldValue::query()->where('section_field_id', $imageField->id)->first();
    expect($fieldValue)->not->toBeNull();
    expect($fieldValue->media_file_id)->not->toBeNull();
});

it('adds grouped faq item fields from cms manager', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $page = Page::factory()->create(['slug' => 'faq', 'title' => 'FAQ']);
    $pageSection = PageSection::query()->create([
        'page_id' => $page->id,
        'section_key' => 'faq_items',
        'label' => 'Daftar FAQ',
        'sort_order' => 1,
        'is_visible' => true,
    ]);

    Livewire::actingAs($admin)
        ->test(CmsManager::class)
        ->set('activePageSlug', 'faq')
        ->call('addFaqItem', $pageSection->id)
        ->assertHasNoErrors()
        ->assertDispatched('toast');

    expect(SectionField::query()->where('page_section_id', $pageSection->id)->count())->toBe(3);
    expect(SectionField::query()->where('page_section_id', $pageSection->id)->pluck('field_key')->implode(' '))
        ->toContain('_category')
        ->toContain('_question')
        ->toContain('_answer');
});

it('keeps article tab in standalone article mode', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    Livewire::actingAs($admin)
        ->test(CmsManager::class)
        ->call('switchCmsTab', 'artikel')
        ->call('startCreatingPage')
        ->set('pageForm.title', 'Berita Terbaru')
        ->assertSet('cmsTab', 'artikel')
        ->assertSee('/artikel/berita-terbaru-{id}');
});

it('auto prefixes slug with galeri- when page type is galeri', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    Livewire::actingAs($admin)
        ->test(CmsManager::class)
        ->call('startCreatingPage')
        ->set('pageType', 'galeri')
        ->set('pageForm.title', 'Kegiatan Wisuda')
        ->assertSet('pageForm.slug', 'galeri-kegiatan-wisuda');
});

it('does not add prefix when page type is statis', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    Livewire::actingAs($admin)
        ->test(CmsManager::class)
        ->call('startCreatingPage')
        ->set('pageType', 'statis')
        ->set('pageForm.title', 'Kontak Kami')
        ->assertSet('pageForm.slug', 'kontak-kami');
});

it('updates slug prefix when page type changes on new page form', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $component = Livewire::actingAs($admin)
        ->test(CmsManager::class)
        ->call('startCreatingPage')
        ->set('pageType', 'artikel')
        ->set('pageForm.title', 'Info Kampus');

    $component->assertSet('pageForm.slug', 'artikel-info-kampus');

    $component
        ->set('pageType', 'galeri')
        ->assertSet('pageForm.slug', 'galeri-info-kampus');

    $component
        ->set('pageType', 'statis')
        ->assertSet('pageForm.slug', 'info-kampus');
});
