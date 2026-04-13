<?php

namespace Database\Factories;

use App\Models\ContentBlock;
use App\Models\TextContent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TextContent>
 */
class TextContentFactory extends Factory
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
            'value' => fake()->paragraph(),
            'format' => 'plain',
        ];
    }
}
