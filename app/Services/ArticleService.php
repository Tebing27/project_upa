<?php

namespace App\Services;

use App\Models\Article;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use stdClass;

class ArticleService
{
    /**
     * @return Collection<int, stdClass>
     */
    public function latest(int $limit = 3): Collection
    {
        return $this->query()
            ->limit($limit)
            ->get()
            ->map(fn (Article $article): stdClass => $this->present($article));
    }

    public function paginate(int $perPage = 9): LengthAwarePaginator
    {
        return $this->query()
            ->paginate($perPage)
            ->through(fn (Article $article): stdClass => $this->present($article));
    }

    public function findByPublicSlug(string $slug): Article
    {
        $articleId = $this->extractArticleId($slug);
        $article = $this->query()->find($articleId);

        if (! $article) {
            throw (new ModelNotFoundException)->setModel(Article::class, [$articleId]);
        }

        return $article;
    }

    public function incrementViews(Article $article): void
    {
        $article->increment('views');
    }

    public function present(Article $article): stdClass
    {
        $body = $this->normalizeBody($article->body ?? '');

        return (object) [
            'id' => $article->id,
            'title' => $article->title,
            'slug' => $article->slug,
            'url' => $article->publicUrl(),
            'image_path' => preg_match('/<img[^>]+src="([^">]+)"/', $article->body ?? '', $matches) ? $matches[1] : null,
            'tags' => $article->tags->pluck('name')->all(),
            'views_count' => (int) $article->views,
            'excerpt' => Str::limit(strip_tags($article->excerpt ?: $body), 120),
            'body' => $body,
            'body_segments' => $this->splitBody($body, $article),
            'published_at' => $article->published_at ?? $article->updated_at,
            'created_at' => $article->created_at,
            'author_name' => $article->author_name ?: $article->creator?->nama ?: 'Admin LSP',
            'related_articles' => $this->relatedArticles($article),
        ];
    }

    private function query(): Builder
    {
        return Article::query()
            ->published()
            ->with(['creator', 'tags'])
            ->latest('published_at')
            ->latest('id');
    }

    private function extractArticleId(string $slug): int
    {
        if (! preg_match('/-(\d+)$/', $slug, $matches)) {
            throw (new ModelNotFoundException)->setModel(Article::class, [$slug]);
        }

        return (int) $matches[1];
    }

    private function normalizeBody(string $html): string
    {
        if ($html === '') {
            return '';
        }

        $document = new \DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);

        $document->loadHTML(
            '<?xml encoding="utf-8" ?><div id="article-root">'.$html.'</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );

        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = $document->getElementById('article-root');

        if (! $root) {
            return $html;
        }

        foreach (iterator_to_array($document->getElementsByTagName('figcaption')) as $caption) {
            if (! $caption instanceof \DOMElement) {
                continue;
            }

            $paragraph = $document->createElement('p');

            while ($caption->firstChild) {
                $paragraph->appendChild($caption->firstChild);
            }

            $caption->parentNode?->replaceChild($paragraph, $caption);
        }

        foreach (iterator_to_array($document->getElementsByTagName('figure')) as $figure) {
            if (! $figure instanceof \DOMElement || ! $figure->parentNode) {
                continue;
            }

            while ($figure->firstChild) {
                $figure->parentNode->insertBefore($figure->firstChild, $figure);
            }

            $figure->parentNode->removeChild($figure);
        }

        foreach ($root->getElementsByTagName('*') as $element) {
            if (! $element instanceof \DOMElement) {
                continue;
            }

            foreach (['data-trix-attachment', 'data-trix-content-type', 'data-trix-attributes'] as $attribute) {
                if ($element->hasAttribute($attribute)) {
                    $element->removeAttribute($attribute);
                }
            }

            if (! $element->hasAttribute('class')) {
                continue;
            }

            $classes = preg_split('/\s+/', trim($element->getAttribute('class'))) ?: [];
            $classes = array_values(array_filter($classes, function (string $class): bool {
                return $class !== 'attachment'
                    && $class !== 'trix-content'
                    && ! str_starts_with($class, 'attachment--');
            }));

            if ($classes === []) {
                $element->removeAttribute('class');

                continue;
            }

            $element->setAttribute('class', implode(' ', $classes));
        }

        $normalized = '';

        foreach ($root->childNodes as $childNode) {
            $normalized .= $document->saveHTML($childNode) ?: '';
        }

        return trim($normalized);
    }

    /**
     * @return array{before:string,after:string}
     */
    private function splitBody(string $html, Article $article): array
    {
        if ($html === '' || blank($article->related_article_ids)) {
            return [
                'before' => $html,
                'after' => '',
            ];
        }

        $segments = $this->extractHtmlSegments($html);

        if (count($segments) < 2) {
            return [
                'before' => $html,
                'after' => '',
            ];
        }

        $splitIndex = (int) ceil(count($segments) / 2);

        return [
            'before' => implode('', array_slice($segments, 0, $splitIndex)),
            'after' => implode('', array_slice($segments, $splitIndex)),
        ];
    }

    /**
     * @return array<int, string>
     */
    private function extractHtmlSegments(string $html): array
    {
        $document = new \DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);

        $document->loadHTML(
            '<?xml encoding="utf-8" ?><div id="article-root">'.$html.'</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );

        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = $document->getElementById('article-root');

        if (! $root) {
            return [$html];
        }

        $segments = [];

        foreach ($root->childNodes as $childNode) {
            $segments[] = trim($document->saveHTML($childNode) ?: '');
        }

        return array_values(array_filter($segments));
    }

    /**
     * @return array<int, stdClass>
     */
    private function relatedArticles(Article $article): array
    {
        $relatedArticleIds = collect(Arr::wrap($article->related_article_ids))
            ->map(fn (mixed $articleId): int => (int) $articleId)
            ->filter(fn (int $articleId): bool => $articleId > 0 && $articleId !== $article->id)
            ->unique()
            ->values();

        if ($relatedArticleIds->isEmpty()) {
            return [];
        }

        $relatedArticles = Article::query()
            ->published()
            ->whereIn('id', $relatedArticleIds)
            ->get()
            ->keyBy('id');

        return $relatedArticleIds
            ->map(function (int $articleId) use ($relatedArticles): ?stdClass {
                $relatedArticle = $relatedArticles->get($articleId);

                if (! $relatedArticle) {
                    return null;
                }

                return (object) [
                    'title' => $relatedArticle->title,
                    'url' => $relatedArticle->publicUrl(),
                    'excerpt' => Str::limit(strip_tags($relatedArticle->excerpt ?: $relatedArticle->body), 110),
                    'published_at' => $relatedArticle->published_at ?? $relatedArticle->updated_at,
                ];
            })
            ->filter()
            ->values()
            ->all();
    }
}
