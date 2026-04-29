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

    <!-- Banner Section -->
    <div class="relative h-[16rem] w-full overflow-hidden bg-slate-950 lg:h-[16rem]">
        <div class="swiper myHeroSwiper h-full w-full">
            <div class="swiper-wrapper">
                @if (isset($homeContent['hero_slides']) && count($homeContent['hero_slides']) > 0)
                    @foreach ($homeContent['hero_slides'] as $slideUrl)
                        <div class="swiper-slide relative h-full w-full">
                            <img src="{{ $slideUrl }}" alt="Hero Slide"
                                class="h-full w-full object-cover object-center">
                        </div>
                    @endforeach
                @else
                    <div class="swiper-slide relative h-full w-full">
                        <img src="{{ asset('images/hero-upnvj.png') }}" alt="Gedung UPN Veteran Jakarta"
                            class="h-full w-full object-cover object-center">
                    </div>
                    <div class="swiper-slide relative h-full w-full">
                        <img src="{{ asset('assets/background.webp') }}" alt="Kampus UPN Veteran Jakarta"
                            class="h-full w-full object-cover object-center">
                    </div>
                @endif
            </div>
            <!-- Pagination Dots -->
            <div class="swiper-pagination !bottom-8"></div>
        </div>
    </div>

    <!-- Centered Content Section -->
    <div class="relative z-20 bg-white py-12 lg:py-16">
        <div class="mx-auto max-w-[76.5rem] px-4 text-center sm:px-6 lg:px-0">
            <div
                class="mb-6 inline-flex items-center rounded-full bg-[#17BC23] px-5 py-2 text-xs font-extrabold text-black shadow-sm sm:mb-8 sm:px-8 sm:py-3 sm:text-sm">
                LSP-P1 UPNVJ
            </div>

            <h1 class="mx-auto mb-6 max-w-4xl text-3xl font-black leading-tight text-gray-900 sm:text-5xl md:text-6xl">
                {{ $homeContent['hero_title'] ?? 'Uji Kompetensi Terakreditasi & Profesional' }}
            </h1>

            <p
                class="mx-auto mb-10 max-w-3xl text-sm font-medium leading-relaxed text-gray-600 sm:text-lg md:text-xl lg:mb-12">
                {{ $homeContent['hero_subtitle'] ?? 'Sistem penilaian dilakukan secara transparan dan objektif oleh Master Asesor yang memiliki keahlian spesifik menggunakan fasilitas berstandar industri.' }}
            </p>

            <div class="flex flex-col items-center justify-center gap-3 sm:flex-row sm:gap-4">
                <a href="{{ $homeContent['cta_link'] ?? route('register') }}"
                    class="group/btn inline-flex min-h-12 w-full items-center justify-center gap-3 rounded-full bg-[#ea580c] px-8 py-3.5 text-center text-sm font-extrabold tracking-wide text-white shadow-lg shadow-orange-500/20 transition hover:-translate-y-0.5 hover:bg-[#c2410c] sm:w-auto sm:min-w-52">
                    {{ $homeContent['cta_text'] ?? 'DAFTAR SEKARANG' }}
                    <svg class="w-4 h-4 transition-transform group-hover/btn:translate-x-1" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
                <a href="#skema-section"
                    @click.prevent="document.getElementById('skema-section')?.scrollIntoView({behavior: 'smooth'})"
                    class="inline-flex min-h-12 w-full items-center justify-center gap-3 rounded-full border-2 border-gray-200 bg-gray-50 px-8 py-3.5 text-center text-sm font-extrabold tracking-wide text-gray-900 transition hover:-translate-y-0.5 hover:border-gray-300 hover:bg-gray-100 sm:w-auto sm:min-w-52">
                    LIHAT SKEMA
                </a>
            </div>
        </div>
    </div>


    <!-- Floating Cek Sertifikat Widget -->
    <div class="relative z-20 pb-20 mx-auto w-full max-w-[76.5rem] px-4 sm:px-6 lg:px-0" x-data="{
        hasSearched: false,
        certificateName: '',
        certificateNumber: '',
        resultUrl() {
            const params = new URLSearchParams();
    
            if (this.certificateName.trim()) {
                params.set('nama', this.certificateName.trim());
            }
    
            if (this.certificateNumber.trim()) {
                params.set('nomor', this.certificateNumber.trim());
            }
    
            const query = params.toString();
    
            return @js(route('cek-sertifikat')) + (query ? `?${query}` : '');
        },
    }">
        <div class="rounded-2xl border border-gray-100 bg-gray-50 p-6 shadow-sm sm:p-8">
            <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h3 class="mb-1 text-xl font-black text-gray-950 sm:text-2xl">Periksa Sertifikat</h3>
                    <p class="text-sm font-medium text-gray-600 sm:text-base">
                        Verifikasi keaslian sertifikat kompetensi Anda di LSP UPNVJ secara online.
                    </p>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white px-4 py-4 sm:px-7 sm:py-5 shadow-sm">
                <div
                    class="grid items-end gap-4 sm:gap-6 lg:grid-cols-[1fr_1fr_auto_auto] lg:divide-x lg:divide-gray-200">
                    <form action="{{ route('cek-sertifikat') }}" method="GET" id="cek-form"
                        @submit.prevent="hasSearched = true" class="contents">
                        <div class="min-w-0 lg:pr-6">
                            <label for="nama"
                                class="mb-2 block text-xs font-black uppercase tracking-wider text-gray-400">Nama
                                Lengkap</label>
                            <input type="text" id="nama" name="nama" placeholder="Isi Nama Lengkap"
                                x-model="certificateName"
                                class="h-12 w-full rounded-lg border border-gray-200 bg-gray-50 px-4 text-sm font-semibold text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/5 lg:h-11">
                        </div>
                        <div class="min-w-0 lg:px-6">
                            <label for="reg_no"
                                class="mb-2 block text-xs font-black uppercase tracking-wider text-gray-400">No.
                                Registrasi / Sertifikat</label>
                            <input type="text" id="reg_no" name="nomor" placeholder="Contoh: 12345"
                                x-model="certificateNumber"
                                class="h-12 w-full rounded-lg border border-gray-200 bg-gray-50 px-4 text-sm font-semibold text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/5 lg:h-11">
                        </div>
                    </form>

                    <div class="lg:px-6">
                        <button type="submit" form="cek-form"
                            class="flex h-12 w-full items-center justify-center gap-3 rounded-xl bg-[#004cad] px-8 text-sm font-black text-white transition hover:bg-[#003d91] sm:h-11 lg:w-40">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            CARI
                        </button>
                    </div>

                    <div class="flex flex-col rounded-xl bg-gray-50 p-4 lg:bg-transparent lg:p-0 lg:pl-6" x-cloak
                        x-show="hasSearched" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0">
                        <span class="text-sm font-extrabold leading-tight text-gray-950"
                            x-text="certificateName || 'Sertifikat Ditemukan'"></span>
                        <a href="{{ route('cek-sertifikat') }}" :href="resultUrl()"
                            class="mt-1 inline-flex items-center gap-1 text-xs font-black text-[#004cad] transition hover:text-[#003d91] uppercase tracking-wider">
                            Lihat Detail
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Selamat Datang Section -->
    <div class="pt-32 pb-24 bg-white slanted-top relative z-10 mt-12 md:mt-0">
        <div class="max-w-[85rem] mx-auto w-full px-4 sm:px-6 md:px-12">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="pr-0 lg:pr-8">
                    <h2 class="text-4xl lg:text-[2.5rem] font-bold mb-6 text-gray-900 leading-tight">Selamat Datang di
                        UPA-LUK</h2>
                    <div class="text-gray-600 mb-8 leading-relaxed text-lg content-html">
                        {!! $homeContent['description'] ??
                            'Lembaga Sertifikasi Profesi (LSP) UPN "Veteran" Jakarta melayani pelaksanaan kegiatan uji kompetensi mahasiswa dengan lisensi resmi dari Badan Nasional Sertifikasi Profesi (BNSP).' !!}
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-10">
                        <!-- Benefit 1 -->
                        <div class="flex items-start gap-4">
                            <div
                                class="bg-blue-50 p-2.5 rounded-lg text-blue-600 shadow-sm border border-blue-100 flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-1">Terlisensi BNSP</h4>
                                <p class="text-sm text-gray-600">Sertifikat valid & diakui secara nasional.</p>
                            </div>
                        </div>
                        <!-- Benefit 2 -->
                        <div class="flex items-start gap-4">
                            <div
                                class="bg-orange-50 p-2.5 rounded-lg text-[#ea580c] shadow-sm border border-orange-100 flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-1">Asesor Kompeten</h4>
                                <p class="text-sm text-gray-600">Teruji dan berpengalaman di industrinya.</p>
                            </div>
                        </div>
                        <!-- Benefit 3 -->
                        <div class="flex items-start gap-4">
                            <div
                                class="bg-emerald-50 p-2.5 rounded-lg text-emerald-600 shadow-sm border border-emerald-100 flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-1">TUK Standar</h4>
                                <p class="text-sm text-gray-600">Perlengkapan uji kompetensi yang memadai.</p>
                            </div>
                        </div>
                        <!-- Benefit 4 -->
                        <div class="flex items-start gap-4">
                            <div
                                class="bg-purple-50 p-2.5 rounded-lg text-purple-600 shadow-sm border border-purple-100 flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-1">Akses Mudah</h4>
                                <p class="text-sm text-gray-600">Pendaftaran & info cepat via portal.</p>
                            </div>
                        </div>
                    </div>
                    <a href="#"
                        class="bg-[#38bdf8] hover:bg-[#0ea5e9] text-white px-8 py-3.5 rounded text-sm tracking-wide font-bold transition inline-block shadow-md shadow-sky-200">
                        TENTANG KAMI
                    </a>
                </div>
                <div class="overflow-hidden rounded-2xl border-4 border-gray-100 shadow-2xl">
                    <div class="aspect-video w-full bg-slate-950">
                        <iframe class="h-full w-full"
                            src="{{ $homeContent['youtube_url'] ?? config('services.youtube.intro_video', 'https://www.youtube.com/embed/shb_YrytjFM') }}"
                            title="Video Pengenalan Aplikasi LSP"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            referrerpolicy="strict-origin-when-cross-origin" allowfullscreen>
                        </iframe>
                    </div>
                    <div class="bg-[#2f4779] px-6 py-5 text-white">
                        <p class="text-sm font-semibold tracking-[0.18em] text-sky-200">VIDEO PENGENALAN</p>
                        <p class="mt-1 text-lg font-bold">Aplikasi LSP UPA LUK UPN Veteran Jakarta</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
    </div> <!-- Close Selamat Datang wrapper early -->

    <!-- Pendaftaran Pipeline Section -->
    <div class="bg-slate-50 border-t border-gray-100 py-20 lg:py-32 relative z-10 overflow-hidden shadow-inner">
        <!-- Subtle dotted background -->
        <div class="absolute inset-0 opacity-[0.03]"
            style="background-image: radial-gradient(#000 1.5px, transparent 1.5px); background-size: 28px 28px;">
        </div>

        <div class="max-w-[85rem] mx-auto w-full relative z-10 px-4 sm:px-6 md:px-12">
            <div class="text-center mb-12 lg:mb-20">
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-gray-950 leading-tight">Langkah Mudah <span
                        class="text-[#ea580c]">Mendapatkan Sertifikat</span></h2>
                <p class="text-gray-600 mt-2 text-base sm:text-lg max-w-2xl mx-auto">Proses sertifikasi kompetensi di
                    UPA LUK
                    dirancang
                    agar cepat, transparan, dan terstruktur.</p>
            </div>

            <div class="relative flex flex-col gap-5 lg:flex-row lg:items-stretch lg:justify-between lg:gap-0">
                @for ($i = 1; $i <= 5; $i++)
                    @php
                        $defaultTitles = [
                            1 => 'Daftar Akun',
                            2 => 'Verifikasi Berkas',
                            3 => 'Pembayaran VA',
                            4 => 'Ujian Kompetensi',
                            5 => 'Terbit Sertifikat',
                        ];
                        $defaultDescs = [
                            1 => 'Buat akun untuk masuk ke portal',
                            2 => 'Upload dokumen persyaratan',
                            3 => 'Selesaikan biaya administrasi',
                            4 => 'Jadwal asesmen tatap muka / online',
                            5 => 'Sertifikat BNSP dirilis ke akun',
                        ];
                        $icons = [
                            1 => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>',
                            2 => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>',
                            3 => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>',
                            4 => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>',
                            5 => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>',
                        ];
                        $title = $homeContent["step_{$i}_title"] ?? $defaultTitles[$i];
                        $desc = $homeContent["step_{$i}_desc"] ?? $defaultDescs[$i];
                    @endphp

                    @if ($title)
                        <div
                            class="relative flex items-start gap-4 sm:gap-5 lg:flex-1 lg:flex-col lg:items-center lg:gap-0 group">
                            @if ($i < 5)
                                <div
                                    class="absolute hidden lg:block w-full left-[50%] top-[4.5rem] px-12 z-[15] pointer-events-none transition-all duration-500 group-hover:-translate-y-8">
                                    <svg class="w-full h-20 overflow-visible block" preserveAspectRatio="none"
                                        viewBox="0 0 100 100">
                                        <defs>
                                            <marker id="arrowhead-{{ $i }}" markerWidth="7"
                                                markerHeight="7" refX="6" refY="3.5" orient="auto"
                                                markerUnits="strokeWidth">
                                                <path d="M1,1 L6,3.5 L1,6" fill="none" stroke="#64748b"
                                                    stroke-width="1.35" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </marker>
                                        </defs>
                                        @if ($i % 2 !== 0)
                                            <path d="M 0,50 C 25,10 75,10 100,50" fill="none" stroke="#64748b"
                                                stroke-width="1.6" stroke-dasharray="4 8" stroke-dashoffset="0"
                                                stroke-linecap="round"
                                                marker-end="url(#arrowhead-{{ $i }})"
                                                vector-effect="non-scaling-stroke" />
                                        @else
                                            <path d="M 0,50 C 25,90 75,90 100,50" fill="none" stroke="#64748b"
                                                stroke-width="1.6" stroke-dasharray="4 8" stroke-dashoffset="0"
                                                stroke-linecap="round"
                                                marker-end="url(#arrowhead-{{ $i }})"
                                                vector-effect="non-scaling-stroke" />
                                        @endif
                                    </svg>
                                </div>

                                <div
                                    class="absolute lg:hidden left-8 top-14 h-[calc(100%+1.25rem)] w-5 -translate-x-1/2 z-[5] pointer-events-none">
                                    <svg class="w-full h-full overflow-visible block" preserveAspectRatio="none"
                                        viewBox="0 0 20 100">
                                        <defs>
                                            <marker id="arrowhead-down-{{ $i }}" markerWidth="7"
                                                markerHeight="7" refX="3.5" refY="5.5" orient="auto"
                                                markerUnits="strokeWidth">
                                                <path d="M1,1 L3.5,6 L6,1" fill="none" stroke="#64748b"
                                                    stroke-width="1.35" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </marker>
                                        </defs>

                                        <path d="M10,0 C10,34 10,66 10,100" fill="none" stroke="#64748b"
                                            stroke-width="1.6" stroke-dasharray="4 8" stroke-dashoffset="0"
                                            stroke-linecap="round"
                                            marker-end="url(#arrowhead-down-{{ $i }})"
                                            vector-effect="non-scaling-stroke" class="process-arrow-path" />
                                    </svg>
                                </div>
                            @endif

                            <div
                                class="relative z-10 flex w-full items-center gap-4 transition-all duration-500 lg:flex-col lg:items-center lg:px-6 lg:py-12 lg:rounded-3xl lg:group-hover:-mt-8 lg:group-hover:bg-white lg:group-hover:shadow-[0_32px_64px_-16px_rgba(0,0,0,0.12)]">
                                <div
                                    class="relative z-20 flex h-16 w-16 shrink-0 rounded-full items-center justify-center transition-all duration-500 bg-white text-[#004cad] shadow-lg border border-gray-100 group-hover:bg-[#004cad] group-hover:text-white group-hover:shadow-xl group-hover:shadow-blue-500/20 lg:mb-8 lg:h-20 lg:w-20">
                                    <svg class="w-8 h-8 lg:w-10 lg:h-10" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        {!! $icons[$i] !!}
                                    </svg>
                                </div>

                                <div
                                    class="ml-4 lg:flex-none lg:border-0 lg:bg-transparent lg:p-0 lg:text-center lg:shadow-none lg:backdrop-blur-none">
                                    <h4
                                        class="text-base sm:text-lg lg:text-xl font-extrabold text-gray-950 mb-1.5 lg:mb-3 transition-colors duration-500">
                                        {{ $title }}</h4>
                                    <p
                                        class="text-sm font-medium text-gray-500 leading-relaxed max-w-none lg:max-w-[180px] lg:mx-auto transition-colors duration-500">
                                        {{ $desc }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                @endfor
            </div>
        </div>
    </div>



    </div>
    </div>

    <!-- Registration Section -->
    <div class="relative z-10 bg-white py-16 sm:py-20 lg:py-24">
        <div class="relative mx-auto w-full max-w-[85rem] px-4 sm:px-6 md:px-12">
            <div
                class="relative overflow-hidden rounded-lg border border-green-300/60 bg-[#17BC23] px-5 py-12 text-center shadow-[0_28px_70px_-35px_rgba(15,23,42,0.55)] sm:px-8 sm:py-14 lg:px-16 lg:py-20">
                <div class="absolute -left-8 top-6 flex gap-4 opacity-15 sm:left-8 sm:top-8">
                    <span
                        class="flex h-16 w-16 rotate-[-10deg] items-center justify-center rounded-full border border-white/50 bg-white/20 text-white">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M12 3 2.5 8l9.5 5 9.5-5L12 3Z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M6.5 10.5v4.25c0 1.8 2.45 3.25 5.5 3.25s5.5-1.45 5.5-3.25V10.5"></path>
                        </svg>
                    </span>
                    <span
                        class="hidden h-14 w-14 rotate-6 items-center justify-center rounded-full border border-white/50 bg-white/20 text-white sm:flex">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M5 4.5h10.5A2.5 2.5 0 0 1 18 7v13H7.5A2.5 2.5 0 0 1 5 17.5v-13Z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M8 8h7M8 11h7M8 17.5A2.5 2.5 0 0 1 10.5 15H18"></path>
                        </svg>
                    </span>
                </div>
                <div class="absolute -right-4 bottom-4 flex gap-4 opacity-15 sm:right-10 sm:bottom-8">
                    <span
                        class="hidden h-16 w-16 items-center justify-center rounded-full border border-white/50 bg-white/20 text-white sm:flex">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M8 4h8a2 2 0 0 1 2 2v14l-4-2-4 2-4-2-4 2V6a2 2 0 0 1 2-2Z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M8 8h8M8 11h8M8 14h4"></path>
                        </svg>
                    </span>
                    <span
                        class="flex h-20 w-20 rotate-12 items-center justify-center rounded-full border border-white/50 bg-white/20 text-white">
                        <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M6 4.5h12v15H6z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M9 8h6M9 11.5h6M9 15h3"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M16 17.5 18 20l2-2.5V14h-4v3.5Z"></path>
                        </svg>
                    </span>
                </div>

                <div class="relative mx-auto flex max-w-4xl flex-col items-center gap-6">
                    <h2 class="text-3xl font-black leading-tight text-white sm:text-4xl lg:text-5xl">
                        Pendaftaran Dibuka
                    </h2>

                    @if (isset($homeContent['reg_desc']) && $homeContent['reg_desc'])
                        <div
                            class="max-w-3xl text-base font-semibold leading-relaxed text-white content-html sm:text-lg">
                            {!! $homeContent['reg_desc'] !!}
                        </div>
                    @else
                        <p class="max-w-3xl text-base font-semibold leading-relaxed text-white sm:text-lg">
                            Unit Penunjang Akademik-layanan Uji Kompetensi (UPA-LUK) UPN "Veteran" Jakarta membuka
                            pendaftaran Uji Kompetensi Batch 2 Tahun 2025
                        </p>
                    @endif

                    <div
                        class="inline-flex w-full flex-col items-center justify-center gap-1 rounded-lg border border-white/45 bg-white/20 px-5 py-4 text-white backdrop-blur-sm sm:w-auto sm:min-w-96 sm:px-8">
                        <p class="text-xs font-black uppercase text-white/80">
                            {{ $homeContent['reg_period_label'] ?? 'Periode Pendaftaran' }}
                        </p>
                        <p class="text-lg font-black leading-snug sm:text-2xl">
                            {{ $homeContent['reg_period_value'] ?? '30 September - 19 Oktober 2025' }}
                        </p>
                    </div>

                    <div class="flex flex-row flex-wrap justify-center gap-3 pt-2 sm:gap-4">
                        <a href="{{ route('register') }}"
                            class="inline-flex min-h-12 min-w-32 items-center justify-center rounded-full bg-[#004cad] px-7 py-3.5 text-sm font-extrabold text-white shadow-lg shadow-blue-950/20 transition hover:-translate-y-0.5 hover:bg-[#003d91] focus:outline-2 focus:outline-offset-2 focus:outline-blue-900 sm:min-w-44 sm:px-8">
                            Daftar
                        </a>
                        <a href="#skema-section"
                            @click.prevent="document.getElementById('skema-section')?.scrollIntoView({behavior: 'smooth'})"
                            class="inline-flex min-h-12 min-w-32 items-center justify-center rounded-full border border-white/70 bg-white/75 px-7 py-3.5 text-sm font-extrabold text-slate-950 shadow-lg shadow-white/20 transition hover:-translate-y-0.5 hover:bg-white focus:outline-2 focus:outline-offset-2 focus:outline-white sm:min-w-44 sm:px-8">
                            Lihat Skema
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Skema Section -->
    <div id="skema-section" class="relative z-10 py-20 pb-28 scroll-mt-24 sm:py-24 lg:py-28" x-data="{
        scroll(direction) {
            const container = this.$refs.skemaContainer;
            const scrollAmount = container.clientWidth * 0.8;
            container.scrollBy({
                left: direction === 'left' ? -scrollAmount : scrollAmount,
                behavior: 'smooth'
            });
        }
    }">
        <div class="relative mx-auto w-full max-w-[85rem] px-4 sm:px-6 md:px-12">
            <div class="mb-8 flex flex-col gap-6 lg:mb-10 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-2xl">
                    <h2 class="text-3xl font-black leading-tight text-gray-950 sm:text-4xl lg:text-[2.75rem]">
                        Skema <span class="text-[#ea580c]">Sertifikasi</span></h2>
                    <p class="mt-3 max-w-xl text-sm font-medium leading-relaxed text-gray-600 sm:text-base">
                        Pilih skema kompetensi sesuai bidang Anda dan mulai proses sertifikasi resmi UPA-LUK UPNVJ.
                    </p>
                </div>

                <div class="flex items-center gap-3 justify-end">
                    <button @click="scroll('left')"
                        class="flex h-11 w-11 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-600 shadow-sm transition hover:border-[#ea580c] hover:bg-[#ea580c] hover:text-white hover:shadow-lg hover:shadow-orange-500/20 active:scale-95"
                        aria-label="Scroll kiri">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                    </button>
                    <button @click="scroll('right')"
                        class="flex h-11 w-11 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-600 shadow-sm transition hover:border-[#ea580c] hover:bg-[#ea580c] hover:text-white hover:shadow-lg hover:shadow-orange-500/20 active:scale-95"
                        aria-label="Scroll kanan">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>

            @if (empty($latestSchemes) || $latestSchemes->isEmpty())
                <div class="text-center text-gray-500 py-10 bg-white rounded-2xl shadow-sm border border-gray-100">
                    <p class="font-medium">Belum ada skema tersedia.</p>
                </div>
            @else
                <div x-ref="skemaContainer"
                    class="skema-scrollbar -mx-4 flex snap-x snap-mandatory gap-5 overflow-x-auto px-6 pb-3 sm:-mx-6 sm:px-6 lg:-mx-12 lg:gap-6 lg:px-12">
                    @foreach ($latestSchemes as $scheme)
                        <article
                            class="group flex w-[86vw] max-w-[22rem] shrink-0 snap-center flex-col overflow-hidden rounded-[1.25rem] bg-white transition duration-300 hover:-translate-y-1 sm:w-[20rem] lg:w-[21rem]">
                            <a href="{{ route('skema.detail', $scheme) }}"
                                class="relative block aspect-[4/3] bg-slate-200">
                                @if ($scheme->gambar_path)
                                    <img src="{{ Storage::url($scheme->gambar_path) }}" alt="{{ $scheme->name }}"
                                        class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                @else
                                    <img src="https://images.unsplash.com/photo-1777047023536-8e47688b77f9?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                                        alt="{{ $scheme->name }}"
                                        class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                @endif

                                @if ($scheme->jenis_skema)
                                    <span
                                        class="absolute right-4 top-4 inline-flex max-w-[calc(100%-2rem)] items-center rounded-full bg-white/95 px-3 py-1.5 text-xs font-extrabold text-gray-900 shadow-sm backdrop-blur-sm">
                                        {{ $scheme->jenis_skema }}
                                    </span>
                                @endif

                                <span
                                    class="absolute -bottom-4 right-5 inline-flex items-center gap-1 rounded-full bg-white px-3 py-1.5 text-[0.7rem] font-extrabold text-gray-900 shadow-lg shadow-black/10">
                                    <svg class="h-3.5 w-3.5 text-[#f59e0b]" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.176 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81H7.03a1 1 0 00.95-.69l1.07-3.292z">
                                        </path>
                                    </svg>
                                    Tersedia
                                </span>
                            </a>

                            <div
                                class="relative z-10 -mt-3 flex flex-1 flex-col rounded-t-[1.25rem] bg-white px-5 pb-5 pt-8">
                                <div class="flex-1">
                                    <h3
                                        class="line-clamp-2 text-lg font-black leading-snug text-gray-950 transition group-hover:text-[#ea580c]">
                                        <a href="{{ route('skema.detail', $scheme) }}">{{ $scheme->name }}</a>
                                    </h3>

                                    <p class="mt-3 line-clamp-3 text-sm font-medium leading-relaxed text-gray-600">
                                        {{ $scheme->description ?: 'Skema ini dirancang untuk menguji kompetensi peserta sesuai standar yang ditetapkan.' }}
                                    </p>
                                </div>

                                <div class="mt-5 flex items-end justify-between gap-4">
                                    <div class="min-w-0">
                                        <p class="text-sm font-extrabold tracking-wide text-gray-400">
                                            Mulai dari</p>
                                        <p class="mt-1 text-base font-black text-gray-950">
                                            @if ($scheme->harga)
                                                Rp{{ number_format((float) $scheme->harga, 0, ',', '.') }}
                                            @else
                                                Hubungi Admin
                                            @endif
                                        </p>
                                    </div>
                                    <a href="{{ route('skema.detail', $scheme) }}"
                                        class="inline-flex min-h-10 shrink-0 items-center justify-center rounded-full bg-gray-100 px-4 text-xs font-extrabold text-gray-950 transition hover:bg-[#ea580c] hover:text-white focus:outline-2 focus:outline-offset-2 focus:outline-[#ea580c]">
                                        Detail
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-12 text-center">
                    <a href="{{ route('skema.index') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-full border border-gray-300 bg-white px-8 py-3.5 text-sm font-extrabold text-gray-700 shadow-sm transition hover:border-[#ea580c] hover:bg-gray-50 hover:text-[#ea580c] hover:shadow-md">
                        Lihat Semua Skema
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Berita & Pengumuman Section -->
    <div class="bg-gray-50 py-24 relative z-10 border-t border-gray-100" x-data="{
        scroll(direction) {
            const container = this.$refs.beritaContainer;
            const scrollAmount = container.clientWidth * 0.8;
            container.scrollBy({
                left: direction === 'left' ? -scrollAmount : scrollAmount,
                behavior: 'smooth'
            });
        }
    }">
        <div class="relative mx-auto w-full max-w-[85rem] px-4 sm:px-6 md:px-12">
            <div class="mb-12 flex flex-col gap-6 lg:mb-16 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-2xl">
                    <h2 class="text-3xl font-black leading-tight text-gray-950 sm:text-4xl lg:text-[2.75rem]">Berita &
                        <span class="text-[#ea580c]">Pengumuman Terbaru</span>
                    </h2>
                    <p class="text-gray-600 mt-4 text-base sm:text-lg">Informasi dan pembaruan terkini seputar kegiatan
                        sertifikasi LSP
                        UPNVJ.</p>
                </div>

                <div class="flex items-center gap-3 justify-end">
                    <button @click="scroll('left')"
                        class="flex h-11 w-11 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-600 shadow-sm transition hover:border-[#ea580c] hover:bg-[#ea580c] hover:text-white hover:shadow-lg hover:shadow-orange-500/20 active:scale-95"
                        aria-label="Scroll kiri">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                    </button>
                    <button @click="scroll('right')"
                        class="flex h-11 w-11 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-600 shadow-sm transition hover:border-[#ea580c] hover:bg-[#ea580c] hover:text-white hover:shadow-lg hover:shadow-orange-500/20 active:scale-95"
                        aria-label="Scroll kanan">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>

            @if (isset($articles) && $articles->count() > 0)
                <div x-ref="beritaContainer"
                    class="skema-scrollbar -mx-4 flex snap-x snap-mandatory gap-6 overflow-x-auto px-6 pb-3 sm:-mx-6 sm:px-6 lg:-mx-12 lg:gap-8 lg:px-12">
                    @foreach ($articles as $article)
                        <div class="w-[86vw] sm:w-[22rem] lg:w-[25rem] shrink-0 snap-center">
                            <x-public.article-card :article="$article" />
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-gray-500 py-10 bg-white rounded-2xl shadow-sm border border-gray-100">
                    <p class="font-medium">Belum ada berita atau pengumuman terbaru.</p>
                </div>
            @endif

            <div class="mt-12 text-center">
                <a href="{{ route('article.index') }}"
                    class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-full text-gray-700 bg-white hover:bg-gray-50 hover:text-[#ea580c] hover:border-[#ea580c] transition-colors gap-2">
                    Lihat Semua Berita
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Galeri Section -->
    <div class="bg-white py-24 relative z-10 border-t border-gray-100">
        <div class="max-w-[85rem] mx-auto w-full relative z-10 px-4 sm:px-6 md:px-12">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 leading-tight">Galeri Kegiatan <span
                        class="text-[#ea580c]">UPA LUK</span></h2>
                <p class="text-gray-600 mt-4 text-lg">Dokumentasi kegiatan dan uji kompetensi di lingkungan Universitas
                    Pembangunan Nasional Veteran Jakarta.</p>
            </div>

            @if (isset($galleries) && $galleries->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2 md:gap-4">
                    @foreach ($galleries as $gallery)
                        <x-public.gallery-card :gallery="$gallery" />
                    @endforeach
                </div>
            @else
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2 md:gap-4">
                    @for ($i = 1; $i <= 4; $i++)
                        <div class="group relative aspect-[4/3] overflow-hidden rounded-xl bg-gray-100">
                            <img src="https://placehold.co/800x600/e2e8f0/475569?text=Galeri+{{ $i }}"
                                alt="Galeri Dummy {{ $i }}"
                                class="absolute inset-0 h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                            <div
                                class="absolute inset-0 flex flex-col justify-end bg-gradient-to-t from-black/80 via-black/40 to-transparent p-4 opacity-0 transition-opacity duration-300 group-hover:opacity-100 md:p-6">
                                <h3 class="text-sm font-bold text-white md:text-lg">Kegiatan LSP {{ $i }}
                                </h3>
                            </div>
                        </div>
                    @endfor
                </div>
            @endif

            <div class="mt-12 text-center">
                <a href="{{ route('gallery.index') }}"
                    class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-full text-gray-700 bg-white hover:bg-gray-50 hover:text-[#ea580c] hover:border-[#ea580c] transition-colors gap-2">
                    Lihat Semua Dokumentasi
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Testimoni Section -->
    <div class="relative py-28 overflow-hidden z-10 flex items-center">
        <!-- Background Image & Overlay -->
        <img src="{{ asset('assets/background.webp') }}" alt="Campus Background"
            class="absolute inset-0 w-full h-full object-cover z-0 filter blur-[2px] scale-105">
        <div class="absolute inset-0 bg-slate-900/80 z-0"></div>

        <div class="max-w-[85rem] mx-auto w-full relative z-10 px-4 sm:px-6 md:px-12">
            <div class="text-center mb-16">
                <h4 class="text-[#f97316] font-bold tracking-widest mb-4 text-sm uppercase">CERITA ALUMNI</h4>
                <h2 class="text-4xl font-bold text-white drop-shadow-md">Apa Kata Mereka?</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @if (isset($homeContent['testimonials']) && count($homeContent['testimonials']) > 0)
                    @foreach ($homeContent['testimonials'] as $testimonial)
                        @if (isset($testimonial['quote']) && $testimonial['quote'])
                            <div
                                class="bg-white p-8 rounded-2xl shadow-[0_4px_24px_-8px_rgba(0,0,0,0.1)] border border-gray-50 relative group hover:-translate-y-2 transition-transform duration-300">
                                <svg class="w-10 h-10 text-gray-100 absolute top-6 right-6 transition-colors group-hover:text-blue-50"
                                    fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z">
                                    </path>
                                </svg>
                                <p class="text-gray-600 leading-relaxed mb-8 relative z-10 text-[0.95rem]">
                                    "{{ $testimonial['quote'] }}"</p>
                                <div class="flex items-center gap-4 relative z-10 border-t border-gray-100 pt-5">
                                    @if (isset($testimonial['avatar']) && $testimonial['avatar'])
                                        <img src="{{ $testimonial['avatar'] }}"
                                            alt="{{ $testimonial['author'] ?? 'Anonim' }}"
                                            class="w-12 h-12 rounded-full object-cover">
                                    @else
                                        <div
                                            class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-lg">
                                            {{ strtoupper(substr($testimonial['author'] ?? 'A', 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <h4 class="font-bold text-gray-900">{{ $testimonial['author'] ?? 'Anonim' }}
                                        </h4>
                                        @if (isset($testimonial['role']) && $testimonial['role'])
                                            <p class="text-xs text-[#ea580c] font-bold tracking-wide uppercase mt-0.5">
                                                {{ $testimonial['role'] }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @else
                    <!-- Testimoni 1 -->
                    <div
                        class="bg-white p-8 rounded-2xl shadow-[0_4px_24px_-8px_rgba(0,0,0,0.1)] border border-gray-50 relative group hover:-translate-y-2 transition-transform duration-300">
                        <svg class="w-10 h-10 text-gray-100 absolute top-6 right-6 transition-colors group-hover:text-blue-50"
                            fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z">
                            </path>
                        </svg>
                        <p class="text-gray-600 leading-relaxed mb-8 relative z-10 text-[0.95rem]">"Memiliki sertifikat
                            kompetensi Web Programmer dari BNSP membuat portofolio dan CV saya jauh lebih menonjol di
                            mata rekruter. Proses sertifikasinya di UPA LUK sangat profesional dan membantu saya saat
                            wawancara kerja pertama."</p>
                        <div class="flex items-center gap-4 relative z-10 border-t border-gray-100 pt-5">
                            <img src="https://ui-avatars.com/api/?name=Budi+Santoso&background=eff6ff&color=1e40af&bold=true"
                                alt="Budi Santoso" class="w-12 h-12 rounded-full object-cover">
                            <div>
                                <h4 class="font-bold text-gray-900">Budi Santoso</h4>
                                <p class="text-xs text-[#ea580c] font-bold tracking-wide uppercase mt-0.5">Alumni
                                    Informatika</p>
                            </div>
                        </div>
                    </div>

                    <!-- Testimoni 2 -->
                    <div
                        class="bg-white p-8 rounded-2xl shadow-[0_4px_24px_-8px_rgba(0,0,0,0.1)] border border-gray-50 relative group hover:-translate-y-2 transition-transform duration-300">
                        <svg class="w-10 h-10 text-gray-100 absolute top-6 right-6 transition-colors group-hover:text-orange-50"
                            fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z">
                            </path>
                        </svg>
                        <p class="text-gray-600 leading-relaxed mb-8 relative z-10 text-[0.95rem]">"Asesornya sangat
                            kompeten. Ujiannya sungguh relevan dengan kebutuhan industri saat ini, tidak sekadar menguji
                            teori semata. Berkat lisensi ini, saya lebih percaya diri saat melamar posisi UI/UX Designer
                            internship."</p>
                        <div class="flex items-center gap-4 relative z-10 border-t border-gray-100 pt-5">
                            <img src="https://ui-avatars.com/api/?name=Siti+Aminah&background=fff7ed&color=c2410c&bold=true"
                                alt="Siti Aminah" class="w-12 h-12 rounded-full object-cover">
                            <div>
                                <h4 class="font-bold text-gray-900">Siti Aminah</h4>
                                <p class="text-xs text-[#ea580c] font-bold tracking-wide uppercase mt-0.5">Mahasiswa
                                    Sistem Informasi</p>
                            </div>
                        </div>
                    </div>

                    <!-- Testimoni 3 -->
                    <div
                        class="bg-white p-8 rounded-2xl shadow-[0_4px_24px_-8px_rgba(0,0,0,0.1)] border border-gray-50 relative group hover:-translate-y-2 transition-transform duration-300">
                        <svg class="w-10 h-10 text-gray-100 absolute top-6 right-6 transition-colors group-hover:text-emerald-50"
                            fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z">
                            </path>
                        </svg>
                        <p class="text-gray-600 leading-relaxed mb-8 relative z-10 text-[0.95rem]">"Sistem
                            pendaftarannya sangat mudah dan layanannya benar-benar responsif. Fasilitas TUK (Tempat Uji
                            Kompetensi) yang disediakan langsung oleh kampus UPNVJ juga terbukti sangat lengkap setara
                            standar BNSP."</p>
                        <div class="flex items-center gap-4 relative z-10 border-t border-gray-100 pt-5">
                            <img src="https://ui-avatars.com/api/?name=Randi+Pratama&background=ecfdf5&color=047857&bold=true"
                                alt="Randi Pratama" class="w-12 h-12 rounded-full object-cover">
                            <div>
                                <h4 class="font-bold text-gray-900">Randi Pratama</h4>
                                <p class="text-xs text-[#ea580c] font-bold tracking-wide uppercase mt-0.5">Alumni D3
                                    Keperawatan</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Footer Component -->
    <x-public.footer />

    <div x-data="{ open: false, message: '', now: new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) }"
        class="fixed bottom-4 right-4 sm:bottom-8 sm:right-8 z-50 flex flex-col items-end font-sans drop-shadow-2xl">

        <div x-show="open" x-transition:enter="transition-all ease-out duration-300 origin-bottom-right"
            x-transition:enter-start="opacity-0 translate-y-10 scale-50"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition-all ease-in duration-200 origin-bottom-right"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-10 scale-50"
            class="mb-4 w-[calc(100vw-2rem)] sm:w-[22rem] max-w-[24rem] overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-2xl"
            style="display: none;">

            <div
                class="flex items-center justify-between bg-gradient-to-r from-[#075e54] to-[#128c7e] px-4 py-3.5 text-white">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white/20 p-1">
                            <img src="{{ asset('assets/logo.webp') }}" alt="Admin"
                                class="h-full w-full rounded-full object-cover bg-white"
                                onerror="this.src='https://ui-avatars.com/api/?name=LSP&background=fff&color=128c7e'">
                        </div>
                        <span
                            class="absolute bottom-0 right-0 h-2.5 w-2.5 rounded-full border-2 border-[#128c7e] bg-green-400"></span>
                    </div>
                    <div>
                        <p class="text-[0.95rem] font-bold leading-tight text-white line-clamp-1">Admin UPA-LUK</p>
                        <p class="text-xs text-green-100 line-clamp-1">Biasanya membalas seketika</p>
                    </div>
                </div>
            </div>

            <div class="min-h-[14rem] sm:min-h-[16rem] bg-[#efeae2] px-4 py-5 relative flex flex-col justify-end"
                style="background-image: url('https://www.transparenttextures.com/patterns/cubes.png');">
                <div x-show="open" x-transition:enter="transition-all ease-out duration-500 delay-200"
                    x-transition:enter-start="opacity-0 translate-y-4 scale-90"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    class="relative max-w-[85%] rounded-2xl rounded-tl-none bg-white px-4 py-2.5 text-slate-800 shadow-sm border border-gray-100/50 mb-auto">
                    <div class="absolute -left-2 top-0 text-white">
                        <svg width="12" height="15" viewBox="0 0 12 15" fill="currentColor">
                            <path d="M12 0H0L12 15V0Z" />
                        </svg>
                    </div>
                    <p class="text-sm sm:text-[0.95rem] leading-snug">Halo! Ada yang bisa kami bantu terkait layanan
                        sertifikasi di UPN Veteran Jakarta? 😊</p>
                    <p class="mt-1 text-right text-[0.65rem] font-medium text-slate-400" x-text="now"></p>
                </div>
            </div>

            <div class="bg-gray-50 px-3 py-3 border-t border-gray-200">
                <form action="https://web.whatsapp.com/send" method="GET" target="_blank"
                    class="flex items-center gap-2"
                    @submit="if (!message.trim()) { message = 'Halo, saya ingin bertanya tentang sertifikasi.' }">
                    <input type="hidden" name="phone" value="{{ $homeContent[8][1] ?? '6287784644193' }}">

                    <div
                        class="flex min-w-0 flex-1 items-center gap-2 rounded-full border border-gray-200 bg-white px-3 sm:px-4 py-2 sm:py-2.5 shadow-sm focus-within:border-[#128c7e] focus-within:ring-1 focus-within:ring-[#128c7e] transition-all">
                        <input x-model="message" name="text" type="text" placeholder="Ketik pesan..."
                            class="min-w-0 flex-1 border-0 bg-transparent p-0 text-base sm:text-sm text-slate-800 outline-none placeholder:text-slate-400 focus:ring-0">
                    </div>

                    <button type="submit"
                        class="flex h-10 w-10 sm:h-11 sm:w-11 shrink-0 items-center justify-center rounded-full bg-[#128c7e] text-white shadow-md transition hover:scale-105 hover:bg-[#075e54]"
                        aria-label="Kirim pesan">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5 translate-x-[2px]" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        <div class="relative z-50">
            <div x-show="!open" class="absolute -top-1 -right-1 z-10 flex h-4 w-4">
                <span
                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-4 w-4 border-2 border-white bg-red-500"></span>
            </div>

            <button type="button" @click="open = !open"
                class="group relative flex h-12 w-12 sm:h-14 sm:w-14 items-center justify-center rounded-full text-white shadow-[0_4px_20px_rgba(37,211,102,0.4)] transition-all duration-300 hover:scale-110"
                :class="open ? 'bg-slate-800 hover:bg-slate-700' : 'bg-[#25D366] hover:bg-[#20bd5a]'"
                aria-label="Toggle WhatsApp">

                <svg x-show="!open" x-transition:enter="transition-all duration-300 ease-out"
                    x-transition:enter-start="opacity-0 rotate-45 scale-50"
                    x-transition:enter-end="opacity-100 rotate-0 scale-100"
                    x-transition:leave="transition-all duration-200 ease-in"
                    x-transition:leave-start="opacity-100 rotate-0 scale-100"
                    x-transition:leave-end="opacity-0 -rotate-45 scale-50" class="absolute h-7 w-7 sm:h-8 sm:w-8"
                    viewBox="0 0 24 24" fill="currentColor">
                    <path
                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51h-.57c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                </svg>

                <svg x-show="open" x-transition:enter="transition-all duration-300 ease-out delay-100"
                    x-transition:enter-start="opacity-0 -rotate-45 scale-50"
                    x-transition:enter-end="opacity-100 rotate-0 scale-100"
                    x-transition:leave="transition-all duration-200 ease-in"
                    x-transition:leave-start="opacity-100 rotate-0 scale-100"
                    x-transition:leave-end="opacity-0 rotate-45 scale-50" class="absolute h-6 w-6 sm:h-7 sm:w-7"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"
                    style="display: none;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Thumbnails
            const thumbsSwiper = new Swiper('.myThumbsSwiper', {
                direction: 'vertical',
                spaceBetween: 20,
                slidesPerView: 'auto',
                freeMode: {
                    enabled: true,
                    sticky: false,
                },
                grabCursor: true,
                mousewheel: {
                    forceToAxis: true,
                },
                watchSlidesProgress: true,
            });

            // Initialize Main Hero
            new Swiper('.myHeroSwiper', {
                loop: true,
                effect: 'fade',
                fadeEffect: {
                    crossFade: true,
                },
                autoplay: {
                    delay: 6000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
            });
        });

        // Ensure Alpine.js knows about the scroll position on load
        document.addEventListener('alpine:init', () => {
            window.dispatchEvent(new CustomEvent('scroll'));
        });
    </script>
</body>

</html>
