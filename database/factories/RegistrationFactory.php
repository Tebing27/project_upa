<?php

namespace Database\Factories;

use App\Models\Registration;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Registration>
 */
class RegistrationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'scheme_id' => \App\Models\Scheme::factory(),
            'payment_reference' => fake()->unique()->uuid(),
            'status' => 'pending_payment',
        ];
    }
}
