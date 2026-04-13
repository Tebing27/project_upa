<?php

namespace Database\Factories;

use App\Models\MediaFile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MediaFile>
 */
class MediaFileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'file_name' => fake()->lexify('image-????').'.png',
            'file_path' => 'cms/images/'.fake()->lexify('image-????').'.png',
            'mime_type' => 'image/png',
            'file_size' => fake()->numberBetween(10_000, 500_000),
            'uploaded_by' => User::factory()->state(['role' => 'admin']),
            'uploaded_at' => now(),
        ];
    }
}
