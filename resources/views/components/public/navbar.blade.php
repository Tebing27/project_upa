@props(['active' => 'beranda', 'sticky' => false])

@php
    $isStickyOrScrolled = $sticky ? 'true' : 'scrolled';

    $navLinks = [
        'home' => ['label' => 'Home', 'route' => 'home'],
        'profil' => [
            'label' => 'Profil',
            'children' => [
                ['label' => 'Tentang kami', 'url' => route('profil') . '#tentang-kami'],
                ['label' => 'Visi & Misi', 'url' => route('profil') . '#visi-misi'],
                ['label' => 'Struktur organisasi', 'url' => route('profil') . '#struktur-organisasi'],
                ['label' => 'Program Kerja', 'url' => '#'],
                ['label' => 'Testimoni', 'url' => '#'],
            ],
        ],
        'sertifikasi' => [
            'label' => 'Sertifikasi',
            'children' => [
                ['label' => 'Skema Sertifikasi', 'route' => 'skema.index'],
                ['label' => 'Alur Sertifikasi', 'url' => '#'],
                ['label' => 'Tempat Uji Kompetensi', 'url' => '#'],
                ['label' => 'Jadwal Uji Kompetensi', 'url' => '#'],
                ['label' => 'Validasi Sertifikat', 'route' => 'cek-sertifikat'],
            ],
        ],
        'media' => [
            'label' => 'Media',
            'children' => [
                ['label' => 'Instagram', 'url' => 'https://www.instagram.com/lspupnvj/'],
                ['label' => 'Youtube', 'url' => 'https://www.youtube.com/@UPNVeteranJakarta'],
                ['label' => 'Facebook', 'url' => 'https://www.facebook.com/'],
                ['label' => 'Hot News', 'route' => 'article.index'],
            ],
        ],
        'informasi' => [
            'label' => 'Informasi',
            'children' => [
                ['label' => 'FAQ (Q & A)', 'route' => 'faq'],
                ['label' => 'Kegiatan (Foto dan Video)', 'route' => 'gallery.index'],
                ['label' => 'Kontak', 'route' => 'kontak'],
            ],
        ],
    ];

    $resolveLink = static function (array $item): string {
        if (isset($item['route'])) {
            return route($item['route']);
        }

        return $item['url'] ?? '#';
    };
@endphp

<div x-data="{ mobileMenuOpen: false }" class="{{ $sticky ? 'sticky top-0 z-50 flex w-full flex-col shadow-md' : 'w-full' }}">
    @include('components.public.navbar._top-bar')

    <div class="{{ $sticky ? 'relative w-full' : 'relative w-full' }}">
        @include('components.public.navbar._main-nav')
        @include('components.public.navbar._mobile-menu')
    </div>
</div>
