<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\PageSection;
use App\Models\SectionField;
use Illuminate\Database\Seeder;

class PageFieldsSeeder extends Seeder
{
    public function run(): void
    {
        foreach (self::pageStructure() as $pageSlug => $sections) {
            $page = Page::query()->where('slug', $pageSlug)->first();

            if (! $page) {
                $this->command->warn("Page with slug '{$pageSlug}' not found. Skipping.");

                continue;
            }

            foreach ($sections as $sectionSortOrder => $sectionData) {
                $pageSection = PageSection::query()->updateOrCreate(
                    [
                        'page_id' => $page->id,
                        'section_key' => $sectionData['key'],
                    ],
                    [
                        'label' => $sectionData['label'],
                        'sort_order' => $sectionSortOrder,
                        'is_visible' => true,
                    ]
                );

                foreach ($sectionData['fields'] as $fieldSortOrder => $fieldData) {
                    SectionField::query()->updateOrCreate(
                        [
                            'page_section_id' => $pageSection->id,
                            'field_key' => $fieldData['key'],
                        ],
                        [
                            'label' => $fieldData['label'],
                            'type' => $fieldData['type'],
                            'sort_order' => $fieldSortOrder,
                            'description' => $fieldData['description'] ?? null,
                        ]
                    );
                }
            }
        }

        $this->command->info('PageFieldsSeeder selesai.');
    }

    /**
     * @return array<string, array<int, array<string, mixed>>>
     */
    public static function pageStructure(): array
    {
        return [
            'home' => [
                1 => [
                    'key' => 'hero',
                    'label' => 'Hero Section',
                    'fields' => [
                        1 => ['key' => 'hero_title', 'label' => 'Hero Title', 'type' => 'text', 'description' => 'Judul utama yang muncul di bagian atas halaman.'],
                        2 => ['key' => 'hero_subtitle', 'label' => 'Hero Subtitle', 'type' => 'textarea', 'description' => 'Deskripsi singkat di bawah judul utama.'],
                        3 => ['key' => 'cta_text', 'label' => 'CTA Text', 'type' => 'text', 'description' => 'Teks tombol Call-to-Action.'],
                        4 => ['key' => 'cta_link', 'label' => 'CTA Link', 'type' => 'url', 'description' => 'URL tujuan tombol CTA.'],
                        5 => ['key' => 'hero_image', 'label' => 'Hero Image', 'type' => 'image', 'description' => 'Gambar latar atau ilustrasi utama hero.'],
                    ],
                ],
                2 => [
                    'key' => 'about',
                    'label' => 'About Section',
                    'fields' => [
                        1 => ['key' => 'description', 'label' => 'Description', 'type' => 'rich_text', 'description' => 'Deskripsi panjang tentang lembaga.'],
                        2 => ['key' => 'about_image', 'label' => 'About Image', 'type' => 'image', 'description' => 'Foto tim atau gedung.'],
                    ],
                ],
                3 => [
                    'key' => 'video',
                    'label' => 'Video Section',
                    'fields' => [
                        1 => ['key' => 'youtube_url', 'label' => 'YouTube URL', 'type' => 'url', 'description' => 'URL embed YouTube (format: https://www.youtube.com/embed/...).'],
                    ],
                ],
                4 => [
                    'key' => 'whatsapp',
                    'label' => 'WhatsApp Contact',
                    'fields' => [
                        1 => ['key' => 'wa_number', 'label' => 'Nomor WhatsApp', 'type' => 'text', 'description' => 'Nomor WA tanpa tanda + atau 0 di depan, contoh: 6281234567890.'],
                        2 => ['key' => 'wa_message', 'label' => 'Pesan Default', 'type' => 'textarea', 'description' => 'Pesan awal yang otomatis terisi saat user klik tombol WA.'],
                    ],
                ],
                5 => [
                    'key' => 'hero_slider',
                    'label' => 'Hero Slider',
                    'fields' => [
                        1 => ['key' => 'slide_1', 'label' => 'Gambar Slide 1', 'type' => 'image', 'description' => 'Gambar pertama pada slider Hero.'],
                        2 => ['key' => 'slide_2', 'label' => 'Gambar Slide 2', 'type' => 'image', 'description' => 'Gambar kedua pada slider Hero.'],
                        3 => ['key' => 'slide_3', 'label' => 'Gambar Slide 3', 'type' => 'image', 'description' => 'Gambar ketiga pada slider Hero.'],
                    ],
                ],
                6 => [
                    'key' => 'home_steps',
                    'label' => 'Langkah Pendaftaran',
                    'fields' => [
                        1 => ['key' => 'step_1_title', 'label' => 'Judul Langkah 1', 'type' => 'text'],
                        2 => ['key' => 'step_1_desc', 'label' => 'Deskripsi Langkah 1', 'type' => 'text'],
                        3 => ['key' => 'step_2_title', 'label' => 'Judul Langkah 2', 'type' => 'text'],
                        4 => ['key' => 'step_2_desc', 'label' => 'Deskripsi Langkah 2', 'type' => 'text'],
                        5 => ['key' => 'step_3_title', 'label' => 'Judul Langkah 3', 'type' => 'text'],
                        6 => ['key' => 'step_3_desc', 'label' => 'Deskripsi Langkah 3', 'type' => 'text'],
                        7 => ['key' => 'step_4_title', 'label' => 'Judul Langkah 4', 'type' => 'text'],
                        8 => ['key' => 'step_4_desc', 'label' => 'Deskripsi Langkah 4', 'type' => 'text'],
                        9 => ['key' => 'step_5_title', 'label' => 'Judul Langkah 5', 'type' => 'text'],
                        10 => ['key' => 'step_5_desc', 'label' => 'Deskripsi Langkah 5', 'type' => 'text'],
                    ],
                ],
                7 => [
                    'key' => 'home_registration',
                    'label' => 'Informasi Registrasi',
                    'fields' => [
                        1 => ['key' => 'reg_subtitle', 'label' => 'Sub Judul', 'type' => 'text'],
                        2 => ['key' => 'reg_title', 'label' => 'Judul Pendaftaran', 'type' => 'text'],
                        3 => ['key' => 'reg_desc', 'label' => 'Deskripsi Pendaftaran', 'type' => 'rich_text'],
                        4 => ['key' => 'reg_period_label', 'label' => 'Label Periode', 'type' => 'text'],
                        5 => ['key' => 'reg_period_value', 'label' => 'Waktu Periode', 'type' => 'text'],
                        6 => ['key' => 'reg_exec_label', 'label' => 'Label Pelaksanaan', 'type' => 'text'],
                        7 => ['key' => 'reg_exec_value', 'label' => 'Waktu Pelaksanaan', 'type' => 'text'],
                    ],
                ],
                8 => [
                    'key' => 'home_testimonials',
                    'label' => 'Apa Kata Mereka',
                    'fields' => [
                        1 => ['key' => 'testi_fallback1_quote', 'label' => 'Quote Testimoni 1', 'type' => 'textarea'],
                        2 => ['key' => 'testi_fallback1_author', 'label' => 'Penulis 1', 'type' => 'text'],
                        3 => ['key' => 'testi_fallback1_role', 'label' => 'Peran/Profesi 1', 'type' => 'text'],
                        4 => ['key' => 'testi_fallback1_avatar', 'label' => 'Foto 1 (Opsional)', 'type' => 'image'],
                        5 => ['key' => 'testi_fallback2_quote', 'label' => 'Quote Testimoni 2', 'type' => 'textarea'],
                        6 => ['key' => 'testi_fallback2_author', 'label' => 'Penulis 2', 'type' => 'text'],
                        7 => ['key' => 'testi_fallback2_role', 'label' => 'Peran/Profesi 2', 'type' => 'text'],
                        8 => ['key' => 'testi_fallback2_avatar', 'label' => 'Foto 2 (Opsional)', 'type' => 'image'],
                    ],
                ],
            ],
            'profil' => [
                1 => [
                    'key' => 'profil_intro',
                    'label' => 'Profil Lembaga',
                    'fields' => [
                        1 => ['key' => 'profil_heading', 'label' => 'Judul Profil', 'type' => 'text', 'description' => 'Judul utama pada section profil.'],
                        2 => ['key' => 'profil_text', 'label' => 'Teks Profil', 'type' => 'rich_text', 'description' => 'Konten halaman profil lembaga.'],
                    ],
                ],
                2 => [
                    'key' => 'profil_tabs',
                    'label' => 'Visi, Misi, Tugas, Wewenang',
                    'fields' => [
                        1 => ['key' => 'visi_title', 'label' => 'Judul Visi', 'type' => 'text'],
                        2 => ['key' => 'visi_text', 'label' => 'Isi Visi', 'type' => 'textarea'],
                        3 => ['key' => 'misi_title', 'label' => 'Judul Misi', 'type' => 'text'],
                        4 => ['key' => 'misi_items', 'label' => 'Daftar Misi', 'type' => 'textarea', 'description' => 'Pisahkan tiap poin dengan baris baru.'],
                        5 => ['key' => 'tugas_title', 'label' => 'Judul Tugas', 'type' => 'text'],
                        6 => ['key' => 'tugas_items', 'label' => 'Daftar Tugas', 'type' => 'textarea', 'description' => 'Pisahkan tiap poin dengan baris baru.'],
                        7 => ['key' => 'wewenang_title', 'label' => 'Judul Wewenang', 'type' => 'text'],
                        8 => ['key' => 'wewenang_items', 'label' => 'Daftar Wewenang', 'type' => 'textarea', 'description' => 'Pisahkan tiap poin dengan baris baru.'],
                    ],
                ],
                3 => [
                    'key' => 'profil_leadership',
                    'label' => 'Pimpinan & Staf',
                    'fields' => [
                        1 => ['key' => 'leader_name', 'label' => 'Nama Pimpinan', 'type' => 'text'],
                        2 => ['key' => 'leader_title', 'label' => 'Jabatan Pimpinan', 'type' => 'text'],
                        3 => ['key' => 'leader_image', 'label' => 'Foto Pimpinan', 'type' => 'image'],
                        4 => ['key' => 'staff_1_name', 'label' => 'Nama Staf 1', 'type' => 'text'],
                        5 => ['key' => 'staff_1_title', 'label' => 'Jabatan Staf 1', 'type' => 'text'],
                        6 => ['key' => 'staff_1_prefix', 'label' => 'Prefix Staf 1', 'type' => 'text'],
                        7 => ['key' => 'staff_1_image', 'label' => 'Foto Staf 1', 'type' => 'image'],
                        8 => ['key' => 'staff_2_name', 'label' => 'Nama Staf 2', 'type' => 'text'],
                        9 => ['key' => 'staff_2_title', 'label' => 'Jabatan Staf 2', 'type' => 'text'],
                        10 => ['key' => 'staff_2_prefix', 'label' => 'Prefix Staf 2', 'type' => 'text'],
                        11 => ['key' => 'staff_2_image', 'label' => 'Foto Staf 2', 'type' => 'image'],
                    ],
                ],
                4 => [
                    'key' => 'profil_structure',
                    'label' => 'Bagan Struktur Organisasi',
                    'fields' => [
                        1 => ['key' => 'structure_heading', 'label' => 'Judul Struktur', 'type' => 'text'],
                        2 => ['key' => 'structure_image', 'label' => 'Gambar Struktur', 'type' => 'image'],
                    ],
                ],
            ],
            'kontak' => [
                1 => [
                    'key' => 'hero',
                    'label' => 'Hero Kontak',
                    'fields' => [
                        1 => ['key' => 'contact_title', 'label' => 'Judul Halaman', 'type' => 'text', 'description' => 'Judul utama halaman kontak.'],
                        2 => ['key' => 'contact_subtitle', 'label' => 'Subjudul Halaman', 'type' => 'textarea', 'description' => 'Deskripsi singkat di bawah judul kontak.'],
                    ],
                ],
                2 => [
                    'key' => 'content',
                    'label' => 'Informasi Kontak',
                    'fields' => [
                        1 => ['key' => 'address_label', 'label' => 'Label Alamat', 'type' => 'text', 'description' => 'Judul kartu alamat.'],
                        2 => ['key' => 'alamat', 'label' => 'Alamat', 'type' => 'textarea', 'description' => 'Alamat lengkap kantor.'],
                        3 => ['key' => 'email_label', 'label' => 'Label Email', 'type' => 'text', 'description' => 'Judul kartu email.'],
                        4 => ['key' => 'email', 'label' => 'Email', 'type' => 'text', 'description' => 'Alamat email resmi lembaga.'],
                        5 => ['key' => 'phone_label', 'label' => 'Label Telepon', 'type' => 'text', 'description' => 'Judul kartu telepon.'],
                        6 => ['key' => 'telepon', 'label' => 'Telepon', 'type' => 'text', 'description' => 'Nomor telepon yang bisa dihubungi.'],
                        7 => ['key' => 'maps_embed', 'label' => 'Google Maps Embed', 'type' => 'textarea', 'description' => 'Kode iframe embed dari Google Maps.'],
                    ],
                ],
            ],
            'skema' => [
                1 => [
                    'key' => 'hero',
                    'label' => 'Header Skema',
                    'fields' => [
                        1 => ['key' => 'scheme_title', 'label' => 'Judul Halaman', 'type' => 'text'],
                        2 => ['key' => 'scheme_subtitle', 'label' => 'Subjudul Halaman', 'type' => 'textarea'],
                        3 => ['key' => 'scheme_search_placeholder', 'label' => 'Placeholder Pencarian', 'type' => 'text'],
                    ],
                ],
            ],
            'alur-sertifikasi' => [
                1 => [
                    'key' => 'hero',
                    'label' => 'Header Alur Sertifikasi',
                    'fields' => [
                        1 => ['key' => 'flow_title', 'label' => 'Judul Halaman', 'type' => 'text'],
                        2 => ['key' => 'flow_subtitle', 'label' => 'Subjudul Halaman', 'type' => 'textarea'],
                    ],
                ],
                2 => [
                    'key' => 'steps',
                    'label' => 'Langkah Alur',
                    'fields' => [
                        1 => ['key' => 'flow_step_1_title', 'label' => 'Judul Langkah 1', 'type' => 'text'],
                        2 => ['key' => 'flow_step_1_desc', 'label' => 'Deskripsi Langkah 1', 'type' => 'textarea'],
                        3 => ['key' => 'flow_step_2_title', 'label' => 'Judul Langkah 2', 'type' => 'text'],
                        4 => ['key' => 'flow_step_2_desc', 'label' => 'Deskripsi Langkah 2', 'type' => 'textarea'],
                        5 => ['key' => 'flow_step_3_title', 'label' => 'Judul Langkah 3', 'type' => 'text'],
                        6 => ['key' => 'flow_step_3_desc', 'label' => 'Deskripsi Langkah 3', 'type' => 'textarea'],
                        7 => ['key' => 'flow_step_4_title', 'label' => 'Judul Langkah 4', 'type' => 'text'],
                        8 => ['key' => 'flow_step_4_desc', 'label' => 'Deskripsi Langkah 4', 'type' => 'textarea'],
                        9 => ['key' => 'flow_step_5_title', 'label' => 'Judul Langkah 5', 'type' => 'text'],
                        10 => ['key' => 'flow_step_5_desc', 'label' => 'Deskripsi Langkah 5', 'type' => 'textarea'],
                    ],
                ],
            ],
            'tempat-uji' => [
                1 => [
                    'key' => 'hero',
                    'label' => 'Header Tempat Uji',
                    'fields' => [
                        1 => ['key' => 'test_center_title', 'label' => 'Judul Halaman', 'type' => 'text'],
                        2 => ['key' => 'test_center_subtitle', 'label' => 'Subjudul Halaman', 'type' => 'textarea'],
                    ],
                ],
                2 => [
                    'key' => 'content',
                    'label' => 'Konten Tempat Uji',
                    'fields' => [
                        1 => ['key' => 'test_center_description', 'label' => 'Deskripsi', 'type' => 'rich_text'],
                        2 => ['key' => 'test_center_address', 'label' => 'Alamat', 'type' => 'textarea'],
                        3 => ['key' => 'test_center_maps_embed', 'label' => 'Google Maps Embed', 'type' => 'textarea'],
                        4 => ['key' => 'test_center_image', 'label' => 'Gambar Tempat Uji', 'type' => 'image'],
                    ],
                ],
            ],
            'jadwal' => [
                1 => [
                    'key' => 'hero',
                    'label' => 'Header Jadwal',
                    'fields' => [
                        1 => ['key' => 'schedule_title', 'label' => 'Judul Halaman', 'type' => 'text'],
                        2 => ['key' => 'schedule_subtitle', 'label' => 'Subjudul Halaman', 'type' => 'textarea'],
                        3 => ['key' => 'schedule_notice', 'label' => 'Catatan Jadwal', 'type' => 'textarea'],
                    ],
                ],
            ],
            'cek-sertifikat' => [
                1 => [
                    'key' => 'hero',
                    'label' => 'Header Validasi Sertifikat',
                    'fields' => [
                        1 => ['key' => 'certificate_check_title', 'label' => 'Judul Halaman', 'type' => 'text'],
                        2 => ['key' => 'certificate_check_subtitle', 'label' => 'Subjudul Halaman', 'type' => 'textarea'],
                        3 => ['key' => 'certificate_check_help_text', 'label' => 'Teks Bantuan', 'type' => 'textarea'],
                    ],
                ],
            ],
            'media' => [
                1 => [
                    'key' => 'content',
                    'label' => 'Media Sosial',
                    'fields' => [
                        1 => ['key' => 'instagram_url', 'label' => 'Instagram URL', 'type' => 'url', 'description' => 'URL profil Instagram.'],
                        2 => ['key' => 'youtube_url', 'label' => 'YouTube URL', 'type' => 'url', 'description' => 'URL channel YouTube.'],
                        3 => ['key' => 'facebook_url', 'label' => 'Facebook URL', 'type' => 'url', 'description' => 'URL halaman Facebook.'],
                    ],
                ],
            ],
            'instagram' => [
                1 => [
                    'key' => 'content',
                    'label' => 'Instagram',
                    'fields' => [
                        1 => ['key' => 'instagram_handle', 'label' => 'Instagram Handle', 'type' => 'text', 'description' => 'Username Instagram tanpa @, contoh: upa_luk.'],
                        2 => ['key' => 'embed_url', 'label' => 'Embed URL', 'type' => 'url', 'description' => 'URL embed Instagram widget jika ada.'],
                    ],
                ],
            ],
            'youtube' => [
                1 => [
                    'key' => 'content',
                    'label' => 'YouTube',
                    'fields' => [
                        1 => ['key' => 'channel_url', 'label' => 'Channel URL', 'type' => 'url', 'description' => 'URL channel YouTube.'],
                        2 => ['key' => 'channel_name', 'label' => 'Nama Channel', 'type' => 'text', 'description' => 'Nama channel YouTube.'],
                    ],
                ],
            ],
            'facebook' => [
                1 => [
                    'key' => 'content',
                    'label' => 'Facebook',
                    'fields' => [
                        1 => ['key' => 'page_url', 'label' => 'Page URL', 'type' => 'url', 'description' => 'URL halaman Facebook.'],
                        2 => ['key' => 'page_name', 'label' => 'Nama Halaman', 'type' => 'text', 'description' => 'Nama halaman Facebook.'],
                    ],
                ],
            ],
            'faq' => [
                1 => [
                    'key' => 'faq_intro',
                    'label' => 'Header FAQ',
                    'fields' => [
                        1 => ['key' => 'faq_title', 'label' => 'Judul FAQ', 'type' => 'text'],
                        2 => ['key' => 'faq_subtitle', 'label' => 'Subjudul FAQ', 'type' => 'textarea'],
                        3 => ['key' => 'faq_search_placeholder', 'label' => 'Placeholder Search', 'type' => 'text'],
                    ],
                ],
                2 => [
                    'key' => 'faq_categories',
                    'label' => 'Kategori FAQ',
                    'fields' => [
                        1 => ['key' => 'faq_category_1', 'label' => 'Kategori 1', 'type' => 'text'],
                        2 => ['key' => 'faq_category_2', 'label' => 'Kategori 2', 'type' => 'text'],
                        3 => ['key' => 'faq_category_3', 'label' => 'Kategori 3', 'type' => 'text'],
                        4 => ['key' => 'faq_category_4', 'label' => 'Kategori 4', 'type' => 'text'],
                    ],
                ],
                3 => [
                    'key' => 'faq_items',
                    'label' => 'Daftar FAQ',
                    'fields' => [
                        1 => ['key' => 'faq_fallback1_category', 'label' => 'Kategori FAQ 1', 'type' => 'text'],
                        2 => ['key' => 'faq_fallback1_question', 'label' => 'Pertanyaan FAQ 1', 'type' => 'text'],
                        3 => ['key' => 'faq_fallback1_answer', 'label' => 'Jawaban FAQ 1', 'type' => 'textarea'],
                        4 => ['key' => 'faq_fallback2_category', 'label' => 'Kategori FAQ 2', 'type' => 'text'],
                        5 => ['key' => 'faq_fallback2_question', 'label' => 'Pertanyaan FAQ 2', 'type' => 'text'],
                        6 => ['key' => 'faq_fallback2_answer', 'label' => 'Jawaban FAQ 2', 'type' => 'textarea'],
                    ],
                ],
                4 => [
                    'key' => 'faq_help',
                    'label' => 'Callout Bantuan',
                    'fields' => [
                        1 => ['key' => 'faq_help_title', 'label' => 'Judul Bantuan', 'type' => 'text'],
                        2 => ['key' => 'faq_help_text', 'label' => 'Deskripsi Bantuan', 'type' => 'textarea'],
                        3 => ['key' => 'faq_help_button_text', 'label' => 'Teks Tombol Bantuan', 'type' => 'text'],
                    ],
                ],
            ],
            'artikel' => [
                1 => [
                    'key' => 'hero',
                    'label' => 'Header Artikel',
                    'fields' => [
                        1 => ['key' => 'article_index_title', 'label' => 'Judul Halaman', 'type' => 'text'],
                        2 => ['key' => 'article_index_subtitle', 'label' => 'Subjudul Halaman', 'type' => 'textarea'],
                    ],
                ],
            ],
            'galeri' => [
                1 => [
                    'key' => 'hero',
                    'label' => 'Header Galeri',
                    'fields' => [
                        1 => ['key' => 'gallery_index_title', 'label' => 'Judul Halaman', 'type' => 'text'],
                        2 => ['key' => 'gallery_index_subtitle', 'label' => 'Subjudul Halaman', 'type' => 'textarea'],
                    ],
                ],
            ],
        ];
    }
}
