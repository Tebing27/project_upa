<?php

use App\Models\Article;
use App\Models\Page;
use App\Models\Tag;
use App\Models\User;

it('renders article content from cms tables on the landing page', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    Article::factory()->create([
        'title' => 'Berita CMS Pertama',
        'slug' => 'berita-cms-pertama',
        'excerpt' => null,
        'body' => '<p>Ringkasan berita dari CMS</p>',
        'created_by' => $admin->id,
    ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Berita CMS Pertama')
        ->assertSee(route('article.index'), false);
});

it('shows article detail content from cms pages', function () {
    $admin = User::factory()->create(['role' => 'admin', 'nama' => 'Admin CMS']);
    $articlePage = Article::factory()->create([
        'title' => 'Artikel Detail CMS',
        'slug' => 'artikel-detail-cms',
        'excerpt' => null,
        'author_name' => 'Admin CMS',
        'body' => '<p>Pembuka artikel.</p><p>Konten detail dari CMS.</p><p>Penutup artikel.</p>',
        'published_at' => now()->setDate(2026, 4, 11)->setTime(9, 30),
        'created_by' => $admin->id,
    ]);
    $articlePage->tags()->sync([
        Tag::factory()->create(['name' => 'Info', 'slug' => 'info'])->id,
        Tag::factory()->create(['name' => 'Kampus', 'slug' => 'kampus'])->id,
    ]);

    $relatedArticle = Article::factory()->create([
        'title' => 'Artikel Terkait CMS',
        'slug' => 'artikel-terkait-cms',
        'author_name' => 'Tim Redaksi',
        'body' => '<p>Ringkasan artikel terkait dari CMS.</p>',
        'published_at' => now(),
    ]);

    $articlePage->update([
        'related_article_ids' => [$relatedArticle->id],
    ]);

    $this->get(route('article.show', ['slug' => $articlePage->publicSlug()]))
        ->assertOk()
        ->assertSee('Artikel Detail CMS')
        ->assertSee('Admin CMS')
        ->assertSee('Info')
        ->assertSee('Kampus')
        ->assertSee('Konten detail dari CMS.', false)
        ->assertSee('Baca Juga')
        ->assertSee('Artikel Terkait CMS');
});

it('removes trix figure wrappers from article detail content', function () {
    $articlePage = Article::factory()->create([
        'title' => 'Artikel Lama Trix',
        'slug' => 'artikel-lama-trix',
        'body' => '<p>Pembuka lama.</p><figure class="attachment attachment--preview" data-trix-attachment="{&quot;contentType&quot;:&quot;image/png&quot;}"><img src="https://example.com/legacy-image.png" alt="Legacy image"><figcaption class="attachment__caption">Caption lama</figcaption></figure><p>Penutup lama.</p>',
        'status' => 'published',
        'published_at' => now(),
    ]);

    $this->get(route('article.show', ['slug' => $articlePage->publicSlug()]))
        ->assertOk()
        ->assertSee('Legacy image')
        ->assertSee('Caption lama')
        ->assertDontSee('<figure', false)
        ->assertDontSee('data-trix-attachment', false)
        ->assertDontSee('attachment--preview', false);
});

it('preserves quill font classes on article detail content', function () {
    $articlePage = Article::factory()->create([
        'title' => 'Artikel Font Quill',
        'slug' => 'artikel-font-quill',
        'body' => '<p><span class="ql-font-serif">Teks serif</span></p><p><span class="ql-font-mono">Teks mono</span></p>',
        'status' => 'published',
        'published_at' => now(),
    ]);

    $this->get(route('article.show', ['slug' => $articlePage->publicSlug()]))
        ->assertOk()
        ->assertSee('ql-font-serif', false)
        ->assertSee('ql-font-mono', false)
        ->assertSee('Teks serif')
        ->assertSee('Teks mono');
});

it('preserves quill size and color formatting on article detail content', function () {
    $articlePage = Article::factory()->create([
        'title' => 'Artikel Format Quill',
        'slug' => 'artikel-format-quill',
        'body' => '<p><span class="ql-size-huge" style="color: rgb(230, 0, 0);">Judul merah besar</span></p>',
        'status' => 'published',
        'published_at' => now(),
    ]);

    $this->get(route('article.show', ['slug' => $articlePage->publicSlug()]))
        ->assertOk()
        ->assertSee('ql-size-huge', false)
        ->assertSee('color: rgb(230, 0, 0)', false)
        ->assertSee('Judul merah besar');
});

it('renders quill alignment classes on article detail content', function () {
    $articlePage = Article::factory()->create([
        'title' => 'Artikel Alignment Quill',
        'slug' => 'artikel-alignment-quill',
        'body' => '<p class="ql-align-center">Teks tengah</p><p class="ql-align-right">Teks kanan</p><p class="ql-align-justify">Teks rata kiri kanan</p>',
        'status' => 'published',
        'published_at' => now(),
    ]);

    $this->get(route('article.show', ['slug' => $articlePage->publicSlug()]))
        ->assertOk()
        ->assertSee('ql-align-center', false)
        ->assertSee('Teks tengah')
        ->assertSee('ql-align-right', false)
        ->assertSee('Teks kanan')
        ->assertSee('ql-align-justify', false)
        ->assertSee('Teks rata kiri kanan');
});

it('uses article id and seo slug on article detail routes', function () {
    $articlePage = Article::factory()->create([
        'title' => 'Hot News Artikel',
        'slug' => 'hot-news-artikel',
        'excerpt' => null,
        'body' => '<p>Isi artikel default</p>',
    ]);

    $response = $this->get(route('article.show', ['slug' => $articlePage->publicSlug()]));

    $response->assertOk()
        ->assertSee('Hot News Artikel');

    expect(route('article.show', ['slug' => $articlePage->publicSlug()]))
        ->toEndWith('/artikel/hot-news-artikel-'.$articlePage->id);
});

it('lists gallery pages on the gallery index', function () {
    $galleryPage = Page::factory()->create([
        'title' => 'Galeri Dokumentasi CMS',
        'slug' => 'kegiatan-dokumentasi-cms',
    ]);

    $this->get(route('gallery.index'))
        ->assertOk()
        ->assertSee('Galeri Dokumentasi CMS');
});

it('renders default artikel and galeri pages created from cms tabs', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $articlePage = Article::factory()->create([
        'title' => 'Hot News Admin',
        'slug' => 'hot-news-admin',
        'excerpt' => null,
        'body' => '<p>Konten berita dari tab default artikel</p>',
        'created_by' => $admin->id,
    ]);

    $this->get(route('article.index'))
        ->assertOk()
        ->assertSee('Hot News Admin');
});
