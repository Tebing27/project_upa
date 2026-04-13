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
        ->toContain(route('faq'))
        ->toContain(route('cek-sertifikat'))
        ->toContain('https://www.instagram.com/lspupnvj/')
        ->toContain('https://www.youtube.com/@UPNVeteranJakarta')
        ->toContain('https://www.facebook.com/')
        ->toContain(route('profil').'#tentang-kami')
        ->toContain(route('profil').'#visi-misi')
        ->toContain(route('profil').'#struktur-organisasi')
        ->toContain(route('kontak'));
});
