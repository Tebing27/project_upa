<?php

namespace App\Models;

use Database\Factories\TextContentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TextContent extends Model
{
    /** @use HasFactory<TextContentFactory> */
    use HasFactory;

    protected $fillable = [
        'content_block_id',
        'value',
        'format',
    ];

    public function contentBlock(): BelongsTo
    {
        return $this->belongsTo(ContentBlock::class);
    }
}
