@props(['active' => 'home', 'sticky' => false])

@php
    // Variabel untuk mengontrol style dinamis menggunakan Alpine.js / Laravel
    $isStickyOrScrolled = $sticky ? 'true' : 'scrolled';
@endphp

<div x-data="{ mobileMenuOpen: false }" class="{{ $sticky ? 'sticky top-0 z-50 w-full flex flex-col shadow-md' : 'w-full' }}">
    <!-- Topbar (Selalu tampil di atas, baik sticky maupun tidak) -->
    <div class="bg-[#1f2937] text-white text-xs md:text-sm py-2 px-6 lg:px-16 z-50 w-full relative">
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

    <!-- Main Navbar Component -->
    <div class="{{ $sticky ? 'w-full relative' : 'relative w-full' }}">
        <nav :class="{
                'fixed top-0 shadow-md bg-white py-3': {{ $sticky ? 'false' : 'scrolled' }},
                'absolute top-0 bg-transparent py-5': {{ $sticky ? 'false' : '!scrolled' }},
                'bg-white py-3': {{ $sticky ? 'true' : 'false' }}
             }"
             class="w-full z-40 transition-all duration-300 px-6 lg:px-16">
            
            <div class="max-w-[85rem] mx-auto flex justify-between items-center w-full">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <img src="{{ asset('assets/logo.webp') }}" alt="Logo UPNVJ"
                        class="h-10 w-10 md:h-14 md:w-14 rounded-full border-2 shadow-sm object-cover"
                        :class="{{ $isStickyOrScrolled }} ? 'border-gray-200' : 'border-white/20'">
                </a>

                <!-- Desktop Menu -->
                <div class="hidden lg:flex items-center gap-8 font-bold text-sm tracking-wide">
                    @php
                        $navLinks = [
                            'home' => ['label' => 'HOME', 'route' => 'home'],
                            'profil' => ['label' => 'PROFIL', 'route' => 'profil'],
                            'skema' => ['label' => 'SKEMA', 'route' => 'skema.index'],
                            'kontak' => ['label' => 'KONTAK', 'route' => 'kontak'],
                            'validasi' => ['label' => 'VALIDASI SERTIFIKAT', 'route' => 'cek-sertifikat'],
                        ];
                    @endphp

                    @foreach ($navLinks as $key => $link)
                        <a href="{{ route($link['route']) }}"
                           :class="[ 
                               {{ $isStickyOrScrolled }} 
                                   ? '{{ $active === $key ? "text-[#2563eb]" : "text-gray-700 hover:text-[#2563eb]" }}' 
                                   : '{{ $active === $key ? "text-orange-500 drop-shadow-sm" : "text-white hover:text-orange-400 drop-shadow-sm" }}'
                           ]"
                           class="transition-colors duration-300">
                           {{ $link['label'] }}
                        </a>
                    @endforeach

                    <!-- Divider -->
                    <div :class="{{ $isStickyOrScrolled }} ? 'bg-gray-300' : 'bg-white/40'"
                        class="h-5 w-0.5 mx-1 transition-colors duration-300 shadow-sm"></div>

                    @auth
                        <!-- Jika sudah login -->
                        <a href="{{ route('dashboard') }}"
                            :class="{{ $isStickyOrScrolled }} 
                                ? 'bg-[#2563eb] text-white hover:bg-[#1d4ed8] shadow-sm' 
                                : 'bg-orange-500 text-white hover:bg-orange-600 shadow-md'"
                            class="px-6 py-2.5 rounded-full transition-all duration-300 transform hover:-translate-y-0.5 ml-2">
                            DASHBOARD
                        </a>
                    @else
                        <!-- Secondary CTA: MASUK -->
                        <a href="{{ route('login') }}"
                            :class="{{ $isStickyOrScrolled }} 
                                ? 'border-gray-300 text-gray-700 hover:border-[#2563eb] hover:text-[#2563eb]' 
                                : 'border-white/60 text-white hover:border-white hover:text-white drop-shadow-sm bg-white/5'"
                            class="px-6 py-2.5 rounded-full border-2 transition-all duration-300 ml-2">
                            MASUK
                        </a>

                        <!-- Primary CTA: DAFTAR -->
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                :class="{{ $isStickyOrScrolled }} 
                                    ? 'bg-orange-500 text-white hover:bg-orange-600 shadow-sm' 
                                    : 'bg-orange-500 text-white hover:bg-orange-600 shadow-lg border-2 border-orange-500/0 hover:border-white/20'"
                                class="px-6 py-2.5 rounded-full transition-all duration-300 transform hover:-translate-y-0.5">
                                DAFTAR
                            </a>
                        @endif
                    @endauth
                </div>

                <!-- Mobile Menu Toggle Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" 
                    :class="{{ $isStickyOrScrolled }} ? 'text-gray-800' : 'text-white'"
                    class="lg:hidden p-2 rounded-md focus:outline-none focus:ring-2 focus:ring-opacity-50 transition-colors duration-300"
                    :class="{{ $isStickyOrScrolled }} ? 'focus:ring-gray-300' : 'focus:ring-white/30'">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>
        </nav>
        
        <!-- ================= MOBILE MENU DRAWER ================= -->
        <div x-show="mobileMenuOpen" 
             style="display: none;" 
             class="fixed inset-0 z-[100] lg:hidden bg-gray-900/60 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
             
             <!-- Drawer Panel -->
             <div class="fixed inset-y-0 right-0 max-w-sm w-full bg-white shadow-2xl flex flex-col"
                  x-transition:enter="transition ease-out duration-300 transform"
                  x-transition:enter-start="translate-x-full"
                  x-transition:enter-end="translate-x-0"
                  x-transition:leave="transition ease-in duration-200 transform"
                  x-transition:leave-start="translate-x-0"
                  x-transition:leave-end="translate-x-full"
                  @click.away="mobileMenuOpen = false">
                 
                 <!-- Header Drawer -->
                 <div class="flex items-center justify-between p-6 border-b border-gray-100 bg-gray-50/50">
                     <span class="text-lg font-bold text-gray-900 tracking-wide">Menu Navigasi</span>
                     <button @click="mobileMenuOpen = false" class="p-2 text-gray-400 hover:text-gray-800 rounded-md hover:bg-gray-200 transition-colors">
                         <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                         </svg>
                     </button>
                 </div>
                 
                 <!-- Mobile Links Array -->
                 <div class="flex-1 overflow-y-auto py-5 px-6 flex flex-col gap-2">
                     @foreach ($navLinks as $key => $link)
                        <a href="{{ route($link['route']) }}" 
                           class="py-3 px-4 rounded-xl font-bold transition-colors 
                                  {{ $active === $key ? 'bg-blue-50 text-[#2563eb]' : 'text-gray-700 hover:bg-gray-50 hover:text-[#2563eb]' }}">
                            {{ $link['label'] }}
                        </a>
                     @endforeach
                 </div>
                 
                 <!-- Actions Footer (Mobile) -->
                 <div class="p-6 border-t border-gray-100 flex flex-col gap-4 bg-gray-50">
                    @auth
                        <a href="{{ route('dashboard') }}" class="w-full text-center px-6 py-3.5 rounded-full bg-[#2563eb] text-white hover:bg-[#1d4ed8] font-bold shadow-md transition-all">
                            KE DASHBOARD
                        </a>
                    @else
                         <!-- Mobile Primary CTA: DAFTAR -->
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="w-full text-center px-6 py-3.5 rounded-full bg-orange-500 text-white hover:bg-orange-600 font-bold shadow-md transition-all">
                                DAFTAR SEKARANG
                            </a>
                        @endif
                        
                        <!-- Mobile Secondary CTA: MASUK -->
                        <a href="{{ route('login') }}" class="w-full text-center px-6 py-3.5 rounded-full border-2 border-gray-300 text-gray-700 hover:border-[#2563eb] hover:text-[#2563eb] hover:bg-white font-bold transition-all">
                            MASUK
                        </a>
                    @endauth
                 </div>
             </div>
        </div>
        <!-- ================= END MOBILE MENU DRAWER ================= -->
    </div>
</div>

