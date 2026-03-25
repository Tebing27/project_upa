<?php

namespace Database\Factories;

use App\Models\Registration;
use App\Models\Scheme;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Registration>
 */
class RegistrationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'scheme_id' => Scheme::factory(),
            'payment_reference' => fake()->unique()->uuid(),
            'status' => 'pending_payment',
        ];
    }
}
