<?php

namespace Database\Factories;

use App\Models\BlockType;
use App\Models\ContentBlock;
use App\Models\Section;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ContentBlock>
 */
class ContentBlockFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'section_id' => Section::factory(),
            'block_type_id' => BlockType::factory(),
            'sort_order' => fake()->numberBetween(1, 9),
        ];
    }
}
