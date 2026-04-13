<?php

namespace App\Models;

use Database\Factories\ContentBlockFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ContentBlock extends Model
{
    /** @use HasFactory<ContentBlockFactory> */
    use HasFactory;

    protected $fillable = [
        'section_id',
        'block_type_id',
        'sort_order',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function blockType(): BelongsTo
    {
        return $this->belongsTo(BlockType::class);
    }

    public function textContent(): HasOne
    {
        return $this->hasOne(TextContent::class);
    }

    public function imageContent(): HasOne
    {
        return $this->hasOne(ImageContent::class);
    }

    public function isText(): bool
    {
        return $this->blockType?->name === 'text';
    }

    public function isImage(): bool
    {
        return $this->blockType?->name === 'image';
    }
}
