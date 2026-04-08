<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Gallery;

class GallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $galleries = [
            [
                'title' => 'Pelatihan Asesor Internal',
                'description' => 'Kegiatan pelatihan intensif selama 3 hari bagi dosen untuk menjadi asesor kompetensi bersertifikat BNSP.',
                'image_path' => 'images/kegiatan-1.jpeg',
            ],
            [
                'title' => 'Uji Kompetensi Batch 1',
                'description' => 'Pelaksanaan uji kompetensi skema Network Administrator di Fakultas Ilmu Komputer.',
                'image_path' => 'images/kegiatan-2.jpeg',
            ],
            [
                'title' => 'Sosialisasi Sertifikasi ke Mahasiswa',
                'description' => 'Pemaparan pentingnya sertifikasi kompetensi ke mahasiswa tingkat akhir oleh Tim UPA LUK.',
                'image_path' => 'images/kegiatan-3.jpeg',
            ],
            [
                'title' => 'Visitasi Master Asesor',
                'description' => 'Kunjungan kerja dan quality assurance dari pihak BNSP untuk evaluasi sistem.',
                'image_path' => null,
            ],
            [
                'title' => 'Penyerahan Sertifikat Kompetensi',
                'description' => 'Momen simbolis penyerahan sertifikat ke asesi terbaik angkatan 2024.',
                'image_path' => null,
            ],
            [
                'title' => 'Rapat Kerja Tahunan LSP',
                'description' => 'Perumusan rencana strategis dan penambahan ruang lingkup skema sertifikasi di UPNVJ.',
                'image_path' => null,
            ]
        ];

        foreach($galleries as $gallery) {
            Gallery::create([
                'title' => $gallery['title'],
                'description' => $gallery['description'],
                'image_path' => $gallery['image_path'],
            ]);
        }
    }
}
