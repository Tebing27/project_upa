<?php

namespace Database\Factories;

use App\Models\Scheme;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RegistrationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'scheme_id' => Scheme::factory(),
            'type' => 'baru',
            'payment_reference' => fake()->unique()->uuid(),
            'status' => 'pending_payment',
        ];
    }
}
