<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SchemeSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'nim' => '123456789',
            'email' => 'test@example.com',
            'password' => Hash::make('password'), // rahasia123
        ]);

        User::factory()->create([
            'name' => 'Admin System',
            'nim' => '987654321',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'user_type'=> 'admin',
        ]);
    }
}
