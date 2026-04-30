<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- SEO & Metadata -->
    <title>{{ config('app.name', 'LSP UPN Veteran Jakarta') }} - Lembaga Sertifikasi Profesi</title>
    <meta name="description"
        content="Unit Penunjang Akademik - Layanan Uji Kompetensi (UPA-LUK) UPN Veteran Jakarta melayani uji kompetensi bersertifikat BNSP untuk mahasiswa.">

    <!-- Open Graph Tags -->
    <meta property="og:title" content="{{ config('app.name', 'LSP UPN Veteran Jakarta') }} - Terlisensi BNSP">
    <meta property="og:description"
        content="Dapatkan sertifikat kompetensi dari BNSP melalui UPA-LUK UPN Veteran Jakarta. Skema sertifikasi lengkap.">
    <meta property="og:image" content="{{ asset('assets/logo.webp') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

    @include('partials.head')
    @livewireStyles

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <style>
        /* Gradient hero background */
        .hero-gradient {
            background: linear-gradient(135deg, #2b4c7e 0%, #1a9bc3 100%);
        }

        /* Slanted top section */
        .slanted-top {
            clip-path: polygon(0 4vw, 100% 0, 100% 100%, 0 100%);
            margin-top: -4vw;
            padding-top: 8vw;
        }

        /* Swiper Navigation Customization */
        .swiper-button-next,
        .swiper-button-prev {
            color: white !important;
            transform: scale(0.7);
        }

        .myHeroSwiper,
        .myHeroSwiper .swiper-wrapper,
        .myHeroSwiper .swiper-slide {
            background-color: #020617;
        }

        .myHeroSwiper .swiper-slide img {
            backface-visibility: hidden;
            transform: translateZ(0);
        }

        .swiper-pagination-bullet {
            background: white !important;
            opacity: 0.5;
        }

        .swiper-pagination-bullet-active {
            background: #3b82f6 !important;
            opacity: 1;
        }

        [x-cloak] {
            display: none !important;
        }

        .skema-scrollbar {
            scrollbar-width: none;
        }

        .skema-scrollbar::-webkit-scrollbar {
            display: none;
        }
    </style>
</head>

<body class="font-sans text-gray-900 antialiased min-h-screen bg-white" x-data="{ scrolled: false }"
    @scroll.window="scrolled = (window.pageYOffset > 20)">

    <x-public.navbar active="home" />

    @include('welcome._hero-banner')
    @include('welcome._hero-content')
    @include('welcome._welcome-section')
    @include('welcome._registration-pipeline')
    @include('welcome._registration-cta')
    @include('welcome._schemes-section')
    @include('welcome._articles-section')
    @include('welcome._gallery-section')
    @include('welcome._testimonials-section')
    <!-- Footer Component -->
    <x-public.footer />

    @include('welcome._whatsapp-widget')
    @include('welcome._scripts')
</body>

</html>
