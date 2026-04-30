        <nav :class="{
            'fixed top-0 bg-white py-3 shadow-md border-b border-gray-100': {{ $sticky ? 'false' : 'scrolled' }},
            'relative bg-white py-3 border-b border-gray-100': {{ $sticky ? 'false' : '!scrolled' }},
            'bg-white py-3 border-b border-gray-100': {{ $sticky ? 'true' : 'false' }}
        }"
            class="z-40 w-full px-6 duration-300 lg:px-16">
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
                                        'text-[#15803d]': ({{ $sticky ? 'true' : 'scrolled' }} || true) &&
                                            '{{ $active === $key ? 'true' : 'false' }}'
                                        === 'true',
                                        'text-gray-700 hover:text-[#15803d]': ({{ $sticky ? 'true' : 'scrolled' }} ||
                                                true) &&
                                            '{{ $active === $key ? 'false' : 'true' }}'
                                        === 'true'
                                    }"
                                    class="flex items-center gap-1 transition-colors duration-300">
                                    {{ strtoupper($link['label']) }}

                                    <span class="relative ml-1 flex h-4 w-4 items-center justify-center">
                                        <span x-show="!open" x-transition:enter="transition opacity duration-300"
                                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                            :class="{ 'text-gray-500': true }" class="absolute">+</span>
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
                                    'text-[#15803d]': ({{ $sticky ? 'true' : 'scrolled' }} || true) &&
                                        '{{ $active === $key ? 'true' : 'false' }}'
                                    === 'true',
                                    'text-gray-700 hover:text-[#15803d]': ({{ $sticky ? 'true' : 'scrolled' }} ||
                                            true) &&
                                        '{{ $active === $key ? 'false' : 'true' }}'
                                    === 'true'
                                }"
                                class="py-4 transition-colors duration-300">
                                {{ strtoupper($link['label']) }}
                            </a>
                        @endif
                    @endforeach

                    <div class="flex items-center gap-2 border-l-2 border-gray-200 pl-4">
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
                        'text-gray-800 focus:ring-gray-300': true
                    }"
                    class="rounded-md p-2 transition-colors duration-300 lg:hidden">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                </button>
            </div>
        </nav>
