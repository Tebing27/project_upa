<?php

namespace Database\Seeders;

use App\Models\BlockType;
use App\Models\ContentBlock;
use App\Models\ImageContent;
use App\Models\MediaFile;
use App\Models\Page;
use App\Models\Section;
use App\Models\SectionType;
use App\Models\TextContent;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CmsContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->ensureCmsTypesExist();

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
            $this->seedPageWithTextBlocks(
                pageData: $pageData,
                sectionTypeName: 'content',
                adminId: $adminId,
            );
        }

        foreach ($this->galleryPages() as $pageData) {
            $this->seedPageWithTextBlocks(
                pageData: $pageData,
                sectionTypeName: 'gallery',
                adminId: $adminId,
            );
        }
    }

    private function ensureCmsTypesExist(): void
    {
        SectionType::query()->firstOrCreate(
            ['name' => 'content'],
            ['description' => 'Section isi utama untuk paragraf atau informasi umum.'],
        );
        SectionType::query()->firstOrCreate(
            ['name' => 'gallery'],
            ['description' => 'Section dengan fokus gambar atau media visual.'],
        );
        BlockType::query()->firstOrCreate(
            ['name' => 'text'],
            ['schema_name' => 'text_content'],
        );
        BlockType::query()->firstOrCreate(
            ['name' => 'image'],
            ['schema_name' => 'image_content'],
        );
    }

    private function seedPageWithTextBlocks(array $pageData, string $sectionTypeName, ?int $adminId): void
    {
        $page = Page::query()->updateOrCreate(
            ['slug' => $pageData['slug']],
            [
                'title' => $pageData['title'],
                'is_published' => true,
                'created_by' => $adminId,
            ],
        );

        $section = Section::query()->updateOrCreate(
            [
                'page_id' => $page->id,
                'sort_order' => 1,
            ],
            [
                'section_type_id' => SectionType::query()->where('name', $sectionTypeName)->value('id'),
                'is_visible' => true,
            ],
        );

        foreach ($pageData['blocks'] as $index => $blockData) {
            if (($blockData['type'] ?? 'text') === 'image') {
                $block = ContentBlock::query()->updateOrCreate(
                    [
                        'section_id' => $section->id,
                        'sort_order' => $index + 1,
                    ],
                    [
                        'block_type_id' => BlockType::query()->where('name', 'image')->value('id'),
                    ],
                );

                $mediaFile = MediaFile::query()->updateOrCreate(
                    ['file_path' => $blockData['file_path']],
                    [
                        'file_name' => basename(parse_url($blockData['file_path'], PHP_URL_PATH) ?: $blockData['file_path']),
                        'mime_type' => $blockData['mime_type'] ?? 'image/jpeg',
                        'file_size' => $blockData['file_size'] ?? 0,
                        'uploaded_by' => $adminId,
                        'uploaded_at' => now(),
                    ],
                );

                ImageContent::query()->updateOrCreate(
                    ['content_block_id' => $block->id],
                    [
                        'media_file_id' => $mediaFile->id,
                        'alt_text' => $blockData['alt_text'] ?? $pageData['title'],
                        'caption' => $blockData['caption'] ?? null,
                    ],
                );

                continue;
            }

            $block = ContentBlock::query()->updateOrCreate(
                [
                    'section_id' => $section->id,
                    'sort_order' => $index + 1,
                ],
                [
                    'block_type_id' => BlockType::query()->where('name', 'text')->value('id'),
                ],
            );

            TextContent::query()->updateOrCreate(
                ['content_block_id' => $block->id],
                [
                    'value' => $blockData['value'],
                    'format' => $blockData['format'] ?? 'plain',
                ],
            );
        }
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
