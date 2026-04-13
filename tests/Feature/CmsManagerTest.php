<?php

use App\Livewire\Admin\CmsManager;
use App\Models\BlockType;
use App\Models\ContentBlock;
use App\Models\Page;
use App\Models\Section;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

it('renders the cms manager page for admin', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get(route('admin.cms'))
        ->assertSuccessful()
        ->assertSeeLivewire(CmsManager::class);
});

it('forbids non admin users from accessing cms manager', function () {
    $user = User::factory()->create(['role' => 'umum']);

    $this->actingAs($user)
        ->get(route('admin.cms'))
        ->assertForbidden();
});

it('creates page tabs, sections, and text blocks from the cms manager', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $component = Livewire::actingAs($admin)
        ->test(CmsManager::class)
        ->call('startCreatingPage')
        ->set('pageForm.title', 'Layanan')
        ->set('pageForm.slug', 'layanan')
        ->set('pageForm.is_published', true)
        ->call('savePage')
        ->assertHasNoErrors();

    $page = Page::query()->where('slug', 'layanan')->first();

    expect($page)->not->toBeNull();

    $component
        ->set('activePageSlug', 'layanan')
        ->call('prepareNewSection')
        ->set('sectionForm.page_id', $page->id)
        ->set('sectionForm.sort_order', 1)
        ->call('saveSection')
        ->assertHasNoErrors();

    $section = Section::query()
        ->where('page_id', $page->id)
        ->latest('id')
        ->first();

    expect($section)->not->toBeNull();

    $component
        ->call('prepareNewBlock', $section->id)
        ->set('blockForm.section_id', $section->id)
        ->set('blockForm.sort_order', 1)
        ->set('blockForm.value', 'Konten layanan sertifikasi untuk calon peserta.')
        ->call('saveBlock')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('pages', [
        'slug' => 'layanan',
        'title' => 'Layanan',
        'is_published' => true,
    ]);

    $this->assertDatabaseHas('sections', [
        'page_id' => $page->id,
        'sort_order' => 1,
        'is_visible' => true,
    ]);

    $this->assertDatabaseHas('content_blocks', [
        'section_id' => $section->id,
        'sort_order' => 1,
    ]);

    $this->assertDatabaseHas('text_contents', [
        'content_block_id' => ContentBlock::query()->where('section_id', $section->id)->latest('id')->value('id'),
        'value' => 'Konten layanan sertifikasi untuk calon peserta.',
        'format' => 'plain',
    ]);
});

it('seeds home page from current welcome content structure', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    Livewire::actingAs($admin)
        ->test(CmsManager::class)
        ->assertSee('Welcome To UPA - LUK')
        ->assertSee('Selamat Datang di UPA-LUK')
        ->assertSee('Langkah Mudah Mendapatkan Sertifikat');

    $homePage = Page::query()->where('slug', 'home')->firstOrFail();

    expect($homePage->sections()->count())->toBeGreaterThanOrEqual(6);
});

it('stores uploaded images for image blocks from cms manager', function () {
    Storage::fake('public');

    $admin = User::factory()->create(['role' => 'admin']);

    $component = Livewire::actingAs($admin)->test(CmsManager::class);

    $page = Page::query()->where('slug', 'home')->firstOrFail();

    $component
        ->set('activePageSlug', 'home')
        ->call('prepareNewSection')
        ->set('sectionForm.page_id', $page->id)
        ->set('sectionForm.sort_order', 2)
        ->call('saveSection')
        ->assertHasNoErrors();

    $section = Section::query()
        ->where('page_id', $page->id)
        ->latest('id')
        ->firstOrFail();
    $imageBlockTypeId = BlockType::query()->where('name', 'image')->value('id');

    $component
        ->call('prepareNewBlock', $section->id)
        ->set('blockForm.section_id', $section->id)
        ->set('blockForm.block_type_id', $imageBlockTypeId)
        ->set('blockForm.sort_order', 1)
        ->set('blockForm.alt_text', 'Banner hero home')
        ->set('blockForm.caption', 'Visual utama homepage')
        ->set('imageUpload', UploadedFile::fake()->image('hero-home.png'))
        ->call('saveBlock')
        ->assertHasNoErrors();

    $block = ContentBlock::query()
        ->where('section_id', $section->id)
        ->latest('id')
        ->with('imageContent.mediaFile')
        ->firstOrFail();

    expect($block->imageContent)->not->toBeNull();
    expect($block->imageContent->mediaFile)->not->toBeNull();

    Storage::disk('public')->assertExists($block->imageContent->mediaFile->file_path);

    $this->assertDatabaseHas('image_contents', [
        'content_block_id' => $block->id,
        'alt_text' => 'Banner hero home',
        'caption' => 'Visual utama homepage',
    ]);
});
