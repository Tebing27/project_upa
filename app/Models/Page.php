<?php

namespace App\Models;

use Database\Factories\PageFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    /** @use HasFactory<PageFactory> */
    use HasFactory;

    private const ARTICLE_PREFIXES = ['artikel-', 'berita-', 'news-'];

    private const GALLERY_PREFIXES = ['galeri-', 'gallery-', 'kegiatan-'];

    protected $fillable = [
        'slug',
        'title',
        'is_published',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
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

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeArticleEntries(Builder $query): Builder
    {
        return $query->published()->where(function (Builder $builder): void {
            foreach (self::ARTICLE_PREFIXES as $prefix) {
                $builder->orWhere('slug', 'like', $prefix.'%');
            }
        });
    }

    public function scopeGalleryEntries(Builder $query): Builder
    {
        return $query->published()->where(function (Builder $builder): void {
            foreach (self::GALLERY_PREFIXES as $prefix) {
                $builder->orWhere('slug', 'like', $prefix.'%');
            }
        });
    }
}
