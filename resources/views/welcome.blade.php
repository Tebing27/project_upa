<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    @include('partials.head')
    @livewireStyles
    <style>
        /* Gradient hero background similar to the image */
        .hero-gradient {
            background: linear-gradient(135deg, #2b4c7e 0%, #1a9bc3 100%);
        }

        /* Slanted top section */
        .slanted-top {
            clip-path: polygon(0 4vw, 100% 0, 100% 100%, 0 100%);
            margin-top: -4vw;
            padding-top: 8vw;
        }

        .slanted-top-reverse {
            clip-path: polygon(0 0, 100% 4vw, 100% 100%, 0 100%);
            margin-top: -4vw;
            padding-top: 8vw;
        }
    </style>
</head>

<body class="font-sans text-gray-900 antialiased min-h-screen bg-white" x-data="{ scrolled: false }"
    @scroll.window="scrolled = (window.pageYOffset > 20)">

    <x-public.navbar active="home" />

    <!-- Hero Section -->
    <div class="hero-gradient min-h-screen flex items-center px-6 lg:px-16 pt-32 pb-32 relative overflow-hidden">
        <div class="max-w-[85rem] mx-auto w-full grid lg:grid-cols-12 gap-12 items-center relative z-10">
            <div class="text-white lg:col-span-7">
                <h1 class="text-5xl lg:text-[4rem] font-bold mb-6 leading-tight">Welcome To UPA - LUK</h1>
                <p class="text-lg lg:text-xl mb-10 leading-relaxed text-blue-50/90 font-medium max-w-2xl">
                    UPN "Veteran" Jakarta Competency Test Service Academic Support Unit or commonly known as LSP has
                    the task of carrying out training and competency test services to students. Students have the
                    opportunity to choose certification according to their competence. Certification to UPN
                    "Veteran" Jakarta students is free of charge (Free) for the first certificate. Students who are
                    declared competent will get a Certificate of Competence from BNSP and are valid nationally.
                </p>
                <div class="flex flex-wrap gap-5">
                    <a href="/daftar"
                        class="bg-[#0ea5e9] hover:bg-[#0284c7] text-white px-8 py-3.5 rounded-full font-bold transition flex items-center gap-3 text-sm tracking-wide shadow-lg shadow-cyan-500/30">
                        <span class="w-1.5 h-1.5 bg-white rounded-full"></span>
                        MASUK
                        <span class="w-1.5 h-1.5 bg-white rounded-full"></span>
                    </a>
                    <a href="{{ route('login') }}"
                        class="bg-white hover:bg-gray-50 text-gray-900 px-8 py-3.5 rounded-full font-bold transition flex items-center gap-3 text-sm tracking-wide shadow-lg shadow-black/10">
                        <span class="w-1.5 h-1.5 bg-[#f97316] rounded-full"></span>
                        DAFTAR
                        <span class="w-1.5 h-1.5 bg-[#f97316] rounded-full"></span>
                    </a>
                </div>
            </div>
            <div class="lg:col-span-5 flex justify-center lg:justify-end">
                <img src="{{ asset('assets/logo.webp') }}" alt="LSP Logo"
                    class="w-72 h-72 md:w-[26rem] md:h-[26rem] object-contain rounded-xl bg-white p-6 shadow-2xl rotate-2 hover:rotate-0 transition-transform duration-500">
            </div>
        </div>

        <!-- Decorative background elements -->
        <div class="absolute -top-40 -left-40 w-[40rem] h-[40rem] bg-blue-400/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -right-40 w-[40rem] h-[40rem] bg-cyan-400/10 rounded-full blur-3xl"></div>
    </div>

    <!-- Selamat Datang Section -->
    <div class="py-24 px-6 lg:px-16 bg-white slanted-top" style="z-index: 5; position: relative;">
        <div class="max-w-[85rem] mx-auto w-full">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="pr-0 lg:pr-8">
                    <h2 class="text-4xl lg:text-[2.5rem] font-bold mb-6 text-gray-900 leading-tight">Selamat Datang di
                        UPA-LUK</h2>
                    <p class="text-gray-600 mb-10 leading-relaxed text-lg">
                        Lembaga Sertifikasi Profesi (LSP) adalah lembaga pelaksanaan kegiatan sertifikasi profesi yang
                        memperoleh lisensi dari Badan Nasional Sertifikasi Profesi (BNSP). Lisensi diberikan melalui
                        proses akreditasi oleh BNSP yang menyatakan bahwa LSP bersangkutan telah memenuhi syarat untuk
                        melakukan kegiatan sertifikasi profesi. Sebagai organisasi tingkat nasional yang berkedudukan di
                        wilayah Republik Indonesia, LSP dapat membuka cabang yang berkedudukan di kota lain / suatu
                        tempat.
                    </p>
                    <a href="#"
                        class="bg-[#38bdf8] hover:bg-[#0ea5e9] text-white px-8 py-3.5 rounded text-sm tracking-wide font-bold transition inline-block shadow-md shadow-sky-200">
                        TENTANG KAMI
                    </a>
                </div>
                <div class="overflow-hidden rounded-2xl border-4 border-gray-100 shadow-2xl">
                    <div class="aspect-video w-full bg-slate-950">
                        <iframe class="h-full w-full"
                            src="https://www.youtube.com/embed/shb_YrytjFM?si=4xpRRIaGDjW62dj9"
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

            <!-- Profile Head -->
            <div class="mt-32 text-center max-w-xl mx-auto">
                <div class="relative inline-block mb-6">
                    <img src="{{ asset('assets/Dr.Kusumajanti.webp') }}" alt="Dr. Kusumajanti"
                        class="w-56 h-72 object-cover mx-auto rounded-xl shadow-lg relative z-10">
                    <div class="absolute inset-0 bg-blue-100 transform translate-x-4 translate-y-4 rounded-xl -z-10">
                    </div>
                </div>
                <h3 class="text-xl font-bold text-gray-900">Dr. Kusumajanti, S.Sos., M.M., M.Si.</h3>
                <p class="text-gray-600 mt-2 font-medium">Kepala UPA LUK UPN Veteran Jakarta</p>
            </div>
        </div>
    </div>

    <!-- Registration Section -->
    <div class="bg-[#2A3F5C] text-white py-32 px-6 lg:px-16 slanted-top relative">
        <div class="max-w-4xl mx-auto w-full text-center z-10 relative">
            <h4 class="text-[#3b82f6] font-bold tracking-widest mb-4 text-sm uppercase">PENDAFATARAN DIBUKA</h4>
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

    <!-- Skema Section (Replacing Berita) -->
    @php
        $latestSchemes = \App\Models\Scheme::latest()->take(3)->get();
    @endphp
    <div class="bg-gray-50 py-32 px-6 lg:px-16 slanted-top relative" style="margin-top: -3vw; z-index: 5;">
        <div class="max-w-[85rem] mx-auto w-full">
            <h2 class="text-4xl font-bold text-center mb-16 text-gray-900">Skema Sertifikasi</h2>

            @if ($latestSchemes->isEmpty())
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

                <!-- If less than 3 schemes, fill with dummy cards to match the design (optional, but good for preview) -->
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

    <footer class="bg-[#2f4779] px-6 py-12 text-white lg:px-16">
        <div class="mx-auto flex max-w-[85rem] items-center justify-center">
            <p class="text-center text-lg font-medium text-white/95">
                Copyright &copy; {{ date('Y') }}. All rights reserved. UPA LUK.
            </p>
        </div>
    </footer>

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
    <script>
        // Ensure Alpine.js knows about the scroll position on load
        document.addEventListener('alpine:init', () => {
            window.dispatchEvent(new CustomEvent('scroll'));
        });
    </script>
</body>

</html>
