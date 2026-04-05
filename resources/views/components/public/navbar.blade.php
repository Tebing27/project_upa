@props(['active' => 'home', 'sticky' => false])

<style>
    /* Navbar dynamic styles for transparent mode */
    .top-nav {
        background-color: transparent;
        padding-top: 1.25rem;
        padding-bottom: 1.25rem;
    }

    .top-nav .nav-link {
        color: white;
        transition: all 0.3s;
    }

    .top-nav .nav-link:hover {
        color: #f97316;
    }

    .top-nav .nav-link.active {
        color: #f97316;
    }

    .scrolled-nav {
        background-color: white;
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .scrolled-nav .nav-link {
        color: #374151;
        transition: all 0.3s;
    }

    .scrolled-nav .nav-link:hover {
        color: #2563eb;
    }

    .scrolled-nav .nav-link.active {
        color: #2563eb;
    }

    /* For sticky solid mode */
    .solid-nav-link {
        color: #374151;
        transition: all 0.3s;
    }

    .solid-nav-link:hover {
        color: #2563eb;
    }

    .solid-nav-link.active {
        color: #2563eb;
    }
</style>

@if ($sticky)
    <!-- Sticky Mode (For pages without Hero) -->
    <div class="sticky top-0 z-50 w-full flex flex-col shadow-md">
        <!-- Topbar -->
        <div class="bg-[#1f2937] text-white text-xs md:text-sm py-2 w-full px-6 lg:px-16 relative z-20">
            <div class="max-w-[85rem] mx-auto flex flex-col md:flex-row justify-between items-center w-full">
                <div class="font-medium">Welcome to UPA LUK</div>
                <div class="flex flex-col md:flex-row gap-2 md:gap-6 mt-2 md:mt-0 text-gray-300">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                        lsp-p1-upnvj@upnvj.ac.id
                    </span>
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Jalan RS. Fatmawati Raya, Pondok Labu
                    </span>
                </div>
            </div>
        </div>

        <!-- Navbar -->
        <nav class="bg-white w-full px-6 lg:px-16 py-3 relative z-10">
            <div class="max-w-[85rem] mx-auto flex justify-between items-center w-full">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <img src="{{ asset('assets/logo.webp') }}" alt="Logo UPNVJ"
                        class="h-10 w-10 md:h-14 md:w-14 rounded-full border-2 border-white/20 shadow-sm object-cover">
                </a>

                <!-- Desktop Menu -->
                <div class="hidden lg:flex items-center gap-8 font-bold text-sm tracking-wide">
                    <a href="{{ route('home') }}"
                        class="solid-nav-link {{ $active === 'home' ? 'active' : '' }}">HOME</a>
                    <a href="{{ route('profil') }}"
                        class="solid-nav-link {{ $active === 'profil' ? 'active' : '' }}">PROFIL</a>
                    <a href="{{ route('skema.index') }}"
                        class="solid-nav-link {{ $active === 'skema' ? 'active' : '' }}">SKEMA</a>
                    <a href="{{ route('kontak') }}"
                        class="solid-nav-link {{ $active === 'kontak' ? 'active' : '' }}">KONTAK</a>
                    <a href="{{ route('cek-sertifikat') }}"
                        class="solid-nav-link {{ $active === 'validasi' ? 'active' : '' }}">VALIDASI SERTIFIKAT</a>

                    <div class="bg-gray-300 h-5 w-[2px] mx-1"></div>

                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="px-6 py-2.5 rounded-full bg-[#2563eb] text-white hover:bg-[#1d4ed8] shadow-sm transition-all duration-300 transform hover:-translate-y-0.5 ml-2">
                            DASHBOARD
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="px-6 py-2.5 rounded-full border border-gray-300 text-gray-700 hover:border-[#2563eb] hover:text-[#2563eb] transition-all duration-300 ml-2">
                            MASUK
                        </a>
                        @if (Route::has('register'))
                            <a href="/daftar"
                                class="px-6 py-2.5 rounded-full bg-[#2563eb] text-white hover:bg-[#1d4ed8] shadow-sm transition-all duration-300 transform hover:-translate-y-0.5">
                                DAFTAR
                            </a>
                        @endif
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <button class="lg:hidden p-2 rounded-md text-gray-800 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>
        </nav>
    </div>
@else
    <!-- Default Transparent -> Fixed Mode (For pages with Hero) -->
    <!-- Topbar -->
    <div class="bg-[#1f2937] text-white text-xs md:text-sm py-2 z-50 relative w-full px-6 lg:px-16">
        <div class="max-w-[85rem] mx-auto flex flex-col md:flex-row justify-between items-center w-full">
            <div class="font-medium">Welcome to UPA LUK</div>
            <div class="flex flex-col md:flex-row gap-2 md:gap-6 mt-2 md:mt-0 text-gray-300">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                        </path>
                    </svg>
                    lsp-p1-upnvj@upnvj.ac.id
                </span>
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Jalan RS. Fatmawati Raya, Pondok Labu
                </span>
            </div>
        </div>
    </div>

    <!-- Main Wrapper for Absolute/Fixed Navbar -->
    <div class="relative w-full">
        <!-- Navbar -->
        <nav :class="scrolled ? 'scrolled-nav fixed top-0 shadow-md' : 'top-nav absolute top-0'"
            class="w-full z-40 transition-all duration-300 px-6 lg:px-16">
            <div class="max-w-[85rem] mx-auto flex justify-between items-center w-full">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <img src="{{ asset('assets/logo.webp') }}" alt="Logo UPNVJ"
                        class="h-10 w-10 md:h-14 md:w-14 rounded-full border-2 border-white/20 shadow-sm object-cover">
                </a>

                <!-- Desktop Menu -->
                <div class="hidden lg:flex items-center gap-8 font-bold text-sm tracking-wide">
                    <a href="{{ route('home') }}" class="nav-link {{ $active === 'home' ? 'active' : '' }}">HOME</a>
                    <a href="{{ route('profil') }}"
                        class="nav-link {{ $active === 'profil' ? 'active' : '' }}">PROFIL</a>
                    <a href="{{ route('skema.index') }}"
                        class="nav-link {{ $active === 'skema' ? 'active' : '' }}">SKEMA</a>
                    <a href="{{ route('kontak') }}"
                        class="nav-link {{ $active === 'kontak' ? 'active' : '' }}">KONTAK</a>
                    <a href="{{ route('cek-sertifikat') }}"
                        class="nav-link {{ $active === 'validasi' ? 'active' : '' }}">VALIDASI SERTIFIKAT</a>

                    <div :class="scrolled ? 'bg-gray-300' : 'bg-white/30'"
                        class="h-5 w-[2px] mx-1 transition-colors duration-300"></div>

                    @auth
                        <a href="{{ route('dashboard') }}"
                            :class="scrolled ? 'bg-[#2563eb] text-white hover:bg-[#1d4ed8] shadow-sm' :
                                'bg-[#f97316] text-white hover:bg-[#ea580c] shadow-md'"
                            class="px-6 py-2.5 rounded-full transition-all duration-300 transform hover:-translate-y-0.5 ml-2">
                            DASHBOARD
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            :class="scrolled ? 'border-gray-300 text-gray-700 hover:border-[#2563eb] hover:text-[#2563eb]' :
                                'border-white/30 text-white hover:border-white hover:text-white'"
                            class="px-6 py-2.5 rounded-full border transition-all duration-300 ml-2">
                            MASUK
                        </a>
                        @if (Route::has('register'))
                            <a href="/register"
                                :class="scrolled ? 'bg-[#2563eb] text-white hover:bg-[#1d4ed8] shadow-sm' :
                                    'bg-[#f97316] text-white hover:bg-[#ea580c] shadow-md'"
                                class="px-6 py-2.5 rounded-full transition-all duration-300 transform hover:-translate-y-0.5">
                                DAFTAR
                            </a>
                        @endif
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <button :class="scrolled ? 'text-gray-800' : 'text-white'"
                    class="lg:hidden p-2 rounded-md focus:outline-none transition-colors duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>
        </nav>
    </div>
@endif
