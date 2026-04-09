<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- SEO & Metadata -->
    <title>{{ config('app.name', 'LSP UPN Veteran Jakarta') }} - Lembaga Sertifikasi Profesi</title>
    <meta name="description" content="Unit Penunjang Akademik - Layanan Uji Kompetensi (UPA-LUK) UPN Veteran Jakarta melayani uji kompetensi bersertifikat BNSP untuk mahasiswa.">
    
    <!-- Open Graph Tags -->
    <meta property="og:title" content="{{ config('app.name', 'LSP UPN Veteran Jakarta') }} - Terlisensi BNSP">
    <meta property="og:description" content="Dapatkan sertifikat kompetensi dari BNSP melalui UPA-LUK UPN Veteran Jakarta. Skema sertifikasi lengkap.">
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
        .swiper-button-next, .swiper-button-prev { color: white !important; transform: scale(0.7); }
    </style>
</head>

<body class="font-sans text-gray-900 antialiased min-h-screen bg-white" x-data="{ scrolled: false }"
    @scroll.window="scrolled = (window.pageYOffset > 20)">

    <x-public.navbar active="home" />

    <!-- Hero Section (Static Text Overlay + Background Swiper Carousel) -->
    <div class="relative w-full min-h-[85vh] lg:h-screen overflow-hidden group">
        
        <!-- Global Dark Overlay for Text Readability -->
        <div class="absolute inset-0 bg-slate-900/60 z-20 pointer-events-none mix-blend-multiply"></div>

        <!-- Static Text Container (Locks text from sliding, stays visible always) -->
        <div class="absolute inset-0 z-30 flex items-center justify-center pointer-events-none pt-16 pb-28 md:pt-20 md:pb-40">
            <div class="text-center text-white px-6 w-full max-w-4xl mx-auto pointer-events-auto">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 border border-white/20 backdrop-blur-md mb-6 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-xs font-bold tracking-wider text-emerald-100 uppercase">Lisensi Resmi BNSP</span>
                </div>
                <h1 class="text-4xl md:text-5xl lg:text-[3.5rem] font-bold mb-6 leading-tight drop-shadow-lg">Uji Kompetensi Terakreditasi & Profesional</h1>
                <p class="text-lg md:text-xl text-gray-200 mb-10 font-medium max-w-3xl mx-auto drop-shadow-md leading-relaxed">
                    Sistem penilaian dilakukan secara transparan dan objektif oleh Master Asesor yang memiliki keahlian spesifik menggunakan fasilitas berstandar industri.
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('register') }}" class="bg-[#ea580c] hover:bg-[#c2410c] text-white px-8 py-4 rounded-full font-bold transition flex items-center justify-center gap-3 text-sm tracking-wide shadow-lg shadow-orange-500/30 group/btn">
                        DAFTAR SEKARANG
                        <svg class="w-4 h-4 transition-transform group-hover/btn:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                    <a href="#skema-section" @click.prevent="document.getElementById('skema-section')?.scrollIntoView({behavior: 'smooth'})" class="bg-white/10 backdrop-blur-sm border-2 border-white/70 hover:bg-white/20 hover:border-white text-white px-8 py-4 rounded-full font-bold transition flex items-center justify-center gap-3 text-sm tracking-wide">
                        LIHAT SKEMA
                    </a>
                </div>
            </div>
        </div>

        <!-- Navigation Arrows (Floating above overlay) -->
        <div class="absolute inset-0 z-40 pointer-events-none">
            <div class="swiper-button-next pointer-events-auto opacity-40 hover:opacity-100 transition-opacity drop-shadow-lg"></div>
            <div class="swiper-button-prev pointer-events-auto opacity-40 hover:opacity-100 transition-opacity drop-shadow-lg"></div>
        </div>

        <!-- Background Swiper Carousel -->
        <div class="swiper myHeroSwiper absolute inset-0 w-full h-full z-10">
            <div class="swiper-wrapper">
                <!-- Slide 1 -->
                <div class="swiper-slide w-full h-full relative">
                    <img src="{{ asset('images/hero-3.jpeg') }}" alt="Asesor Background" class="w-full h-full object-cover blur-[3px] scale-[1.03] transition-transform duration-[10000ms] hover:scale-110">
                </div>

                <!-- Slide 2 -->
                <div class="swiper-slide w-full h-full relative">
                    <img src="{{ asset('images/hero-2.jpeg') }}" alt="Fasilitas Background" class="w-full h-full object-cover blur-[3px] scale-[1.03] transition-transform duration-[10000ms] hover:scale-110">
                </div>

                <!-- Slide 3 -->
                <div class="swiper-slide w-full h-full relative">
                    <img src="{{ asset('images/hero-upnvj.png') }}" alt="Pendaftaran Background" class="w-full h-full object-cover blur-[3px] scale-[1.03] transition-transform duration-[10000ms] hover:scale-110">
                </div>
            </div>
        </div>
    </div>


    <!-- Floating Cek Sertifikat Widget -->
    <div class="relative z-30 -mt-20 md:-mt-28 px-6 lg:px-16 max-w-5xl mx-auto w-full">
        <div class="bg-white rounded-[1.5rem] shadow-2xl shadow-[#1a9bc3]/10 border border-gray-100 p-8">
            <div class="flex flex-col md:flex-row items-center gap-8 md:gap-10">
                <div class="md:w-1/3 text-center md:text-left">
                    <div class="inline-flex items-center justify-center p-3.5 bg-blue-50 rounded-xl text-blue-600 mb-4 shadow-sm border border-blue-100">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Cek Sertifikat</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Verifikasi keaslian sertifikat kompetensi Anda di LSP UPNVJ secara online untuk membuktikan validitasnya.</p>
                </div>
                <div class="md:w-2/3 w-full border-t md:border-t-0 md:border-l border-gray-100 pt-6 md:pt-0 md:pl-10">
                    <form action="{{ route('cek-sertifikat') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="flex flex-col">
                            <label for="nama" class="text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" id="nama" name="nama" placeholder="Isi nama sesuai KTP" class="border border-gray-200 rounded-xl px-4 py-3.5 outline-none focus:border-[#2563eb] focus:ring-2 focus:ring-[#2563eb]/20 transition-all text-gray-700 bg-gray-50/50 w-full placeholder-gray-400">
                        </div>
                        <div class="flex flex-col">
                            <label for="reg_no" class="text-sm font-bold text-gray-700 mb-2">No. Registrasi / Sertifikat</label>
                            <input type="text" id="reg_no" name="nomor" placeholder="Contoh: REG-2025-XXXX" class="border border-gray-200 rounded-xl px-4 py-3.5 outline-none focus:border-[#2563eb] focus:ring-2 focus:ring-[#2563eb]/20 transition-all text-gray-700 bg-gray-50/50 w-full placeholder-gray-400">
                        </div>
                        <div class="sm:col-span-2 mt-2">
                            <button type="submit" class="w-full bg-[#2563eb] hover:bg-[#1d4ed8] text-white font-bold py-4 rounded-xl shadow-md hover:shadow-lg transition-all flex justify-center items-center gap-2 group">
                                <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                Cek Sertifikat Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Selamat Datang Section -->
    <div class="pt-32 pb-24 px-6 lg:px-16 bg-white slanted-top relative z-10 mt-12 md:mt-0">
        <div class="max-w-[85rem] mx-auto w-full">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="pr-0 lg:pr-8">
                    <h2 class="text-4xl lg:text-[2.5rem] font-bold mb-6 text-gray-900 leading-tight">Selamat Datang di
                        UPA-LUK</h2>
                    <p class="text-gray-600 mb-8 leading-relaxed text-lg">
                        Lembaga Sertifikasi Profesi (LSP) UPN "Veteran" Jakarta melayani pelaksanaan kegiatan uji kompetensi mahasiswa dengan lisensi resmi dari Badan Nasional Sertifikasi Profesi (BNSP).
                    </p>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-10">
                        <!-- Benefit 1 -->
                        <div class="flex items-start gap-4">
                            <div class="bg-blue-50 p-2.5 rounded-lg text-blue-600 shadow-sm border border-blue-100 flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-1">Terlisensi BNSP</h4>
                                <p class="text-sm text-gray-600">Sertifikat valid & diakui secara nasional.</p>
                            </div>
                        </div>
                        <!-- Benefit 2 -->
                        <div class="flex items-start gap-4">
                            <div class="bg-orange-50 p-2.5 rounded-lg text-[#ea580c] shadow-sm border border-orange-100 flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-1">Asesor Kompeten</h4>
                                <p class="text-sm text-gray-600">Teruji dan berpengalaman di industrinya.</p>
                            </div>
                        </div>
                        <!-- Benefit 3 -->
                        <div class="flex items-start gap-4">
                            <div class="bg-emerald-50 p-2.5 rounded-lg text-emerald-600 shadow-sm border border-emerald-100 flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-1">TUK Standar</h4>
                                <p class="text-sm text-gray-600">Perlengkapan uji kompetensi yang memadai.</p>
                            </div>
                        </div>
                        <!-- Benefit 4 -->
                        <div class="flex items-start gap-4">
                            <div class="bg-purple-50 p-2.5 rounded-lg text-purple-600 shadow-sm border border-purple-100 flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
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
                            src="{{ config('services.youtube.intro_video', 'https://www.youtube.com/embed/shb_YrytjFM') }}"
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
    <div class="bg-slate-50 border-t border-gray-100 py-24 px-6 lg:px-16 relative z-10 overflow-hidden shadow-inner">
        <!-- Subtle dotted background -->
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(#000 1.5px, transparent 1.5px); background-size: 28px 28px;"></div>
        
        <div class="max-w-[85rem] mx-auto w-full relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 leading-tight">Langkah Mudah <span class="text-[#ea580c]">Mendapatkan Sertifikat</span></h2>
                <p class="text-gray-600 mt-4 text-lg">Proses sertifikasi kompetensi di UPA LUK dirancang agar cepat, transparan, dan terstruktur.</p>
            </div>
            
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center relative w-full px-4 md:px-0">
                <!-- Connecting line for desktop -->
                <div class="hidden md:block absolute top-10 left-[10%] w-[80%] h-1 bg-gray-200 -translate-y-1/2 z-0"></div>
                
                <!-- Connecting line for mobile -->
                <div class="md:hidden absolute top-[10%] left-[3.25rem] w-1 h-[80%] bg-gray-200 -translate-x-1/2 z-0"></div>

                <!-- Step 1 -->
                <div class="relative z-10 flex flex-row md:flex-col items-center bg-transparent w-full md:w-1/5 group mb-8 md:mb-0 gap-6 md:gap-0 mt-2">
                    <div class="w-16 h-16 shrink-0 rounded-full bg-blue-50 text-blue-600 border-4 border-white shadow-md flex items-center justify-center md:mb-5 group-hover:bg-blue-600 group-hover:text-white group-hover:scale-110 transition-all duration-300 relative z-10">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    </div>
                    <div class="md:text-center mt-2 p-2 w-full md:w-auto bg-white/50 backdrop-blur-sm rounded-lg md:bg-transparent">
                        <h4 class="font-bold text-gray-900 text-lg md:text-base">1. Daftar Akun</h4>
                        <p class="text-sm text-gray-500 mt-1 md:mt-2">Buat akun untuk masuk ke portal</p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="relative z-10 flex flex-row md:flex-col items-center bg-transparent w-full md:w-1/5 group mb-8 md:mb-0 gap-6 md:gap-0 mt-2">
                    <div class="w-16 h-16 shrink-0 rounded-full bg-orange-50 text-[#ea580c] border-4 border-white shadow-md flex items-center justify-center md:mb-5 group-hover:bg-[#ea580c] group-hover:text-white group-hover:scale-110 transition-all duration-300 relative z-10">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div class="md:text-center mt-2 p-2 w-full md:w-auto bg-white/50 backdrop-blur-sm rounded-lg md:bg-transparent">
                        <h4 class="font-bold text-gray-900 text-lg md:text-base">2. Verifikasi Berkas</h4>
                        <p class="text-sm text-gray-500 mt-1 md:mt-2">Upload dokumen persyaratan</p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="relative z-10 flex flex-row md:flex-col items-center bg-transparent w-full md:w-1/5 group mb-8 md:mb-0 gap-6 md:gap-0 mt-2">
                    <div class="w-16 h-16 shrink-0 rounded-full bg-emerald-50 text-emerald-500 border-4 border-white shadow-md flex items-center justify-center md:mb-5 group-hover:bg-emerald-500 group-hover:text-white group-hover:scale-110 transition-all duration-300 relative z-10">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                    <div class="md:text-center mt-2 p-2 w-full md:w-auto bg-white/50 backdrop-blur-sm rounded-lg md:bg-transparent">
                        <h4 class="font-bold text-gray-900 text-lg md:text-base">3. Pembayaran VA</h4>
                        <p class="text-sm text-gray-500 mt-1 md:mt-2">Selesaikan biaya administrasi</p>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="relative z-10 flex flex-row md:flex-col items-center bg-transparent w-full md:w-1/5 group mb-8 md:mb-0 gap-6 md:gap-0 mt-2">
                    <div class="w-16 h-16 shrink-0 rounded-full bg-purple-50 text-purple-600 border-4 border-white shadow-md flex items-center justify-center md:mb-5 group-hover:bg-purple-600 group-hover:text-white group-hover:scale-110 transition-all duration-300 relative z-10">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <div class="md:text-center mt-2 p-2 w-full md:w-auto bg-white/50 backdrop-blur-sm rounded-lg md:bg-transparent">
                        <h4 class="font-bold text-gray-900 text-lg md:text-base">4. Ujian Kompetensi</h4>
                        <p class="text-sm text-gray-500 mt-1 md:mt-2">Jadwal asesmen tatap muka / online</p>
                    </div>
                </div>

                <!-- Step 5 -->
                <div class="relative z-10 flex flex-row md:flex-col items-center bg-transparent w-full md:w-1/5 group gap-6 md:gap-0 mt-2">
                    <div class="w-16 h-16 shrink-0 rounded-full bg-blue-50 text-blue-600 border-4 border-white shadow-md flex items-center justify-center md:mb-5 group-hover:bg-blue-600 group-hover:text-white group-hover:scale-110 transition-all duration-300 relative z-10">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <div class="md:text-center mt-2 p-2 w-full md:w-auto bg-white/50 backdrop-blur-sm rounded-lg md:bg-transparent">
                        <h4 class="font-bold text-gray-900 text-lg md:text-base">5. Terbit Sertifikat</h4>
                        <p class="text-sm text-gray-500 mt-1 md:mt-2">Sertifikat BNSP dirilis ke akun</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Registration Section -->
    <div class="bg-[#2A3F5C] text-white py-32 px-6 lg:px-16 slanted-top relative z-10">
        <div class="max-w-4xl mx-auto w-full text-center z-10 relative">
            <h4 class="text-[#3b82f6] font-bold tracking-widest mb-4 text-sm uppercase">PENDAFTARAN DIBUKA</h4>
            <h2 class="text-4xl md:text-5xl font-bold mb-8">Registrasi Sertifikasi Di Buka</h2>
            <p class="text-lg text-gray-300 mb-12 leading-relaxed">
                Unit Penunjang Akademik-layanan Uji Kompetensi (UPA-LUK) UPN "Veteran"<br>
                Jakarta membuka pendaftaran Uji Kompetensi Batch 2 Tahun 2025
            </p>

            <div class="mb-6">
                <p class="text-gray-400 mb-2 font-medium">Periode Pendaftaran:</p>
                <p class="text-2xl font-bold text-white">30 September - 19 Oktober 2025</p>
            </div>

            <div class="mb-14">
                <p class="text-gray-400 mb-2 font-medium">Pelaksanaan Uji Kompetensi:</p>
                <p class="text-2xl font-bold text-white">27 - 31 Oktober 2025</p>
            </div>

            <div class="flex flex-col sm:flex-row justify-center gap-5">
                <a href="/daftar"
                    class="bg-[#4ade80] hover:bg-[#22c55e] text-[#064e3b] px-10 py-3.5 rounded text-sm tracking-wide transition font-bold shadow-lg shadow-green-500/20">
                    Pendaftaran
                </a>
                <a href="#"
                    class="bg-[#4ade80] hover:bg-[#22c55e] text-[#064e3b] px-10 py-3.5 rounded text-sm tracking-wide transition font-bold shadow-lg shadow-green-500/20">
                    Aplikasi Ujikom
                </a>
            </div>
        </div>
    </div>

    <!-- Skema Section -->
    <div class="bg-white py-32 px-6 lg:px-16 slanted-top relative z-10 -mt-12">
        <div class="max-w-[85rem] mx-auto w-full">
            <h2 class="text-4xl font-bold text-center mb-6 text-gray-900">Skema Sertifikasi</h2>

            <!-- Minimal Search Bar -->
            <form action="{{ route('skema.index') }}" method="GET" class="max-w-xl mx-auto mb-12 relative group">
                <input type="text" name="q" placeholder="Cari nama skema kompetensi..." 
                    class="w-full pl-12 pr-4 py-3.5 rounded-full border border-gray-200 focus:border-[#ea580c] focus:ring-2 focus:ring-[#ea580c]/20 outline-none transition-all shadow-sm text-gray-700 bg-white placeholder-gray-400">
                <button type="submit" aria-label="Cari Skema" class="absolute left-4 top-1/2 -translate-y-1/2 z-10 focus:outline-none">
                    <svg class="w-5 h-5 text-gray-400 group-focus-within:text-[#ea580c] hover:text-[#ea580c] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
            </form>

            @if (empty($latestSchemes) || $latestSchemes->isEmpty())
                <div class="text-center text-gray-500 py-10 bg-white rounded-2xl shadow-sm border border-gray-100">
                    <p class="font-medium">Belum ada skema tersedia.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($latestSchemes as $scheme)
                        <div
                            class="group flex flex-col overflow-hidden rounded-[1.25rem] bg-white shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] transition-all hover:shadow-[0_4px_20px_-4px_rgba(0,0,0,0.1)] border border-gray-50">
                            <div class="relative h-56 overflow-hidden bg-gradient-to-br from-emerald-400 to-teal-500">
                                @if ($scheme->gambar_path)
                                    <img src="{{ Storage::url($scheme->gambar_path) }}" alt="{{ $scheme->name }}"
                                        class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                                @else
                                    <img src="https://placehold.co/600x400/e2e8f0/475569?text=Gambar+Skema"
                                        alt="{{ $scheme->name }}"
                                        class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                                @endif

                                @if ($scheme->jenis_skema)
                                    <div class="absolute top-4 left-4">
                                        <span
                                            class="inline-flex items-center rounded-full bg-white/95 px-3.5 py-1.5 text-xs font-bold text-gray-800 shadow-sm backdrop-blur-sm">
                                            {{ $scheme->jenis_skema }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <div class="flex flex-1 flex-col p-6 lg:p-8">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-4 text-xs font-semibold text-gray-500">
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-[#f97316]" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            {{ $scheme->created_at ? $scheme->created_at->format('F d, Y') : 'Baru' }}
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-[#f97316]" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                </path>
                                            </svg>
                                            Upaluk
                                        </div>
                                    </div>

                                    <h3
                                        class="text-xl font-bold text-gray-900 line-clamp-2 mb-3 leading-snug group-hover:text-[#f97316] transition-colors">
                                        {{ $scheme->name }}</h3>

                                    @if ($scheme->description)
                                        <p class="text-sm text-gray-600 line-clamp-3 mb-6 leading-relaxed">
                                            {{ $scheme->description }}</p>
                                    @else
                                        <p class="text-sm text-gray-600 line-clamp-3 mb-6 leading-relaxed">Deskripsi
                                            skema belum tersedia. Skema ini dirancang untuk menguji kompetensi peserta
                                            sesuai dengan standar yang ditetapkan.</p>
                                    @endif
                                </div>

                                <div class="mt-auto pt-5 border-t border-gray-100 flex justify-between items-center">
                                    <div class="flex items-center text-sm text-[#f97316] font-bold gap-1.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                            </path>
                                        </svg>
                                        Skema
                                    </div>
                                    <a href="{{ route('skema.detail', $scheme) }}"
                                        class="text-sm font-bold text-gray-900 hover:text-[#f97316] transition-colors flex items-center gap-1">
                                        Read More
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- If less than 3 schemes, fill with dummy cards -->
                @if ($latestSchemes->count() < 3)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-8">
                        @for ($i = 0; $i < 3 - $latestSchemes->count(); $i++)
                            <div
                                class="group flex flex-col overflow-hidden rounded-[1.25rem] bg-white shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] transition-all hover:shadow-[0_4px_20px_-4px_rgba(0,0,0,0.1)] border border-gray-50 opacity-60">
                                <div class="relative h-56 overflow-hidden bg-gray-200">
                                    <img src="https://placehold.co/600x400/e2e8f0/475569?text=Skema+Placeholder"
                                        alt="Placeholder" class="h-full w-full object-cover">
                                </div>
                                <div class="flex flex-1 flex-col p-6 lg:p-8">
                                    <div class="flex-1">
                                        <h3 class="text-xl font-bold text-gray-900 mb-3">Skema Contoh
                                            {{ $i + 1 }}</h3>
                                        <p class="text-sm text-gray-600 line-clamp-3 mb-6">Ini adalah skema contoh
                                            karena belum ada cukup data di database untuk menampilkan 3 skema.</p>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                @endif
            @endif
        </div>
    </div>

    <!-- Berita & Pengumuman Section -->
    <div class="bg-gray-50 py-24 px-6 lg:px-16 relative z-10 border-t border-gray-100">
        <div class="max-w-[85rem] mx-auto w-full relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 leading-tight">Berita & <span class="text-[#ea580c]">Pengumuman Terbaru</span></h2>
                <p class="text-gray-600 mt-4 text-lg">Informasi dan pembaruan terkini seputar kegiatan sertifikasi LSP UPNVJ.</p>
            </div>

            @if(isset($articles) && $articles->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($articles as $article)
                        <div class="group flex flex-col overflow-hidden rounded-[1.25rem] bg-white shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] transition-all hover:shadow-[0_4px_20px_-4px_rgba(0,0,0,0.1)] border border-gray-50">
                            <!-- Thumbnail 16:9 -->
                            <div class="relative w-full aspect-video overflow-hidden bg-gray-200">
                                @if($article->image_path)
                                    <img src="{{ Storage::url($article->image_path) }}" alt="{{ $article->title }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                                @else
                                    <img src="https://placehold.co/600x338/f1f5f9/64748b?text=News+Update" alt="News Placeholder" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                                @endif
                            </div>
                            
                            <!-- Content -->
                            <div class="flex flex-1 flex-col p-6 lg:p-8">
                                <div class="flex-1">
                                    <!-- Meta -->
                                    <div class="flex items-center justify-between gap-3 mb-4 text-xs font-semibold text-gray-500">
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-[#ea580c]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            {{ $article->published_at ? $article->published_at->format('d M Y') : $article->created_at->format('d M Y') }}
                                        </div>
                                        <div class="flex items-center gap-1.5" title="Dilihat {{ $article->views_count }} kali">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            {{ $article->views_count }}
                                        </div>
                                    </div>
                                    
                                    <!-- Tags -->
                                    @if($article->tags && is_array($article->tags) && count($article->tags) > 0)
                                        <div class="flex flex-wrap gap-2 mb-3">
                                            @foreach($article->tags as $tag)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700">{{ $tag }}</span>
                                            @endforeach
                                        </div>
                                    @endif

                                    <!-- Title -->
                                    <h3 class="text-xl font-bold text-gray-900 line-clamp-2 mb-3 leading-snug group-hover:text-[#ea580c] transition-colors">
                                        {{ $article->title }}
                                    </h3>
                                    
                                    <!-- Excerpt -->
                                    <p class="text-sm text-gray-600 line-clamp-3 mb-4 leading-relaxed">
                                        {{ $article->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($article->body), 100) }}
                                    </p>
                                </div>
                                
                                <!-- Read More Link -->
                                <div class="mt-auto pt-5 border-t border-gray-100 flex justify-start items-center">
                                    <a href="#" class="text-sm font-bold text-[#ea580c] hover:text-[#c2410c] transition-colors flex items-center gap-1">
                                        Baca Selengkapnya
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-gray-500 py-10 bg-white rounded-2xl shadow-sm border border-gray-100">
                    <p class="font-medium">Belum ada berita atau pengumuman terbaru.</p>
                </div>
            @endif

            <div class="mt-12 text-center">
                <a href="{{ route('article.index') }}" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-full text-gray-700 bg-white hover:bg-gray-50 hover:text-[#ea580c] hover:border-[#ea580c] transition-colors gap-2">
                    Lihat Semua Berita
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Galeri Section -->
    <div class="bg-white py-24 px-6 lg:px-16 relative z-10 border-t border-gray-100">
        <div class="max-w-[85rem] mx-auto w-full relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 leading-tight">Galeri Kegiatan <span class="text-[#ea580c]">UPA LUK</span></h2>
                <p class="text-gray-600 mt-4 text-lg">Dokumentasi kegiatan dan uji kompetensi di lingkungan Universitas Pembangunan Nasional Veteran Jakarta.</p>
            </div>

            @if(isset($galleries) && $galleries->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2 md:gap-4">
                    @foreach($galleries as $gallery)
                    <div class="group relative overflow-hidden rounded-xl bg-gray-900 aspect-[4/3] cursor-pointer">
                        @if($gallery->image_path)
                            <img src="{{ Str::startsWith($gallery->image_path, 'http') ? $gallery->image_path : Storage::url($gallery->image_path) }}" alt="{{ $gallery->title }}" class="absolute inset-0 h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                        @else
                            <img src="https://placehold.co/800x600/e2e8f0/475569?text=Galeri" alt="Gallery Placeholder" class="absolute inset-0 h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                        @endif

                        <!-- Hover Overlay -->
                        <div class="absolute inset-0 flex flex-col justify-end bg-gradient-to-t from-black/80 via-black/40 to-transparent p-4 md:p-6 opacity-0 transition-all duration-300 group-hover:opacity-100">
                            <h3 class="mb-1 text-sm md:text-lg font-bold text-white leading-snug transform translate-y-4 transition-transform duration-300 group-hover:translate-y-0">{{ $gallery->title }}</h3>
                            @if($gallery->description)
                                <p class="text-xs md:text-sm text-gray-300 line-clamp-2 transform translate-y-4 transition-transform duration-300 opacity-0 group-hover:opacity-100 group-hover:translate-y-0 delay-75">{{ $gallery->description }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-gray-500 py-10 bg-gray-50 rounded-2xl shadow-sm border border-gray-100">
                    <p class="font-medium">Galeri kegiatan belum tersedia.</p>
                </div>
            @endif

            <div class="mt-12 text-center">
                <a href="{{ route('gallery.index') }}" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-full text-gray-700 bg-white hover:bg-gray-50 hover:text-[#ea580c] hover:border-[#ea580c] transition-colors gap-2">
                    Lihat Semua Dokumentasi
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Testimoni Section -->
    <div class="relative py-28 px-6 lg:px-16 overflow-hidden z-10 flex items-center">
        <!-- Background Image & Overlay -->
        <img src="{{ asset('assets/background.webp') }}" alt="Campus Background" class="absolute inset-0 w-full h-full object-cover z-0 filter blur-[2px] scale-105">
        <div class="absolute inset-0 bg-slate-900/80 z-0"></div>

        <div class="max-w-[85rem] mx-auto w-full relative z-10">
            <div class="text-center mb-16">
                <h4 class="text-[#f97316] font-bold tracking-widest mb-4 text-sm uppercase">CERITA ALUMNI</h4>
                <h2 class="text-4xl font-bold text-white drop-shadow-md">Apa Kata Mereka?</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Testimoni 1 -->
                <div class="bg-white p-8 rounded-2xl shadow-[0_4px_24px_-8px_rgba(0,0,0,0.1)] border border-gray-50 relative group hover:-translate-y-2 transition-transform duration-300">
                    <svg class="w-10 h-10 text-gray-100 absolute top-6 right-6 transition-colors group-hover:text-blue-50" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"></path></svg>
                    <p class="text-gray-600 leading-relaxed mb-8 relative z-10 text-[0.95rem]">"Memiliki sertifikat kompetensi Web Programmer dari BNSP membuat portofolio dan CV saya jauh lebih menonjol di mata rekruter. Proses sertifikasinya di UPA LUK sangat profesional dan membantu saya saat wawancara kerja pertama."</p>
                    <div class="flex items-center gap-4 relative z-10 border-t border-gray-100 pt-5">
                        <img src="https://ui-avatars.com/api/?name=Budi+Santoso&background=eff6ff&color=1e40af&bold=true" alt="Budi Santoso" class="w-12 h-12 rounded-full object-cover">
                        <div>
                            <h4 class="font-bold text-gray-900">Budi Santoso</h4>
                            <p class="text-xs text-[#ea580c] font-bold tracking-wide uppercase mt-0.5">Alumni Informatika</p>
                        </div>
                    </div>
                </div>

                <!-- Testimoni 2 -->
                <div class="bg-white p-8 rounded-2xl shadow-[0_4px_24px_-8px_rgba(0,0,0,0.1)] border border-gray-50 relative group hover:-translate-y-2 transition-transform duration-300">
                    <svg class="w-10 h-10 text-gray-100 absolute top-6 right-6 transition-colors group-hover:text-orange-50" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"></path></svg>
                    <p class="text-gray-600 leading-relaxed mb-8 relative z-10 text-[0.95rem]">"Asesornya sangat kompeten. Ujiannya sungguh relevan dengan kebutuhan industri saat ini, tidak sekadar menguji teori semata. Berkat lisensi ini, saya lebih percaya diri saat melamar posisi UI/UX Designer internship."</p>
                    <div class="flex items-center gap-4 relative z-10 border-t border-gray-100 pt-5">
                        <img src="https://ui-avatars.com/api/?name=Siti+Aminah&background=fff7ed&color=c2410c&bold=true" alt="Siti Aminah" class="w-12 h-12 rounded-full object-cover">
                        <div>
                            <h4 class="font-bold text-gray-900">Siti Aminah</h4>
                            <p class="text-xs text-[#ea580c] font-bold tracking-wide uppercase mt-0.5">Mahasiswa Sistem Informasi</p>
                        </div>
                    </div>
                </div>

                <!-- Testimoni 3 -->
                <div class="bg-white p-8 rounded-2xl shadow-[0_4px_24px_-8px_rgba(0,0,0,0.1)] border border-gray-50 relative group hover:-translate-y-2 transition-transform duration-300">
                    <svg class="w-10 h-10 text-gray-100 absolute top-6 right-6 transition-colors group-hover:text-emerald-50" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"></path></svg>
                    <p class="text-gray-600 leading-relaxed mb-8 relative z-10 text-[0.95rem]">"Sistem pendaftarannya sangat mudah dan layanannya benar-benar responsif. Fasilitas TUK (Tempat Uji Kompetensi) yang disediakan langsung oleh kampus UPNVJ juga terbukti sangat lengkap setara standar BNSP."</p>
                    <div class="flex items-center gap-4 relative z-10 border-t border-gray-100 pt-5">
                        <img src="https://ui-avatars.com/api/?name=Randi+Pratama&background=ecfdf5&color=047857&bold=true" alt="Randi Pratama" class="w-12 h-12 rounded-full object-cover">
                        <div>
                            <h4 class="font-bold text-gray-900">Randi Pratama</h4>
                            <p class="text-xs text-[#ea580c] font-bold tracking-wide uppercase mt-0.5">Alumni D3 Keperawatan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Component -->
    <x-public.footer />

    <!-- Scroll to Top -->
    <button @click="window.scrollTo({top: 0, behavior: 'smooth'})" x-show="scrolled"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-10"
        x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-10"
        class="fixed bottom-8 right-8 bg-[#1e40af] text-white p-3.5 rounded-full shadow-2xl hover:bg-blue-800 transition z-50 hover:-translate-y-1">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
        </svg>
    </button>

    @livewireScripts
    
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        // Init Swiper
        document.addEventListener('DOMContentLoaded', function () {
            const swiper = new Swiper(".myHeroSwiper", {
                loop: true,
                effect: "fade", /* Optional if you included the fade effect module, visually amazing for backgrounds */
                fadeEffect: { crossFade: true },
                autoplay: {
                    delay: 6000,
                    disableOnInteraction: false,
                },
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
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

