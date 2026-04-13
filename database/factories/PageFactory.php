<?php

namespace Database\Factories;

use App\Models\Page;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Page>
 */
class PageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'slug' => fake()->unique()->slug(),
            'title' => Str::title(fake()->words(2, true)),
            'excerpt' => fake()->sentence(),
            'author_name' => fake()->name(),
            'editor_name' => fake()->name(),
            'tags' => [fake()->word(), fake()->word()],
            'related_article_ids' => [],
            'is_published' => true,
            'published_at' => now(),
            'created_by' => User::factory()->state(['role' => 'admin']),
        ];
    }
}
