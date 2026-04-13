<?php

use App\Models\MediaFile;
use App\Models\Page;
use Database\Seeders\CmsContentSeeder;

it('seeds dummy cms article and gallery pages', function () {
    $this->seed(CmsContentSeeder::class);

    expect(Page::query()->articleEntries()->count())->toBe(3);
    expect(Page::query()->galleryEntries()->count())->toBe(6);

    $this->assertDatabaseHas('pages', [
        'slug' => 'berita-pembukaan-skema-sertifikasi-2026',
        'title' => 'Pembukaan Skema Sertifikasi 2026',
        'is_published' => true,
    ]);

    $this->assertDatabaseHas('pages', [
        'slug' => 'galeri-kegiatan-asesmen-lab-komputer',
        'title' => 'Pelatihan Asesor Internal',
        'is_published' => true,
    ]);

    expect(MediaFile::query()->count())->toBe(3);

    $this->assertDatabaseHas('media_files', [
        'file_path' => 'https://i.ibb.co.com/yBPy1KGh/kegiatan-1.jpg',
    ]);
});
