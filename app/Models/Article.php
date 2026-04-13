<?php

namespace App\Models;

use Database\Factories\ArticleFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Article extends Model
{
    /** @use HasFactory<ArticleFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'author_name',
        'excerpt',
        'body',
        'related_article_ids',
        'status',
        'published_at',
        'created_by',
        'views',
    ];

    protected function casts(): array
    {
        return [
            'related_article_ids' => 'array',
            'published_at' => 'datetime',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function publicUrl(): string
    {
        return route('article.show', ['slug' => $this->publicSlug()]);
    }

    public function publicSlug(): string
    {
        return (Str::slug($this->title) ?: $this->slug).'-'.$this->id;
    }
}
