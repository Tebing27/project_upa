@props(['active' => 'beranda', 'sticky' => false])

@php
    $isStickyOrScrolled = $sticky ? 'true' : 'scrolled';

    $navLinks = [
        'home' => ['label' => 'Home', 'route' => 'home'],
        'profil' => [
            'label' => 'Profil',
            'children' => [
                ['label' => 'Tentang kami', 'route' => 'profil'],
                ['label' => 'Visi & Misi', 'url' => '#'],
                ['label' => 'Struktur organisasi', 'url' => '#'],
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
                ['label' => 'Instagram', 'url' => '#'],
                ['label' => 'Youtube', 'url' => '#'],
                ['label' => 'Facebook', 'url' => '#'],
                ['label' => 'Hot News', 'route' => 'article.index'],
            ],
        ],
        'informasi' => [
            'label' => 'Informasi',
            'children' => [
                ['label' => 'FAQ (Q & A)', 'url' => '#'],
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

    <div class="relative z-50 w-full bg-[#1f2937] px-6 py-2 text-xs text-white md:text-sm lg:px-16">
        <div
            class="mx-auto flex w-full max-w-340 flex-col items-center justify-between text-center md:flex-row md:text-left">
            <div class="font-medium">Welcome to UPA LUK</div>
            <div class="mt-2 flex flex-col items-center gap-2 text-gray-300 md:mt-0 md:flex-row md:gap-6">
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
            'fixed top-0 bg-white py-3 shadow-md border-b border-gray-100': {{ $sticky ? 'false' : 'scrolled' }},
            'absolute top-0 bg-transparent py-4 border-b border-transparent': {{ $sticky ? 'false' : '!scrolled' }},
            'bg-white py-3 border-b border-gray-100': {{ $sticky ? 'true' : 'false' }}
        }"
            class="z-40 w-full px-6 transition-all duration-300 lg:px-16">
            <div class="mx-auto flex w-full max-w-340 items-center justify-between">

                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <img src="{{ asset('assets/logo.webp') }}" alt="Logo UPA Bahasa"
                        class="h-10 md:h-14 object-contain">
                </a>

                <div class="hidden items-center gap-6 text-sm font-bold tracking-wide lg:flex">
                    @foreach ($navLinks as $key => $link)
                        @if (isset($link['children']))
                            <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false"
                                class="relative py-4">
                                <button
                                    :class="{
                                        'text-[#15803d]': {{ $sticky ? 'true' : 'scrolled' }} &&
                                            '{{ $active === $key ? 'true' : 'false' }}'
                                        === 'true',
                                        'text-white': {{ $sticky ? 'false' : '!scrolled' }} &&
                                            '{{ $active === $key ? 'true' : 'false' }}'
                                        === 'true',
                                        'text-gray-700 hover:text-[#15803d]': {{ $sticky ? 'true' : 'scrolled' }} &&
                                            '{{ $active === $key ? 'false' : 'true' }}'
                                        === 'true',
                                        'text-white hover:text-[#86efac]': {{ $sticky ? 'false' : '!scrolled' }} &&
                                            '{{ $active === $key ? 'false' : 'true' }}'
                                        === 'true'
                                    }"
                                    class="flex items-center gap-1 transition-colors duration-300">
                                    {{ strtoupper($link['label']) }}

                                    <span class="relative ml-1 flex h-4 w-4 items-center justify-center">
                                        <span x-show="!open" x-transition:enter="transition opacity duration-300"
                                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                            :class="{{ $sticky ? '{ \'text-gray-500\': true }' : '{ \'text-gray-500\': scrolled, \'text-white/80\': !scrolled }' }}"
                                            class="absolute">+</span>
                                        <span x-show="open" style="display: none;"
                                            x-transition:enter="transition opacity duration-300"
                                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                            class="absolute text-[#15803d]">-</span>
                                    </span>
                                </button>

                                <div x-show="open" style="display: none;"
                                    x-transition:enter="transition ease-out duration-300 transform origin-top"
                                    x-transition:enter-start="opacity-0 -translate-y-4 scale-y-90"
                                    x-transition:enter-end="opacity-100 translate-y-0 scale-y-100"
                                    x-transition:leave="transition ease-in duration-200 transform origin-top"
                                    x-transition:leave-start="opacity-100 translate-y-0 scale-y-100"
                                    x-transition:leave-end="opacity-0 -translate-y-4 scale-y-90"
                                    class="absolute left-0 top-full mt-0 w-56 bg-white shadow-xl border-b-4 border-[#15803d] py-3 z-50">
                                    @foreach ($link['children'] as $child)
                                        <a href="{{ $resolveLink($child) }}"
                                            class="block px-6 py-2 text-sm font-normal text-gray-700 hover:bg-gray-50 hover:text-[#15803d] transition-colors">
                                            {{ $child['label'] }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <a href="{{ $resolveLink($link) }}"
                                :class="{
                                    'text-[#15803d]': {{ $sticky ? 'true' : 'scrolled' }} &&
                                        '{{ $active === $key ? 'true' : 'false' }}'
                                    === 'true',
                                    'text-white': {{ $sticky ? 'false' : '!scrolled' }} &&
                                        '{{ $active === $key ? 'true' : 'false' }}'
                                    === 'true',
                                    'text-gray-700 hover:text-[#15803d]': {{ $sticky ? 'true' : 'scrolled' }} &&
                                        '{{ $active === $key ? 'false' : 'true' }}'
                                    === 'true',
                                    'text-white hover:text-[#86efac]': {{ $sticky ? 'false' : '!scrolled' }} &&
                                        '{{ $active === $key ? 'false' : 'true' }}'
                                    === 'true'
                                }"
                                class="py-4 transition-colors duration-300">
                                {{ strtoupper($link['label']) }}
                            </a>
                        @endif
                    @endforeach

                    <div :class="{
                        'border-gray-200': {{ $sticky ? 'true' : 'scrolled' }},
                        'border-white/30': {{ $sticky ? 'false' : '!scrolled' }}
                    }"
                        class="flex items-center gap-2 border-l-2 pl-4">
                        <img src="https://flagcdn.com/w20/gb.png" alt="English"
                            class="h-4 w-6 object-cover cursor-pointer hover:opacity-80">
                        <img src="https://flagcdn.com/w20/id.png" alt="Indonesia"
                            class="h-4 w-6 object-cover cursor-pointer hover:opacity-80">
                    </div>

                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="ml-2 rounded-full bg-[#15803d] px-6 py-2.5 text-white transition-all hover:bg-[#166534] shadow-sm">
                            DASHBOARD
                        </a>
                    @else
                        <div class="ml-2 flex items-center gap-3">
                            <a href="{{ route('login') }}"
                                class="rounded-full border-2 border-[#ea580c] px-5 py-2 text-[#ea580c] transition-all hover:bg-[#c2410c] hover:text-white">
                                MASUK
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="rounded-full bg-[#ea580c] hover:bg-[#c2410c] px-5 py-2.5 text-white transition-all shadow-sm">
                                    DAFTAR
                                </a>
                            @endif
                        </div>
                    @endauth
                </div>

                <button @click="mobileMenuOpen = !mobileMenuOpen"
                    :class="{
                        'text-gray-800 focus:ring-gray-300': {{ $sticky ? 'true' : 'scrolled' }},
                        'text-white focus:ring-white/40': {{ $sticky ? 'false' : '!scrolled' }}
                    }"
                    class="rounded-md p-2 transition-colors duration-300 lg:hidden">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                </button>
            </div>
        </nav>

        <div x-show="mobileMenuOpen" class="fixed inset-0 z-100 lg:hidden" style="display: none;">

            <div x-show="mobileMenuOpen" x-transition:enter="transition ease-in-out duration-500"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in-out duration-400" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" class="absolute inset-0 bg-white"> </div>

            <div x-show="mobileMenuOpen"
                x-transition:enter="transition ease-out duration-500 cubic-bezier(0.16, 1, 0.3, 1)"
                x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in duration-400 cubic-bezier(0.7, 0, 0.84, 0)"
                x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                class="fixed inset-y-0 right-0 flex w-full flex-col bg-white overflow-y-auto">

                <div class="px-8 pt-10 pb-4 flex justify-start">
                    <button @click="mobileMenuOpen = false"
                        class="bg-[#15803d] text-white rounded-full p-2 h-10 w-10 flex items-center justify-center hover:bg-green-800 transition-transform active:scale-95">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="flex flex-col px-8">
                    @foreach ($navLinks as $key => $link)
                        @if (isset($link['children']))
                            <div x-data="{ subOpen: false }" class="flex flex-col">
                                <div class="flex items-center justify-between border-b border-gray-200 py-4">
                                    @if (isset($link['route']) || isset($link['url']))
                                        <a href="{{ $resolveLink($link) }}"
                                            class="text-base font-semibold text-gray-900">{{ $link['label'] }}</a>
                                    @else
                                        <span
                                            class="text-base font-semibold text-gray-900">{{ $link['label'] }}</span>
                                    @endif

                                    <button @click.prevent="subOpen = !subOpen"
                                        class="flex h-8 w-8 items-center justify-center rounded bg-[#15803d] text-lg font-medium text-white">
                                        <span x-show="!subOpen">+</span>
                                        <span x-show="subOpen" style="display: none;">-</span>
                                    </button>
                                </div>

                                <div x-show="subOpen" class="flex flex-col pl-4 pb-2 pt-2 gap-3"
                                    style="display:none;" x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 -translate-y-2"
                                    x-transition:enter-end="opacity-100 translate-y-0">
                                    @foreach ($link['children'] as $child)
                                        <a href="{{ $resolveLink($child) }}"
                                            class="border-b border-gray-100 pb-2 text-sm font-medium text-gray-600 hover:text-[#15803d]">{{ $child['label'] }}</a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <a href="{{ $resolveLink($link) }}"
                                class="border-b border-gray-200 py-4 text-base font-semibold text-gray-900">
                                {{ $link['label'] }}
                            </a>
                        @endif
                    @endforeach

                    <div class="border-b border-gray-200 py-4">
                        <img src="https://flagcdn.com/w20/gb.png" alt="English"
                            class="h-4 w-6 object-cover shadow-sm">
                    </div>
                    <div class="border-b border-gray-200 py-4">
                        <img src="https://flagcdn.com/w20/id.png" alt="Indonesia"
                            class="h-4 w-6 object-cover shadow-sm">
                    </div>
                </div>

                <div class="px-8 py-8 mt-4 mb-8 flex flex-col gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="w-full rounded-full bg-[#15803d] px-6 py-3.5 text-center font-bold text-white shadow-md transition-all hover:bg-[#166534]">
                            KE DASHBOARD
                        </a>
                    @else
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="w-full rounded-full bg-[#15803d] px-6 py-3.5 text-center font-bold text-white shadow-md transition-all hover:bg-[#166534]">
                                Daftar
                            </a>
                        @endif
                        <a href="{{ route('login') }}"
                            class="w-full rounded-full border-2 border-[#15803d] px-6 py-3 text-center font-bold text-[#15803d] transition-all hover:bg-[#15803d] hover:text-white">
                            Masuk
                        </a>
                    @endauth
                </div>

            </div>
        </div>
    </div>
</div>
