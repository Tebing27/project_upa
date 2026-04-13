<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Scheme;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\View\View;
use stdClass;

class LandingPageController extends Controller
{
    public function index(): View
    {
        $latestSchemes = Scheme::latest()->take(3)->get();
        $articles = $this->articlePagesQuery()
            ->take(3)
            ->get()
            ->map(fn (Page $page): stdClass => $this->mapArticlePage($page));
        $galleries = $this->galleryPagesQuery()
            ->take(6)
            ->get()
            ->map(fn (Page $page): stdClass => $this->mapGalleryPage($page));

        return view('welcome', compact('latestSchemes', 'articles', 'galleries'));
    }

    public function articlesIndex(): View
    {
        $articles = $this->articlePagesQuery()
            ->paginate(9)
            ->through(fn (Page $page): stdClass => $this->mapArticlePage($page));

        return view('article-index', compact('articles'));
    }

    public function showArticle(string $slug): View
    {
        $page = $this->articlePagesQuery()
            ->where('slug', $slug)
            ->firstOrFail();
        $article = $this->mapArticlePage($page);

        return view('article-detail', compact('article'));
    }

    public function galleryIndex(): View
    {
        $galleries = $this->galleryPagesQuery()
            ->paginate(12)
            ->through(fn (Page $page): stdClass => $this->mapGalleryPage($page));

        return view('gallery-index', compact('galleries'));
    }

    private function articlePagesQuery(): Builder
    {
        return Page::query()
            ->articleEntries()
            ->with([
                'creator',
                'sections.sectionType',
                'sections.contentBlocks.blockType',
                'sections.contentBlocks.textContent',
                'sections.contentBlocks.imageContent.mediaFile',
            ])
            ->latest('updated_at');
    }

    private function galleryPagesQuery(): Builder
    {
        return Page::query()
            ->galleryEntries()
            ->with([
                'sections.sectionType',
                'sections.contentBlocks.blockType',
                'sections.contentBlocks.textContent',
                'sections.contentBlocks.imageContent.mediaFile',
            ])
            ->latest('updated_at');
    }

    private function mapArticlePage(Page $page): stdClass
    {
        $textBlocks = $this->textBlocks($page);
        $body = $this->renderBody($textBlocks);
        $excerptSource = $textBlocks->skip(1)->first()?->textContent?->value
            ?? $textBlocks->first()?->textContent?->value
            ?? '';

        return (object) [
            'title' => $page->title,
            'slug' => $page->slug,
            'image_path' => $this->firstImagePath($page),
            'tags' => $this->pageTags($page),
            'views_count' => 0,
            'excerpt' => Str::limit(strip_tags($excerptSource), 120),
            'body' => $body,
            'published_at' => $page->updated_at,
            'created_at' => $page->created_at,
            'user' => $page->creator,
        ];
    }

    private function mapGalleryPage(Page $page): stdClass
    {
        $textBlocks = $this->textBlocks($page);

        return (object) [
            'title' => $page->title,
            'image_path' => $this->firstImagePath($page),
            'description' => Str::limit(
                strip_tags($textBlocks->first()?->textContent?->value ?? ''),
                160
            ),
        ];
    }

    private function textBlocks(Page $page): Collection
    {
        return $this->visibleSections($page)
            ->flatMap(fn ($section) => $section->contentBlocks)
            ->filter(fn ($block) => $block->blockType?->name === 'text' && filled($block->textContent?->value))
            ->values();
    }

    private function firstImagePath(Page $page): ?string
    {
        $filePath = $this->visibleSections($page)
            ->flatMap(fn ($section) => $section->contentBlocks)
            ->first(fn ($block) => $block->blockType?->name === 'image' && filled($block->imageContent?->mediaFile?->file_path))
            ?->imageContent
            ?->mediaFile
            ?->file_path;

        if (! is_string($filePath) || blank($filePath)) {
            return null;
        }

        return Str::startsWith($filePath, ['http://', 'https://'])
            ? $filePath
            : \Storage::url($filePath);
    }

    private function pageTags(Page $page): array
    {
        return $this->visibleSections($page)
            ->map(fn ($section) => $section->sectionType?->name)
            ->filter()
            ->unique()
            ->map(fn (string $name): string => Str::headline($name))
            ->values()
            ->all();
    }

    private function renderBody(Collection $textBlocks): string
    {
        return $textBlocks
            ->map(function ($block): string {
                $value = $block->textContent?->value ?? '';
                $format = $block->textContent?->format ?? 'plain';

                return match ($format) {
                    'html' => $value,
                    'markdown' => Str::markdown($value),
                    default => '<p>'.e($value).'</p>',
                };
            })
            ->implode("\n");
    }

    private function visibleSections(Page $page): Collection
    {
        return $page->sections->where('is_visible', true)->values();
    }
}
