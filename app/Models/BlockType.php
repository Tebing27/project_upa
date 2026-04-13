<?php

namespace App\Models;

use Database\Factories\BlockTypeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlockType extends Model
{
    /** @use HasFactory<BlockTypeFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'schema_name',
    ];

    public function contentBlocks(): HasMany
    {
        return $this->hasMany(ContentBlock::class);
    }
}
