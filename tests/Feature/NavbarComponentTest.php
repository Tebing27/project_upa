<?php

use Illuminate\Support\Facades\Blade;

it('renders the public navbar with the updated dropdown structure', function () {
    $html = Blade::render('<x-public.navbar active="home" />');

    expect($html)
        ->toContain('Home')
        ->toContain('Profil')
        ->toContain('Visi &amp; Misi')
        ->toContain('Validasi Sertifikat')
        ->toContain('FAQ (Q &amp; A)')
        ->toContain('bg-transparent py-4 border-b border-transparent')
        ->toContain('bg-white py-3 shadow-md border-b border-gray-100')
        ->toContain(route('article.index'))
        ->toContain(route('gallery.index'))
        ->toContain(route('cek-sertifikat'))
        ->toContain(route('kontak'));
});
