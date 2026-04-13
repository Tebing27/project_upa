<?php

namespace App\Livewire\Admin;

use App\Models\BlockType;
use App\Models\ContentBlock;
use App\Models\MediaFile;
use App\Models\Page;
use App\Models\Section;
use App\Models\SectionType;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

#[Layout('components.layouts.app')]
class CmsManager extends Component
{
    use WithFileUploads;

    public string $activePageSlug = 'home';

    public bool $isCreatingPage = false;

    public ?int $pageId = null;

    public ?int $sectionId = null;

    public ?int $blockId = null;

    public array $pageForm = [
        'title' => '',
        'slug' => '',
        'is_published' => true,
    ];

    public array $sectionForm = [
        'page_id' => null,
        'section_type_id' => null,
        'sort_order' => 1,
        'is_visible' => true,
    ];

    public array $blockForm = [
        'section_id' => null,
        'block_type_id' => null,
        'sort_order' => 1,
        'value' => '',
        'format' => 'plain',
        'alt_text' => '',
        'caption' => '',
    ];

    /** @var TemporaryUploadedFile|null */
    public $imageUpload;

    public ?string $existingImageUrl = null;

    public ?string $existingImageName = null;

    public function mount(): void
    {
        $this->ensureCmsDefaultsExist();
        $this->syncActivePage();
        if ($this->activePage) {
            $this->editPage($this->activePage->id);
        } else {
            $this->startCreatingPage();
        }
        $this->prepareNewSection();
        $this->prepareNewBlock();
    }

    #[Computed]
    public function pages(): Collection
    {
        return Page::query()
            ->withCount('sections')
            ->orderBy('id')
            ->get();
    }

    #[Computed]
    public function activePage(): ?Page
    {
        return Page::query()
            ->where('slug', $this->activePageSlug)
            ->first();
    }

    #[Computed]
    public function sectionTypes(): Collection
    {
        return SectionType::query()->orderBy('name')->get();
    }

    #[Computed]
    public function blockTypes(): Collection
    {
        return BlockType::query()->orderBy('id')->get();
    }

    #[Computed]
    public function sections(): Collection
    {
        if (! $this->activePage) {
            return collect();
        }

        return Section::query()
            ->with([
                'sectionType',
                'contentBlocks.blockType',
                'contentBlocks.textContent',
                'contentBlocks.imageContent.mediaFile',
            ])
            ->where('page_id', $this->activePage->id)
            ->orderBy('sort_order')
            ->get();
    }

    #[Computed]
    public function availableSectionsForBlock(): Collection
    {
        return $this->sections->map(fn (Section $section): array => [
            'id' => $section->id,
            'label' => sprintf(
                '%s #%d',
                Str::headline($section->sectionType?->name ?? 'Section'),
                $section->sort_order
            ),
        ]);
    }

    public function updatedPageFormTitle(string $value): void
    {
        if ($this->pageId === null && blank($this->pageForm['slug'])) {
            $this->pageForm['slug'] = Str::slug($value);
        }
    }

    public function updatedActivePageSlug(): void
    {
        $this->syncActivePage();

        if ($this->activePage) {
            $this->isCreatingPage = false;
            $this->pageId = $this->activePage->id;
            $this->fillPageForm($this->activePage);
        } else {
            $this->startCreatingPage();
        }

        $this->prepareNewSection();
        $this->prepareNewBlock();
        $this->resetErrorBag();
    }

    public function updatedBlockFormSectionId(mixed $value): void
    {
        if (filled($value)) {
            $this->blockForm['sort_order'] = $this->nextBlockSortOrder((int) $value);
        }
    }

    public function selectPage(string $slug): void
    {
        $this->isCreatingPage = false;
        $this->activePageSlug = $slug;
    }

    public function startCreatingPage(): void
    {
        $this->isCreatingPage = true;
        $this->pageId = null;
        $this->pageForm = [
            'title' => '',
            'slug' => '',
            'is_published' => true,
        ];

        $this->resetErrorBag();
    }

    public function editPage(int $pageId): void
    {
        $page = Page::query()->findOrFail($pageId);

        $this->isCreatingPage = false;
        $this->pageId = $page->id;
        $this->fillPageForm($page);
        $this->activePageSlug = $page->slug;
        $this->resetErrorBag();
    }

    public function savePage(): void
    {
        $validated = $this->validate($this->pageRules(), $this->pageMessages());

        if ($this->pageId) {
            $page = Page::query()->findOrFail($this->pageId);
            $page->update($validated['pageForm']);
            $message = 'Halaman CMS berhasil diperbarui.';
        } else {
            $page = Page::query()->create([
                ...$validated['pageForm'],
                'created_by' => auth()->id(),
            ]);
            $message = 'Tab halaman CMS baru berhasil dibuat.';
        }

        $this->isCreatingPage = false;
        $this->pageId = $page->id;
        $this->activePageSlug = $page->slug;

        unset($this->pages, $this->activePage, $this->sections, $this->availableSectionsForBlock);

        $this->dispatch('toast', ['message' => $message, 'type' => 'success']);
    }

    public function deletePage(int $pageId): void
    {
        if (Page::query()->count() <= 1) {
            $this->dispatch('toast', ['message' => 'Minimal harus ada satu halaman CMS.', 'type' => 'error']);

            return;
        }

        $page = Page::query()
            ->with('sections.contentBlocks.imageContent.mediaFile')
            ->findOrFail($pageId);

        foreach ($page->sections as $section) {
            foreach ($section->contentBlocks as $block) {
                $this->deleteMediaForBlock($block);
            }
        }

        $page->delete();

        unset($this->pages, $this->activePage, $this->sections, $this->availableSectionsForBlock);

        $this->syncActivePage();
        $activePageId = Page::query()->where('slug', $this->activePageSlug)->value('id');
        if ($activePageId) {
            $this->editPage($activePageId);
        } else {
            $this->startCreatingPage();
        }
        $this->prepareNewSection();
        $this->prepareNewBlock();

        $this->dispatch('toast', ['message' => 'Halaman CMS berhasil dihapus.', 'type' => 'success']);
    }

    public function prepareNewSection(): void
    {
        $this->sectionId = null;
        $this->sectionForm = [
            'page_id' => $this->activePage?->id,
            'section_type_id' => $this->sectionTypes->first()?->id,
            'sort_order' => $this->nextSectionSortOrder(),
            'is_visible' => true,
        ];

        $this->resetErrorBag();
    }

    public function editSection(int $sectionId): void
    {
        $section = Section::query()->findOrFail($sectionId);

        $this->sectionId = $section->id;
        $this->sectionForm = [
            'page_id' => $section->page_id,
            'section_type_id' => $section->section_type_id,
            'sort_order' => $section->sort_order,
            'is_visible' => $section->is_visible,
        ];

        $this->activePageSlug = $section->page->slug;
        $this->resetErrorBag();
    }

    public function saveSection(): void
    {
        $validated = $this->validate($this->sectionRules(), $this->sectionMessages());

        if ($this->sectionId) {
            $section = Section::query()->findOrFail($this->sectionId);
            $section->update($validated['sectionForm']);
            $message = 'Section berhasil diperbarui.';
        } else {
            $section = Section::query()->create($validated['sectionForm']);
            $message = 'Section baru berhasil ditambahkan.';
        }

        $this->activePageSlug = $section->page->slug;
        unset($this->sections, $this->pages, $this->activePage, $this->availableSectionsForBlock);
        $this->prepareNewSection();
        $this->prepareNewBlock($section->id);

        $this->dispatch('toast', ['message' => $message, 'type' => 'success']);
    }

    public function deleteSection(int $sectionId): void
    {
        $section = Section::query()->with('contentBlocks.imageContent.mediaFile')->findOrFail($sectionId);

        foreach ($section->contentBlocks as $block) {
            $this->deleteMediaForBlock($block);
        }

        $section->delete();

        unset($this->sections, $this->pages, $this->activePage, $this->availableSectionsForBlock);
        $this->prepareNewSection();
        $this->prepareNewBlock();

        $this->dispatch('toast', ['message' => 'Section berhasil dihapus.', 'type' => 'success']);
    }

    public function prepareNewBlock(?int $sectionId = null): void
    {
        $defaultSectionId = $sectionId ?? $this->sections->first()?->id;
        $defaultBlockTypeId = $this->blockTypes->first()?->id;

        $this->blockId = null;
        $this->blockForm = [
            'section_id' => $defaultSectionId,
            'block_type_id' => $defaultBlockTypeId,
            'sort_order' => $defaultSectionId ? $this->nextBlockSortOrder((int) $defaultSectionId) : 1,
            'value' => '',
            'format' => 'plain',
            'alt_text' => '',
            'caption' => '',
        ];
        $this->imageUpload = null;
        $this->existingImageUrl = null;
        $this->existingImageName = null;
        $this->resetErrorBag();
    }

    public function editBlock(int $blockId): void
    {
        $block = ContentBlock::query()
            ->with(['section.page', 'textContent', 'imageContent.mediaFile'])
            ->findOrFail($blockId);

        $this->blockId = $block->id;
        $this->activePageSlug = $block->section->page->slug;
        $this->blockForm = [
            'section_id' => $block->section_id,
            'block_type_id' => $block->block_type_id,
            'sort_order' => $block->sort_order,
            'value' => $block->textContent?->value ?? '',
            'format' => $block->textContent?->format ?? 'plain',
            'alt_text' => $block->imageContent?->alt_text ?? '',
            'caption' => $block->imageContent?->caption ?? '',
        ];
        $this->imageUpload = null;
        $this->existingImageUrl = $block->imageContent?->mediaFile?->file_path
            ? Storage::url($block->imageContent->mediaFile->file_path)
            : null;
        $this->existingImageName = $block->imageContent?->mediaFile?->file_name;
        $this->resetErrorBag();
    }

    public function saveBlock(): void
    {
        $validated = $this->validate($this->blockRules(), $this->blockMessages());
        $currentBlockTypeName = $this->resolveBlockTypeName((int) $validated['blockForm']['block_type_id']);

        if ($this->blockId) {
            $block = ContentBlock::query()
                ->with(['textContent', 'imageContent.mediaFile'])
                ->findOrFail($this->blockId);
            $block->update([
                'section_id' => $validated['blockForm']['section_id'],
                'block_type_id' => $validated['blockForm']['block_type_id'],
                'sort_order' => $validated['blockForm']['sort_order'],
            ]);
            $message = 'Block konten berhasil diperbarui.';
        } else {
            $block = ContentBlock::query()->create([
                'section_id' => $validated['blockForm']['section_id'],
                'block_type_id' => $validated['blockForm']['block_type_id'],
                'sort_order' => $validated['blockForm']['sort_order'],
            ]);
            $message = 'Block konten baru berhasil ditambahkan.';
        }

        if ($currentBlockTypeName === 'text') {
            $this->syncTextBlock($block, $validated['blockForm']);
        } else {
            $this->syncImageBlock($block, $validated['blockForm']);
        }

        unset($this->sections, $this->availableSectionsForBlock);
        $this->prepareNewBlock($block->section_id);

        $this->dispatch('toast', ['message' => $message, 'type' => 'success']);
    }

    public function deleteBlock(int $blockId): void
    {
        $block = ContentBlock::query()
            ->with(['textContent', 'imageContent.mediaFile'])
            ->findOrFail($blockId);

        $this->deleteMediaForBlock($block);
        $block->delete();

        unset($this->sections, $this->availableSectionsForBlock);
        $this->prepareNewBlock();

        $this->dispatch('toast', ['message' => 'Block konten berhasil dihapus.', 'type' => 'success']);
    }

    public function render(): View
    {
        return view('livewire.admin.cms-manager');
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    protected function pageRules(): array
    {
        return [
            'pageForm.title' => ['required', 'string', 'max:255'],
            'pageForm.slug' => ['required', 'alpha_dash', 'max:255', Rule::unique('pages', 'slug')->ignore($this->pageId)],
            'pageForm.is_published' => ['boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function pageMessages(): array
    {
        return [
            'pageForm.title.required' => 'Judul halaman wajib diisi.',
            'pageForm.slug.required' => 'Slug halaman wajib diisi.',
            'pageForm.slug.alpha_dash' => 'Slug hanya boleh berisi huruf kecil, angka, strip, atau underscore.',
            'pageForm.slug.unique' => 'Slug halaman sudah digunakan.',
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    protected function sectionRules(): array
    {
        return [
            'sectionForm.page_id' => ['required', 'exists:pages,id'],
            'sectionForm.section_type_id' => ['required', 'exists:section_types,id'],
            'sectionForm.sort_order' => ['required', 'integer', 'min:1'],
            'sectionForm.is_visible' => ['boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function sectionMessages(): array
    {
        return [
            'sectionForm.page_id.required' => 'Tab halaman harus dipilih.',
            'sectionForm.section_type_id.required' => 'Tipe section harus dipilih.',
            'sectionForm.sort_order.required' => 'Urutan section wajib diisi.',
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    protected function blockRules(): array
    {
        $currentBlockTypeName = $this->resolveBlockTypeName();

        $rules = [
            'blockForm.section_id' => ['required', 'exists:sections,id'],
            'blockForm.block_type_id' => ['required', 'exists:block_types,id'],
            'blockForm.sort_order' => ['required', 'integer', 'min:1'],
            'blockForm.value' => ['nullable', 'string'],
            'blockForm.format' => ['required', 'string', 'max:255'],
            'blockForm.alt_text' => ['nullable', 'string', 'max:255'],
            'blockForm.caption' => ['nullable', 'string', 'max:255'],
            'imageUpload' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];

        if ($currentBlockTypeName === 'text') {
            $rules['blockForm.value'] = ['required', 'string'];
        }

        if ($currentBlockTypeName === 'image' && $this->blockId === null && ! $this->imageUpload) {
            $rules['imageUpload'] = ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'];
        }

        return $rules;
    }

    /**
     * @return array<string, string>
     */
    protected function blockMessages(): array
    {
        return [
            'blockForm.section_id.required' => 'Pilih section tujuan untuk block ini.',
            'blockForm.block_type_id.required' => 'Pilih tipe block terlebih dahulu.',
            'blockForm.sort_order.required' => 'Urutan block wajib diisi.',
            'blockForm.value.required' => 'Konten teks wajib diisi untuk block teks.',
            'imageUpload.required' => 'Gambar wajib diupload untuk block gambar baru.',
        ];
    }

    private function fillPageForm(Page $page): void
    {
        $this->pageForm = [
            'title' => $page->title,
            'slug' => $page->slug,
            'is_published' => $page->is_published,
        ];
    }

    private function nextSectionSortOrder(): int
    {
        if (! $this->activePage) {
            return 1;
        }

        return (int) $this->activePage->sections()->max('sort_order') + 1;
    }

    private function nextBlockSortOrder(int $sectionId): int
    {
        return (int) ContentBlock::query()
            ->where('section_id', $sectionId)
            ->max('sort_order') + 1;
    }

    public function selectedBlockTypeName(): ?string
    {
        return $this->resolveBlockTypeName();
    }

    private function resolveBlockTypeName(?int $blockTypeId = null): ?string
    {
        $resolvedBlockTypeId = $blockTypeId ?? (filled($this->blockForm['block_type_id']) ? (int) $this->blockForm['block_type_id'] : null);

        if (! $resolvedBlockTypeId) {
            return null;
        }

        return $this->blockTypes
            ->firstWhere('id', $resolvedBlockTypeId)
            ?->name;
    }

    private function syncTextBlock(ContentBlock $block, array $blockForm): void
    {
        $block->textContent()->updateOrCreate([], [
            'value' => $blockForm['value'],
            'format' => $blockForm['format'],
        ]);

        $this->deleteMediaForBlock($block);
    }

    private function syncImageBlock(ContentBlock $block, array $blockForm): void
    {
        $mediaFile = $block->imageContent?->mediaFile;

        if ($this->imageUpload) {
            $this->deleteMediaForBlock($block);

            $storedPath = $this->imageUpload->store('cms/images', 'public');
            $mediaFile = MediaFile::query()->create([
                'file_name' => $this->imageUpload->getClientOriginalName(),
                'file_path' => $storedPath,
                'mime_type' => $this->imageUpload->getMimeType(),
                'file_size' => $this->imageUpload->getSize(),
                'uploaded_by' => auth()->id(),
                'uploaded_at' => now(),
            ]);
        }

        if (! $mediaFile) {
            return;
        }

        $block->textContent()?->delete();
        $block->imageContent()->updateOrCreate([], [
            'media_file_id' => $mediaFile->id,
            'alt_text' => $blockForm['alt_text'] ?: null,
            'caption' => $blockForm['caption'] ?: null,
        ]);
    }

    private function deleteMediaForBlock(ContentBlock $block): void
    {
        $block->loadMissing('imageContent.mediaFile');

        $imageContent = $block->imageContent;

        if (! $imageContent) {
            return;
        }

        $mediaFile = $imageContent->mediaFile;
        $filePath = $mediaFile?->file_path;

        $imageContent->delete();
        $mediaFile?->delete();

        if ($filePath) {
            Storage::disk('public')->delete($filePath);
        }
    }

    private function syncActivePage(): void
    {
        $page = Page::query()->where('slug', $this->activePageSlug)->first()
            ?? Page::query()->orderBy('id')->first();

        $this->activePageSlug = $page?->slug ?? '';
    }

    private function ensureCmsDefaultsExist(): void
    {
        $sectionTypeDefaults = [
            ['name' => 'hero', 'description' => 'Section pembuka dengan judul utama dan visual besar.'],
            ['name' => 'content', 'description' => 'Section isi utama untuk paragraf atau informasi umum.'],
            ['name' => 'gallery', 'description' => 'Section dengan fokus gambar atau media visual.'],
            ['name' => 'schedule', 'description' => 'Section berisi agenda, jadwal, atau timeline kegiatan.'],
        ];

        foreach ($sectionTypeDefaults as $sectionTypeDefault) {
            SectionType::query()->firstOrCreate(
                ['name' => $sectionTypeDefault['name']],
                ['description' => $sectionTypeDefault['description']],
            );
        }

        $blockTypeDefaults = [
            ['name' => 'text', 'schema_name' => 'text_content'],
            ['name' => 'image', 'schema_name' => 'image_content'],
        ];

        foreach ($blockTypeDefaults as $blockTypeDefault) {
            BlockType::query()->firstOrCreate(
                ['name' => $blockTypeDefault['name']],
                ['schema_name' => $blockTypeDefault['schema_name']],
            );
        }

        $pageDefaults = [
            ['slug' => 'home', 'title' => 'Home'],
            ['slug' => 'profil', 'title' => 'Profil'],
            ['slug' => 'kontak', 'title' => 'Kontak'],
            ['slug' => 'cek-sertifikat', 'title' => 'Cek Sertifikat'],
            ['slug' => 'skema', 'title' => 'Skema'],
            ['slug' => 'jadwal', 'title' => 'Jadwal'],
        ];

        foreach ($pageDefaults as $pageDefault) {
            $page = Page::query()->firstOrCreate(
                ['slug' => $pageDefault['slug']],
                [
                    'title' => $pageDefault['title'],
                    'is_published' => true,
                    'created_by' => auth()->id(),
                ],
            );

            if ($page->slug === 'home' && ! $page->sections()->exists()) {
                $this->seedHomePageContent($page);
            }
        }
    }

    private function seedHomePageContent(Page $page): void
    {
        $heroSection = $page->sections()->create([
            'section_type_id' => SectionType::query()->where('name', 'hero')->value('id'),
            'sort_order' => 1,
            'is_visible' => true,
        ]);

        $this->createTextBlock($heroSection, 1, 'Welcome To UPA - LUK');
        $this->createTextBlock($heroSection, 2, 'UPN "Veteran" Jakarta Competency Test Service Academic Support Unit melayani uji kompetensi mahasiswa dengan sertifikasi BNSP sesuai bidang kompetensinya.');

        $certificateSection = $page->sections()->create([
            'section_type_id' => SectionType::query()->where('name', 'content')->value('id'),
            'sort_order' => 2,
            'is_visible' => true,
        ]);

        $this->createTextBlock($certificateSection, 1, 'Cek Sertifikat');
        $this->createTextBlock($certificateSection, 2, 'Verifikasi keaslian sertifikat kompetensi secara online untuk memastikan validitas sertifikat yang diterbitkan.');

        $introSection = $page->sections()->create([
            'section_type_id' => SectionType::query()->where('name', 'content')->value('id'),
            'sort_order' => 3,
            'is_visible' => true,
        ]);

        $this->createTextBlock($introSection, 1, 'Selamat Datang di UPA-LUK');
        $this->createTextBlock($introSection, 2, 'Lembaga Sertifikasi Profesi UPN "Veteran" Jakarta melayani pelaksanaan uji kompetensi mahasiswa dengan lisensi resmi dari Badan Nasional Sertifikasi Profesi.');

        $pipelineSection = $page->sections()->create([
            'section_type_id' => SectionType::query()->where('name', 'content')->value('id'),
            'sort_order' => 4,
            'is_visible' => true,
        ]);

        $this->createTextBlock($pipelineSection, 1, 'Langkah Mudah Mendapatkan Sertifikat');
        $this->createTextBlock($pipelineSection, 2, 'Proses sertifikasi dirancang cepat, transparan, dan terstruktur mulai dari daftar akun, verifikasi berkas, pembayaran, asesmen, hingga terbit sertifikat.');

        $schemeSection = $page->sections()->create([
            'section_type_id' => SectionType::query()->where('name', 'content')->value('id'),
            'sort_order' => 5,
            'is_visible' => true,
        ]);

        $this->createTextBlock($schemeSection, 1, 'Skema Sertifikasi Terbaru');
        $this->createTextBlock($schemeSection, 2, 'Bagian ini menampilkan daftar skema sertifikasi terbaru yang tersedia untuk peserta.');

        $testimonialSection = $page->sections()->create([
            'section_type_id' => SectionType::query()->where('name', 'content')->value('id'),
            'sort_order' => 6,
            'is_visible' => true,
        ]);

        $this->createTextBlock($testimonialSection, 1, 'Apa Kata Mereka?');
        $this->createTextBlock($testimonialSection, 2, 'Cerita alumni dan peserta mengenai manfaat sertifikasi kompetensi di UPA-LUK UPN Veteran Jakarta.');
    }

    private function createTextBlock(Section $section, int $sortOrder, string $value): void
    {
        $block = $section->contentBlocks()->create([
            'block_type_id' => BlockType::query()->where('name', 'text')->value('id'),
            'sort_order' => $sortOrder,
        ]);

        $block->textContent()->create([
            'value' => $value,
            'format' => 'plain',
        ]);
    }
}
