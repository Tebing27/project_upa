<?php

namespace Database\Seeders;

use App\Models\Faculty;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Faculty & Study Program
        $facultyFIK = Faculty::firstOrCreate(['name' => 'Fakultas Ilmu Komputer']);
        $studyProgramIF = StudyProgram::firstOrCreate(['name' => 'S1 Informatika', 'faculty_id' => $facultyFIK->id]);
        $studyProgramSI = StudyProgram::firstOrCreate(['name' => 'Sistem Informasi', 'faculty_id' => $facultyFIK->id]);

        $facultyFEB = Faculty::firstOrCreate(['name' => 'Fakultas Ekonomi dan Bisnis']);
        $studyProgramAkt = StudyProgram::firstOrCreate(['name' => 'Akuntansi', 'faculty_id' => $facultyFEB->id]);

        // 2. Create Users (Admin, Asesor, Mahasiswa)
        User::factory()->create([
            'name' => 'Admin LSP UPNVJ',
            'nim' => '199310202025061009',
            'email' => 'admin.lsp@upnvj.ac.id',
            'password' => Hash::make('password'),
            'role' => 'admin_lsp',
            'study_program_id' => $studyProgramIF->id,
        ]);

        User::factory()->create([
            'name' => 'Asesor Informatika',
            'nim' => '0000000002',
            'email' => 'asesor.if@upnvj.ac.id',
            'password' => Hash::make('password'),
            'role' => 'asesor',
            'study_program_id' => $studyProgramIF->id,
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'nim' => '123456789',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa', // Pastikan defaultnya mahasiswa jika dibutuhkan
            'study_program_id' => $studyProgramIF->id,
        ]);

        // 3. Create Schemes and Pivot
        $this->call([
            SchemeSeeder::class,
        ]);
    }
}
