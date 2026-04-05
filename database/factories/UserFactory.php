<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'nim' => fake()->unique()->numerify('#########'),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'user_type' => 'upnvj',
            'profile_completed_at' => now(),
            'remember_token' => Str::random(10),
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the model has two-factor authentication configured.
     */
    public function withTwoFactor(): static
    {
        return $this->state(fn (array $attributes) => [
            'two_factor_secret' => encrypt('secret'),
            'two_factor_recovery_codes' => encrypt(json_encode(['recovery-code-1'])),
            'two_factor_confirmed_at' => now(),
        ]);
    }

    /**
     * Indicate that the user is a general public registrant.
     */
    public function general(): static
    {
        return $this->state(fn (array $attributes) => [
            'nim' => 'NON-'.Str::upper(fake()->unique()->bothify('##########')),
            'user_type' => 'umum',
            'profile_completed_at' => null,
            'fakultas' => null,
            'status_semester' => null,
            'total_sks' => null,
        ]);
    }

    /**
     * Indicate that the general user has completed their biodata.
     */
    public function completedGeneralProfile(): static
    {
        return $this->general()->state(fn (array $attributes) => [
            'name' => 'Peserta Umum',
            'no_ktp' => '3174000000000001',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1998-04-10',
            'jenis_kelamin' => 'L',
            'alamat_rumah' => 'Jl. Contoh No. 1, Jakarta',
            'domisili_provinsi' => 'DKI Jakarta',
            'domisili_kota' => 'Jakarta Selatan',
            'domisili_kecamatan' => 'Setiabudi',
            'no_wa' => '081234567890',
            'pendidikan_terakhir' => 'S1',
            'nama_institusi' => 'Universitas Contoh',
            'program_studi' => 'Teknik Informatika',
            'pekerjaan' => 'Karyawan Swasta',
            'profile_completed_at' => now(),
        ]);
    }
}
