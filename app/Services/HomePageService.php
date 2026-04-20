<?php

namespace App\Services;

use App\Models\Gallery;
use App\Models\Page;
use App\Models\Scheme;
use Illuminate\Support\Collection;

class HomePageService
{
    /**
     * @return Collection<int, Scheme>
     */
    public function latestSchemes(int $limit = 3): Collection
    {
        return Scheme::query()
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * @return Collection<int, Gallery>
     */
    public function latestPublishedGalleries(int $limit = 6): Collection
    {
        return Gallery::query()
            ->where('is_published', true)
            ->latest('id')
            ->limit($limit)
            ->get();
    }

    /**
     * @return array<string, mixed>
     */
    public function content(): array
    {
        $homePage = Page::query()
            ->where('slug', 'home')
            ->with([
                'pageSections' => fn ($query) => $query->where('is_visible', true),
                'pageSections.fields.value',
            ])
            ->first();

        if (! $homePage) {
            return [];
        }

        $content = [
            'hero_slides' => [],
            'testimonials' => [],
        ];

        foreach ($homePage->pageSections as $pageSection) {
            foreach ($pageSection->fields as $field) {
                $value = $field->isImage()
                    ? ($field->value?->imageUrl() ?? null)
                    : ($field->value?->value ?? null);

                if ($value !== null && $value !== '') {
                    $content[$field->field_key] = $value;
                }

                if ($pageSection->section_key === 'hero_slider' && preg_match('/^slide_[a-z0-9]+$/i', $field->field_key)) {
                    if ($value) {
                        $content['hero_slides'][] = $value;
                    }
                }

                if (
                    $pageSection->section_key === 'home_testimonials'
                    && preg_match('/^(testi_[a-z0-9]+)_([a-z]+)$/i', $field->field_key, $matches)
                ) {
                    $prefix = $matches[1];
                    $subKey = $matches[2];

                    if ($value) {
                        $content['testimonials'][$prefix][$subKey] = $value;
                    }
                }
            }
        }

        return $content;
    }
}
