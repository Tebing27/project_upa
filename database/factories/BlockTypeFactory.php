<?php

namespace Database\Factories;

use App\Models\BlockType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BlockType>
 */
class BlockTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->slug(),
            'schema_name' => fake()->slug(),
        ];
    }
}
