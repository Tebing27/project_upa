<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SectionField extends Model
{
    protected $fillable = [
        'page_section_id',
        'field_key',
        'label',
        'type',
        'sort_order',
        'description',
    ];

    public function pageSection(): BelongsTo
    {
        return $this->belongsTo(PageSection::class);
    }

    public function value(): HasOne
    {
        return $this->hasOne(FieldValue::class);
    }

    public function isImage(): bool
    {
        return $this->type === 'image';
    }

    public function isRichText(): bool
    {
        return $this->type === 'rich_text';
    }

    public function isTextarea(): bool
    {
        return $this->type === 'textarea';
    }

    public function isUrl(): bool
    {
        return $this->type === 'url';
    }
}
