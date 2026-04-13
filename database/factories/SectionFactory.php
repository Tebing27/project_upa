<?php

namespace Database\Factories;

use App\Models\Page;
use App\Models\Section;
use App\Models\SectionType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Section>
 */
class SectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'page_id' => Page::factory(),
            'section_type_id' => SectionType::factory(),
            'sort_order' => fake()->numberBetween(1, 9),
            'is_visible' => true,
        ];
    }
}
