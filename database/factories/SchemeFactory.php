<?php

namespace Database\Factories;

use App\Models\Faculty;
use App\Models\StudyProgram;
use Illuminate\Database\Eloquent\Factories\Factory;

class SchemeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'faculty_id' => Faculty::factory(),
            'study_program_id' => StudyProgram::factory(),
            'nama' => fake()->jobTitle(),
            'deskripsi' => fake()->sentence(),
            'is_active' => true,
            'is_popular' => false,
        ];
    }
}
