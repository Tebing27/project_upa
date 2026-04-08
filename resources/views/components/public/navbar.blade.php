@props(['active' => 'home', 'sticky' => false])

@php
    $isStickyOrScrolled = $sticky ? 'true' : 'scrolled';
    $navLinks = [
        'home' => ['label' => 'HOME', 'route' => 'home'],
        'profil' => ['label' => 'PROFIL', 'route' => 'profil'],
        'skema' => ['label' => 'SKEMA', 'route' => 'skema.index'],
        'kontak' => ['label' => 'KONTAK', 'route' => 'kontak'],
        'validasi' => ['label' => 'VALIDASI SERTIFIKAT', 'route' => 'cek-sertifikat'],
    ];
@endphp

<div x-data="{ mobileMenuOpen: false }"
    class="{{ $sticky ? 'sticky top-0 z-50 flex w-full flex-col shadow-md' : 'w-full' }}">
    <div class="relative z-50 w-full bg-[#1f2937] px-6 py-2 text-xs text-white md:text-sm lg:px-16">
        <div class="mx-auto flex w-full max-w-[85rem] flex-col items-center justify-between md:flex-row">
            <div class="font-medium">Welcome to UPA LUK</div>
            <div class="mt-2 flex flex-col gap-2 text-gray-300 md:mt-0 md:flex-row md:gap-6">
                <span class="flex items-center gap-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    lsp-p1-upnvj@upnvj.ac.id
                </span>
                <span class="flex items-center gap-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Jalan RS. Fatmawati Raya, Pondok Labu
                </span>
            </div>
        </div>
    </div>

    <div class="{{ $sticky ? 'relative w-full' : 'relative w-full' }}">
        <nav :class="{
            'fixed top-0 bg-white py-3 shadow-md': {{ $sticky ? 'false' : 'scrolled' }},
            'absolute top-0 bg-transparent py-5': {{ $sticky ? 'false' : '!scrolled' }},
            'bg-white py-3': {{ $sticky ? 'true' : 'false' }}
        }" class="z-40 w-full px-6 transition-all duration-300 lg:px-16">
            <div class="mx-auto flex w-full max-w-[85rem] items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <img src="{{ asset('assets/logo.webp') }}" alt="Logo UPNVJ"
                        class="h-10 w-10 rounded-full border-2 object-cover shadow-sm md:h-14 md:w-14"
                        :class="{{ $isStickyOrScrolled }} ? 'border-gray-200' : 'border-white/20'">
                </a>

                <div class="hidden items-center gap-8 text-sm font-bold tracking-wide lg:flex">
                    @foreach ($navLinks as $key => $link)
                        <a href="{{ route($link['route']) }}" :class="[
                            {{ $isStickyOrScrolled }}
                                ? '{{ $active === $key ? 'text-[#2563eb]' : 'text-gray-700 hover:text-[#2563eb]' }}'
                                : '{{ $active === $key ? 'text-orange-500 drop-shadow-sm' : 'text-white hover:text-orange-400 drop-shadow-sm' }}'
                        ]" class="transition-colors duration-300">
                            {{ $link['label'] }}
                        </a>
                    @endforeach

                    <div :class="{{ $isStickyOrScrolled }} ? 'bg-gray-300' : 'bg-white/40'"
                        class="mx-1 h-5 w-0.5 shadow-sm transition-colors duration-300"></div>

                    @auth
                        <a href="{{ route('dashboard') }}" :class="{{ $isStickyOrScrolled }}
                            ? 'bg-[#2563eb] text-white hover:bg-[#1d4ed8] shadow-sm'
                            : 'bg-orange-500 text-white hover:bg-orange-600 shadow-md'"
                            class="ml-2 rounded-full px-6 py-2.5 transition-all duration-300 hover:-translate-y-0.5">
                            DASHBOARD
                        </a>
                    @else
                        <a href="{{ route('login') }}" :class="{{ $isStickyOrScrolled }}
                            ? 'border-gray-300 text-gray-700 hover:border-[#2563eb] hover:text-[#2563eb]'
                            : 'border-white/60 bg-white/5 text-white hover:border-white hover:text-white drop-shadow-sm'"
                            class="ml-2 rounded-full border-2 px-6 py-2.5 transition-all duration-300">
                            MASUK
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" :class="{{ $isStickyOrScrolled }}
                                ? 'bg-orange-500 text-white hover:bg-orange-600 shadow-sm'
                                : 'border-2 border-orange-500/0 bg-orange-500 text-white shadow-lg hover:border-white/20 hover:bg-orange-600'"
                                class="rounded-full px-6 py-2.5 transition-all duration-300 hover:-translate-y-0.5">
                                DAFTAR
                            </a>
                        @endif
                    @endauth
                </div>

                <button @click="mobileMenuOpen = !mobileMenuOpen"
                    :class="{{ $isStickyOrScrolled }} ? 'text-gray-800 focus:ring-gray-300' : 'text-white focus:ring-white/30'"
                    class="rounded-md p-2 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-opacity-50 lg:hidden">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                </button>
            </div>
        </nav>

        <div x-show="mobileMenuOpen" style="display: none;"
            class="fixed inset-0 z-[100] bg-gray-900/60 backdrop-blur-sm lg:hidden"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="fixed inset-y-0 right-0 flex w-full max-w-sm flex-col bg-white shadow-2xl"
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="translate-x-0"
                x-transition:leave-end="translate-x-full" @click.away="mobileMenuOpen = false">
                <div class="flex items-center justify-between border-b border-gray-100 bg-gray-50/50 p-6">
                    <span class="text-lg font-bold tracking-wide text-gray-900">Menu Navigasi</span>
                    <button @click="mobileMenuOpen = false"
                        class="rounded-md p-2 text-gray-400 transition-colors hover:bg-gray-200 hover:text-gray-800">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="flex flex-1 flex-col gap-2 overflow-y-auto px-6 py-5">
                    @foreach ($navLinks as $key => $link)
                        <a href="{{ route($link['route']) }}"
                            class="{{ $active === $key ? 'bg-blue-50 text-[#2563eb]' : 'text-gray-700 hover:bg-gray-50 hover:text-[#2563eb]' }} rounded-xl px-4 py-3 font-bold transition-colors">
                            {{ $link['label'] }}
                        </a>
                    @endforeach
                </div>

                <div class="flex flex-col gap-4 border-t border-gray-100 bg-gray-50 p-6">
                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="w-full rounded-full bg-[#2563eb] px-6 py-3.5 text-center font-bold text-white shadow-md transition-all hover:bg-[#1d4ed8]">
                            KE DASHBOARD
                        </a>
                    @else
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="w-full rounded-full bg-orange-500 px-6 py-3.5 text-center font-bold text-white shadow-md transition-all hover:bg-orange-600">
                                DAFTAR SEKARANG
                            </a>
                        @endif

                        <a href="{{ route('login') }}"
                            class="w-full rounded-full border-2 border-gray-300 px-6 py-3.5 text-center font-bold text-gray-700 transition-all hover:border-[#2563eb] hover:bg-white hover:text-[#2563eb]">
                            MASUK
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
