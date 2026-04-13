<?php

namespace App\Models;

use Database\Factories\PageFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Page extends Model
{
    /** @use HasFactory<PageFactory> */
    use HasFactory;

    public const ARTICLE_SLUGS = ['artikel', 'berita', 'news'];

    public const ARTICLE_PREFIXES = ['artikel-', 'berita-', 'news-'];

    public const GALLERY_SLUGS = ['galeri', 'gallery', 'kegiatan'];

    public const GALLERY_PREFIXES = ['galeri-', 'gallery-', 'kegiatan-'];

    protected $fillable = [
        'slug',
        'title',
        'excerpt',
        'author_name',
        'editor_name',
        'tags',
        'related_article_ids',
        'is_published',
        'published_at',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'related_article_ids' => 'array',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class)->orderBy('sort_order');
    }

    public function pageSections(): HasMany
    {
        return $this->hasMany(PageSection::class)->orderBy('sort_order');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeArticleEntries(Builder $query): Builder
    {
        return $query->published()->where(function (Builder $builder): void {
            $builder->whereIn('slug', self::ARTICLE_SLUGS);

            foreach (self::ARTICLE_PREFIXES as $prefix) {
                $builder->orWhere('slug', 'like', $prefix.'%');
            }
        });
    }

    public function scopeGalleryEntries(Builder $query): Builder
    {
        return $query->published()->where(function (Builder $builder): void {
            $builder->whereIn('slug', self::GALLERY_SLUGS);

            foreach (self::GALLERY_PREFIXES as $prefix) {
                $builder->orWhere('slug', 'like', $prefix.'%');
            }
        });
    }

    public function publicArticleSlug(): string
    {
        $baseSlug = Str::slug($this->title) ?: Str::slug($this->slug) ?: 'artikel';

        return $baseSlug.'-'.$this->id;
    }
}
