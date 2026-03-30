<?php

namespace Database\Seeders;

use App\Models\Scheme;
use App\Models\StudyProgram;
use Illuminate\Database\Seeder;

class SchemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schemes = [
            [
                'name' => 'Skema Sertifikasi Programmer',
                'faculty' => 'Fakultas Ilmu Komputer',
                'study_program' => 'S1 Informatika',
                'description' => 'Sertifikasi kompetensi programmer level junior.',
                'is_active' => true,
            ],
            [
                'name' => 'Skema Sertifikasi Network Administrator',
                'faculty' => 'Fakultas Ilmu Komputer',
                'study_program' => 'Sistem Informasi',
                'description' => 'Sertifikasi kompetensi network administrator madya.',
                'is_active' => true,
            ],
            [
                'name' => 'Skema Sertifikasi Akuntan Publik',
                'faculty' => 'Fakultas Ekonomi dan Bisnis',
                'study_program' => 'Akuntansi',
                'description' => 'Sertifikasi dasar untuk akuntan publik profesional.',
                'is_active' => true,
            ],
        ];

        foreach ($schemes as $scheme) {
            $studyProgramName = $scheme['study_program'];
            $facultyName = $scheme['faculty'];
            unset($scheme['faculty'], $scheme['study_program']);

            $createdScheme = Scheme::create($scheme);

            $studyProgram = StudyProgram::where('name', $studyProgramName)->first();
            if ($studyProgram) {
                $createdScheme->studyPrograms()->attach($studyProgram->id);
            }
        }
    }
}
