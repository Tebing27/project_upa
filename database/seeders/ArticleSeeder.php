<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil admin yang sudah dibuat oleh DatabaseSeeder sebelumnya
        $admin = User::where('role', 'admin')->first();

        // Kalau misalnya admin belum ada (jaga-jaga error), kita bypass
        if (!$admin) {
            $this->command->error('User admin belum dibuat di DatabaseSeeder! Seeder artikel dibatalkan.');
            return;
        }

        // Data dummy artikel lengkap
        $articles = [
            [
                'title' => 'Pendaftaran Uji Kompetensi Batch 3 Resmi Dibuka',
                'excerpt' => 'Kesempatan emas untuk mahasiswa UPNVJ. Segera daftarkan diri Anda sebelum kuota penuh. Pendaftaran ditutup minggu depan.',
                'body' => '<p>Detail lengkap mengenai pendaftaran dan persyaratan ujian kompetensi untuk Batch 3 telah resmi diumumkan. Mahasiswa diharapkan segera melengkapi berkas yang dibutuhkan melalui dashboard masing-masing.</p>',
                'tags' => ['Pengumuman', 'Pendaftaran', 'Batch 3'],
                'published_at' => now(),
                'views_count' => 145,
            ],
            [
                'title' => 'Tips Lulus Ujian Sertifikasi Skema Jaringan Komputer',
                'excerpt' => 'Banyak peserta yang gagal pada tahap praktik. Berikut adalah kisi-kisi dan tips langsung dari Asesor BNSP agar Anda lulus kompeten.',
                'body' => '<p>Berdasarkan evaluasi tahun lalu, banyak asesi yang kurang teliti saat instalasi perangkat fisik. Pastikan Anda sudah memahami topologi dasar dan cara konfigurasi routing yang benar sesuai standar industri.</p>',
                'tags' => ['Tips', 'Skema Jaringan'],
                'published_at' => now()->subDays(2),
                'views_count' => 312,
            ],
            [
                'title' => 'Pelatihan Asesor Kompetensi di Lingkungan UPA LUK',
                'excerpt' => 'Guna meningkatkan kualitas pengujian, puluhan dosen UPNVJ mengikuti pelatihan dan sertifikasi asesor kompetensi secara intensif.',
                'body' => '<p>Kegiatan ini berlangsung selama 4 hari dan dihadiri oleh master asesor dari BNSP. Diharapkan dengan bertambahnya jumlah asesor yang kompeten, proses sertifikasi di UPNVJ akan berjalan lebih transparan dan optimal.</p>',
                'tags' => ['Berita', 'Kegiatan', 'Asesor'],
                'published_at' => now()->subDays(5),
                'views_count' => 89,
            ],
        ];

        // Looping untuk insert ke database
        foreach ($articles as $article) {
            Article::create([
                'user_id' => $admin->id,
                'title' => $article['title'],
                'slug' => Str::slug($article['title']),
                'image_path' => null, // Biarkan null agar fallback gambar di UI bisa jalan
                'tags' => $article['tags'], // Bakal otomatis jadi JSON kalau di Model udah di-cast
                'excerpt' => $article['excerpt'],
                'body' => $article['body'],
                'views_count' => $article['views_count'],
                'published_at' => $article['published_at'],
            ]);
        }
    }
}