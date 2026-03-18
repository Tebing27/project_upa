<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SchemeSeeder::class ,
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'nim' => '123456789',
            'email' => 'test@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'), //rahasia123
        ]);
    }
}
