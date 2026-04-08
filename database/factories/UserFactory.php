<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'role' => 'mahasiswa',
            'profile_completed_at' => now(),
            'remember_token' => Str::random(10),
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ];
    }

    public function general(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'umum',
            'profile_completed_at' => null,
        ]);
    }

    public function completedGeneralProfile(): static
    {
        return $this->general()->state(fn (array $attributes) => [
            'profile_completed_at' => now(),
        ]);
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function withTwoFactor(): static
    {
        return $this->state(fn (array $attributes) => [
            'two_factor_secret' => Crypt::encryptString('secret'),
            'two_factor_recovery_codes' => Crypt::encryptString(json_encode([
                'recovery-code-1',
                'recovery-code-2',
            ], JSON_THROW_ON_ERROR)),
            'two_factor_confirmed_at' => now(),
        ]);
    }
}
