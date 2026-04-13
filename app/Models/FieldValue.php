<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class FieldValue extends Model
{
    protected $fillable = [
        'section_field_id',
        'value',
        'media_file_id',
    ];

    public function sectionField(): BelongsTo
    {
        return $this->belongsTo(SectionField::class);
    }

    public function mediaFile(): BelongsTo
    {
        return $this->belongsTo(MediaFile::class);
    }

    public function imageUrl(): ?string
    {
        if (! $this->mediaFile || ! $this->mediaFile->file_path) {
            return null;
        }

        return Storage::url($this->mediaFile->file_path);
    }
}
