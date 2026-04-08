<?php

namespace Database\Factories;

use App\Models\Scheme;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CertificateFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'scheme_id' => Scheme::factory(),
            'certificate_number' => fake()->unique()->uuid(),
            'status' => 'active',
        ];
    }
}
