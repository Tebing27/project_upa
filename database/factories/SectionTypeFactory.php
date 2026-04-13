<?php

namespace Database\Factories;

use App\Models\SectionType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<SectionType>
 */
class SectionTypeFactory extends Factory
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
            'description' => Str::ucfirst(fake()->sentence()),
        ];
    }
}
