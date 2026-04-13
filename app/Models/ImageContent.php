<?php

namespace App\Models;

use Database\Factories\ImageContentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImageContent extends Model
{
    /** @use HasFactory<ImageContentFactory> */
    use HasFactory;

    protected $fillable = [
        'content_block_id',
        'media_file_id',
        'alt_text',
        'caption',
    ];

    public function contentBlock(): BelongsTo
    {
        return $this->belongsTo(ContentBlock::class);
    }

    public function mediaFile(): BelongsTo
    {
        return $this->belongsTo(MediaFile::class);
    }
}
