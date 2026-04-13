<?php

namespace App\Livewire\Admin;

use App\Models\Article;
use App\Models\FieldValue;
use App\Models\MediaFile;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\SectionField;
use App\Models\Tag;
use Illuminate\Support\Carbon;
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
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class CmsManager extends Component
{
    use WithFileUploads, WithPagination;

    public string $cmsTab = 'landing';

    public string $activePageSlug = 'home';

    public bool $isCreatingPage = false;

    public ?int $pageId = null;

    public ?int $articleId = null;

    /** @var string Tipe halaman: 'artikel', 'galeri', atau 'statis' */
    public string $pageType = 'statis';

    public array $pageForm = [
        'title' => '',
        'slug' => '',
        'editor_name' => '',
        'tags' => '',
        'is_published' => true,
        'published_at' => '',
    ];

    public array $articleForm = [
        'excerpt' => '',
        'body' => '',
        'body_format' => 'html',
        'related_article_ids' => [],
    ];

    public array $selectedArticles = [];

    /**
     * Field values indexed by section_field_id.
     *
     * @var array<int, string>
     */
    public array $fieldValues = [];

    /**
     * Per-section last-saved timestamps, indexed by page_section_id.
     *
     * @var array<int, string|null>
     */
    public array $lastSavedAt = [];

    /**
     * Pending image uploads indexed by section_field_id.
     *
     * @var array<int, TemporaryUploadedFile|null>
     */
    public array $fieldImages = [];

    public function mount(): void
    {
        $this->ensurePageDefaultsExist();
        $this->syncActivePage();
        $this->syncCmsTab();

        if ($this->activePage) {
            $this->editPage($this->activePage->id);
        } else {
            $this->startCreatingPage();
        }

        $this->loadFieldValues();
    }

    #[Computed]
    public function pages(): Collection
    {
        return Page::query()
            ->withCount('pageSections')
            ->orderByDesc('published_at')
            ->orderBy('id')
            ->get();
    }

    #[Computed]
    public function articleEntries()
    {
        return Article::query()
            ->with('tags')
            ->orderByRaw("case when status = 'published' then 0 else 1 end")
            ->orderByDesc('published_at')
            ->orderByDesc('updated_at')
            ->paginate(10);
    }

    #[Computed]
    public function totalArticlesCount()
    {
        return Article::count();
    }

    #[Computed]
    public function publishedArticlesCount()
    {
        return Article::where('status', 'published')->count();
    }

    #[Computed]
    public function draftArticlesCount()
    {
        return Article::where('status', 'draft')->count();
    }

    #[Computed]
    public function totalArticlesViews()
    {
        return Article::sum('views');
    }

    #[Computed]
    public function filteredPages(): Collection
    {
        return $this->pages->filter(function (Page $page): bool {
            $pageType = $this->resolvePageType($page->slug);

            return match ($this->cmsTab) {
                'artikel' => $pageType === 'artikel',
                'gallery' => $pageType === 'galeri',
                default => $pageType === 'statis',
            };
        })->values();
    }

    #[Computed]
    public function activePage(): ?Page
    {
        return Page::query()
            ->where('slug', $this->activePageSlug)
            ->first();
    }

    #[Computed]
    public function activePageSections(): Collection
    {
        if (! $this->activePage) {
            return collect();
        }

        return PageSection::query()
            ->with(['fields.value.mediaFile'])
            ->where('page_id', $this->activePage->id)
            ->orderBy('sort_order')
            ->get();
    }

    #[Computed]
    public function availableRecommendedArticles(): Collection
    {
        return Article::query()
            ->when($this->articleId, fn ($query) => $query->where('id', '!=', $this->articleId))
            ->orderByDesc('published_at')
            ->orderByDesc('updated_at')
            ->get(['id', 'title', 'published_at']);
    }

    public function updatedPageFormTitle(string $value): void
    {
        if ($this->pageId === null && blank($this->pageForm['slug'])) {
            $this->pageForm['slug'] = $this->buildSlugWithPrefix(Str::slug($value));
        }
    }

    public function updatedPageType(): void
    {
        if ($this->pageId === null && filled($this->pageForm['slug'])) {
            $bare = $this->stripSlugPrefix($this->pageForm['slug']);
            $this->pageForm['slug'] = $this->buildSlugWithPrefix($bare);
        }

        if ($this->pageType !== 'artikel') {
            $this->resetArticleForm();
        }

        $this->syncCmsTab();
    }

    public function updatedActivePageSlug(): void
    {
        $this->syncActivePage();
        $this->syncCmsTab();

        if ($this->activePage) {
            $this->isCreatingPage = false;
            $this->pageId = $this->activePage->id;
            $this->fillPageForm($this->activePage);
            $this->resetArticleForm();
        } else {
            $this->startCreatingPage();
        }

        $this->loadFieldValues();
        $this->resetErrorBag();
    }

    public function switchCmsTab(string $tab): void
    {
        if (! in_array($tab, ['landing', 'artikel', 'gallery'], true)) {
            return;
        }

        $this->cmsTab = $tab;

        if ($this->isCreatingPage) {
            $this->pageType = $this->pageTypeFromCmsTab($tab);

            return;
        }

        if ($tab === 'artikel') {
            $this->isCreatingPage = false;
            $this->articleId = null;
            $this->pageType = 'artikel';
            $this->selectedArticles = [];

            return;
        }

        if ($tab === 'gallery') {
            $this->isCreatingPage = false;
            $this->pageType = 'galeri';

            return;
        }

        $this->syncActivePageForCurrentTab();
    }

    public function selectPage(string $slug): void
    {
        $this->isCreatingPage = false;
        $this->activePageSlug = $slug;
        $page = Page::query()->where('slug', $slug)->first();

        if ($page) {
            $this->cmsTab = $this->cmsTabFromPageType($this->resolvePageType($page->slug));
        }

        $this->loadFieldValues();
    }

    public function selectArticle(int $articleId): void
    {
        $this->editArticle($articleId);
    }

    public function startCreatingPage(): void
    {
        $this->isCreatingPage = true;
        $this->pageId = null;
        $this->articleId = null;
        $this->pageType = $this->pageTypeFromCmsTab($this->cmsTab);
        $this->pageForm = [
            'title' => '',
            'slug' => '',
            'editor_name' => '',
            'tags' => '',
            'is_published' => true,
            'published_at' => '',
        ];
        $this->resetArticleForm();
        $this->resetErrorBag();
    }

    public function closeEditor(): void
    {
        $this->isCreatingPage = false;
        $this->articleId = null;
        $this->selectedArticles = [];

        if ($this->cmsTab !== 'artikel') {
            $this->syncActivePageForCurrentTab();
        }
    }

    public function editPage(int $pageId): void
    {
        $page = Page::query()->findOrFail($pageId);

        $this->isCreatingPage = false;
        $this->pageId = $page->id;
        $this->fillPageForm($page);
        $this->cmsTab = $this->cmsTabFromPageType($this->pageType);
        $this->activePageSlug = $page->slug;
        $this->resetArticleForm();
        $this->resetErrorBag();
    }

    public function editArticle(int $articleId): void
    {
        $article = Article::query()->with('tags')->findOrFail($articleId);

        $this->isCreatingPage = false;
        $this->pageType = 'artikel';
        $this->cmsTab = 'artikel';
        $this->articleId = $article->id;
        $this->pageId = null;
        $this->fillArticleMetaForm($article);
        $this->fillArticleContentForm($article);
        $this->resetErrorBag();
    }

    public function savePage(): void
    {
        if ($this->pageType === 'artikel') {
            $this->saveArticleMeta();

            return;
        }

        $this->normalizePageForm();

        $validated = $this->validate($this->pageRules(), $this->pageMessages());
        $pagePayload = $this->preparePagePayload($validated['pageForm']);

        if ($this->pageId) {
            $page = Page::query()->findOrFail($this->pageId);
            $page->update($pagePayload);
            $message = 'Halaman CMS berhasil diperbarui.';
        } else {
            $page = Page::query()->create([
                ...$pagePayload,
                'created_by' => auth()->id(),
            ]);
            $message = 'Tab halaman CMS baru berhasil dibuat.';
        }

        $this->isCreatingPage = false;
        $this->pageId = $page->id;
        $this->activePageSlug = $page->slug;
        $this->cmsTab = $this->cmsTabFromPageType($this->pageType);
        $this->resetArticleForm();

        unset($this->pages, $this->filteredPages, $this->activePage, $this->activePageSections, $this->availableRecommendedArticles);

        $this->loadFieldValues();
        $this->dispatch('toast', ['message' => $message, 'type' => 'success']);
    }

    public function saveFullArticle(): void
    {
        $this->saveArticleMeta();

        // Ensure meta saving was successful and we have an article ID
        if ($this->articleId && empty($this->getErrorBag()->all())) {
            $this->saveArticleContent();
        }
    }

    public function saveArticleContent(): void
    {
        if (! $this->articleId || $this->pageType !== 'artikel') {
            $this->dispatch('toast', ['message' => 'Simpan halaman artikel terlebih dahulu.', 'type' => 'error']);

            return;
        }

        $validated = $this->validate($this->articleRules(), $this->articleMessages());

        // Extract excerpt from body: remove headings and get normal text
        $bodyHtml = $validated['articleForm']['body'] ?? '';
        $excerpt = $this->generateExcerptFromHtml($bodyHtml);

        $article = Article::query()->findOrFail($this->articleId);
        $article->update([
            'excerpt' => $excerpt ?: null,
            'body' => $bodyHtml,
            'related_article_ids' => collect($validated['articleForm']['related_article_ids'] ?? [])
                ->map(fn (mixed $articleId): int => (int) $articleId)
                ->filter(fn (int $articleId): bool => $articleId > 0 && $articleId !== $article->id)
                ->unique()
                ->values()
                ->all(),
        ]);

        unset($this->articleEntries, $this->availableRecommendedArticles);
        $this->fillArticleContentForm($article->fresh());

        $this->dispatch('toast', ['message' => 'Konten artikel berhasil diperbarui.', 'type' => 'success']);
    }

    private function generateExcerptFromHtml(string $html): string
    {
        if (empty(trim($html))) {
            return '';
        }

        // Remove H1-H6 using regex easily
        $htmlWithoutHeadings = preg_replace('/<h[1-6][^>]*>.*?<\/h[1-6]>/is', '', $html);
        $plainText = strip_tags($htmlWithoutHeadings);
        $plainText = trim(preg_replace('/\s+/', ' ', $plainText));

        return Str::limit($plainText, 150, '');
    }

    public function addSliderImage(int $pageSectionId): void
    {
        $pageSection = PageSection::query()->findOrFail($pageSectionId);
        $count = $pageSection->fields()->count() + 1;
        $uniqueSlide = 'slide_'.uniqid();

        SectionField::query()->create([
            'page_section_id' => $pageSectionId,
            'field_key' => $uniqueSlide,
            'label' => 'Gambar Slide '.$count,
            'type' => 'image',
            'sort_order' => $count,
            'description' => 'Gambar dinamis pada slider Hero.',
        ]);

        unset($this->activePageSections);
        $this->loadFieldValues();
        $this->dispatch('toast', ['message' => 'Slot slide baru berhasil ditambahkan.', 'type' => 'success']);
    }

    public function deleteField(int $fieldId): void
    {
        $field = SectionField::query()->findOrFail($fieldId);

        if ($field->value?->mediaFile) {
            Storage::disk('public')->delete($field->value->mediaFile->file_path);
            $field->value->mediaFile->delete();
        }

        $field->delete();

        unset($this->activePageSections);
        $this->loadFieldValues();
        $this->dispatch('toast', ['message' => 'Field berhasil dihapus.', 'type' => 'success']);
    }

    public function addTestimonial(int $pageSectionId): void
    {
        $pageSection = PageSection::query()->findOrFail($pageSectionId);
        $id = uniqid('testi_');
        $count = $pageSection->fields()->count() + 1;

        $fields = [
            ['key' => $id.'_quote', 'label' => 'Quote Testimoni Baru', 'type' => 'textarea'],
            ['key' => $id.'_author', 'label' => 'Penulis Baru', 'type' => 'text'],
            ['key' => $id.'_role', 'label' => 'Peran/Profesi Baru', 'type' => 'text'],
            ['key' => $id.'_avatar', 'label' => 'Foto (Opsional)', 'type' => 'image'],
        ];

        foreach ($fields as $index => $fieldData) {
            SectionField::query()->create([
                'page_section_id' => $pageSectionId,
                'field_key' => $fieldData['key'],
                'label' => $fieldData['label'],
                'type' => $fieldData['type'],
                'sort_order' => $count + $index,
            ]);
        }

        unset($this->activePageSections);
        $this->loadFieldValues();
        $this->dispatch('toast', ['message' => 'Slot testimoni baru berhasil ditambahkan.', 'type' => 'success']);
    }

    public function addFaqItem(int $pageSectionId): void
    {
        $pageSection = PageSection::query()->findOrFail($pageSectionId);
        $id = uniqid('faq_');
        $count = $pageSection->fields()->count() + 1;

        $fields = [
            ['key' => $id.'_category', 'label' => 'Kategori FAQ Baru', 'type' => 'text'],
            ['key' => $id.'_question', 'label' => 'Pertanyaan FAQ Baru', 'type' => 'text'],
            ['key' => $id.'_answer', 'label' => 'Jawaban FAQ Baru', 'type' => 'textarea'],
        ];

        foreach ($fields as $index => $fieldData) {
            SectionField::query()->create([
                'page_section_id' => $pageSectionId,
                'field_key' => $fieldData['key'],
                'label' => $fieldData['label'],
                'type' => $fieldData['type'],
                'sort_order' => $count + $index,
            ]);
        }

        unset($this->activePageSections);
        $this->loadFieldValues();
        $this->dispatch('toast', ['message' => 'Item FAQ baru berhasil ditambahkan.', 'type' => 'success']);
    }

    public function deleteTestimonialGroup(string $prefix): void
    {
        $fields = SectionField::query()
            ->where('field_key', 'like', $prefix.'%')
            ->get();

        foreach ($fields as $field) {
            if ($field->value?->mediaFile) {
                Storage::disk('public')->delete($field->value->mediaFile->file_path);
                $field->value->mediaFile->delete();
            }
            $field->delete();
        }

        unset($this->activePageSections);
        $this->loadFieldValues();
        $this->dispatch('toast', ['message' => 'Grup testimoni berhasil dihapus.', 'type' => 'success']);
    }

    public function deleteFaqGroup(string $prefix): void
    {
        $fields = SectionField::query()
            ->where('field_key', 'like', $prefix.'%')
            ->get();

        foreach ($fields as $field) {
            if ($field->value?->mediaFile) {
                Storage::disk('public')->delete($field->value->mediaFile->file_path);
                $field->value->mediaFile->delete();
            }

            $field->delete();
        }

        unset($this->activePageSections);
        $this->loadFieldValues();
        $this->dispatch('toast', ['message' => 'Item FAQ berhasil dihapus.', 'type' => 'success']);
    }

    /**
     * Save field values for a specific page section.
     */
    public function saveFieldValues(int $pageSectionId): void
    {
        $pageSection = PageSection::query()
            ->with('fields')
            ->findOrFail($pageSectionId);

        foreach ($pageSection->fields as $field) {
            $rawValue = $this->fieldValues[$field->id] ?? null;

            if ($field->isImage()) {
                $this->saveImageField($field, $pageSectionId);

                continue;
            }

            FieldValue::query()->updateOrCreate(
                ['section_field_id' => $field->id],
                ['value' => $rawValue, 'media_file_id' => null]
            );
        }

        $this->lastSavedAt[$pageSectionId] = now()->format('H:i:s');

        unset($this->activePageSections);

        $this->dispatch('toast', ['message' => "Section '{$pageSection->label}' berhasil disimpan.", 'type' => 'success']);
    }

    private function saveImageField(SectionField $field, int $pageSectionId): void
    {
        /** @var TemporaryUploadedFile|null $upload */
        $upload = $this->fieldImages[$field->id] ?? null;

        if (! $upload) {
            return;
        }

        $existingFieldValue = FieldValue::query()
            ->with('mediaFile')
            ->where('section_field_id', $field->id)
            ->first();

        if ($existingFieldValue && $existingFieldValue->mediaFile) {
            Storage::disk('public')->delete($existingFieldValue->mediaFile->file_path);
            $existingFieldValue->mediaFile->delete();
        }

        $storedPath = $upload->store('cms/images', 'public');

        $mediaFile = MediaFile::query()->create([
            'file_name' => $upload->getClientOriginalName(),
            'file_path' => $storedPath,
            'mime_type' => $upload->getMimeType(),
            'file_size' => $upload->getSize(),
            'uploaded_by' => auth()->id(),
            'uploaded_at' => now(),
        ]);

        FieldValue::query()->updateOrCreate(
            ['section_field_id' => $field->id],
            ['value' => null, 'media_file_id' => $mediaFile->id]
        );

        unset($this->fieldImages[$field->id]);
    }

    private function saveArticleMeta(): void
    {
        $validated = $this->validate($this->articleMetaRules(), $this->pageMessages());
        $publishedAt = filled($validated['pageForm']['published_at'])
            ? Carbon::createFromFormat('Y-m-d', $validated['pageForm']['published_at'])->startOfDay()
            : ((bool) $validated['pageForm']['is_published'] ? now() : null);

        $payload = [
            'title' => trim($validated['pageForm']['title']),
            'slug' => $this->generateUniqueArticleSlug(trim($validated['pageForm']['title'])),
            'author_name' => trim((string) ($validated['pageForm']['editor_name'] ?? '')) ?: null,
            'status' => (bool) $validated['pageForm']['is_published'] ? 'published' : 'draft',
            'published_at' => $publishedAt,
        ];

        if ($this->articleId) {
            $article = Article::query()->findOrFail($this->articleId);
            $article->update($payload);
            $message = 'Artikel berhasil diperbarui.';
        } else {
            $article = Article::query()->create([
                ...$payload,
                'created_by' => auth()->id(),
                'excerpt' => $this->articleForm['excerpt'] ?: null,
                'body' => $this->articleForm['body'] ?: null,
                'related_article_ids' => [],
            ]);
            $message = 'Artikel baru berhasil dibuat.';
        }

        $this->syncArticleTags($article, (string) ($validated['pageForm']['tags'] ?? ''));
        $this->articleId = $article->id;
        $this->isCreatingPage = false;

        unset($this->articleEntries, $this->availableRecommendedArticles);

        $article->load('tags');
        $this->fillArticleMetaForm($article);
        $this->fillArticleContentForm($article);

        $this->dispatch('toast', ['message' => $message, 'type' => 'success']);
    }

    public function deletePage(int $pageId): void
    {
        if ($this->pageType === 'artikel' && $this->articleId) {
            $this->deleteArticle($this->articleId);

            return;
        }

        if (Page::query()->count() <= 1) {
            $this->dispatch('toast', ['message' => 'Minimal harus ada satu halaman CMS.', 'type' => 'error']);

            return;
        }

        $page = Page::query()->findOrFail($pageId);
        $page->delete();

        unset($this->pages, $this->filteredPages, $this->activePage, $this->activePageSections, $this->availableRecommendedArticles);

        $this->syncActivePage();
        $activePageId = Page::query()->where('slug', $this->activePageSlug)->value('id');

        if ($activePageId) {
            $this->editPage($activePageId);
        } else {
            $this->startCreatingPage();
        }

        $this->loadFieldValues();
        $this->dispatch('toast', ['message' => 'Halaman CMS berhasil dihapus.', 'type' => 'success']);
    }

    private function deleteArticle(int $articleId): void
    {
        Article::query()->findOrFail($articleId)->delete();

        unset($this->articleEntries, $this->availableRecommendedArticles);

        $nextArticle = Article::first();

        if ($nextArticle) {
            $this->closeEditor();
        } else {
            $this->startCreatingPage();
        }

        $this->dispatch('toast', ['message' => 'Artikel berhasil dihapus.', 'type' => 'success']);
    }

    public function bulkDelete(): void
    {
        if (empty($this->selectedArticles)) {
            return;
        }

        Article::whereIn('id', $this->selectedArticles)->delete();
        $this->selectedArticles = [];
        unset($this->articleEntries, $this->totalArticlesCount, $this->publishedArticlesCount, $this->draftArticlesCount, $this->totalArticlesViews);
        $this->dispatch('toast', ['message' => 'Artikel terpilih berhasil dihapus.', 'type' => 'success']);
    }

    public function bulkArchive(): void
    {
        if (empty($this->selectedArticles)) {
            return;
        }

        Article::whereIn('id', $this->selectedArticles)->update(['status' => 'draft']);
        $this->selectedArticles = [];
        unset($this->articleEntries, $this->totalArticlesCount, $this->publishedArticlesCount, $this->draftArticlesCount, $this->totalArticlesViews);
        $this->dispatch('toast', ['message' => 'Artikel terpilih berhasil diarsipkan.', 'type' => 'success']);
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
            'pageForm.editor_name' => ['nullable', 'string', 'max:255'],
            'pageForm.tags' => ['nullable', 'string', 'max:500'],
            'pageForm.is_published' => ['boolean'],
            'pageForm.published_at' => ['nullable', 'date'],
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
            'pageForm.published_at.date' => 'Tanggal publish harus berupa tanggal yang valid.',
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    protected function articleMetaRules(): array
    {
        return [
            'pageForm.title' => ['required', 'string', 'max:255'],
            'pageForm.editor_name' => ['nullable', 'string', 'max:255'],
            'pageForm.tags' => ['nullable', 'string', 'max:500'],
            'pageForm.is_published' => ['boolean'],
            'pageForm.published_at' => ['nullable', 'date'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    protected function articleRules(): array
    {
        return [
            'articleForm.excerpt' => ['nullable', 'string'],
            'articleForm.body' => ['required', 'string'],
            'articleForm.body_format' => ['required', 'string', Rule::in(['html'])],
            'articleForm.related_article_ids' => ['array'],
            'articleForm.related_article_ids.*' => ['integer', 'exists:articles,id'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function articleMessages(): array
    {
        return [
            'articleForm.body.required' => 'Isi artikel wajib diisi.',
            'articleForm.body_format.in' => 'Format artikel tidak valid.',
            'articleForm.related_article_ids.*.exists' => 'Artikel pada pilihan Baca Juga tidak ditemukan.',
        ];
    }

    private function loadFieldValues(): void
    {
        $this->fieldValues = [];
        $this->fieldImages = [];

        if (! $this->activePage) {
            return;
        }

        $sections = PageSection::query()
            ->with(['fields.value'])
            ->where('page_id', $this->activePage->id)
            ->get();

        foreach ($sections as $section) {
            foreach ($section->fields as $field) {
                if (! $field->isImage()) {
                    $this->fieldValues[$field->id] = $field->value?->value ?? '';
                }
            }
        }
    }

    private function fillPageForm(Page $page): void
    {
        $this->pageForm = [
            'title' => $page->title,
            'slug' => $page->slug,
            'editor_name' => $page->author_name ?? $page->editor_name ?? '',
            'tags' => is_array($page->tags) ? implode(', ', $page->tags) : '',
            'is_published' => $page->is_published,
            'published_at' => $page->published_at?->format('Y-m-d') ?? '',
        ];
        $this->pageType = $this->resolvePageType($page->slug);
    }

    private function fillArticleMetaForm(Article $article): void
    {
        $this->pageForm = [
            'title' => $article->title,
            'slug' => $article->slug,
            'editor_name' => $article->author_name ?? '',
            'tags' => $article->tags->pluck('name')->implode(', '),
            'is_published' => $article->status === 'published',
            'published_at' => $article->published_at?->format('Y-m-d') ?? '',
        ];
    }

    private function fillArticleContentForm(Article $article): void
    {
        $this->articleForm = [
            'excerpt' => $article->excerpt ?? '',
            'body' => $article->body ?? '',
            'body_format' => 'html',
            'related_article_ids' => collect($article->related_article_ids ?? [])
                ->map(fn (mixed $articleId): int => (int) $articleId)
                ->filter(fn (int $articleId): bool => $articleId > 0 && $articleId !== $article->id)
                ->values()
                ->all(),
        ];
    }

    private function resetArticleForm(): void
    {
        $this->articleForm = [
            'excerpt' => '',
            'body' => '',
            'body_format' => 'html',
            'related_article_ids' => [],
        ];
    }

    private function resolvePageType(string $slug): string
    {
        if (in_array($slug, Page::ARTICLE_SLUGS, true)) {
            return 'artikel';
        }

        foreach (Page::ARTICLE_PREFIXES as $prefix) {
            if (str_starts_with($slug, $prefix)) {
                return 'artikel';
            }
        }

        if (in_array($slug, Page::GALLERY_SLUGS, true)) {
            return 'galeri';
        }

        foreach (Page::GALLERY_PREFIXES as $prefix) {
            if (str_starts_with($slug, $prefix)) {
                return 'galeri';
            }
        }

        return 'statis';
    }

    private function buildSlugWithPrefix(string $bare): string
    {
        $normalizedBare = Str::slug($bare);

        if ($normalizedBare === '') {
            return '';
        }

        return match ($this->pageType) {
            'artikel' => 'artikel-'.$normalizedBare,
            'galeri' => 'galeri-'.$normalizedBare,
            default => $normalizedBare,
        };
    }

    private function stripSlugPrefix(string $slug): string
    {
        $allPrefixes = array_merge(Page::ARTICLE_PREFIXES, Page::GALLERY_PREFIXES);

        foreach ($allPrefixes as $prefix) {
            if (str_starts_with($slug, $prefix)) {
                return substr($slug, strlen($prefix));
            }
        }

        return $slug;
    }

    private function normalizePageForm(): void
    {
        $title = trim($this->pageForm['title']);
        $rawSlug = trim($this->pageForm['slug']);
        $normalizedRawSlug = Str::slug($rawSlug);
        $baseSlug = $this->stripSlugPrefix($normalizedRawSlug);
        $fallbackSlug = Str::slug($title);

        if ($this->pageId !== null && in_array($normalizedRawSlug, [...Page::ARTICLE_SLUGS, ...Page::GALLERY_SLUGS], true)) {
            $this->pageForm['slug'] = $normalizedRawSlug;
        } elseif ($this->pageType === 'artikel' || $this->pageType === 'galeri') {
            $this->pageForm['slug'] = $this->buildSlugWithPrefix($baseSlug !== '' ? $baseSlug : $fallbackSlug);
        } else {
            $this->pageForm['slug'] = $baseSlug !== '' ? Str::slug($baseSlug) : $fallbackSlug;
        }

        $this->pageForm['title'] = $title;
        $this->pageForm['editor_name'] = trim((string) ($this->pageForm['editor_name'] ?? ''));
        $this->pageForm['tags'] = trim((string) ($this->pageForm['tags'] ?? ''));
        $this->pageForm['published_at'] = trim((string) ($this->pageForm['published_at'] ?? ''));
    }

    /**
     * @param  array<string, mixed>  $pageForm
     * @return array<string, mixed>
     */
    private function preparePagePayload(array $pageForm): array
    {
        $payload = [
            'title' => $pageForm['title'],
            'slug' => $pageForm['slug'],
            'is_published' => (bool) $pageForm['is_published'],
        ];

        if ($this->pageType === 'artikel') {
            $payload['author_name'] = $pageForm['editor_name'] ?: null;
            $payload['editor_name'] = $pageForm['editor_name'] ?: null;
            $payload['tags'] = collect(explode(',', (string) $pageForm['tags']))
                ->map(fn (string $tag): string => trim($tag))
                ->filter()
                ->unique()
                ->values()
                ->all();
            $payload['related_article_ids'] = [];
            $payload['published_at'] = $pageForm['published_at']
                ? Carbon::createFromFormat('Y-m-d', $pageForm['published_at'])->startOfDay()
                : ($payload['is_published'] ? now() : null);
        } else {
            $payload['author_name'] = null;
            $payload['editor_name'] = null;
            $payload['tags'] = null;
            $payload['related_article_ids'] = null;
            $payload['published_at'] = null;
        }

        return $payload;
    }

    private function syncArticleTags(Article $article, string $rawTags): void
    {
        $tagIds = collect(explode(',', $rawTags))
            ->map(fn (string $tag): string => trim($tag))
            ->filter()
            ->unique()
            ->map(function (string $tagName): int {
                $tag = Tag::query()->firstOrCreate(
                    ['slug' => Str::slug($tagName)],
                    ['name' => $tagName],
                );

                if ($tag->name !== $tagName) {
                    $tag->update(['name' => $tagName]);
                }

                return $tag->id;
            })
            ->values()
            ->all();

        $article->tags()->sync($tagIds);
    }

    private function generateUniqueArticleSlug(string $title): string
    {
        $baseSlug = Str::slug($title) ?: 'artikel';
        $slug = $baseSlug;
        $suffix = 2;

        while (
            Article::query()
                ->where('slug', $slug)
                ->when($this->articleId, fn ($query) => $query->where('id', '!=', $this->articleId))
                ->exists()
        ) {
            $slug = $baseSlug.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }

    private function syncActivePage(): void
    {
        $page = Page::query()->where('slug', $this->activePageSlug)->first()
            ?? Page::query()->orderBy('id')->first();

        $this->activePageSlug = $page?->slug ?? '';
    }

    private function syncCmsTab(): void
    {
        if ($this->isCreatingPage) {
            $this->cmsTab = $this->cmsTabFromPageType($this->pageType);

            return;
        }

        if (! $this->activePage) {
            return;
        }

        $this->cmsTab = $this->cmsTabFromPageType($this->resolvePageType($this->activePage->slug));
    }

    private function syncActivePageForCurrentTab(): void
    {
        $page = $this->filteredPages->first();

        if ($page) {
            $this->activePageSlug = $page->slug;
            $this->editPage($page->id);
            $this->loadFieldValues();

            return;
        }

        $this->startCreatingPage();
    }

    private function pageTypeFromCmsTab(string $tab): string
    {
        return match ($tab) {
            'artikel' => 'artikel',
            'gallery' => 'galeri',
            default => 'statis',
        };
    }

    private function cmsTabFromPageType(string $pageType): string
    {
        return match ($pageType) {
            'artikel' => 'artikel',
            'galeri' => 'gallery',
            default => 'landing',
        };
    }

    private function ensurePageDefaultsExist(): void
    {
        $pageDefaults = [
            ['slug' => 'home', 'title' => 'Home'],
            ['slug' => 'profil', 'title' => 'Profil'],
            ['slug' => 'skema', 'title' => 'Skema Sertifikasi'],
            ['slug' => 'alur-sertifikasi', 'title' => 'Alur Sertifikasi'],
            ['slug' => 'tempat-uji', 'title' => 'Tempat Uji Kompetensi'],
            ['slug' => 'jadwal', 'title' => 'Jadwal Uji Kompetensi'],
            ['slug' => 'cek-sertifikat', 'title' => 'Validasi Sertifikat'],
            ['slug' => 'media', 'title' => 'Media'],
            ['slug' => 'instagram', 'title' => 'Instagram'],
            ['slug' => 'youtube', 'title' => 'YouTube'],
            ['slug' => 'facebook', 'title' => 'Facebook'],
            ['slug' => 'artikel', 'title' => 'Hot News (Artikel)'],
            ['slug' => 'faq', 'title' => 'FAQ (Q & A)'],
            ['slug' => 'galeri', 'title' => 'Kegiatan (Galeri)'],
            ['slug' => 'kontak', 'title' => 'Kontak'],
        ];

        foreach ($pageDefaults as $pageDefault) {
            Page::query()->firstOrCreate(
                ['slug' => $pageDefault['slug']],
                [
                    'title' => $pageDefault['title'],
                    'is_published' => true,
                    'created_by' => auth()->id(),
                ],
            );
        }
    }
}
