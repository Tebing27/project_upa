<?php

namespace Database\Seeders;

use App\Models\Assessor;
use App\Models\Faculty;
use App\Models\Scheme;
use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Faculties and Study Programs
        $fik = Faculty::create(['name' => 'Fakultas Ilmu Komputer']);
        $feb = Faculty::create(['name' => 'Fakultas Ekonomi dan Bisnis']);

        $inf = StudyProgram::create(['faculty_id' => $fik->id, 'nama' => 'Informatika']);
        $si = StudyProgram::create(['faculty_id' => $fik->id, 'nama' => 'Sistem Informasi']);
        $ak = StudyProgram::create(['faculty_id' => $feb->id, 'nama' => 'Akuntansi']);

        // 2. Create Schemes
        $schemesData = [
            [
                'faculty_id' => $fik->id,
                'study_program_id' => $inf->id,
                'nama' => 'Skema Sertifikasi Programmer',
                'deskripsi' => 'Sertifikasi kompetensi programmer level junior.',
                'is_active' => true,
            ],
            [
                'faculty_id' => $fik->id,
                'study_program_id' => $si->id,
                'nama' => 'Skema Sertifikasi Network Administrator',
                'deskripsi' => 'Sertifikasi kompetensi network administrator madya.',
                'is_active' => true,
            ],
            [
                'faculty_id' => $feb->id,
                'study_program_id' => $ak->id,
                'nama' => 'Skema Sertifikasi Akuntan Publik',
                'deskripsi' => 'Sertifikasi dasar untuk akuntan publik profesional.',
                'is_active' => true,
            ],
        ];

        foreach ($schemesData as $schemeData) {
            Scheme::create($schemeData);
        }

        // 3. Assessors
        Assessor::create(['nama' => 'Budi Santoso']);
        Assessor::create(['nama' => 'Siti Aminah']);

        // 4. Admin User
        $admin = User::create([
            'nama' => 'Admin System',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
            'profile_completed_at' => now(),
        ]);

        // 5. Test Mahasiswa User
        $mahasiswa = User::create([
            'nama' => 'Mahasiswa Test',
            'email' => 'mahasiswa@example.com',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'email_verified_at' => now(),
            'profile_completed_at' => now(),
        ]);
        $mahasiswa->mahasiswaProfile()->create([
            'nim' => '123456789',
            'total_sks' => 144,
            'status_semester' => 'Aktif',
        ]);
        $mahasiswa->profile()->create([
            'fakultas' => 'Fakultas Ilmu Komputer',
            'program_studi' => 'Informatika',
            'jenis_kelamin' => 'L',
            'no_wa' => '081234567890',
        ]);

        // 6. Test Umum User
        $umum = User::create([
            'nama' => 'Umum Test',
            'email' => 'umum@example.com',
            'password' => Hash::make('password'),
            'role' => 'umum',
            'email_verified_at' => now(),
            'profile_completed_at' => now(),
        ]);
        $umum->umumProfile()->create([
            'no_ktp' => '3174000000000001',
            'pendidikan_terakhir' => 'S1',
            'nama_pekerjaan' => 'Software Engineer',
        ]);
        $umum->profile()->create([
            'jenis_kelamin' => 'L',
            'no_wa' => '081234567891',
        ]);

        $this->call([
            CmsContentSeeder::class,
        ]);
    }
}
