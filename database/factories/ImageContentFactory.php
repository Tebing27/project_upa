<?php

namespace Database\Factories;

use App\Models\ContentBlock;
use App\Models\ImageContent;
use App\Models\MediaFile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ImageContent>
 */
class ImageContentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'content_block_id' => ContentBlock::factory(),
            'media_file_id' => MediaFile::factory(),
            'alt_text' => fake()->sentence(3),
            'caption' => fake()->sentence(),
        ];
    }
}
