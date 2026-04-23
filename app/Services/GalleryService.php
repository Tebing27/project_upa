<?php

namespace App\Services;

use App\Models\Gallery;
use App\Models\Page;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use stdClass;

class GalleryService
{
    public function paginatePublished(int $perPage = 12): LengthAwarePaginator
    {
        $galleries = Gallery::query()
            ->where('is_published', true)
            ->latest('id')
            ->paginate($perPage);

        if ($galleries->isNotEmpty()) {
            return $galleries;
        }

        return $this->paginateFallbackPages($perPage);
    }

    public function latestPublished(int $limit = 6): Collection
    {
        return Gallery::query()
            ->where('is_published', true)
            ->latest('id')
            ->limit($limit)
            ->get();
    }

    private function paginateFallbackPages(int $perPage): LengthAwarePaginator
    {
        $galleryPages = Page::query()
            ->galleryEntries()
            ->latest('published_at')
            ->latest('id')
            ->get()
            ->map(fn (Page $page): stdClass => (object) [
                'title' => $page->title,
                'description' => $page->excerpt,
                'file_path' => null,
                'type' => 'photo',
            ]);

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $items = $galleryPages->forPage($currentPage, $perPage)->values();

        return new LengthAwarePaginator(
            items: $items,
            total: $galleryPages->count(),
            perPage: $perPage,
            currentPage: $currentPage,
            options: [
                'path' => request()->url(),
                'pageName' => 'page',
            ],
        );
    }
}
