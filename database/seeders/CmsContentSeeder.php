<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\MediaFile;
use App\Models\Page;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CmsContentSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = User::query()->where('role', 'admin')->value('id')
            ?? User::query()->value('id')
            ?? User::query()->create([
                'nama' => 'CMS Seeder Admin',
                'email' => 'cms-seeder@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
                'profile_completed_at' => now(),
            ])->id;

        foreach ($this->articlePages() as $pageData) {
            $this->seedArticle($pageData, $adminId);
        }

        foreach ($this->galleryPages() as $pageData) {
            $this->seedGalleryPage($pageData, $adminId);
        }
    }

    private function seedGalleryPage(array $pageData, ?int $adminId): void
    {
        $page = Page::query()->updateOrCreate(
            ['slug' => $pageData['slug']],
            [
                'title' => $pageData['title'],
                'is_published' => true,
                'created_by' => $adminId,
            ],
        );

        // Simpan gambar pertama sebagai MediaFile jika ada (tanpa relasi ke sections)
        foreach ($pageData['blocks'] as $blockData) {
            if (($blockData['type'] ?? 'text') === 'image') {
                MediaFile::query()->updateOrCreate(
                    ['file_path' => $blockData['file_path']],
                    [
                        'file_name' => basename(parse_url($blockData['file_path'], PHP_URL_PATH) ?: $blockData['file_path']),
                        'mime_type' => $blockData['mime_type'] ?? 'image/jpeg',
                        'file_size' => $blockData['file_size'] ?? 0,
                        'uploaded_by' => $adminId,
                        'uploaded_at' => now(),
                    ],
                );
            }
        }
    }

    private function seedArticle(array $articleData, ?int $adminId): void
    {
        $article = Article::query()->updateOrCreate(
            ['slug' => $articleData['slug']],
            [
                'title' => $articleData['title'],
                'author_name' => $articleData['author_name'] ?? 'Tim CMS',
                'excerpt' => $articleData['excerpt'] ?? null,
                'body' => collect($articleData['blocks'])
                    ->where('type', '!=', 'image')
                    ->map(function (array $block): string {
                        if (($block['format'] ?? 'plain') === 'html') {
                            return $block['value'];
                        }

                        return '<p>'.$block['value'].'</p>';
                    })
                    ->implode(''),
                'status' => 'published',
                'published_at' => now(),
                'created_by' => $adminId,
            ],
        );

        $tagIds = collect($articleData['tags'] ?? [])
            ->map(function (string $tagName): int {
                return Tag::query()->firstOrCreate(
                    ['slug' => Str::slug($tagName)],
                    ['name' => $tagName],
                )->id;
            })
            ->all();

        $article->tags()->sync($tagIds);
    }

    private function articlePages(): array
    {
        return [
            [
                'slug' => 'berita-pembukaan-skema-sertifikasi-2026',
                'title' => 'Pembukaan Skema Sertifikasi 2026',
                'blocks' => [
                    [
                        'value' => 'Pendaftaran skema sertifikasi 2026 resmi dibuka untuk mahasiswa dan peserta umum.',
                    ],
                    [
                        'value' => '<h2>Informasi Penting</h2><p>Peserta dapat memilih skema sesuai kompetensi dan mengikuti tahapan verifikasi melalui dashboard. Kuota awal dibuka untuk beberapa program studi prioritas.</p>',
                        'format' => 'html',
                    ],
                ],
            ],
            [
                'slug' => 'artikel-workshop-persiapan-uji-kompetensi',
                'title' => 'Workshop Persiapan Uji Kompetensi',
                'blocks' => [
                    [
                        'value' => 'UPA LUK mengadakan workshop intensif untuk membantu peserta memahami alur asesmen dan dokumen wajib.',
                    ],
                    [
                        'value' => 'Materi workshop mencakup simulasi asesmen, penyusunan portofolio, dan sesi tanya jawab bersama asesor.',
                    ],
                ],
            ],
            [
                'slug' => 'news-update-validasi-sertifikat-digital',
                'title' => 'Update Validasi Sertifikat Digital',
                'blocks' => [
                    [
                        'value' => 'Layanan validasi sertifikat digital kini dapat diakses lebih cepat melalui halaman publik UPA LUK.',
                    ],
                    [
                        'value' => 'Pengguna cukup memasukkan nomor sertifikat dan data pendukung untuk memeriksa keabsahan sertifikat secara mandiri.',
                    ],
                ],
            ],
        ];
    }

    private function galleryPages(): array
    {
        return [
            [
                'slug' => 'galeri-kegiatan-asesmen-lab-komputer',
                'title' => 'Pelatihan Asesor Internal',
                'blocks' => [
                    [
                        'value' => 'Kegiatan pelatihan intensif selama 3 hari bagi dosen untuk menjadi asesor kompetensi bersertifikat BNSP.',
                    ],
                    [
                        'type' => 'image',
                        'file_path' => 'https://i.ibb.co.com/yBPy1KGh/kegiatan-1.jpg',
                        'caption' => 'Pelatihan Asesor Internal',
                    ],
                ],
            ],
            [
                'slug' => 'galeri-uji-kompetensi-batch-1',
                'title' => 'Uji Kompetensi Batch 1',
                'blocks' => [
                    [
                        'value' => 'Pelaksanaan uji kompetensi skema Network Administrator di Fakultas Ilmu Komputer.',
                    ],
                    [
                        'type' => 'image',
                        'file_path' => 'https://i.ibb.co.com/ccsdqVGM/kegiatan-2.jpg',
                        'caption' => 'Uji Kompetensi Batch 1',
                    ],
                ],
            ],
            [
                'slug' => 'gallery-sosialisasi-sertifikasi-mahasiswa',
                'title' => 'Sosialisasi Sertifikasi ke Mahasiswa',
                'blocks' => [
                    [
                        'value' => 'Pemaparan pentingnya sertifikasi kompetensi ke mahasiswa tingkat akhir oleh Tim UPA LUK.',
                    ],
                    [
                        'type' => 'image',
                        'file_path' => 'https://i.ibb.co.com/wZKJ2B9h/kegiatan-3.jpg',
                        'caption' => 'Sosialisasi Sertifikasi ke Mahasiswa',
                    ],
                ],
            ],
            [
                'slug' => 'galeri-visitasi-dan-monitoring-tuk',
                'title' => 'Visitasi Master Asesor',
                'blocks' => [
                    ['value' => 'Kunjungan kerja dan quality assurance dari pihak BNSP untuk evaluasi sistem.'],
                ],
            ],
            [
                'slug' => 'gallery-penyerahan-sertifikat-kompetensi',
                'title' => 'Penyerahan Sertifikat Kompetensi',
                'blocks' => [
                    ['value' => 'Momen penyerahan sertifikat kompetensi kepada peserta yang dinyatakan kompeten.'],
                ],
            ],
            [
                'slug' => 'kegiatan-kunjungan-industri-dan-kolaborasi',
                'title' => 'Rapat Kerja Tahunan LSP',
                'blocks' => [
                    ['value' => 'Perumusan rencana strategis dan penambahan ruang lingkup skema sertifikasi di UPNVJ.'],
                ],
            ],
        ];
    }
}
